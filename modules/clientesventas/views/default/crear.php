<?php

use app\modules\clientesventas\bundles\ClientesVentasAsset;

    $bundle = ClientesVentasAsset::register($this);
?>
<form id="frm-cliente-venta">

    <div class="form-group">
        <label>Tipo</label>
        <select class="form-control" id="tipo_entidad" name="tipo_entidad" style="width: 100%;">
            <option value="" disabled selected>Seleccione</option>
            <?php foreach ($tipo_entidad as $v): ?>
                <option value="<?= $v->id_tipo_entidad ?>"><?= $v->descripcion ?></option>
            <?php endforeach; ?>
        </select>
    </div>  

    <div class="row">
        <div class="form-group col-md-6">
             <label class="font-small">Tipo Documento </label>
                    <select class="form-control form-control-sm" id="tipo_documento" name="tipo_documento">
                        <?php foreach ($tipo_documento as $v) : ?>
                            <option value="<?= $v->id_tipo_documento ?>"><?= $v->documento ?></option>
                        <?php endforeach; ?>
                    </select>
            
        </div>  

        <div class="form-group col-md-6">
            <label>Numero Documento<span class="text-danger">*</span></label>
            <input type="text" style="text-transform:uppercase;" class="form-control" placeholder="Ingrese Numero Documento" name="numero_documento" id="numero_documento" 
                   value=""/>
        </div>
    </div>


    <div class="form-group">
        <label>Nombre<span class="text-danger">*</span></label>
        <input type="text" style="text-transform:uppercase;" class="form-control" placeholder="Ingrese Nombre" name="razon_social" id="razon_social" />
    </div>

    <div class="row">
        <div class="form-group col-md-6">
            <label>Telefono</label>
            <input type="text" style="text-transform:uppercase;" class="form-control" placeholder="Ingrese Telefono" name="telefono" id="telefono" />
        </div>

        <div class="form-group col-md-6">
            <label>Correo</label>
            <input type="email" style="text-transform:uppercase;" class="form-control" placeholder="Ingrese Correo" name="correo" id="correo" />
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-6">
            <label>Ubigeo<span class="text-danger">*</span></label>
            <select class="form-control select2" id="ubigeos" name="ubigeos" style="width: 100%;">
                <option value="" disabled selected>Seleccione</option>
                <?php foreach ($ubigeos as $v): ?>
                    <option value="<?= $v->id_ubigeo ?>"><?= $v->nombre_departamento . ' - ' . $v->nombre_provincia . ' - ' . $v->nombre_distrito ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group col-md-6">
            <label>Direccion<span class="text-danger">*</span></label>
            <input type="text" style="text-transform:uppercase;" class="form-control" placeholder="Ingrese Direccion" name="direccion" id="direccion" />
        </div>
    </div>


    <hr>

    <div class="text-right">
        <button class="btn btn-primary mr-2" id="btn-guardar-cliente">Guardar</button>
        <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>

    </div>
</form>
