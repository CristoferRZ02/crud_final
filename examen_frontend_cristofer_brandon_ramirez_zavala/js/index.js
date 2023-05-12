$(function(){
    getUsuarios();
});

url='http://localhost/EJERCICIO_PRUEBA/API/examen_backend_cristofer_brandon_ramirez_zavala/usuarioController.php';

//Funcion que se ejecutara una vez entremos a nuestra pagina, en esta se realiza una peticion GET para mostrar todos los datos existentes en la base
function getUsuarios(){
    $('#contenido').empty();
    $.ajax({
        type:'GET',
        url:url,
        dataType:'json',
        success: function(respuesta){
            var usuarios = respuesta;
            if(usuarios.length > 0){
                jQuery.each(usuarios,function(i,usu){
                    var btnEditar = '<button class="btn btn-warning openModal" data-id_usuario="'+usu.id_usuario+'" data-nombre_usuario = "'+usu.nombre_usuario+'" data-apellido_paterno_usuario = "'+usu.apellido_paterno_usuario+'" data-apellido_materno_usuario = "'+usu.apellido_materno_usuario+'" data-correo_electronico = "'+usu.correo_electronico+'" data-direccion = "'+usu.direccion+'" data-telefono = "'+usu.telefono+'" data-fecha_nacimiento = "'+usu.fecha_nacimiento+'" data-op="2" data-bs-toggle="modal" data-bs-target="#modalUsuarios"><i class="fa-solid fa-edit"></i></button>';
                    var btnEliminar = '<button class="btn btn-danger delete" data-id_usuario="'+usu.id_usuario+'" data-nombre_usuario = "'+usu.nombre_usuario+'"><i class="fa-solid fa-trash"></i></button>';

                    // Se calcula la edad en base a la fecha de nacimiento capturada
                    var fechaNacimiento = new Date(usu.fecha_nacimiento);

                    //Objeto que contiene la fecha actual
                    var hoy = new Date();
                    var edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
                    var mes = hoy.getMonth() - fechaNacimiento.getMonth();

                    if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
                        edad--;
                    }

                    // Formateo de la fecha de nacimiento
                    var dia = fechaNacimiento.getUTCDate();
                    var mes = fechaNacimiento.getUTCMonth() + 1; // Se suma 1 porque los meses comienzan desde 0
                    var año = fechaNacimiento.getUTCFullYear();
                    var fechaFormateada = dia + '/' + mes + '/' + año;

                    $('#contenido').append('<tr></tr>'+i+'</td><td>'+usu.id_usuario+'</td><td>'+usu.nombre_usuario+' '+usu.apellido_paterno_usuario+' '+
                    usu.apellido_materno_usuario+'</td><td>'+usu.correo_electronico+'</td><td>'+usu.direccion+'</td><td>'
                    +usu.telefono+'</td><td>'+fechaFormateada+'</td><td>'+edad+'</td><td>'+btnEditar+'</td><td>'+btnEliminar);
                });
            }
        },
        error:function(){
            showAlerta('Error al mostrar los usuarios','error')
        }
    });
}

$(document).on('click','.openModal',function(){
    limpiar();
    var opcion = $(this).attr('data-op');
    if(opcion=='1'){
        $("#titulo_modal").html('Registrar Usuario');
        $("#btnGuardar").attr('data-operacion',1);
    }else{
        $("#titulo_modal").html('Editar Usuario');
        $("#btnGuardar").attr('data-operacion',2);
        var id_usuario=$(this).attr('data-id_usuario');
        var nombre_usuario = $(this).attr('data-nombre_usuario');
        var apellido_paterno_usuario = $(this).attr('data-apellido_paterno_usuario');
        var apellido_materno_usuario = $(this).attr('data-apellido_materno_usuario');
        var correo_electronico = $(this).attr('data-correo_electronico');
        var direccion = $(this).attr('data-direccion');
        var telefono = $(this).attr('data-telefono');
        var fecha = $(this).attr('data-fecha_nacimiento');

        $("#id_usuario").val(id_usuario);
        $("#nombre_usuario").val(nombre_usuario);
        $("#apellido_paterno_usuario").val(apellido_paterno_usuario);
        $("#apellido_materno_usuario").val(apellido_materno_usuario);
        $("#correo_electronico").val(correo_electronico);
        $("#direccion").val(direccion);
        $("#telefono").val(telefono);
        $("#fecha_nacimiento").val(fecha);
    }

    window.setTimeout(function(){
        $("#nombre_usuario").trigger('focus');
    },500);
    
});

$(document).on('click','#btnGuardar',function(){
    var id_usuario= $("#id_usuario").val();
    var nombre_usuario = $("#nombre_usuario").val().trim();
    var apellido_paterno_usuario = $("#apellido_paterno_usuario").val().trim();
    var apellido_materno_usuario = $("#apellido_materno_usuario").val().trim();
    var correo_electronico = $("#correo_electronico").val().trim();
    var contraseña = $("#contraseña").val().trim();
    var direccion =  $("#direccion").val().trim();
    var telefono = $("#telefono").val().trim();
    var fecha =  $("#fecha_nacimiento").val().trim();
    var opcion = $("#btnGuardar").attr('data-operacion');
    var metodo,parametros;

    if(opcion == '1'){
        metodo = 'POST';
        parametros={nombre_usuario:nombre_usuario,apellido_paterno_usuario:apellido_paterno_usuario,
                    apellido_materno_usuario:apellido_materno_usuario,correo_electronico:correo_electronico,contraseña:contraseña,
                    direccion:direccion,telefono:telefono,fecha_nacimiento:fecha}
    }else{
        metodo = 'PUT';
        parametros = {id_usuario:id_usuario,nombre_usuario:nombre_usuario,apellido_paterno_usuario:apellido_paterno_usuario,
                      apellido_materno_usuario:apellido_materno_usuario,correo_electronico:correo_electronico,contraseña:contraseña,
                      direccion:direccion,telefono:telefono,fecha_nacimiento:fecha}
    }

    if(nombre_usuario === ''){
        showAlerta('Ingresa tu nombre','warning','nombre_usuario');
    }else if(apellido_paterno_usuario === ''){
        showAlerta('Ingresa tu Apellido Paterno','warning','apellido_paterno_usuario');
    }else if(apellido_materno_usuario === ''){
        showAlerta('Ingresa Apellido Materno','warning','apellido_materno_usuario');
    }else if(correo_electronico === ''){
        showAlerta('Ingrese su Correo','warning','correo_electronico');
    }else if(contraseña === ''){
        showAlerta('Ingrese su Contraseña','warning','contraseña');
    }else if(direccion === ''){
        showAlerta('Ingrese su Direccion','warning','direccion');
    }else if(telefono === ''){
        showAlerta('Ingrese su Telefono','warning','telefono');
    }else if(fecha === ''){
        showAlerta('Ingrese una Fecha','warning','fecha');
    }else{
        enviarSolicitud(metodo,parametros);
    }
});

$(document).on('click','.delete',function(){
    var id_usuario = $(this).attr('data-id_usuario');
    var nombre_usuario = $(this).attr('data-nombre_usuario');
    const swalWithBootsrrapButtons = swal.mixin({
        customClass:{confirmButton: 'btn btn-success ms-3',cancelButton:'bt btn-danger'},buttonsStyling:false
    }); 
    swalWithBootsrrapButtons.fire({
        title: 'Seguro que desea eliminar al usuario: '+nombre_usuario,
        text:'Se perdera la informacion del usuario',
        icon:'question',
        showCancelButton:true,
        confirmButtonText:'Si, eliminar',
        cancelButtonText:'Cancelar',
        reverseButtons:true
    }).then((result) =>{
        if(result.isConfirmed){
            enviarSolicitud('DELETE',{id_usuario:id_usuario});
        }else{
            showAlerta('El Usuario NO fue eliminado','error');
        }
    });

});


//Funcion utilizada para mandar las peticiones POST,PUT y DELETE
function enviarSolicitud(metodo,parametros){
    $.ajax({
        type:metodo,
        url:url,
        data:JSON.stringify(parametros),
        dataType:'json',
        success: function(respuesta){
            showAlerta(respuesta[1],respuesta[0]);
            if(respuesta[0] === 'success'){
                $('#btnCerrar').trigger('click');
                getUsuarios();
            }
        },
        error: function(){
            showAlerta('Error en la solicitud','error');
        }
    });
}


function limpiar(){
    $("#id_usuario").val('');
    $("#nombre_usuario").val('');
    $("#apellido_paterno_usuario").val('');
    $("#apellido_materno_usuario").val('');
    $("#correo_electronico").val('');
    $("#direccion").val('');
    $("#telefono").val('');
    $("#fecha_nacimiento").val('');
}


//Funcion creada para regresar la alerta en base a los casos dentro de las funciones
function showAlerta(mensaje,icono,foco){
    if(foco !==""){
        $('#'+foco).trigger('focus');    
    }

    Swal.fire({
        title:mensaje,
        icon:icono,
        customClass: {confirmButton: 'btn btn-primary', popup:'animated xoomIn'},
        buttonsStyling:false
    });
}