"use strict";
 

$(document).ready(function () {

    var fecha = new Date();
    var hoy = fecha.getDate();
    var mes_actual = fecha.getMonth() + 1;
    var anio_actual = fecha.getFullYear();

    if (mes_actual <= 9) {
        mes_actual = "0" + mes_actual; 
    }

    if (hoy <= 9) {
        hoy = "0" + hoy; 
    }

    variables_grafica_hoy();

    function variables_grafica_hoy(){

        var horas_num = [];
        var array_hd_full = [];

        for (var i = 0; i <= 23; i++) { 
            
            if (i <= 9) {
                i = "0" + i; 
            }

            horas_num.push(i);
        }

        var dia_inicio = anio_actual + "-" + mes_actual + "-" + hoy + " 00:00:00";
        var dia_fin = anio_actual + "-" + mes_actual + "-" + hoy+ " 23:59:59";
        var tipo = "dia";
        var sub_i = 11;
        var sub_f = 13;

        arreglo_datos(dia_inicio,dia_fin,horas_num,tipo,sub_i,sub_f,array_hd_full);
    }


    function arreglo_datos(dia_inicio,dia_fin,array_hd,tipo,sub_i,sub_f,array_hd_full){

        $.ajax({
            type: "POST",
            dataType: 'json',
            url: APP_URL + '/articulosventas/default/total-ventas',
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',

            data: {
                dia_inicio: dia_inicio,
                dia_fin: dia_fin,
                tipo: tipo,
            },
            success: function (response) {

                var arr_total = [];
                var arr_fecha = [];

                $(response).each(function(i, v) {

                    arr_total.push(v.total);
                    arr_fecha.push(v.fecha_reg.substring(sub_i,sub_f));
                })

                var arr_total_dias = [];
                var contador = 0;

                for (var j = 0; j < array_hd.length; j++) {
                                
                    for (var k = 0; k < arr_fecha.length; k++) {
                                    
                        if (contador == 0) {

                            if (tipo == "mes") {

                                if (array_hd_full[j] == arr_fecha[k]) {

                                    arr_total_dias.push(arr_total[k]);
                                    contador ++;
                                }

                            }else if(tipo == "dia"){

                                if (array_hd[j] == arr_fecha[k]) {

                                    arr_total_dias.push(arr_total[k]);
                                    contador ++;
                                }
                            }  
                        }
                    }

                    if(contador == 0){

                        arr_total_dias.push("0.00");
                    }
                    contador = 0;
                }

                var total_ventas = 0;
                var dia_inicio_sub = dia_inicio.substring(8,10);
                var mes_inicio_sub = parseInt(dia_inicio.substring(5,7));
                var dia_fin_sub = dia_fin.substring(8,10);
                var mes_fin_sub = parseInt(dia_fin.substring(5,7));

                for (var i = 0; i < arr_total_dias.length; i++) {
                    total_ventas = parseFloat(total_ventas) + parseFloat(arr_total_dias[i]); 
                }
                
                $("#lbl_total_ventas").text("s/. "+total_ventas.toFixed(2));

                if (tipo == "mes") {

                    $("#lbl_fecha_rango").text("Ventas del "+dia_inicio_sub + " de " + obtenerNombreMes(mes_inicio_sub) +
                     " al " + dia_fin_sub + " de " + obtenerNombreMes(mes_fin_sub));

                }else if(tipo == "dia"){

                    $("#lbl_fecha_rango").text("Ventas del "+ dia_inicio_sub + " de " + obtenerNombreMes(mes_inicio_sub));
                }
 
                grafica(array_hd,arr_total_dias);
            }
        });
    }


    function obtenerNombreMes(numero) {

        let miFecha = new Date();
        
        if (0 < numero && numero <= 12) {

            miFecha.setMonth(numero - 1);
            return new Intl.DateTimeFormat('es-ES', { month: 'long'}).format(miFecha);
        } else {
            return null;
        }
    }
 


    $("#optiondia").click(function () {

       variables_grafica_hoy();
    })


    $("#optionsemana").click(function () {
        
        var n_dias = [];
        var n_dias_full = [];
        var sub_i = 0;
        var sub_f = 10
        var tipo = "mes";
        var dia = new Date(anio_actual+"-"+mes_actual+"-"+hoy +" 00:00:00").getDay();
        dia = (parseInt(hoy)-parseInt(dia));

        if (dia < 1) {

            dia_inicio = anio_actual +"-"+ mes_actual +"-"+ "01 00:00:00"
            dia_fin = anio_actual +"-"+ mes_actual +"-"+ "06 23:59:59"

            for (var i = 1; i <= 7; i++) {

                if (i <= 9) {
                    i = "0" + i; 
                }
                n_dias.push(parseInt(i));
                n_dias_full.push(anio_actual + "-" + mes_actual + "-" + i);
            }
        }
        else{

            var dia_fin_sem = parseInt(dia) + 6;

            for (var i = dia; i <= dia_fin_sem; i++) {

                if (dia <= 9) {
                    dia = "0" + dia; 
                }     

                if (dia_fin_sem <= 9) {
                    dia_fin_sem = "0" + dia_fin_sem; 
                }

                n_dias.push(parseInt(i));
                n_dias_full.push(anio_actual + "-" + mes_actual + "-" + i);
            }

            var dia_inicio = anio_actual +"-"+ mes_actual +"-"+ dia + " 00:00:00";
            var dia_fin = anio_actual +"-"+ mes_actual +"-"+ dia_fin_sem +" 23:59:59";
        }
        
        arreglo_datos(dia_inicio,dia_fin,n_dias,tipo,sub_i,sub_f,n_dias_full);
    })

    $("#optionmes").click(function () {

        var tipo = "mes";
        var n_dias = [];
        var n_dias_full = [];
        var sub_i = 0;
        var sub_f = 10;
        var dias_max = new Date(fecha.getFullYear(), fecha.getMonth() + 1, 0).getDate();
        var dia_inicio = anio_actual + "-" + mes_actual + "-" + "01 00:00:00";
        var dia_fin = anio_actual + "-" + mes_actual + "-" + dias_max + " 23:59:59";    

        for (var i = 1; i <= dias_max; i++) {   
            
            if (i <= 9) {
                i = "0" + i; 
            }
            n_dias.push(parseInt(i));
            n_dias_full.push(anio_actual + "-" + mes_actual + "-" + i);
        }

        arreglo_datos(dia_inicio,dia_fin,n_dias,tipo,sub_i,sub_f,n_dias_full);
    })
});



function grafica(array_hd,arr_total_dias){

    var options = {
        series: [{
            name: 'S/',
            data: arr_total_dias
        }],
            chart: {
            height: 370,
            type: 'area'
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth'
        },
        xaxis: {
            name: 'S/',
            categories: array_hd,
            } 
        };

    var chart = new ApexCharts(document.querySelector("#chart"), options);        
    chart.render();

    chart.destroy();
    var chart = new ApexCharts(document.querySelector("#chart"), options);     
    chart.render();
}








