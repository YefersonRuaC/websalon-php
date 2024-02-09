<?php

namespace Model;

class Usuario extends ActiveRecord {
    //Base de datos
    protected static $tabla = 'usuarios';
    //CREANDO LA FORMA DE LOS DATOS DE los usuarios
    //Recordemos que este columnasDb va iterar sobre los datos o registros de la tabla
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'telefono', 'admin', 
    'confirmado', 'token'];

    //public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    //Una vez que creemos una nueva nueva instancia de este objeto (de Usuario) vamos a ir agregando los argumentos
    //($args) con los atributos de nuestra clase ($id, $nombre, ...)
    public function __construct($args = []) 
    {
        //Con null y '' podremos validar si estan vacios esos datos en el objeto. Y revisar por el objeto
        //y si esta vacio mostrarle una alerta a el usuario
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? '0';//admin y confirmado van a tener solo valores de 1 o 0
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
    }

    //Mensajes de validacion para la creacion de una cuenta
    //Como esta funcion se mandara llamar desde LoginController, debe estar public
    public function validarNuevaCuenta() {
        if(!$this->nombre) {
            //Sabemos que $alertas es un arreglo que en principio esta vacio.
            
            //Estamos definiedo dos arreglos
            //1) ['error']: este es el tipo de mensaje que queremos mostrar
            //2) []: en principio es una arreglo vacio, pero este contendra el mensaje de error
            self::$alertas['error'][] = 'El nombre es obligatorio';//$alertas viene desde la clase padre (ActiveRecord)
        }
        
        if(!$this->apellido) {
            self::$alertas['error'][] = 'El apellido es obligatorio';
        }

        if(!$this->telefono) {
            self::$alertas['error'][] = 'El telefono es obligatorio';
        }

        if(!$this->email) {
            self::$alertas['error'][] = 'El email es obligatorio';
        }

        if(!$this->password) {
            self::$alertas['error'][] = 'El password es obligatorio';
        }

        //Con strlen retornamos la longitud de un string, ayuda para evitar que el usuario ponga un password
        //muy corto que pueda ser hackeado facilmente
        if(strlen($this->password) < 6) {//
            self::$alertas['error'][] = 'El password debe contener al menos 6 caracteres';
        }

        return self::$alertas;
        //return static::$alertas;
    }

    //Metodo para hacer validacion al momento del usuario inicar sesion
    public function validarLogin() {
        //Si no hay un email
        if(!$this->email) {
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        //Si no hay un password
        if(!$this->password) {
            self::$alertas['error'][] = 'El password es obligatorio';
        }

        //Para poder retornar estas alertas hacia el LoginController o cualquier lugar donde necesitemos usarlas
        return self::$alertas;
    }

    //Metodo para validar el email al usuario querer recuperar su cuenta
    public function validarEmail() {
        //Si no hay un email
        if(!$this->email) {
            self::$alertas['error'][] = 'El email es obligatorio';
        }

        return self::$alertas;
    }

    //Meotod para validar password (en recuperar password). Debemos validar que tenga password y un minimo de caracteres
    public function validarPassword() {
        if(!$this->password) {
            self::$alertas['error'][] = 'El password es obligatorio';
        }

        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El password debe tener al menos 6 caracteres';
        }

        return self::$alertas;
    }

    //Revisa si el usuario ya existe
    public function existeUsuario() {
        //Realizamos la consulta a nuestra BD, leyendo los datos que tenemos en memoria con el email
        $query = " SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";

        //Ejecutamos la consulta
        $resultado = self::$db->query($query);

        //Mediante num_rows nos damos cuenta si hay resultado o no. Si hay resultado aparece un 1, lo que
        //indica que la persona con ese correo ya esta registrada. Si hay un 0 quiere decir que no coincide
        //con ningun otro correo en nuestra BD
        if($resultado->num_rows) {
            //Si ese usuario ya esta registrado, lo agregamos a las alertas
            self::$alertas['error'][] = 'El usuario ya esta registrado';
        }

        return $resultado;
    }

    //Metodo para Hashear el password que el usuario ingrese
    public function hashPassword() {
        //password_hash: es una de las funciones de php para hashear passwords
        //Toma el password ($this->password) y el metodo de hash que usaremos (PASSWORD_BCRYPT)
        //Y lo asignamos a el mismo objeto ($this->password = ...)
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    //Tanto password_hash() como uniqid() cada que se recarga la pagina generan una cadena distinta

    //Metodo para generar un token unico para que el usuario pueda confirmar su cuenta mediante su correo
    public function crearToken() {
        //uniqid: es una funcion de php que sirve para crear un id unico, el cual no esta tanbien para un
        //password (ya que no es muy extenso). Pero esta perfecto para un token que sera validado pronto
        $this->token = uniqid();
    }

    //Metodo si ya existe un usuario, compara el password que ingreso y el de la BD
    public function comprobarPasswordAndVerificado($password) {
        //password_verify es una funcion de php, a la cual le pasamos el password que se escribe y el de la BD
        //esta los compara y retorna TRUE o FALSE
        $resultado = password_verify($password, $this->password);
        //password: es el que el usuario escribio en el formulario
        //$this->password: este es el password que viene desde la BD
        
        if(!$resultado || !$this->confirmado) {
            //Si el usuario NO esta confirmado (hay un 0 en el campo de la BD)
            self::$alertas['error'][] = 'Password incorrecto o su cuenta no ha sido confirmada';
        } else {
            //Si el usuario SI esta confirmado (hay un 1 en el campo de la BD)
            return true;
        }
    }
}