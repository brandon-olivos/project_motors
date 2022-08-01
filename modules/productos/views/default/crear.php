
<script src="../modules/productos/jsbarcode/CODE128.js"></script>
<script src="../modules/productos/jsbarcode/JsBarcode.js"></script>


<form id="form-articulos">

                <ul class="nav nav-tabs nav-tabs-line">
                    <li class="nav-item">
                        <a class="nav-link active" id="tb_info" data-toggle="tab" href="#tab_info_basica">Info. Básica</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" id="tb_precio" href="#tab_precios">Precios</a>
                    </li>

                    <li class="nav-item" hidden>
                        <a class="nav-link" data-toggle="tab" id="tb_marca" href="#tab_marca">Marca</a>
                    </li>

                    <li class="nav-item" hidden>
                        <a class="nav-link" data-toggle="tab"  id="tb_categoria" href="#tab_categoria">Categoría</a>
                    </li>
                </ul>
                <div class="tab-content mt-5" id="myTabContent">
                    
                    <div class="tab-pane fade show active" id="tab_info_basica" role="tabpanel">

                        <div class="row">   
                            <div class="form-group col-md-12">
                                <label>Nombre<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="Ingrese nombre" name="nombre_articulo" id="nombre_articulo" maxlength="200" />
                            </div>
                          
                        </div>


                        <div class="row" hidden>

                            <div class="form-group col-md-6">
                                <label>Código de barras<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="Ingrese código de barras" name="codigo_barras_articulo" id="codigo_barras_articulo" maxlength="200" />
                            </div>

                            
                        </div>


                        <div class="row">   
                            <div class="form-group col-md-12">
                                <label>Descripción</label>
                                <input type="text" class="form-control" placeholder="Ingrese descripción" name="desc_articulo" id="desc_articulo" maxlength="500" />
                            </div>

                        </div>

                        <div class="row">

                            <div class="form-group col-md-6">
                                <label>Número de serie<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="Ingrese número de serie" name="numero_serie_articulo" id="numero_serie_articulo" maxlength="200" />
                            </div>

                            <div class="form-group col-md-6">
                                <img id="barcode_cod"/>
                            </div>
                        </div>     



                        <div class="row">

                            <div class="form-group col-md-5">
                                <label>Marca<span class="text-danger">*</span></label>
                                <select class="form-control select2" id="id_marca" name="id_marca" style="width: 100%;">
                                    <option value="" disabled selected>Seleccione</option>

                                    <?php foreach ($articulosmarca as $v): ?>
                                        <option value="<?= $v->id_marca ?>"><?= $v->nombre_marca?></option>
                                    <?php endforeach; ?>

                                </select>
                            </div>

                            <div class="col-md-1" align="left" style="margin-left: -15px;margin-top: 7px;">
                                <button type="button" class="btn btn-icon btn-primary btn-sm mt-7" id="btn_crear_marca" name="btn_crear_marca"><i class="fa fa-plus"></i></button>
                            </div>


                            <div class="form-group col-md-5">
                                <label>Categoria<span class="text-danger">*</span></label>
                                <select class="form-control select2" id="id_categoria" name="id_categoria" style="width: 100%;">
                                    <option value="" disabled selected>Seleccione</option>

                                    <?php foreach ($articuloscategoria as $v): ?>
                                        <option value="<?= $v->id_categoria ?>"><?= $v->nombre_categoria?></option>
                                    <?php endforeach; ?>

                                </select>
                            </div>

                            <div class="col-md-1" align="left" style="margin-left: -15px; margin-top: 7px;">
                                <button type="button" class="btn btn-icon btn-primary btn-sm mt-7" id="btn_crear_categoria" name="btn_crear_categoria"><i class="fa fa-plus"></i></button>
                            </div>    
                        </div> 
                        
                    </div>







                    <div class="tab-pane fade" id="tab_precios" role="tabpanel">


                        <div class="row">

                            <div class="form-group col-md-6">

                                <label>Precio de venta</label>
                                <input checked style="width:10%" type="radio" name="radio_tipos_precios" id="radio_precio_venta"/>
                            </div> 

                            <div class="form-group col-md-6">

                                <label style="margin-top: -10px;">Precio bruto</label>
                                
                                <input style="width:10%;" type="radio" name="radio_tipos_precios" id="radio_precio_bruto"/> 
                            </div>
                        </div>

                        <div class="row">   
                            <div class="form-group col-md-6">

                                <label>Precio Unitario<span class="text-danger">*</span></label>
                                <input type="number" class="form-control" placeholder="Ingrese precio unitario" name="valor_articulo" id="valor_articulo"/>
                            </div>
                          
                            <div class="form-group col-md-6">

                                <label>% de aumento</label>
                                <input type="number" class="form-control" placeholder="Ingrese % de aumento" name="porcentaje_aumento_articulo" id="porcentaje_aumento_articulo"/>
                            </div>
                        </div>


                        <div class="row">   
                            <div class="form-group col-md-4">

                                <label>Precio bruto</label>
                                <input type="number" disabled class="form-control" name="precio_bruto_articulo" id="precio_bruto_articulo"/>
                            </div>

                            <div class="form-group col-md-4">

                                <label>IGV</label>
                                <input type="number" disabled class="form-control" name="igv_articulo" id="igv_articulo"/>
                            </div>

                            <div class="form-group col-md-4">

                                <label>Precio venta al público</label>
                                <input type="number" disabled class="form-control" name="precio_venta_articulo" id="precio_venta_articulo"/>
                            </div>
                          
                        </div>
                        
                    </div>


                    <div class="tab-pane fade" id="tab_marca" role="tabpanel">

                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>Nombre Marca</label>
                                <input type="text" class="form-control" placeholder="Ingrese descripción" name="nombre_marca_pr" id="nombre_marca_pr" maxlength="500" />
                            </div> 

                            <div class="form-group" style="margin-left: 10px;">
                                <input type="button" value="Guardar" class="btn btn-primary mr-2" id="btn-guardar-marca">

                                <input type="button" value="Cancelar" class="btn btn-secondary mr-2" id="btn-salir-marca">
                            </div>                   
                        </div>
                    </div>




                    <div class="tab-pane fade" id="tab_categoria" role="tabpanel">

                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>Nombre Categoría</label>
                                <input type="text" class="form-control" placeholder="Ingrese descripción" name="nombre_categoria_pr" id="nombre_categoria_pr" maxlength="500" />
                            </div> 

                            <div class="form-group" style="margin-left: 10px;">
                                <input type="button" value="Guardar" class="btn btn-primary mr-2" id="btn-guardar-categoria">

                                <input type="button" value="Cancelar" class="btn btn-secondary mr-2" id="btn-salir-categoria">
                            </div>                   
                        </div>
                    </div>
                </div>

  
    <hr>

    <div id="buttons_form">
    <button class="btn btn-primary mr-2" id="btn-guardar">Guardar</button>
    <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>
    </div>
</form>
