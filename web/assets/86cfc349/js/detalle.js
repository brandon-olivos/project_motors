
function verDetalleVenta(id_articulo_venta,numero_venta,fecha,nombre_cliente,tipo_doc_cliente,numero_doc_cliente,comprobante,forma_pago,nota,persona,usuario,total){

	$.post(APP_URL + '/articulosventas/default/get-modal-detalle/', {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Detalle de venta:  "+numero_venta+"</strong></h2>",
            message: resp.plantilla,
            size: 'large',
            buttons: {}
        }); 

        $("#lbl_fecha").text(fecha);
        $("#lbl_persona").text(usuario);
        $("#lbl_nombre_cliente").text(nombre_cliente);
        $("#lbl_doc_cliente").text(numero_doc_cliente);
        $("#lbl_comprobante").text(comprobante);
        $("#lbl_forma_pago").text(forma_pago);

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
            
            var datatable = iniciarTabla("#tabla-ventas-detalle", "/articulosventas/default/detalles/"+id_articulo_venta, "#tabla-ventas-detalle-buscar", columnas);
         
        });
    }, 'json');

}
