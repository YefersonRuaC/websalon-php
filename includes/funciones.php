<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function sanitizar($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

//Funcion que revisa si el usuario esta autenticado
function isAuth() : void {
    if(!isset($_SESSION['login'])) {//isset indica si una varibale esta definida o no
        header('Location: /');//Si no esta definida, redireccionamos al usuario a /
    }
}

function isAdmin() : void {

    if(!isset($_SESSION['admin'])) {//Si no es un admin, lo manda a la pagina (/) y no lo deja entrar a (/admin)
        header('Location: /');
    }
}

function esUltimo(string $actual, string $proximo): bool {

    if($actual !== $proximo){
        return true;
    }
    return false;
}