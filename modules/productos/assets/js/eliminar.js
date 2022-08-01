function funcionEliminar(id,estado) {

    var confirmetext = "";
    var confirmetext_2 = "";

    if (estado == 0) {

        confirmetext = "Inactivar";
        confirmetext_2 = "Inactivado"; 

    }else if(estado == 1){

        confirmetext = "Activar";
        confirmetext_2 = "Activado"; 
    }

    Swal.fire({
        title: "¿Está seguro?",
        //text: "¡No podrás revertir esto!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, ¡"+confirmetext+"!",
        cancelButtonText: "No, ¡cancelar!",
        reverseButtons: true
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: APP_URL + '/productos/default/delete',
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                data: {
                    id_articulo: id,
                    estado_articulo:estado
                },
                success: function (response) {
                    if (response > 0) {
                        Swal.fire(confirmetext_2 +"!", "El registro fue "+confirmetext_2+" correctamente.", "success")
                    }
                    datatable.reload();
                    datatable_inactivo.reload();
                }
            });
        } else if (result.dismiss === "cancel") {
            Swal.fire("Cancelado", "Tu registro está seguro.", "error")
        }
    });
}
