function funcionDescargarExcel(fecha) {
    $("#loader").show();

    var fecha_texto = new Date().toLocaleDateString()

    var data = new FormData();

    data.append('fecha', fecha_texto);  

    var xhr = new XMLHttpRequest();
    xhr.open('POST', APP_URL + '/ventas/default/exportarexcel', true);
    xhr.responseType = 'blob';

    xhr.onload = function (e) {
        if (this.status == 200) {
            var blob = new Blob([this.response]);
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = "Lista-Ventas" + fecha_texto + ".xlsx";
            link.click();
            $("#loader").hide();
        }
    };

    xhr.send(data);
}

 