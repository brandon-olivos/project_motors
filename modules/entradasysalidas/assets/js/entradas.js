"use strict";
var columnas = [
   
    {
        field: "fecha",
        title: "Fecha y hora"
    },
    {
        field: "nombre_articulo",
        title: "Producto"
    },
    
    {
        field: "nombre_operacion",
        title: "Operación"
    },
   
    {
        field: "nombre_motivo",
        title: "Motivo"
    },
   
    {
        field: "nota",
        title: "Nota"
    },
   
    // {
    //     field: "referencia",
    //     title: "Referencia"
    // },
   
    {
        field: "cantidad",
        title: "Cantidad(un)"
    },
   
    {
        field: "inventario_contable",
        title: "Inv.Contable"
    }
];

var datatable_entrada = iniciarTabla("#tabla-entradasalida-entrada", "/entradasysalidas/entrada/lista", "#tabla-entradasalida-entrada-buscar", columnas);
