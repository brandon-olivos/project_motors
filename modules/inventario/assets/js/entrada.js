function funcionCrearEntrada(id,nombre,cantidad) {
    $.post(APP_URL + '/inventario/default/get-modal-entrada-crear/', {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>"+nombre+"</strong></h2>",
            message: resp.plantilla,
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });

         $("#cmb_motivos").select2({
            placeholder: "Seleccione motivo"
        });

        $(document).ready(function () {

        	$("#cantidad_actual").text(cantidad);
        	$("#cantidad_nueva").text(cantidad);

        	$("#cantidad_a_ingresar").on('input', function () {

				if (/[^0-9]/.test($('#cantidad_a_ingresar').val() )) { 
		        	
		        	this.value = "";
		        	
		        	$("#cantidad_nueva").text(cantidad);
			    }else{

			    	if ($("#cantidad_a_ingresar").val() == "") {

			    		$("#cantidad_nueva").text(cantidad);
			    	}else{

			    		$("#cantidad_nueva").text(parseInt($("#cantidad_a_ingresar").val()) + parseInt(cantidad));
			    	}    	
			    }
			});
                

        	$("#btn-guardar").click(function () {

                $("#form-entrada").validate({
                    rules: {
                              
                        cantidad_a_ingresar: "required",
                        cmb_motivos: "required", 
                    },
                    messages: {
                       
                        cantidad_a_ingresar: "Por favor ingrese datos",
                    },
                    submitHandler: function () {

                        var cantidad_actual = $("#cantidad_actual").text();
                        var motivos = $("#cmb_motivos").val();
                        var cantidad_nueva = $("#cantidad_nueva").text();
                        var nota = $("#nota").val();
                        var cantidad_entrada_salida = $("#cantidad_a_ingresar").val();

                        
                            $.ajax({
                                type: "POST",
                                dataType: 'json',
                                url: APP_URL + '/inventario/default/createntrada',
                                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                data: {
                                   	
                                   	id_articulo: id,			
                                    id_motivo: motivos,
                                    nota: nota,
                                    id_operacion:1,
                                    cantidad_actual:cantidad,
                                    inventario_contable:cantidad_nueva,
                                    cantidad_entrada_salida:cantidad_entrada_salida,
                                    
                                },
                                success: function (response) {
                                    
                                    bootbox.hideAll();
                                    if (response) {

                                        notificacion('Accion realizada con exito', 'success');
                                    } else {
                                        notificacion('Error al guardar datos', 'error');
                                    }
                                    datatable.reload();
                                }
                            });
                        

                       
                    }
                });
             });

        });
    }, 'json');
}
