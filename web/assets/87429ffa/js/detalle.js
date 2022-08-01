
function verDetalleVenta(id_articulo_venta,numero_venta,fecha,nombre_cliente,tipo_doc_cliente,numero_doc_cliente,comprobante,forma_pago,nota,persona,usuario,total){

	$.post(APP_URL + '/ventas/default/get-modal-detalle/', {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Detalle de venta:  "+numero_venta+"</strong></h2>",
            message: resp.plantilla,
            size: 'large',
            buttons: {}
        }); 


        $.ajax({
            type: "POST",
            dataType: 'json',
            url: APP_URL + '/ventas/default/buscarotrosdetalles',
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',

            data: {
                numero_venta: numero_venta,
            },
            success: function (response) {
                console.log(response.length);
                if (response.length > 0) {
                    $("#lbl_mano_obra").text("S/. "+response[0].mano_obra);
                    $("#lbl_otros").text("S/. "+response[0].otros);
                }else{
                    $("#lbl_mano_obra").text("S/. 0.00");
                    $("#lbl_otros").text("S/. 0.00");
                }
            }
        });


        $("#lbl_fecha").text(fecha);
        $("#lbl_persona").text(usuario);
        $("#lbl_nombre_cliente").text(nombre_cliente);
        $("#lbl_doc_cliente").text(numero_doc_cliente);
        $("#lbl_comprobante").text(comprobante);
        $("#lbl_forma_pago").text(forma_pago);
        $("#lbl_total").text("S/. "+total);


        $(document).ready(function () {

            var columnas = [
                {
                    field: "cantidad",
                    title: "Cantidad",
                    width: 75,
                    textAlign: 'right'
                },
                {
                    field: "articulo",
                    title: "Descripción del producto",
                    width: 250
                },

                {
                    field: "precio_unitario",
                    title: "Precio unitario",
                    width: 90,
                    textAlign: 'right'
                },
                {
                    field: "subtotal",
                    title: "Valor total",
                    width: 90,
                    textAlign: 'right'
                }
            ];
            
            var datatable = iniciarTabla("#tabla-ventas-detalle", "/ventas/default/detalles/"+id_articulo_venta, "#tabla-ventas-detalle-buscar", columnas);
         
        });
    }, 'json');

}
