function funcionEditar(id) {
    $.post(APP_URL + '/articuloscategoria/default/get-modal-edit/' + id, {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Editar Categoria</strong></h2>",
            message: resp.plantilla,
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });

        $(document).ready(function () {
            $("#btn-guardar").click(function () {
                $("#form-articuloscategoria").validate({
                    rules: {
                      
                        nombre_categoria: "required",                     
                    },
                    messages: {
                   
                        nombre_categoria: "Por favor ingrese datos",                   
                    },
                    submitHandler: function () {
                        var nombre_categoria = $("#nombre_categoria").val();

                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/articuloscategoria/default/update',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {
                                
                                id_categoria: id,
                                nombre_categoria: nombre_categoria
                            },
                            success: function (response) {
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
