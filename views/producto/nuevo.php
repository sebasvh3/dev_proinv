<?php // $this->verObj($this->categorias)?>

<h4>Nuevo Producto</h4><br>

<form role="form" method="post" action="app.php/Producto/guardar">
    <div class="row">
     <div class="form-group">   
       <div class="col-md-1"><label for="exampleInputEmail1">Descripción:</label></div>
       <div class="col-md-4"><input type="text" class="form-control" id="inputDescripcion" name="descripcion"></div>
       <div class="col-md-1"><label for="exampleInputEmail1">Cantidad (gr):</label></div>
       <div class="col-md-1"><input type="text" class="form-control" id="inputCantidad" name="cantidad_gr"></div>
     </div>
   </div>    
    <div class="row">
     <div class="form-group">
       <div class="col-md-1"><label for="exampleInputEmail1">Cód Ean 13:</label></div>
       <div class="col-md-4"><input type="text" class="form-control" id="inputDescripcion" name="codigo"></div>
       <div class="col-md-1"><label for="exampleInputEmail1">Categoria:</label></div>
       <div class="col-md-2">
           <select  class="form-control"  name="id_categoria" >
               <option ></option>
               <?php foreach ($this->categorias as $categoria):  ?>
                   <option value='<?php echo $categoria['id'] ?>'><?php echo $categoria['descripcion'] ?></option>
               <?php endforeach;?>
           </select>
       </div>
     </div>
   </div>
   <div class="row">   
      <div class="col-md-2 col-md-offset-8"><button type="submit" class="btn btn-default btn-info btn-sm">Guardar</button></div>
   </div>
</form>



