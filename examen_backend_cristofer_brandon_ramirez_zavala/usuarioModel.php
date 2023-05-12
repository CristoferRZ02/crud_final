<?php

    class usersModel{
        public $conexion;
        //PARAMETROS QUE SON ENVIADOS PARA CONECTARSE A LA BASE DE DATOS
        public function __construct(){
            $this->conexion = new mysqli("127.0.0.1","root","Crisbrz02*","users");
            mysqli_set_charset($this->conexion,'utf8');
        }

        public function getUsers($id_usuario = null){

            if($id_usuario != null){
                $comple_id = " AND id_usuario = $id_usuario";
            }

            $users = [];
            $query = "SELECT * FROM usuario WHERE TRUE".@$comple_id;
            $registros = mysqli_query($this->conexion,$query);
            while($row = mysqli_fetch_assoc($registros)){
                array_push($users,$row);
            }
            return $users;
        }

        //FUNCION QUE SE UTILIZA PARA INSERTAR LOS DATOS DEL USUARIO EN LA BASE DE DATOS
        public function saveUsers($nombre_usuario,$apellido_paterno_usuario,$apellido_materno_usuario,$correo_electronico,$contraseña,$direccion,$telefono,$fecha_nacimiento){
            //SE VALIDA QUE NO EXISTA UN REGISTRO CON LOS MISMOS DATOS, EN CASO DE QUE EXISTA ARROJARA UN ERROR
            $valida = $this->validaUsuarios($nombre_usuario,$apellido_paterno_usuario,$apellido_materno_usuario,$correo_electronico,$contraseña,$direccion,$telefono,$fecha_nacimiento);
            if(count($valida) > 0){
                $resultado =["error","Ya Existe un Usuario con el nombre : ".$nombre_usuario.' y con apellidos: '.$apellido_paterno_usuario.' '.$apellido_materno_usuario.' y correo: '.$correo_electronico];
            }else{
                $queryInsert = "INSERT INTO usuario (nombre_usuario,apellido_paterno_usuario,apellido_materno_usuario,correo_electronico,
                contraseña,direccion,telefono,fecha_nacimiento)
                                VALUES('$nombre_usuario','$apellido_paterno_usuario','$apellido_materno_usuario','$correo_electronico',
                                '$contraseña','$direccion',$telefono,'$fecha_nacimiento')";
                // echo $queryInsert.'<br>'; 
                mysqli_query($this->conexion,$queryInsert);
                $resultado = ['success','Usuario Guardado'];
            }
               
            return $resultado;
        }

        public function updateUsers($id_usuario,$nombre_usuario,$apellido_paterno_usuario,$apellido_materno_usuario,$correo_electronico,$contraseña,$direccion,$telefono,$fecha_nacimiento){
            
            $existe = $this->getUsers($id_usuario);
            $resultado = ['error','No existe el usuario con el id_usuario: '.$id_usuario];
            if(count($existe)>0){
                $valida = $this->validaUsuarios($nombre_usuario,$apellido_paterno_usuario,$apellido_materno_usuario,$correo_electronico,$contraseña,$direccion,$telefono,$fecha_nacimiento);
                $resultado =['error',"Ya Existe un Usuario con el nombre : ".$nombre_usuario.' y con apellidos: '.$apellido_paterno_usuario.' '.$apellido_materno_usuario.' y correo: '.$correo_electronico];
                if(count($valida) == 0){
                    $queryUpdate = "UPDATE usuario SET nombre_usuario = '$nombre_usuario',apellido_paterno_usuario = '$apellido_paterno_usuario',
                                    apellido_materno_usuario = '$apellido_materno_usuario',correo_electronico = '$correo_electronico',
                                    contraseña = '$contraseña',direccion = '$direccion',telefono = $telefono,fecha_nacimiento = '$fecha_nacimiento'
                                    WHERE id_usuario = $id_usuario";
                    mysqli_query($this->conexion,$queryUpdate);

                    $resultado = ["success","Usuario Actualizado Correctamente"];
                }
                
            }

            return $resultado;
        }

        public function deleteUsers($id_usuario){
            //Valida que el id que estas intentando eliminar si exista
            $valida = $this->getUsers($id_usuario);
            $resultado =["error","No Existe el producto con el id_usuario: ".$id_usuario];
            if(count($valida) > 0){
                $queryDelete = "DELETE FROM usuario WHERE id_usuario = $id_usuario";
                // echo $queryDelete;
                mysqli_query($this->conexion,$queryDelete);
                $resultado = ["success","Usuario Borrado Correctamente"];
            }
            
            return $resultado;
        }

        public function validaUsuarios($nombre_usuario,$apellido_paterno_usuario,$apellido_materno_usuario,$correo_electronico,$contraseña,$direccion,$telefono,$fecha_nacimiento){
            $users = [];
            $query = "SELECT * FROM usuario WHERE nombre_usuario = '$nombre_usuario' AND apellido_paterno_usuario = '$apellido_paterno_usuario'
                       AND apellido_materno_usuario = '$apellido_materno_usuario' AND correo_electronico = '$correo_electronico' ";

            $registros = mysqli_query($this->conexion,$query);
            while($row = mysqli_fetch_assoc($registros)){
                array_push($users,$row);
            }
            return $users;
            
        }
    }


?>