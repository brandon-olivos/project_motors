$("#modal-articulosmarca").on("click", function () {
    $.post(APP_URL + '/marcas/default/get-modal', {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Registro de Marca</strong></h2>",
            message: resp.plantilla,
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });

        $(document).ready(function () {
            $("#btn-guardar").click(function () {
                $("#form-articulosmarca").validate({
                    rules: {
                             
                        nombre_marca: "required",                 
                    },
                    messages: {
                       
                        nombre_marca: "Por favor ingrese datos",
                        
                    },
                    submitHandler: function () {

                        var nombre_marca = $("#nombre_marca").val();
            
                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/marcas/default/create',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {
                               
                                nombre_marca: nombre_marca,
                           
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
});
