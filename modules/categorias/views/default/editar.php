<form id="form-articuloscategoria">

    <div class="row">   
        <div class="form-group col-md-12">
            <label>Descripción<span class="text-danger">*</span></label>
            <input type="text" class="form-control" placeholder="Ingrese descripción" name="nombre_categoria" id="nombre_categoria" value="<?= $articuloscategoria->nombre_categoria ?>"/>
        </div>     
    </div>

    <hr>
    <button class="btn btn-primary mr-2" id="btn-guardar">Guardar</button>
    <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>
</form>
