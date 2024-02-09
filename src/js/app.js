let paso = 1;//Este es el numero de la seccion que se mostrar de primero

//vrbles paginador
const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
    id: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}

document.addEventListener('DOMContentLoaded', function() {
    iniciarApp();
});

function iniciarApp() {
    mostrarSeccion();//Muestra y oculta las secciones
    tabs();//Cambia de seccion cuando se de click en los tabs
    botonesPaginador();//Agrega o quita los botones del paginador
    paginaSiguiente();
    paginaAnterior();

    consultarAPI();//Consulta la API en el backend de php

    idCliente();
    nombreCliente();//Añade el nombre del cliente al objeto de cita
    seleccionarFecha();//Añade la fecha de la cita a el objeto de cita
    seleccionarHora();//Añade la hora de la cita a el objeto de cita

    mostrarResumen();//Muestra el resumen de la cita
}

function mostrarSeccion() {
    //Ocultar la seccion que tenga la clase de mostrar
    const seccionAnterior = document.querySelector('.mostrar');//Seleccionamos la seccion con la clase de mostrar

    if(seccionAnterior) {//Si hay una seccion con la clase, la remueve, sino no hace nada
        seccionAnterior.classList.remove('mostrar');//Y le quitamos esa clase, para luego agregarsela a otra
    }
    
    //Seleccionar la seccion con el paso
    const pasoSelector = `#paso-${paso}`;//Seleccionamos el elemento
    const seccion = document.querySelector(pasoSelector);
    seccion.classList.add('mostrar');//Y agregamos la clase que muestra el contenido de esa seccion

    //Quita la clase de actual al tab anterior
    const tabAnterior = document.querySelector('.actual');//Seleccionamos la seccion con la clase de actual

    if(tabAnterior) {//Si hay una seccion con la clase, la remueve, sino no hace nada
        tabAnterior.classList.remove('actual');//Y le quitamos esa clase, para luego agregarsela a otra
    }

    //Resalta el tab actual
    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add('actual');
}

function tabs() {
    const botones = document.querySelectorAll('.tabs button');

    botones.forEach( boton =>  {//Accediendo a los tres botones, uno a uno
        boton.addEventListener('click', function(e) {//e: es el evento que se va registrar, en este caso mostrar una seccion u otra
            paso = parseInt(e.target.dataset.paso)//Accediendo al valor de la clas data-paso="#"

            mostrarSeccion();

            botonesPaginador();
        });
    });
}

//PAGINADOR
function botonesPaginador() {
    const paginaAnterior = document.querySelector('#anterior');
    const paginaSiguiente = document.querySelector('#siguiente');

    if(paso === 1) {
        paginaAnterior.classList.add('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    } else if(paso === 3){
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.add('ocultar');
    //Si esta en el paso tres (resumen), mandamos llamar la funcion mostrarResumen()
        mostrarResumen();
    } else {
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    }

    mostrarSeccion();
}

function paginaAnterior() {
    const paginaAnterior = document.querySelector('#anterior');
    paginaAnterior.addEventListener('click', function() {

        if(paso <= pasoInicial) {
            return
        }
        paso--;//Va de uno en uno el paginador

        botonesPaginador();
    });
}

function paginaSiguiente() {
    const paginaSiguiente = document.querySelector('#siguiente');
    paginaSiguiente.addEventListener('click', function() {

        if(paso >= pasoFinal) {
            return
        }
        paso++;//Va de uno en uno el paginador

        botonesPaginador();
    });
}

//async: permite que se ejecuten otras funciones mientras que esta (una funcion mas pesada) se esta ejecutando
//a la par, es decir que al demorarse mas para ejecutar (al ser mas pesada) no detiene la ejecucion de otras funciones
async function consultarAPI() {
    //Funcion async/await siempre debe ir con un try{}catch{}. Ya que previene (si hay un error) que la aplicacion
    //deje de funcionar
    try {
        //url (endpoint) que contiene la API
        //POSTMAN
        const url = `/api/servicios`;//Esta url funciona siempre y cuando el back y el front esten juntos
        //THUNDER
        //const url = 'http://127.0.0.1:300/api/servicios'
    //await: espera hasta que descargue todo el contenido de la API (se demore mucho o no) y luego ejecuta el 
    //codigo que va despues de esta linea (por ejemplo si hay una funcion como mostrarServicios(), etc)
        const resultado = await fetch(url);
        const servicios = await resultado.json();

        //Esta funcion al estar aqui, "espera" a que todo lo de la url de la API cargue para ahi si ejecutarse
        mostrarServicios(servicios);
        
    }catch (error) {
        console.log(error);
    }
}

//Mostraremos los servicios que vienen desde la API en pantalla
function mostrarServicios(servicios) {
    //Iteraremos en cada uno de los servicios
    servicios.forEach( servicio => {
        const { id, nombre, precio } = servicio;
        //Agregar html asi con JS se llama destructuring
        //<p> nombre servicio
        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        //<p> precio servicio
        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$${precio}`;

        //<div> div servicio
        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id;

        servicioDiv.onclick = function() {
            seleccionarServicio(servicio);
        }

        //Metemos los parrafos (P) de nombreServicio y precioServicio en un div
        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);

        //Mostrarlo en pantalla
        //Mediante el id="servicios" del div que tenemos vacio en la vista (cita/index.php) vamos a inyectar
        //a la vista del usuario el nombre y precio del servicio
        document.querySelector('#servicios').appendChild(servicioDiv);
    });
}

function seleccionarServicio(servicio) {
    const { id } = servicio;//Accedemos a la propiedad id, del servicio
    const { servicios } = cita;//Accedemos a la propiedad servicios: [] de cita

    //Identificar el elemento al que se le da click
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

    //Comprobar si un servicio ya fue agregado o quitarlo
    //.some va iterar sobre el arreglo de servicios y retorna true o false en caso de que un elemento ya 
    //exista en el arreglo. Si retorna true, ya esta agregado. Si retorna false, no lo esta
    if( servicios.some(agregado => agregado.id === id) ) {//Compara los id, para ver si ya esta ese elemento
        //Eliminar el servicio
        cita.servicios = servicios.filter(agregado => agregado.id !== id);
        divServicio.classList.remove('seleccionado');
    }else {
        //Agregar el servicio
    //Servicio es el objeto que se pasa al darle click al recuadro
        cita.servicios = [...servicios, servicio];//Estamos actualizando la propiedad servicios del objeto cita 
    //agregando un nuevo servicio a la lista existente de servicios (al darle click). 

        divServicio.classList.add('seleccionado');
    } 

    // console.log(cita);
}

function idCliente() {
    const id = document.querySelector('#id').value;

    cita.id = id;
}

function nombreCliente() {
    const nombre = document.querySelector('#nombre').value;

    cita.nombre = nombre;
}

function seleccionarFecha() {
    const inputFecha = document.querySelector('#fecha');
    inputFecha.addEventListener('input', function(e) {
        //0 = Domingo, ..., 6 = Sabado
        const dia = new Date(e.target.value).getUTCDay();

        if( [1,0].includes(dia) ) {//Seleccionamos los dias 0 y 6
            //Si el usuario, selecciona uno de estos dos dias, le informaremos que no se trabaja
            //No peromite asignar el dia en el campo
            e.target.value = '';
            mostrarAlerta('Lunes y Domingos no abrimos', 'error', '.formulario');
        } else {
            //Si selecciona alguno de los otros dias, si le va permitir seleccionar la fecha
            //Seleccionamos la fecha
            cita.fecha = e.target.value;
        }
    });
}

function seleccionarHora() {
    const inputHora = document.querySelector('#hora');
    inputHora.addEventListener('input', function(e) {

        const horaCita = e.target.value;
        const hora = horaCita.split(":")[0];//split: permite separar un string

        if(hora < 9 || hora > 19) {
            e.target.value = '';
            mostrarAlerta('Hora no valida', 'error', '.formulario');
        } else {
            cita.hora = e.target.value;
        }
    }); 
}

//elemento = a etiqueta (clase) donde se mostrara el mensaje. Y desaparece solo se inabilitara en el resumen
function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {
    // Previene que se generen mas de una alerta
    const alertaPrevia = document.querySelector('.alerta');
    if(alertaPrevia) {
        alertaPrevia.remove();
    };

    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);

    //Mostrar la alerta en pantalla
    const etiqueta = document.querySelector(elemento);
    etiqueta.appendChild(alerta);

    if(desaparece) {
        //Que desaparezca la alerta luego de un tiempo
        setTimeout( () => {
            alerta.remove();
        }, 5000);
    }
}

function mostrarResumen() {
    const resumen = document.querySelector('.contenido-resumen');

    //Limpiar el contenido de resumen
    while(resumen.firstChild) {
        resumen.removeChild(resumen.firstChild);
    }

    //Object.values itera sobre el objeto de cita y el includes verifica si hay un string vacio ('') o no
    if(Object.values(cita).includes('') || cita.servicios.length === 0 ) {//O si el arreglo esta vacio usamos .legth
        //Si faltan datos. (Que NO desaparezca la alerta)
        mostrarAlerta('Faltan datos de Servicio, Fecha u Hora', 'error', '.contenido-resumen', false)

        return
    } 
    
    //(A esta altura, ya SI tenemos todos los datos de la cita) Ya que paso el condicional anterior
    // Formatear el div de resumen
    const { nombre, fecha, hora, servicios } = cita;//Estraemos toda la informacion del objeto cita

    //Heading para servicios en el resumen
    const headingServicios = document.createElement('H3');
    headingServicios.textContent = 'Resumen de Servicios';
    //Le pasamos el headinServicios a la pagina de resumen
    resumen.appendChild(headingServicios);

    //Iterando y mostrando los servicios
    servicios.forEach(servicio => {
        const { id, precio, nombre } = servicio;

        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        const textoServicio = document.createElement('P');
        textoServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Precio:</span> $${precio}`;

        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);

        resumen.appendChild(contenedorServicio);
    });

    //Heading para los datos de la cita en el resumen
    const headingCita = document.createElement('H3');
    headingCita.textContent = 'Resumen de la cita';
    //Le pasamos el headinServicios a la pagina de resumen
    resumen.appendChild(headingCita);

    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;

    //Formatear la fecha
    const fechaObj = new Date(fecha);
    const mes = fechaObj.getMonth();
    const dia = fechaObj.getDate() + 2;
    const year = fechaObj.getFullYear();

    const fechaUTC = new Date( Date.UTC(year, mes, dia) );

    const opciones = { weekDay: 'long', year: 'numeric', month: 'long', day: 'numeric' }
    const fechaFormateada = fechaUTC.toLocaleDateString('es-CO', opciones);


    const fechaCita = document.createElement('P');
    fechaCita.innerHTML = `<span>Fecha:</span> ${fecha}`;

    const horaCita = document.createElement('P');
    horaCita.innerHTML = `<span>Hora:</span> ${hora}`;

    //Boton para crear una cita
    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent = 'Reservar cita';
    botonReservar.onclick = reservarCita;

    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);

    resumen.appendChild(botonReservar);
}

//Funcion para reservar cita al darle click al boton
async function reservarCita() {
    //Estraer los valores del objeto de cita
    const { nombre, fecha, hora, servicios, id } = cita;
    //Para reconocer a que servicio espefico selecciono el usuario, mediante el id de cada servicio
    //el map es para recorrer los servicios, y asignar el id de cada servicios a la vrble idServicios
    const idServicios = servicios.map( servicio => servicio.id);

    //FormData(): es como el submit de un formulario, tendra toda la informacion que se enviara
    const datos = new FormData();
    //append es la forma de agregar datos a el FormData() 
    datos.append('fecha', fecha);//'' va la llave, y la variable es el valor que la llave contendra
    datos.append('hora', hora);
    datos.append('usuarioId', id);
    datos.append('servicios', idServicios);

    //TIP: para asegurarnos que estamos enviando en el FormData(). Asi podemos inspeccionar que estamos colocando
    //console.log([...datos]);//en el FormData() antes de enviarlo y asegurarnos que se esta enviando la info correcta
    //return;//ponemos este return para que no se ejecute la peticion de abajo

    try{
            //Peticion hacia la API
        const url = '/api/citas';

        //Deben ser dos await, uno con la respuesta y otro con el resultado
        //Para comunicarse con la API (hacer la peticion)
        const respuesta = await fetch(url, {
            method: 'POST',//Para que s epueda conectar con el controlador definido en el index.php
            //fetch debe identificar que existe el FormData(), para tomar sus datos y enviarlos como parte de la
            body: datos//peticion POST a la url o endpoint que definimos
        });

        //Para enviar peticion a nuestra API 
        const resultado = await respuesta.json();//El primer (resultado) viene desde esta vrble

        //El segundo (resultado) viene desde la api, ActiveRecord en el metodo crear();
        //Alerta de exito SweetAlert
        if(resultado.resultado) {
            Swal.fire({
                icon: "success",
                title: "Cita creada",
                text: "Tu cita fue creada correctamente",
                button: 'OK'
            }).then( () => {//Para que la pagina se recargue y no pueda darle crear muchas veces a la misma cita
                window.location.reload();
            });
        }
    }catch(error) {
        //Alerta de exito SweetAlert
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Hubo un error al crear la cita",
            button: 'OK'
          });
    }
}