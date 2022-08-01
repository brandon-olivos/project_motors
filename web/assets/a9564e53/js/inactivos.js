"use strict";
var columnas = [
   
    {
        field: "id_articulo",
        title: "Código",
        width: 70,
    },

    {
        field: "nombre_articulo",
        title: "Nombre",
        width: 200,
    },
    // {
    //     field: "desc_articulo",
    //     title: "Descripción"
    // },

    {
        field: "nombre_categoria",
        title: "Categoria",
        width: 120,
    },

    {
        field: "nombre_marca",
        title: "Marca",
        width: 100,
    },
    // {
    //     field: "cantidad_articulo",
    //     title: "Cantidad",
    //     width: 100
    // },
    
    {
        field: "precio_venta_articulo",
        title: "Precio de Venta",
        width: 70,
    },
    {
        field: "fecha_articulo",
        title: "Fecha",
        width: 130,
    },
   
    {
        field: "accion",
        title: "Acciones"
    }
];

var datatable_inactivo = iniciarTabla("#tabla-articulos-inactivos", "/articulos/inactivos/lista", "#tabla-articulos-inactivos-buscar", columnas);
