<?php
    $this->addRutaJs("movimiento/transacciones.js"); 
?>

<h4>Entrada de Productos</h4>
<pre>
<?php // var_dump($this->getBodegas());    ?>
</pre>
<form role="form" method="post" id="form-entrada" >
 <div class="form-group">   
    <div class="row">
       <div class="col-md-1"><label for="exampleInputEmail1">Fecha:</label></div>
       <div class="col-md-2">
           <div class="date input-group input-append">
               <input type="text" class="form-control input-sm" value="" id="fecha" readonly="" name="fecha_trans">
               <span class="input-group-addon add-on">
                   <i class="fa fa-calendar"></i>
               </span>
           </div>
       </div>
       <div class="col-md-1 col-md-offset-1"><label for="exampleInputEmail1">Bodega:</label></div>
       <div class="col-md-3">
           <select class="form-control input-sm" id="select_id_bodega" name="id_bodega">
               <?php foreach ($this->getBodegas() as $bodega):  ?>
                   <option value='<?php echo $bodega['id'] ?>'><?php echo $bodega['descripcion'] ?></option>
               <?php endforeach;?>
           </select>
       </div>
   </div>    
    <div class="row">
       <div class="col-md-1"><label for="exampleInputEmail1">Categoria:</label></div>
       <div class="col-md-3">
           <select class="form-control input-sm" id="select_id_categoria">
               <option ></option>
               <?php foreach ($this->getCategorias() as $categoria):  ?>
                   <option value='<?php echo $categoria['id'] ?>'><?php echo $categoria['descripcion'] ?></option>
               <?php endforeach;?>
           </select>
       </div>
       <div class="col-md-1"><label >Producto</label></div>
       <div class="col-md-4">
           <select class="form-control input-sm" id="select_id_producto" name="id_producto">
               <!--<option></option>-->
           </select>
       </div>
     </div>
   </div>
    <div class="row">
        <div class="form-group">
              <div class="col-md-1"><label >Cantidad:</label></div>
              <div class="col-md-2"><input type="text" class="form-control input-sm" id="input_existencia" name="cant_trans"></div>    
              <div class="col-md-1 col-md-offset-1"><label >Documento:</label></div>
              <div class="col-md-2"><input type="text" class="form-control input-sm" id="input_documento" name="documento"></div>    
        </div>
    </div>
</form>
<div class="row">   
  <div class="col-md-2 col-md-offset-8"><button  class="btn btn-default btn-info btn-sm" id="button_guardar_salida">Guardar</button></div>
</div>

