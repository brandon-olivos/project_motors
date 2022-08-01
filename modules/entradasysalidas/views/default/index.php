<?php

use app\modules\entradasysalidas\bundles\ArticulosentradasalidaAsset;

$bundle = ArticulosentradasalidaAsset::register($this);
?>
<!--begin::Card-->
<div class="card card-custom">
    <div class="card-header flex-wrap border-0 pt-6 pb-0">
        <div class="card-title">
            <h3 class="card-label">Lista de Entradas y Salidas</h3>
        </div>
        
    </div>
    <div class="card-body">


        <ul class="nav nav-tabs nav-tabs-line">

                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#tab_todos">Todos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab_entradas">Entradas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#tab_salidas">Salidas</a>
                    </li>
        </ul>
                
                <div class="tab-content mt-5" id="myTabContent">
                    
                    <div class="tab-pane fade show active" id="tab_todos" role="tabpanel">

                        <div class="row align-items-center pb-5">
                            <div class="col-md-12">
                                <div class="input-icon">
                                    <input type="text" class="form-control" placeholder="Buscar..." id="tabla-entradasalida-todos-buscar" />
                                    <span>
                                    <i class="flaticon2-search-1 text-muted"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!--begin: Datatable-->
                        <div class="datatable datatable-bordered datatable-head-custom" id="tabla-entradasalida-todos"></div>
                        <!--end: Datatable-->

                    </div>


                    <div class="tab-pane fade show" id="tab_entradas" role="tabpanel">
    
                        
                        <div class="row align-items-center pb-5">
                            <div class="col-md-12">
                                <div class="input-icon">
                                    <input type="text" class="form-control" placeholder="Buscar..." id="tabla-entradasalida-entrada-buscar" />
                                    <span>
                                    <i class="flaticon2-search-1 text-muted"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!--begin: Datatable-->
                        <div class="datatable datatable-bordered datatable-head-custom" id="tabla-entradasalida-entrada"></div>
                        <!--end: Datatable-->

                    </div>

                    <div class="tab-pane fade show" id="tab_salidas" role="tabpanel">
                        
                        <div class="row align-items-center pb-5">
                            <div class="col-md-12">
                                <div class="input-icon">
                                    <input type="text" class="form-control" placeholder="Buscar..." id="tabla-entradasalida-salida-buscar" />
                                    <span>
                                    <i class="flaticon2-search-1 text-muted"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!--begin: Datatable-->
                        <div class="datatable datatable-bordered datatable-head-custom" id="tabla-entradasalida-salida"></div>
                        <!--end: Datatable-->
               
                    </div>
                        
                </div>

    </div>
</div>
<!--end::Card-->