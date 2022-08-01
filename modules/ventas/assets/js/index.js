"use strict";



var columnas = [
    {
        field: "fecha",
        title: "Fecha y hora",
        width: 150,

    },
    {
        field: "comprobante",
        title: "Comprobante",
        width: 110
    },
    {
        field: "numero_venta",
        title: "Nº venta",
        width: 120
    },

    {
        field: "nombre_cliente",
        title: "Cliente",
        width: 280,
    },
    // {
    //     field: "forma_pago",
    //     title: "Forma de pago",
    //     width: 120,
    // },

    {
        field: "total",
        title: "Total",
        width: 90,
        textAlign: 'right'
    },

    {
        field: "accion",
        title: "Detalle",
        
    }
];

var datatable = iniciarTabla("#tabla-ventas", "/ventas/default/listaventas", "#tabla-ventas-buscar", columnas);
