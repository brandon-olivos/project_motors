<?php

use app\modules\ubigeos\bundles\UbigeosAsset;

$bundle = UbigeosAsset::register($this);
?>
<!--begin::Card-->
<div class="card card-custom">
    <div class="card-header flex-wrap border-0 pt-6 pb-0">
        <div class="card-title">
            <h3 class="card-label">Lista de Ubigeoss Registradas </h3>
        </div>
        <div class="card-toolbar">
            <!--begin::Button-->
            <button id="modal-ubigeos" class="btn btn-primary">
                <i class="text-white flaticon-avatar"></i>
                Registrar Ubigeos
            </button>
            <!--end::Button-->
        </div>
    </div>
    <div class="card-body">
        <!--begin: Search Form-->
        <!--begin::Search Form-->
        <div class="mb-7">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="input-icon">
                        <input type="text" class="form-control" placeholder="Buscar..." id="tabla-ubigeos-buscar" />
                        <span>
                            <i class="flaticon2-search-1 text-muted"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Search Form-->
        <!--end: Search Form-->
        <!--begin: Datatable-->
        <div class="datatable datatable-bordered datatable-head-custom" id="tabla-ubigeos"></div>
        <!--end: Datatable-->
    </div>
</div>
<!--end::Card-->