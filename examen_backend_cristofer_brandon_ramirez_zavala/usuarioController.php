<?php
header('content-type: application/json; charset= utf-8');
require_once('usuarioModel.php');

$usersModel = new usersModel();

switch($_SERVER['REQUEST_METHOD']){
    case 'GET':
        $respuesta = $usersModel->getUsers();
        echo json_encode($respuesta,JSON_UNESCAPED_UNICODE);
    break;

    case 'POST':

        $_POST = json_decode(file_get_contents('php://input',true));

        if(!isset($_POST->nombre_usuario) || is_null($_POST->nombre_usuario)){
            $respuesta = ['error','El nombre del usuario no debe de estar vacio'];
        }elseif(!isset($_POST->apellido_paterno_usuario) || is_null($_POST->apellido_paterno_usuario)){
            $respuesta = ['error','El apellido paterno del usuario no debe de estar vacio'];
        }elseif(!isset($_POST->apellido_materno_usuario) || is_null($_POST->apellido_materno_usuario)){
            $respuesta = ['error','El apellido materno del usuario no debe de estar vacio'];
        }elseif(!isset($_POST->correo_electronico) || is_null($_POST->correo_electronico)){
            $respuesta = ['error','El correo del usuario no debe de estar vacio'];
        }elseif(!isset($_POST->contraseña) || is_null($_POST->contraseña)){
            $respuesta = ['error','El campo contraseña no debe de estar vacio'];
        }elseif(!isset($_POST->direccion) || is_null($_POST->direccion)){
            $respuesta = ['error','La direccion del usuario no debe de estar vacio'];
        }elseif(!isset($_POST->telefono) || is_null($_POST->telefono)){
            $respuesta = ['error','El telefono del usuario no debe de estar vacio'];
        }elseif(!isset($_POST->fecha_nacimiento) || is_null($_POST->fecha_nacimiento)){
            $respuesta = ['error','La fecha de nacimiento del usuario no debe de estar vacio'];
        }else{
            $respuesta = $usersModel->saveUsers($_POST->nombre_usuario,$_POST->apellido_paterno_usuario,$_POST->apellido_materno_usuario,
            $_POST->correo_electronico,$_POST->contraseña,$_POST->direccion,$_POST->telefono,$_POST->fecha_nacimiento);
        }

        echo json_encode($respuesta,JSON_UNESCAPED_UNICODE);


    break;
    
    case 'PUT':
        
        $_PUT = json_decode(file_get_contents('php://input',true));
        if(!isset($_PUT->id_usuario) || is_null($_PUT->id_usuario)){
            $respuesta = ['error','El ID del usuario no debe de estar vacio'];
        }elseif(!isset($_PUT->nombre_usuario) || is_null($_PUT->nombre_usuario)){
            $respuesta = ['error','El nombre del usuario no debe de estar vacio y no puede ser numerico'];
        }elseif(!isset($_PUT->apellido_paterno_usuario) || is_null($_PUT->apellido_paterno_usuario)){
            $respuesta = ['error','El apellido paterno del usuario no debe de estar vacio'];
        }elseif(!isset($_PUT->apellido_materno_usuario) || is_null($_PUT->apellido_materno_usuario)){
            $respuesta = ['error','El apellido materno del usuario no debe de estar vacio'];
        }elseif(!isset($_PUT->correo_electronico) || is_null($_PUT->correo_electronico)){
            $respuesta = ['error','El correo del usuario no debe de estar vacio'];
        }elseif(!isset($_PUT->direccion) || is_null($_PUT->direccion)){
            $respuesta = ['error','La direccion del usuario no debe de estar vacio'];
        }elseif(!isset($_PUT->contraseña) || is_null($_PUT->contraseña)){
            $respuesta = ['error','El campo contraseña no debe de estar vacio'];
        }elseif(!isset($_PUT->telefono) || is_null($_PUT->telefono)){
            $respuesta = ['error','El telefono del usuario no debe de estar vacio'];
        }elseif(!isset($_PUT->fecha_nacimiento) || is_null($_PUT->fecha_nacimiento)){
            $respuesta = ['error','La fecha de nacimiento del usuario no debe de estar vacio'];
        }else{
            $respuesta = $usersModel->updateUsers($_PUT->id_usuario,$_PUT->nombre_usuario,$_PUT->apellido_paterno_usuario,
                         $_PUT->apellido_materno_usuario,$_PUT->correo_electronico,$_PUT->contraseña,$_PUT->direccion,$_PUT->telefono,
                         $_PUT->fecha_nacimiento);
        }

        echo json_encode($respuesta,JSON_UNESCAPED_UNICODE);

    break;

    case 'DELETE':
        $_DELETE = json_decode(file_get_contents('php://input',true));

        if(!isset($_DELETE->id_usuario) || is_null($_DELETE->id_usuario)){
            $respuesta = ['error','El ID del usuario no debe de estar vacio'];
        }else{
            $respuesta = $usersModel->deleteUsers($_DELETE->id_usuario);
        }
        echo json_encode($respuesta,JSON_UNESCAPED_UNICODE);

    break;

}


?>