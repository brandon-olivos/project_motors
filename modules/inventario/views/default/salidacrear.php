<form id="form-salida">

    <div class="row">

        <div class="form-group col-md-6">
            <label>Cantidad a disminuir(un)<span class="text-danger">*</span></label>
            <input  type="number" class="form-control" placeholder="Debes ingresar un valor mayor a 1" name="cantidad_a_ingresar" id="cantidad_a_ingresar"/>
        </div>

        <div class="form-group col-md-1">
        </div>
        <div class="form-group col-md-5">

            <br>
            <label style="font-size: 15px;">Inventario actual : <span id="cantidad_actual" name="cantidad_actual"></span></label>
            <br>
            <strong class="text-primary" style="font-size: 15px;">Nuevo inventario : <span class="text-primary" id="cantidad_nueva" name="cantidad_nueva"></span></strong>
        </div>
    </div>

    
    <div class="row">

        <div class="form-group col-md-12">
            <label>Motivo<span class="text-danger">*</span></label>
            <select class="form-control select2" id="cmb_motivos" name="cmb_motivos" style="width: 100%;">
                <option value="" disabled selected>Seleccione</option>
                <?php foreach ($motivos as $v): ?>
                    <option value="<?= $v->id_motivo ?>"><?= $v->nombre_motivo ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="row">

        <div class="form-group col-md-12">
            <label>Nota<span class="text-danger"></span></label>
            <input type="text" class="form-control" placeholder="Ingrese nota" name="nota" id="nota" />
        </div>

    </div>

  
    <hr>
    <button class="btn btn-primary mr-2" id="btn-guardar">Guardar</button>
    <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>
</form>