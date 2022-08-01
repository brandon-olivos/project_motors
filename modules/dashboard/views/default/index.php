<?php

use app\modules\dashboard\bundles\DashboardAsset;

$bundle = DashboardAsset::register($this);
?>

<!--begin::Card-->
<div class="card card-custom">
    <div style="margin-bottom: -20px;" class="card-header flex-wrap border-0 pt-6 pb-0">
        <div class="card-title">
            <h3 class="card-label">Estadísticas de ventas</h3>
        </div>     
    </div>

    <div class="card-body">

         <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-primary active">
                <input type="radio" name="options" id="optiondia" autocomplete="off" checked> Día
            </label>
            <label class="btn btn-primary">
                <input type="radio" name="options" id="optionsemana" autocomplete="off"> Semana
            </label>
            <label class="btn btn-primary">
                <input type="radio" name="options" id="optionmes" autocomplete="off"> Mes
            </label>
        </div>

        

        <div class="row">

            <div class="col-lg-5">              
                
                <div style="margin-bottom:20px; font-size:30px;color:#848d99;font-weight: 500;">
                    <br>
                    <label class="card-label" id="lbl_fecha_rango"></label>
                </div>  


                <div style="font-size:30px;color:#848d99;font-weight: 500;">
                    <label class="card-label">Total de ventas</label>
                </div>  

                <div style="font-size:80px; color:#68c6e8">
                    <label class="card-label" id="lbl_total_ventas"></label>
                </div>  

            </div> 

            <div class="col-lg-7">              
                <div class="card card-custom gutter-b">
                <div id="chart" style="min-height: 600px;">
                </div>

                </div>
            </div> 
        </div>
    </div>
</div>


