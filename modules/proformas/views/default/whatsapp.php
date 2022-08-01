<form id="form-enviar-wsp">

    <div class="row">   
        <div class="form-group col-md-12">
            <label>Celular<span class="text-danger">*</span></label>
            <input maxlength="9" type="number" class="form-control" placeholder="Ingrese celular" name="celular_wsp" id="celular_wsp" />
        </div>     
    </div>

    <div class="row">   
        <div class="form-group col-md-12">
            <label>Mensaje<span class="text-danger"></span></label>
            <textarea type="text" class="form-control" placeholder="Ingrese mensaje" name="msn_wsp" id="msn_wsp" value=""></textarea>
        </div>     
    </div>
    
    <hr>
    <button class="btn btn-primary mr-2" id="btn-guardar" onclick="enviarWsp()">Guardar</button>
    <a class="btn btn-secondary" id="btn-cancelar">Cancelar</a>
</form>

<script type="text/javascript">
    
$(document).ready(function (){

    $('#celular_wsp').on('input', function (e) {
        if (!/[^0-9]/.test(this.value)) {
            this.value = this.value.replace(/[^0-9]/,"");
        }
    });
});

</script>