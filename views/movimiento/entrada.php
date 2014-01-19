<h4>Entrada de Productos</h4>

<form role="form" method="post" action="">
    <div class="row">
     <div class="form-group">   
       <div class="col-md-1"><label for="exampleInputEmail1">Fecha:</label></div>
       <div class="col-md-2">
           <div class="date input-group input-append">
               <input type="text" class="form-control" value="" >
               <span class="input-group-addon add-on">
                   <i class="fa fa-calendar"></i>
               </span>
           </div>
       </div>
     </div>
   </div>    
    <div class="row">
     <div class="form-group">
       <div class="col-md-1"><label for="exampleInputEmail1">Categoria:</label></div>
       <div class="col-md-3">
           <select class="form-control">
               <option ></option>
               <?php foreach ($this->getCategorias() as $categoria):  ?>
                   <option value='<?php echo $categoria['id'] ?>'><?php echo $categoria['descripcion'] ?></option>
               <?php endforeach;?>
           </select>
       </div>
       <div class="col-md-1"><label >Producto</label></div>
       <div class="col-md-4">
           <select class="form-control">
               <option>Saborizado</option>
               <option>Marca de Terceros</option>
           </select>
       </div>
     </div>
   </div>
    <div class="row">
        <div class="form-group">
              <div class="col-md-1"><label for="exampleInputEmail1">Cantidad:</label></div>
              <div class="col-md-2"><input type="text" class="form-control"></div>    
        </div>
    </div>
   <div class="row">   
      <div class="col-md-2 col-md-offset-8"><button type="submit" class="btn btn-default btn-info btn-sm">Guardar</button></div>
   </div>
</form>

<pre>
    <?php var_dump($this->getCategorias());?>
</pre>

