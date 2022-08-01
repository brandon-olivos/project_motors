
<form id="form-detalle-venta">

	<div class="row">

        <div class="form-group input-group-sm col-md-12">

        	<table>
        		<tr>
        			<td><label style="font-size:12px;margin-top: 5px;padding: 1px 10px;">Fecha y hora: </label></td>
        			<td><span style="font-size:14px;padding: 1px 10px;" id="lbl_fecha"></span></td>
        		
                    <td></td>
                    <td></td>
                
        			<td><label style="font-size:12px;margin-top: 5px;padding: 1px 10px;">Cajero: </label></td>
        			<td><span style="font-size:14px; padding: 1px 10px;" id="lbl_persona"></span></td>
        		</tr>

        		<tr>
        			<td><label style="font-size:12px;margin-top: 5px;padding: 1px 10px;">Cliente: </label></td>
        			<td><span style="font-size:14px;padding: 1px 10px;" id="lbl_nombre_cliente"></span> </td>
        		      
                    <td></td>
                    <td></td>

        			<td><label style="font-size:12px;margin-top: 5px;padding: 1px 10px;">Doc. cliente: </label></td>
        			<td><span style="font-size:14px;padding: 1px 10px;" id="lbl_doc_cliente"></span></td>
        		</tr>

        		<tr>
        			<td><label style="font-size:12px;margin-top: 5px;padding: 1px 10px;">Comprobante: </label></td>
        			<td><span style="font-size:14px;padding: 1px 10px;" id="lbl_comprobante"></span></td>
        		      
                    <td></td>
                    <td></td>

        			<td><label style="font-size:12px;margin-top: 5px;padding: 1px 10px;">Forma de pago: </label></td>
        			<td><span style="font-size:14px;padding: 1px 10px;" id="lbl_forma_pago"></span></td>
        		</tr>

                <tr>
                    <td><label style="font-size:12px;margin-top: 5px;padding: 1px 10px;">Mano de Obra: </label></td>
                    <td><span style="font-size:14px;padding: 1px 10px;" id="lbl_mano_obra"></span></td>
                      
                    <td></td>
                    <td></td>

                    <td><label style="font-size:12px;margin-top: 5px;padding: 1px 10px;">Otros: </label></td>
                    <td><span style="font-size:14px;padding: 1px 10px;" id="lbl_otros"></span></td>
                </tr>

                <tr>


                    <td><label style="font-size:20px;margin-top: 5px;padding: 1px 10px;color: #3699ff;">TOTAL: </label></td>
                    <td><span style="font-size:20px;padding: 1px 10px;color: #3699ff;" id="lbl_total">S/. </span></td>
                      
                </tr>

        	</table>

        </div>

    </div>


    <div class="row"> 

        <div class="form-group input-group-sm col-md-12">
           
            <input hidden type="text" id="tabla-ventas-detalle-buscar" />
            <div class="datatable datatable-bordered datatable-head-custom" id="tabla-ventas-detalle"></div>

        </div>     
    </div>

    <div class="row"> 

        <div class="form-group input-group-sm col-md-12">
           
            <input hidden type="text" id="tabla-ventas-detalle-buscar" />
            <div class="datatable datatable-bordered datatable-head-custom" id="tabla-ventas-detalle"></div>

        </div>     
    </div>
   
    <hr>
</form>
