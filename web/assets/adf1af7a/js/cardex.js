function funcionCardex(id,nombre) {
    $.post(APP_URL + '/articulosinventario/default/get-modal/', {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Cardex :  " + nombre + "</strong></h2>",
            message: resp.plantilla,
            size: 'extra-large',
            buttons: {}
        });

        $("#btn-cancelar").click(function () {
            bootbox.hideAll();
        });

        $(document).ready(function () {

        	"use strict";
			var columnas = [
			   
			    {
			        field: "fecha",
			        title: "Fecha y hora",
			        width: 150,
			    },
			    {
			        field: "nombre_operacion",
			        title: "Operación",
			        width: 120,
			    },
			    {
			        field: "nombre_motivo",
			        title: "Motivo",
			        width: 130,
			    },
			    {
			        field: "nota",
			        title: "Nota"
			    },{
			        field: "cantidad",
			        title: "Stock ant.",
			        width: 90,
        			textAlign: 'right'

			    },{
			        field: "entradasalida",
			        title: "In/out",
			        width: 90,
        			textAlign: 'right'

			    },{
			        field: "inventario_contable",
			        title: "Inv.Contable",
			        width: 100,
        			textAlign: 'right'
			    }
			    
			];

			var datatable = iniciarTabla("#tabla-cardex", "/articulosinventario/default/cardex/" + id, "#tabla-cardex-buscar", columnas);
			
        });
    }, 'json');
}
