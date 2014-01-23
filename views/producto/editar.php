<h4>Editar Producto: <?php echo $this->getSeleccionado()->getId() ?></h4><br>

<form role="form" method="post" action="app.php/Producto/guardar">
    <div class="row">
     <div class="form-group"> 
       
       <input type="text" id="inputid" name="id" value="<?php echo $this->getSeleccionado()->getId()?>">  
       <div class="col-md-1"><label for="exampleInputEmail1">Descripción:</label></div>
       <div class="col-md-4"><input type="text" class="form-control" id="inputDescripcion" name="descripcion" value="<?php echo $this->getSeleccionado()->getDescripcion()?>"></div>
       <div class="col-md-1"><label for="exampleInputEmail1">Cantidad (gr):</label></div>
       <div class="col-md-1"><input type="text" class="form-control" id="inputCantidad" name="cantidad_gr" value="<?php echo $this->getSeleccionado()->getCantidad_gr()?>"></div>
     </div>
   </div>    
    <div class="row">
     <div class="form-group">
       <div class="col-md-1"><label for="exampleInputEmail1">Código:</label></div>
       <div class="col-md-4"><input type="text" class="form-control" id="inputDescripcion" name="codigo" value="<?php echo $this->getSeleccionado()->getCodigo() ?>"></div>
       <div class="col-md-1"><label for="exampleInputEmail1">Tercero:</label></div>
       <div class="col-md-2"><input type="text" class="form-control" id="inputCantidad" name="tercero"></div>
     </div>
   </div>
   <div class="row">   
      <div class="col-md-2 col-md-offset-8"><button type="submit" class="btn btn-default btn-info btn-sm">Guardar</button></div>
   </div>
</form>
