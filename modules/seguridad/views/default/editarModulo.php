<form id="form-modulo">
    <div class="form-group">
        <label>Nombre Módulo<span class="text-danger">*</span></label>
        <input type="text" class="form-control" placeholder="Ingrese nombre del módulo" name="nombre" id="nombre" value="<?= $modulo->nombre_opcion ?>"/>
    </div>
    <div class="form-group">
        <label>Ruta<span class="text-danger">*</span></label>
        <input type="text" class="form-control" placeholder="Ingrese ruta" name="ruta" id="ruta" value="<?= $modulo->url ?>"/>
    </div>

    <div class="form-group">
        <label>Módulo Padre<span class="text-danger">*</span></label>
        <select class="form-control" name="padre" id="padre">


                <option value="0" <?= $modulo->flg_padre == 1 ? 'selected' : '' ?> >Padre</option>


                <?php foreach ($padres as $p): ?>

                    <option value="<?= $p->id_opcion ?>" 

                        <?= $modulo->id_padre == $p->id_opcion ? 'selected' : '' ?> >

                        <?= $p->nombre_opcion ?>
                            
                    </option>

                <?php endforeach; ?>

           
        </select>
    </div>

    <hr>
    <button class="btn btn-primary mr-2" id="btn-guardar">Actualizar</button>
    <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>
</form>