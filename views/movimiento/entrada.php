<?php
    //** Se adiciona js solo para la vista entrada
    $this->addRutaJs("movimiento/entradas.js"); 
//    $this->addRutaJs("entradas.js"); 
?>


<h4>Entrada de Productos</h4>
<pre>
<?php var_dump($this->getBodegas());    ?>

</pre>
<form role="form" method="post" action="app.php/Movimiento/guardar">
 <div class="form-group">   
    <div class="row">
       <div class="col-md-1"><label for="exampleInputEmail1">Fecha:</label></div>
       <div class="col-md-2">
           <div class="date input-group input-append">
               <input type="text" class="form-control" value="" id="fecha" readonly="">
               <span class="input-group-addon add-on">
                   <i class="fa fa-calendar"></i>
               </span>
           </div>
       </div>
       <div class="col-md-1 col-md-offset-1"><label for="exampleInputEmail1">Bodega:</label></div>
       <div class="col-md-3">
           <select class="form-control" id="select_id_bodega">
               <?php foreach ($this->getBodegas() as $bodega):  ?>
                   <option value='<?php echo $bodega['id'] ?>'><?php echo $bodega['descripcion'] ?></option>
               <?php endforeach;?>
           </select>
       </div>
   </div>    
    <div class="row">
       <div class="col-md-1"><label for="exampleInputEmail1">Categoria:</label></div>
       <div class="col-md-3">
           <select class="form-control" id="select_id_categoria">
               <option ></option>
               <?php foreach ($this->getCategorias() as $categoria):  ?>
                   <option value='<?php echo $categoria['id'] ?>'><?php echo $categoria['descripcion'] ?></option>
               <?php endforeach;?>
           </select>
       </div>
       <div class="col-md-1"><label >Producto</label></div>
       <div class="col-md-4">
           <select class="form-control" id="select_id_producto" name="id_producto">
           </select>
       </div>
     </div>
   </div>
    <div class="row">
        <div class="form-group">
              <div class="col-md-1"><label >Cantidad:</label></div>
              <div class="col-md-2"><input type="text" class="form-control" id="input_existencia" name="existencia"></div>    
        </div>
    </div>
   <div class="row">   
      <div class="col-md-2 col-md-offset-8"><button type="submit" class="btn btn-default btn-info btn-sm">Guardar</button></div>
   </div>
</form>
<pre>
    <?php // var_dump($this->getCategorias());?>
</pre>
<pre><?php // var_dump($this->rutasJs);?></pre>
