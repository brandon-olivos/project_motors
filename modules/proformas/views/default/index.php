<?php

use app\modules\proformas\bundles\ProformasAsset;

$bundle = ProformasAsset::register($this);
?>
<!--begin::Card-->
<div class="card card-custom">
    <div class="card-header flex-wrap border-0 pt-6 pb-0">
        <div class="card-title">
            <h3 class="card-label">Lista de Proformas</h3>
        </div>
        <div class="card-toolbar">
            <!--begin::Button-->
              <!-- <a  onclick="funcionDescargarExcel()" class="btn btn-light-success mr-5"  >
                <i  class="icon-xl fas fa-file-excel "></i>
                
            </a> -->
            <a href="proformas/default/crear" class="btn btn-primary">
                <i class="text-white flaticon-file-1"></i>
                Registrar proforma
            </a>
            <!--end::Button-->
        </div>
    </div>
    <div class="card-body">

        <div class="mb-7">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="input-icon">
                        <input type="text" class="form-control" placeholder="Buscar..." id="tabla-proformas-buscar" />
                        <span>
                            <i class="flaticon2-search-1 text-muted"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="datatable datatable-bordered datatable-head-custom" id="tabla-proformas"></div>
        <!--end: Datatable-->
    </div>
</div>
<!--end::Card-->