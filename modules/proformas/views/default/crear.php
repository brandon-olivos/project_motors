<?php

use app\modules\proformas\bundles\ProformasAsset;

$bundle = ProformasAsset::register($this);
?>

<!--begin::Card-->
<div class="card card-custom gutter-b example example-compact">
    <!--begin::Form-->
    <form class="form" id="frm-articulo-venta">
        <div class="card-body">


            <div class="alert alert-custom alert-primary pt-1 pb-1 pl-1">
                <div class="alert-text font-weight-bold">Productos </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Seleccionar productos</label>
                        <select class="form-control form-control-sm" id="id_articulos" name="id_articulos">
                        <option disabled selected>Seleccione</option>
                            <?php foreach ($articulos as $v): ?>
                                <option value="<?= $v->id_articulo. '||' .$v->codigo_barras_articulo.  '||' .$v->nombre_articulo . '||' .$v->precio_venta_articulo . '||' .$v->cantidad_articulo?>"><?= $v->id_articulo. ' - ' .$v->nombre_articulo?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>


                <div class="col-md-1">
                    <div class="form-group">
                        <label>Cant.</label>
                        <input type="number" class="form-control form-control-sm" min="1" id="cantidad_elegida" name="cantidad_elegida" value="1">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label>P.Uni.</label>
                        <input type="number" disabled class="form-control form-control-sm" id="precio_unidad" name="precio_unidad">
                    </div>
                </div>

                <div class="col-md-1">
                    <div class="form-group">
                        <label>Stock</label>
                        <input type="number" disabled class="form-control form-control-sm" min="1" id="cantidad_stock" name="cantidad_stock" value="">
                    </div>
                </div>

                 <div class="col-md-2">
                    <div class="form-group">
                        <br>
                        <input type="button" class="btn btn-primary mr-2" id="btn_ingresar_articulo" value="Seleccionar">
                    </div>
                </div>
            </div>


           <div class="row">
                <div class="col-md-12">
                    <div class="form-group" id="div_table">
                        <table align="center" class="datatable-table" style="border-color: #e4e6ef;" border="1" id="tabla_articulos_venta">
                            <tr align="" style="background: #3699ff; color:#ffffff">
                                <td style="padding: 10px 10px;">ID</td>
                                <td style="padding: 10px 21px;">Codigo Barras</td>
                                <td style="padding: 10px 170px;">Producto</td>
                                <td style="padding: 10px 10px;">Precio Un.</td>
                                <td style="padding: 10px 10px;">Cantidad</td>
                                <td style="padding: 10px 13px;">SubTotal</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>


<!--             <div class="alert alert-custom alert-primary pt-1 pb-1 pl-1">
                <div class="alert-text font-weight-bold">Otros</div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Mano de obra</label>
                        <input type="number" class="form-control form-control-sm" min="0" id="mano_obra" name="mano_obra" value="0">
                    </div>
                </div>


               <div class="col-md-6">
                    <div class="form-group">
                        <label>Otros</label>
                        <input type="number" class="form-control form-control-sm" min="0" id="otros" name="otros" value="0">
                    </div>
                </div>

            </div> -->


            <div class="row">                     
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Total a pagar </label>
                        <input type="text"  disabled class="form-control form-control-sm" id="total_articulos" name="total_articulos" value="0.00">
                    </div>
                </div>            
            </div>
        </div>
        <div class="card-footer text-right">
            <button type="submit" class="btn btn-primary mr-2" id="btn-guardar">Guardar</button>
            <a href="<?= Yii::$app->urlManager->createUrl("proformas"); ?>" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
    <!--end::Form-->
</div>
<!--end::Card-->