<?php

use app\modules\productos\bundles\ArticulosAsset;
$bundle = ArticulosAsset::register($this);

?>

<div class="card card-custom gutter-b">
    <div class="card-body">
        <div class="example-preview">
            <ul class="nav nav-tabs nav-tabs-line">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#tab_activos">Activos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab_inactivos">Inactivos</a>
                </li>
            </ul>
            <div class="tab-content mt-5" id="myTabContent">
                

                <div class="tab-pane fade show active" id="tab_activos" role="tabpanel">
                    <div class="row align-items-center pb-5">
                        <div class="col-md-9">
                            <div class="input-icon">
                                <input type="text" class="form-control" placeholder="Buscar..." id="tabla-articulos-buscar" />
                                <span>
                                    <i class="flaticon2-search-1 text-muted"></i>
                                </span>
                            </div>
                        </div>

                        <div class="col-md-3 text-right">

                            <button title="Reporte Excel" type="button" style="margin-right: 5px;" class="btn btn-primary btn-light-success btn-sm"  onclick="funcionDescargarReporte()" >
                                    <i class="icon-xl fas fa-file-excel "></i>
                            </button>

                           <button id="modal-articulos" class="btn btn-primary">
                                <i class="text-white flaticon-bag"></i>
                                Registrar Producto
                            </button>
                        </div>

                
                    </div>
                    <!--begin: Datatable-->
                    <div class="datatable datatable-bordered datatable-head-custom" id="tabla-articulos"></div>
                    <!--end: Datatable-->
                </div>


                <div class="tab-pane fade" id="tab_inactivos" role="tabpanel">
                    <div class="row align-items-center pb-5">
                        <div class="col-md-12">
                            <div class="input-icon">
                                <input type="text" class="form-control" placeholder="Buscar..." id="tabla-articulos-inactivos-buscar" />
                                <span>
                                    <i class="flaticon2-search-1 text-muted"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!--begin: Datatable-->
                    <div class="datatable datatable-bordered datatable-head-custom" id="tabla-articulos-inactivos"></div>
                    <!--end: Datatable-->
                </div>

            </div>
        </div>
    </div>
</div>

