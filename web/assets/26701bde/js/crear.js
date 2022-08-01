$(document).ready(function (){

    $("#id_articulos").select2({
        placeholder: "Seleccione articulos"
    })

    $("#id_cliente").select2({
        placeholder: "Seleccione cliente"
    })

    $("#tipo_documento").select2({
        placeholder: "Seleccione documento"
    })


    contador_ = 0;

    array_articulos = [];
    array_precio_uni = [];
    array_cantidad = [];
    array_subtotal = [];
    array_stock = [];

    $("#btn_ingresar_articulo").attr('disabled',true);
    $("#cantidad_elegida").attr('disabled',true);
    $("#tabla_articulos_venta").hide();

    $("#id_articulos").change(function (){
        
        array_datos_articulos = $(this).val();
        array_datos_articulos = array_datos_articulos.split("||");

        $("#cantidad_stock").val(array_datos_articulos[4]);
        $("#cantidad_elegida").prop("max",$("#cantidad_stock").val());
        $("#btn_ingresar_articulo").attr('disabled',false);
        $("#cantidad_elegida").attr('disabled',false);
    });


    $("#btn_limpiar_cliente").on('click', function (e) {

        $("#tipo_documento").attr('disabled',false);
        $("#tipo_documento").val(0);
        $("#tipo_documento").trigger('change');

        $("#numero_documento").attr('disabled',false);
        $("#numero_documento").val("");

        $("#id_cliente").val(0);
        $("#id_cliente").trigger('change');

        $("#nombrecliente").val("");
    })




    $("#id_cliente").change(function (){
        
        if ($("#id_cliente").val() == 1) {

            $("#numero_documento").attr('disabled',true);
            $("#numero_documento").val("00000000");

            $("#tipo_documento").val(1);
            $("#tipo_documento").trigger('change');

            $("#nombrecliente").val("GENERAL");
        }
    });
    



    $("#btn_ingresar_articulo").on('click', function (e) {
        
        if (parseInt($("#cantidad_elegida").val()) > parseInt($("#cantidad_stock").val()) ||
            parseInt($("#cantidad_elegida").val()) == 0) {

            notificacion('La cantidad ingresada es inválida', 'error');

        }else{

            array_datos_articulos = $("#id_articulos").val();
            array_datos_articulos = array_datos_articulos.split("||");

            $("#tabla_articulos_venta").show();

            if (contador_%2 == 0){
                color = "#eee";
            }else{
                color = "#ffffff;"
            }

            tabla_ = '<tr style="background: '+ color+';">'+
                    '<td style="padding: 5px 10px 5px 10px" align="right"><label id="tabla_id_articulo_'+contador_+'">'+array_datos_articulos[0]+'</label></td>'+
                    '<td style="padding: 5px 10px 5px 10px" align="right">'+array_datos_articulos[1]+'</td>'+
                    '<td style="padding: 5px 10px 5px 10px">'+array_datos_articulos[2]+'</td>'+
                    '<td style="padding: 5px 10px 5px 10px" align="right">'+array_datos_articulos[3]+'</td>'+
                    '<td style="padding: 5px 10px 5px 10px" align="right">'+ $("#cantidad_elegida").val()+'</td>'+
                    '<td style="padding: 5px 10px 5px 10px" align="right">'+parseFloat(($("#cantidad_elegida").val()*array_datos_articulos[3])).toFixed(2) +'</td>'+
                '</tr>';
                 
            if (contador_ > 0) {

                var row_repetido = 0;

                for (var i = 0; i < contador_; i++) {
                                          
                    if (array_datos_articulos[0] == array_articulos[i]) {
                           
                        row_repetido++;
                    }
                }


                if (row_repetido == 0) {

                    contador_++;
                            
                        array_articulos.push(array_datos_articulos[0]);
                        array_stock.push(array_datos_articulos[4]);
                        array_precio_uni.push(array_datos_articulos[3]);
                        array_cantidad.push(parseInt($("#cantidad_elegida").val()));
                        array_subtotal.push(parseFloat(($("#cantidad_elegida").val()*array_datos_articulos[3])).toFixed(2));

                        $("#tabla_articulos_venta").append(tabla_);     

                }else{

                    notificacion('El producto ya fue ingresado', 'error');
                }
            }
            else{
                array_articulos.push(array_datos_articulos[0]);
                array_precio_uni.push(array_datos_articulos[3]);
                array_stock.push(array_datos_articulos[4]);
                array_cantidad.push(parseInt($("#cantidad_elegida").val()));
                array_subtotal.push(parseFloat(($("#cantidad_elegida").val()*array_datos_articulos[3])).toFixed(2));
                        
                $("#tabla_articulos_venta").append(tabla_);
                contador_++;
            }

            //total
            total = 0;
            for (var j = 0; j < array_subtotal.length; j++) {

                total += parseFloat(array_subtotal[j]);
            }

            //limpiar   
            $("#cantidad_stock").val(0);
            $("#total_articulos").val(total.toFixed(2));
            $("#btn_ingresar_articulo").attr('disabled',true);

            $("#cantidad_elegida").val(1);
            $("#cantidad_elegida").attr('disabled',true);
        }   
    });


    $("#numero_documento").blur(function () {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: APP_URL + '/articulosventas/default/buscar-documento',
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',

            data: {
                numero_documento: $("#numero_documento").val(),
                tipo_documento: $("#tipo_documento").val()
            },
            success: function (response) {
                $("#nombrecliente").val(response);
            }
        });

    });


    $("#btn-guardar").click(function () {

        

        $("#frm-articulo-venta").validate({
            rules: {
                //id_cliente: "required",
                tipo_documento: "required",
                numero_documento: "required",
                nombrecliente: "required",
                fecha: "required",
                id_forma_pago:"required",
                id_tipo_comprobante:"required",
                id_articulos: "required",
                cantidad_elegida:"required",
                cantidad_stock:"required",
                total_articulos:"required",
            },
            messages: {
                //d_cliente: "Seleccione cliente",
                tipo_documento: "Seleccione tipo documento",
                //nombrecliente: "Ingrese datos",
                numero_documento: "Ingrese número documento",
                fecha: "Ingrese fecha",
                id_forma_pago:"Seleccione forma pago",
                id_tipo_comprobante:"Seleccione tipo comprobante",
                cantidad_elegida:"Elija la cantidad por producto",
                cantidad_stock:"Cantidad máxima",
                total_articulos: "Seleccione producto",
            },
            submitHandler: function () {

                var id_cliente = $("#id_cliente").val();
                var tipo_documento = $("#tipo_documento").val();
                var nombre_cliente = $("#nombrecliente").val();
                var numero_documento = $("#numero_documento").val();

                //cliente general sin datos
                if (id_cliente == 1) {

                    numero_documento = "00000000";
                    tipo_documento = 1;
                    nombre_cliente = "GENERAL"
                }

                //cliente general con datos
                if (id_cliente == null && nombre_cliente != "") {

                    id_cliente = 1;
                }

                var fecha = $("#fecha").val();
                var id_forma_pago = $("#id_forma_pago").val();
                var id_tipo_comprobante = $("#id_tipo_comprobante").val();
                //
                var cantidad_elegida = $("#cantidad_elegida").val();
                var cantidad_stock = $("#cantidad_stock").val();
                var total_articulos = $("#total_articulos").val();

                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: APP_URL + '/articulosventas/default/create',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    data: {

                        id_cliente: id_cliente,
                        tipo_documento: tipo_documento,
                        nombre_cliente: nombre_cliente,
                        numero_documento: numero_documento,
                        fecha: fecha,
                        id_tipo_comprobante: id_tipo_comprobante,
                        cantidad_elegida: cantidad_elegida,
                        cantidad_stock: cantidad_stock,
                        id_forma_pago: id_forma_pago,
                        total_articulos: total_articulos,

                        array_articulos: array_articulos,
                        array_precio_uni: array_precio_uni,
                        array_cantidad: array_cantidad,
                        array_stock: array_stock,
                        array_subtotal: array_subtotal,
                        
                    },
                    
                    success: function (response) {

                        window.location.href = APP_URL + '/articulosventas';
                    }, 

                    error: function (error) {

                        notificacion('Error al guardar datos', 'danger');
                    }
                });
            }
        });
    });

});
