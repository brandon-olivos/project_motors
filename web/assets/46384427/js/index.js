"use strict";
var columnas = [
   
    {
        field: "id_marca",
        title: "Código"
    },
    {
        field: "nombre_marca",
        title: "Descripción"
    },
   
    {
        field: "accion",
        title: "Acciones"
    }
];

var datatable = iniciarTabla("#tabla-articulosmarca", "/marcas/default/lista", "#tabla-articulos-marca-buscar", columnas);
