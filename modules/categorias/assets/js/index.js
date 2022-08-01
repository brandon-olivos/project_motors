"use strict";
var columnas = [
   
    {
        field: "id_categoria",
        title: "Código"
    },
    {
        field: "nombre_categoria",
        title: "Descripción"
    },
   
    {
        field: "accion",
        title: "Acciones"
    }
];

var datatable = iniciarTabla("#tabla-articuloscategoria", "/categorias/default/lista", "#tabla-articulos-categoria-buscar", columnas);
