
$(document).ready(function (){

    var id_proforma_g = 0;
    var numero_proforma_g = "";
}); 

function abrirWhatsapp(id_proforma,numero_proforma){

	$.post(APP_URL + '/proformas/default/get-modal-whatsapp/', {}, function (resp) {
        bootbox.dialog({
            title: "<h2><strong>Enviar proforma "+numero_proforma+"</strong></h2>",
            message: resp.plantilla,
            //size: 'large',
            buttons: {}
        });
    }, 'json');

    id_proforma_g = id_proforma;
    numero_proforma_g  = numero_proforma;
}


function enviarWsp(){

    $("#form-enviar-wsp").validate({
        rules: {
                                 
            celular_wsp: "required",
            msn_wsp: "required",               
        },
        messages: {
                       
            celular_wsp: "Por favor ingrese datos",
            msn_wsp: "Por favor ingrese datos",          
        },
        submitHandler: function () {

            var win = "https://api.whatsapp.com/send?phone=+51"+$('#celular_wsp').val()+"&text="+$('#msn_wsp').val();
            window.open(win);
        }
    });
}










