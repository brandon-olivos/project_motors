"use strict";
var columnasUsuario = [
    {
        field: "usuario",
        title: "Usuario"
    },
    {
        field: "perfil",
        title: "Perfil"
    },
    {
        field: "area",
        title: "Area"
    },
    {
        field: "persona",
        title: "Persona"
    },
    {
        field: "accion",
        title: "Acciones",
        width: 210
    }
];


var usuario_repetido = 1;


var datatableUsuario = iniciarTabla("#tabla-usuario", "/seguridad/usuario/lista", "#tabla-usuario-buscar", columnasUsuario);
$("#modal-usuario").on("click", function () {
    $.post(APP_URL + '/seguridad/usuario/get-modal', {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Registro Usuario</strong></h2>",
            message: resp.plantilla,
            buttons: {}
        });
        $("#persona").select2({
            placeholder: "Seleccione Persona"
        })

        $("#area").select2({
            placeholder: "Seleccione Área"
        })

        $("#perfil").select2({
            placeholder: "Seleccione Perfil"
        })


        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });

        

        $("#usuario").blur(function () {

            $.ajax({
                type: "POST",
                dataType: 'json',
                url: APP_URL + '/seguridad/usuario/buscar-usuario',
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',

                data: {
                    usuario: $("#usuario").val()
                },
                success: function (response) {
                    //usuario nuevo
                    if (response == 0) {

                        notificacion('Usuario válido', 'success');
                        usuario_repetido = 0;
                    }else{

                        notificacion('Usuario existente', 'error');
                        usuario_repetido = 1;
                    }
                }
            });
        });

        $(document).ready(function () {
            $("#btn-guardar").click(function () {
                $("#form-usuario").validate({
                    rules: {
                        persona: "required",
                        area: "required",
                        perfil: "required",
                        correo: {
                            required: true,
                            email: true
                        },
                        usuario: "required",
                        password: "required",
                    },
                    messages: {
                        persona: "Por favor seleccione",
                        area: "Por favor seleccione",
                        perfil: "Por favor seleccione",
                        correo: "Por favor ingrese un correo valido",
                        usuario: "Por favor ingrese datos",
                        password: "Por favor ingrese datos",
                    },
                    submitHandler: function () {

                        //usuario aceptado
                        if (usuario_repetido == 0) {

                            var persona = $("#persona").val();
                            var area = $("#area").val();
                            var perfil = $("#perfil").val();
                            var correo = $("#correo").val();
                            var usuario = $("#usuario").val();
                            var password = $("#password").val();

                            $.ajax({
                                type: "POST",
                                dataType: 'json',
                                url: APP_URL + '/seguridad/usuario/create',
                                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                data: {
                                    persona: persona,
                                    area: area,
                                    perfil: perfil,
                                    correo: correo,
                                    usuario: usuario,
                                    password: password
                                },
                                success: function (response) {
                                    bootbox.hideAll();
                                    if (response) {
                                        notificacion('Accion realizada con exito', 'success');
                                    } else {
                                        notificacion('Error al guardar datos', 'error');
                                    }
                                    datatableUsuario.reload()
                                }
                            });

                        }else{

                            notificacion('Usuario existente', 'error');
                        }       
                    }
                });
            });
        }
        );
    }, 'json');
});
function funcionEditarUsuario(id) {
    $.post(APP_URL + '/seguridad/usuario/get-modal-edit/' + id, {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Editar Usuario</strong></h2>",
            message: resp.plantilla,
            buttons: {}
        });

        $("#usuarioedit").blur(function () {

            $.ajax({
                type: "POST",
                dataType: 'json',
                url: APP_URL + '/seguridad/usuario/buscar-usuario',
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',

                data: {
                    usuario: $("#usuarioedit").val()
                },
                success: function (response) {
                    //usuario nuevo
                    if (response == 0) {

                        notificacion('Usuario válido', 'success');
                        usuario_repetido = 0;
                    }else{

                        notificacion('Usuario existente', 'error');
                        usuario_repetido = 1;
                    }
                }
            });
        });
       


        $("#persona").select2({
            placeholder: "Seleccione Persona"
        })

        $("#area").select2({
            placeholder: "Seleccione Área"
        })

        $("#perfil").select2({
            placeholder: "Seleccione Perfil"
        })

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });



        

        $(document).ready(function () {
            $("#btn-guardar").click(function () {
                $("#form-usuario").validate({
                    rules: {
                        persona: "required",
                        area: "required",
                        perfil: "required",
                        correo: {
                            required: true,
                            email: true
                        },
                        usuario: "required",
                    },
                    messages: {
                        persona: "Por favor seleccione",
                        area: "Por favor seleccione",
                        perfil: "Por favor seleccione",
                        correo: "Por favor ingrese un correo valido",
                        usuario: "Por favor ingrese datos",
                    },
                    submitHandler: function () {

                        //usuario aceptado
                        if (usuario_repetido == 0) {

                            var persona = $("#persona").val();
                            var area = $("#area").val();
                            var perfil = $("#perfil").val();
                            var correo = $("#correo").val();
                            var usuario = $("#usuarioedit").val();
                            var password = $("#password").val();

                            $.ajax({
                                type: "POST",
                                dataType: 'json',
                                url: APP_URL + '/seguridad/usuario/update',
                                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                data: {
                                    id_usuario: id,
                                    persona: persona,
                                    area: area,
                                    perfil: perfil,
                                    correo: correo,
                                    usuario: usuario,
                                    password: password,
                                },
                                success: function (response) {
                                    bootbox.hideAll();
                                    if (response) {
                                        notificacion('Accion realizada con exito', 'success');
                                    } else {
                                        notificacion('Error al guardar datos', 'error');
                                    }
                                    datatableUsuario.reload()
                                }
                            });
                        }else{

                            notificacion('Usuario existente', 'error');
                        }   
                    }
                });
            });
        });
    }, 'json');
}


function funcionEliminarUsuario(id) {
    Swal.fire({
        title: "¿Está seguro?",
        text: "¡No podrás revertir esto!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, ¡bórralo!",
        cancelButtonText: "No, ¡cancelar!",
        reverseButtons: true
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: APP_URL + '/seguridad/usuario/delete',
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                data: {
                    id_usuario: id
                },
                success: function (response) {
                    if (response > 0) {
                        Swal.fire("Eliminado!", "El registro fue eliminado correctamente.", "success")
                    }
                    datatableUsuario.reload()
                }
            });
        } else if (result.dismiss === "cancel") {
            Swal.fire("Cancelado", "Tu registro está seguro.", "error")
        }
    });
}
