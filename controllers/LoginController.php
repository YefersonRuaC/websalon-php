<?php
namespace Controllers;

use Classes\Email;
use MVC\Router;
use Model\Usuario;

class LoginController {
    public static function login(Router $router) {
        //Inicia como un arreglo vacio, pero a medida de los resultado en el if($_SERVER....), este arreglo
        //se va ir llenando
        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Instanciamos new Usuario y a el usuario (contructor) le pasaremos todo lo que el usuario escriba
            //en POST (al darle enviar al formulario)
            //Es decir, la variable $auth contiene el email y password que el usuario escribio
            $auth = new Usuario($_POST);//Y ya de esa forma nos crea el objeto con la estructura del usuario

            //En caso de que pase los dos if del metodo validarLogin(), el arreglo en este punto esta vacio
            $alertas = $auth->validarLogin();

            //Si alertas esta vacio, significa que el usuario lleno el email y el password
            if(empty($alertas)) {
                //Comprobar que exista el usuario
                $usuario = Usuario::where('email', $auth->email);

                if($usuario) {
                    //Verificar el password
                    if( $usuario->comprobarPasswordAndVerificado($auth->password) ) {
                        //A estas alturas 
                        //1. el usuario envio los datos correctamente ($alertas = $auth->validarLogin();)
                        //2. existe el usuario ($usuario = Usuario::where('email', $auth->email);)
                        //3. su password es correcto y que esta verificado ($usuario->comprobarPasswordAndVerificado($auth->password))

                        //Ahora 4. Nos resta Autenticar el usuario
                        //session_start();//Una ves iniciamos una sesion tenemos acceso a la superglobal de SESSION

                        //Este $usuario recordemos que viene desde el metodo where(), la cual nos retorna desde
                        //la BD un "espejo" que tenemos
                        $_SESSION['id'] = $usuario->id;//Guardaremos datos como el id
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        //Redireccionamiento (dependera mucho si el usuario es admin o no)
                        //Dependiendo del tipo de usuario, tendremos un redireccionamiento distinto
                        if($usuario->admin === "1") {
                            $_SESSION['admin'] = $usuario->admin ?? null;

                            header('Location: /admin');
                        } else {
                            //Redireccionamos a la pagina main del usuario (/cliente en este caso) y con eso
                            //el usuario ya habra en este punto, iniciado sesion
                            header('Location: /cita');
                        }
                    }
                } else {
                    Usuario::setAlerta('error', 'Usuario incorrecto');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        //Recordar que el metodo render() es el que nos mostrara las vistas (este esta en Router.php)
        //ponemos solo el nombre del archivo (sin extencion) ya que en el render ya esta configurado con 
        //la extension de .php
        $router->render('auth/login', [
            'alertas' => $alertas
        ]); 
    }
 
    public static function logout() {
        //session_start();
        $_SESSION = [];

        header('Location: /');
    }

    public static function olvide(Router $router) {
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Instanciamos con la informacion de POST
            $auth = new Usuario($_POST);

            $alertas = $auth->validarEmail();

            //
            if(empty($alertas)) {
                //Mira si el correo que escribirmos es igual auno de la BD (si lo existe o no el resgistro)
                $usuario = Usuario::where('email', $auth->email);

                //Miraremos que si existe el usuario y que este confirmado
                if($usuario && $usuario->confirmado === '1') {
                    //Generar un nuevo token de un unico uso
                    $usuario->crearToken();

                    //Como ya tenemos un id, este realiza un update en la BD
                    $usuario->guardar();

                    //Enviar el email
                    //Instanciando con las varibales del contructor (deben estar en el mismo orden que el constructor)
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    //Alerta de envio de email
                    Usuario::setAlerta('exito', 'Revisa tu email');

                } else {
                    //setAlerta: para agregar alertas
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                }
            }
        }

        //getAlertas: para obtener alertas
        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router) {
        $alertas = [];//Arreglo vacio que se va ir llenando segun pasen acciones

        $error = false;
        
        //Obteniendo el valor del token (por medio de la url)
        $token = sanitizar($_GET['token']);

        //Buscar usuario por su token
        //Recordemos que este $usuario contiene toda la informacion del usuario desde la BD
        $usuario = Usuario::where('token', $token);//Si alguien intenta adivinar el token, arrojamos un null y podemos mandar la alerta

        //Si $usuario esta vacio (o null) quiere decir que no encontro un usuario con ese token
        if(empty($usuario)) {
            Usuario::setAlerta('error', 'Token no valido');
            $error = true;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //Leer el nuevo password y guardarlo
            $password = new Usuario($_POST);

            $alertas = $password->validarPassword();

            //Si $alertas esta vacio (es decir, que el password proporcionado cumple)
            if(empty($alertas)) {
                //De esta forma eliminamos el password actual que tiene el usuario
                $usuario->password = null;

                //Tomamos de la instancia de $password el password y se lo asignamos al $usuario
                //Es decir que tomamos el nuevo password ingresado y se lo pasamos al objeto de usuario
                //Con ($usuario->password) el nuevo password lo hacemos parte del objeto
                $usuario->password = $password->password;

                //Hasheamos el password nuevo ingresado
                $usuario->hashPassword();

                //Con el password listo, eliminamos el token de la BD
                $usuario->token = null;

                $resultado = $usuario->guardar();

                //Si el resultado se guarda correctamente
                if($resultado) {
                    header('Location: /');//Lo redireccionamos para que pueda volver a iniciar sesion
                    //En este casi el inicio de sesion es la pagina principal (/)
                }
            }
        }

        //getAlertas: para obtener alertas
        $alertas = Usuario::getAlertas();

        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router) {

        //Creamos una nueva instancia de usuario. Y le pasamos todos los datos que tengamos en POST
        $usuario = new Usuario($_POST);
        
        //Alertas vacias
        //El usuario al entrar por primera vez a la pagina, el arreglo de $alertas esta vacio
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            //Gracias a "sincronizar", indicamos que sincronice el objeto que en esos momento esta vacio
            //con los datos que vienen del POST, es decir que:
            //Mantiene el valor en el campo al enviar el formulario incompleto y al darle actualizar la pagina 
            //(no deja que se borre) 
            $usuario->sincronizar($_POST);

            //Pero cuando el usuario le de en crear cuenta, pues $alertas que en principio estaba vacio, va 
            //tener el resultado del metodo de validarNuevaCuenta con errore o no
            $alertas = $usuario->validarNuevaCuenta();

            //Revisar que alertas este vacio
            if(empty($alertas)) {
                //Verificar que el usuario (email) no este registrado
                $resultado = $usuario->existeUsuario();

                //Si el usuario ya esta registrado
                if($resultado->num_rows) {
                    //En caso de que haya resultado, este llena $alertas y manda el mensaje hacia la vista
                    $alertas = Usuario::getAlertas();

                }else {//Si el usuario no esta registrado, almacenaremos el usuario a la BD

                   //Hashear el password
                   $usuario->hashPassword();

                   //Generar un token unico
                   $usuario->crearToken();

                   //Enviar el email
                   //A Email le debemos pasar la informacion o valores que requiere el constructor de la clase Email
                   //Instanciando el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    
                    //Mandamos el email de confirmacion con el token a el usuario
                    $email->enviarConfirmacion();

                    //Crear el usuario
                    $resultado = $usuario->guardar();

                    if($resultado) {
                        header('Location: /mensaje');
                    }

                }
            }

        }

        //Mostramos en la vista
        //Recordemos que con este render podemos pasar datos y variables hacia las vistas (views)
        $router->render('auth/crear-cuenta', [
            //De esta forma el objeto que tenemos al darle en enviar formulario ya va estar en la vista disponible
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router) {

        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router) {
        $alertas = [];

        //Sabemos que el token se muestra en el url de la pagina, con el GET podemos leer la url con este token 
        //en ella (leer el valor de este token)
        $token = sanitizar($_GET['token']);

        //Recordar que ponemos la sintaxis de (::) ya que en ActiveRecord el where esta como (static function)
        //es decir que no necesitamos instanciar el metodo para poder acceder a el. Como puede ser de la forma
        //con (->)
        //Al ser este metodo dinamico, le pasamos la columna que queremos y el valor del token de la url
        $usuario = Usuario::where('token', $token);
        
        //Si el token de la url coincide con el de la BD, nos traera el objeto de $usuario, si no traera un null
        if(empty($usuario) || $usuario->token === ''){
            //Mostrar mensaje de error
            //Recordemos que setAlerta en el ActiveRecord toma el tipo de alerta y el mensaje de la alerta
            Usuario::setAlerta('error', 'Confirmacion no valida');
        } else {
            //Modificar a usuario confirmado
            $usuario->confirmado = '1';

            //Cuando el usuario se confirme, pasamos el token existente en BD por un null
            $usuario->token = null;

            //Como vamos a pasa el campo de confirmado de 0 a 1 para poder confirmar, debemos actualizar el
            //registro de la BD por medio del metodo actualizar
            $usuario->guardar();

            Usuario::setAlerta('exito', 'Cuenta confirmada satisfactoriamente');
        }

        //Instanciamos $alertas aqui para que esas alertas que se estan guardando en memoria (en el if por ejemplo)
        //pueda leerlas poco antes de renderizar la vista
        $alertas = Usuario::getAlertas();

        //Tener en cuenta que este es un token de un solo uso, apenas se actualice la BD, agregue el confirmado
        //y elimine el token, este deja ya de funciona ya que logicamente no va coincidir

        //Renderizar la vista
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}