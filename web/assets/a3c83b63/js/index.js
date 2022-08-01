"use strict";



var columnas = [
    {
        field: "fecha",
        title: "Fecha y hora",
        //width: 150,

    },
    {
        field: "numero_proforma",
        title: "Nº Proforma",
        //width: 120
    },

    {
        field: "total",
        title: "Total",
        //width: 100,
        textAlign: 'right'
    },

    {
        field: "accion",
        title: "Detalle",    
    }
];

var datatable = iniciarTabla("#tabla-proformas", "/proformas/default/listaproformas", "#tabla-proformas-buscar", columnas);
