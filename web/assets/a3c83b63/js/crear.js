
$(document).ready(function (){

    var total = 0;

    $("#id_articulos").select2({
        placeholder: "Seleccione articulos"
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
        $("#precio_unidad").val(array_datos_articulos[3]);
        $("#cantidad_stock").val(array_datos_articulos[4]);
        $("#cantidad_elegida").prop("max",$("#cantidad_stock").val());
        $("#btn_ingresar_articulo").attr('disabled',false);
        $("#cantidad_elegida").attr('disabled',false);
    });


    // $("#mano_obra").change(function (){
        
    //     $("#total_articulos").val((total + parseFloat($("#otros").val()) + parseFloat($("#mano_obra").val())).toFixed(2));
    // });

    // $("#otros").change(function (){
        
    //     $("#total_articulos").val((total + parseFloat($("#otros").val()) + parseFloat($("#mano_obra").val())).toFixed(2));
    // });


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
            $("#precio_unidad").val();
            //$("#total_articulos").val((total + parseFloat($("#otros").val()) + parseFloat($("#mano_obra").val())).toFixed(2));
            $("#total_articulos").val((total).toFixed(2));
            $("#btn_ingresar_articulo").attr('disabled',true);

            $("#cantidad_elegida").val(1);
            $("#cantidad_elegida").attr('disabled',true);
        }   
    });


        $("#btn-guardar").click(function () {

        $("#frm-articulo-venta").validate({
            rules: {

                id_articulos: "required",
                cantidad_elegida:"required",
                cantidad_stock:"required",
                total_articulos:"required",
            },
            messages: {
                
                cantidad_elegida:"Elija la cantidad por producto",
                cantidad_stock:"Cantidad máxima",
                total_articulos: "Seleccione producto",
            },
            submitHandler: function () {

                var cantidad_elegida = $("#cantidad_elegida").val();
                var cantidad_stock = $("#cantidad_stock").val();
                var total_articulos = $("#total_articulos").val();

                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: APP_URL + '/proformas/default/create',
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    data: {

                        cantidad_elegida: cantidad_elegida,
                        cantidad_stock: cantidad_stock,
                        total_articulos: total_articulos,

                        array_articulos: array_articulos,
                        array_precio_uni: array_precio_uni,
                        array_cantidad: array_cantidad,
                        array_stock: array_stock,
                        array_subtotal: array_subtotal,     
                    },
                    
                    success: function (response) {

                        window.location.href = APP_URL + '/proformas';
                    }, 

                    error: function (error) {

                        notificacion('Error al guardar datos', 'danger');
                    }
                });
            }
        });
    });

});