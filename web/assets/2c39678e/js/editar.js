function funcionEditar(id) {
    $.post(APP_URL + '/clientesventas/default/get-modal-edit/' + id, {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Editar Entidad</strong></h2>",
            message: resp.plantilla,
             size: 'large',
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });

        $("#ubigeos").select2({
            placeholder: "Seleccioné Ubigeo"
        });

        $(document).ready(function () {
            $("#btn-guardar").click(function () {
                $("#frm-cliente").validate({
                    rules: {
                        tipo_entidad: "required",
                        tipo_documento_entidad: "required",
                        numero_documento_entidad: "required",
                        razon_social: "required",
                        telefono: "required",
                        correo: "required",
                        ubigeos: "required",
                    },
                    messages: {
                        tipo_entidad: "Seleccione entidad",
                        tipo_documento_entidad: "Seleccione documento",
                        numero_documento_entidad: "Por favor ingrese datos",
                        razon_social: "Por favor ingrese datos",
                        telefono: "Por favor ingrese datos",
                        correo: "Por favor ingrese datos",
                        ubigeos: "Seleccione ubigeo",
                    },
                    submitHandler: function () {
                        var tipo_entidad = $("#tipo_entidad").val();
                        var tipo_documento = $("#tipo_documento").val();
                        var numero_documento = $("#numero_documento").val();
                        var razon_social = $("#razon_social").val();
                        var telefono = $("#telefono").val();
                        var correo = $("#correo").val();
                        var direccion = $("#direccion").val();
                        var ubigeos = $("#ubigeos").val();

                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/clientesventas/default/update',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {

                                id_entidad: id,
                                tipo_entidad: tipo_entidad,
                                tipo_documento: tipo_documento,
                                numero_documento: numero_documento,
                                razon_social: razon_social,
                                telefono: telefono,
                                correo: correo,
                                ubigeos: ubigeos,
                                direccion: direccion,
                            },
                            
                            success: function (response) {
                                //console.log("id_direccion"+response);
                                bootbox.hideAll();
                                if (response) {
                                    notificacion('Accion realizada con exito', 'success');
                                } else {
                                    notificacion('Error al guardar datos', 'error');
                                }
                                datatable.reload()
                            }
                            
                        });
                    }
                });
            });
        });
    }, 'json');
}
