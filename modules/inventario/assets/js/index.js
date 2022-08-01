"use strict";
var columnas = [
   
    {
        field: "id_articulo",
        title: "Código",
        width: 70
    },
    {
        field: "nombre_articulo",
        title: "Nombre",
        width: 300
    },
    {
        field: "entradas",
        title: "Entradas",
        width: 75
    },
    {
        field: "salidas",
        title: "Salidas",
        width: 75
    },{
        field: "cantidad_articulo",
        title: "Inventario",
        width: 85
    },{
        field: "accion",
        title: "Acciones",
        width: 220  
    }
    
];

var datatable = iniciarTabla("#tabla-articulos-inventario", "/inventario/default/lista", "#tabla-articulos-inventario-buscar", columnas);
