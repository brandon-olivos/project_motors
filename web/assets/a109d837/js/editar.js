function calcular_precios(){
    
    unitario = $("#valor_articulo").val();
    porcentaje = $("#porcentaje_aumento_articulo").val();

    if (unitario == 0 || unitario == null || unitario == '') {

        $("#precio_bruto_articulo").val('0.00');
        $("#igv_articulo").val('0.00');
        $("#precio_venta_articulo").val('0.00');
    }

    else{

        if ($("#radio_precio_bruto").prop("checked") == true ){

            pv = 0; 
            igv = 0;

            if (porcentaje == 0 || porcentaje == null || porcentaje == '') {
                pb = parseFloat(unitario);
            }else{
                pb = parseFloat(unitario);
                pb = pb * (1+(porcentaje/100));
            }

            igv = parseFloat(pb * 0.18);
            pv = pb + igv;

            $("#precio_bruto_articulo").val(pb.toFixed(2));
            $("#igv_articulo").val(igv.toFixed(2));
            $("#precio_venta_articulo").val(pv.toFixed(2));
       }
       else if ($("#radio_precio_venta").prop("checked") == true ) {

            pb = 0;
            igv = 0;

            if (porcentaje == 0 || porcentaje == null || porcentaje == '') {
                pv = parseFloat(unitario);
            }else{
                pv = parseFloat(unitario);
                pv = pv * (1+(porcentaje/100));
            }

            pb = (pv * 100)/118;
            igv = pv - pb;

            $("#precio_bruto_articulo").val(pb.toFixed(2));
            $("#igv_articulo").val(igv.toFixed(2));
            $("#precio_venta_articulo").val(pv.toFixed(2));
       }     
    }
}


function funcionEditar(id,estado) {
    $.post(APP_URL + '/articulos/default/get-modal-edit/' + id, {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Editar Articulos</strong></h2>",
            message: resp.plantilla,
            size: 'large',
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });

         $("#id_categoria").select2({
            placeholder: "Seleccione categoria"
        });

         $("#id_marca").select2({
            placeholder: "Seleccione marca"
        });

        $(document).ready(function () {

            calcular_precios();

            $("#numero_serie_articulo").on('input',function(e){

                if (($(this).val()) == "") {

                    $("#barcode_cod").hide();
                    $("#codigo_barras_articulo").val('');
                }else{

                    $("#barcode_cod").show();
                    $("#codigo_barras_articulo").val(($(this).val()));
                    $("#barcode_cod").append('<script>$("#barcode_cod").JsBarcode("'+($(this).val())+'",{format:"CODE128",displayValue:true,fontSize:20,width:1,height:35});</script>');
                }  
            });
            
            $('#radio_precio_bruto').on('input',function(e){
                calcular_precios();
            });

            $('#radio_precio_venta').on('input',function(e){
                calcular_precios();
            });

            $('#valor_articulo').on('input',function(e){
                calcular_precios();
            });

            $('#porcentaje_aumento_articulo').on('input',function(e){
                calcular_precios();
            });


            $("#btn-guardar").click(function () {
                $("#form-articulos").validate({
                    rules: {
                        
                        nombre_articulo: "required",
                        //desc_articulo: "required",
                        id_categoria: "required",
                        codigo_barras_articulo: "required",
                        numero_serie_articulo: "required",
                        //precios
                        valor_articulo: "required",
                        tipo_precio_articulo: "required",
                        igv_articulo: "required", 
                        precio_bruto_articulo: "required",
                        precio_venta_articulo: "required",
                      
                    },
                    messages: {

                        nombre_articulo: "Por favor ingrese datos",
                        //desc_articulo: "Por favor ingrese datos",
                        id_marca: "Por favor seleccione marca",
                        id_categoria: "Por favor seleccione categoria",
                        codigo_barras_articulo: "Por favor ingrese datos",
                        numero_serie_articulo: "Por favor ingrese datos",
                        valor_articulo: "Por favor ingrese datos",
                        codigo_barras_articulo: "Por favor ingrese datos",
                        numero_serie_articulo: "Por favor ingrese datos",
                    
                    },
                    submitHandler: function () {

                        var nombre_articulo = $("#nombre_articulo").val();
                        var desc_articulo = $("#desc_articulo").val();
                        var id_marca = $("#id_marca").val();
                        var id_categoria = $("#id_categoria").val();
                        var valor_articulo = $("#valor_articulo").val();
                        var codigo_barras_articulo = $("#codigo_barras_articulo").val();
                        var numero_serie_articulo = $("#numero_serie_articulo").val();
                        var estado_articulo = estado;

                        var tipo_precio_articulo = '';

                        if ($("#radio_precio_venta").prop("checked") == true ) {
                            tipo_precio_articulo = 0;
                        }else{
                            tipo_precio_articulo = 1;
                        }

                        var porcentaje_aumento_articulo = $("#porcentaje_aumento_articulo").val().trim();
                        var igv_articulo = $("#igv_articulo").val().trim();
                        var precio_bruto_articulo = $("#precio_bruto_articulo").val().trim();
                        var precio_venta_articulo = $("#precio_venta_articulo").val().trim();                                         

                        if (valor_articulo != null || valor_articulo != "") {

                            $.ajax({
                            type: "POST",
                            dataType: 'json',
                            url: APP_URL + '/articulos/default/update',
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            data: {
                                
                                id_articulo: id,
                                id_marca: id_marca,
                                id_categoria: id_categoria,
                                nombre_articulo: nombre_articulo,
                                desc_articulo: desc_articulo,
                                valor_articulo: valor_articulo,
                                estado_articulo: estado_articulo,
                                codigo_barras_articulo: codigo_barras_articulo,
                                numero_serie_articulo: numero_serie_articulo,

                                tipo_precio_articulo: tipo_precio_articulo,
                                porcentaje_aumento_articulo: porcentaje_aumento_articulo,
                                igv_articulo: igv_articulo,
                                precio_bruto_articulo: precio_bruto_articulo,
                                precio_venta_articulo: precio_venta_articulo,
                                },
                                success: function (response) {
                                    bootbox.hideAll();
                                    if (response) {
                                        notificacion('Accion realizada con exito', 'success');
                                    } else {
                                        notificacion('Error al guardar datos', 'error');
                                    }

                                    if (estado == 0) {

                                        datatable_inactivo.reload();
                                    }else{
                                        datatable.reload()
                                    }
                                    
                                }
                            });
                        }
                        else {
                            notificacion('Error al guardar datos', 'error');
                        }  
                    }
                });
            });
        });
    }, 'json');
}
