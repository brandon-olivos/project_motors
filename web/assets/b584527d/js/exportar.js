 function funcionDescargarReporte() {

    //animacion de carga
    $("#loader").show();

    var fecha_texto = new Date().toLocaleDateString();
    
    //envia datos del formulario index    
    var data = new FormData();
    data.append('buscar', $("#tabla-articulos-buscar").val());

    var xhr = new XMLHttpRequest();
    xhr.open('POST', APP_URL + '/productos/default/exportar', true);
    xhr.responseType = 'blob';

    xhr.onload = function (e) {
        if (this.status == 200) {
            var blob = new Blob([this.response]);
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = "Reporte-productos" + fecha_texto + ".xlsx";
            link.click();
            $("#loader").hide();
        }
    };
   xhr.send(data); 
}
 