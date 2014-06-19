<?php
    $this->addRutaJs("usuario/usuario.js"); 
?>


<h4>Nuevo Usuario</h4><br>

<form role="form" method="post" action="app.php/Producto/guardar">
    <div class="row">
    <div class="col-md-6">
        <div class="row">
         <div class="form-group">   
           <div class="col-md-4"><label for="exampleInputEmail1">Nombre Usuario:</label></div>
           <div class="col-md-6"><input type="text" class="form-control input-sm" id="inputDescripcion" name="nickname"></div>
         </div>
       </div>    
       <div class="row">
         <div class="form-group">   
           <div class="col-md-4"><label for="exampleInputEmail1">Nombre Completo:</label></div>
           <div class="col-md-6"><input type="text" class="form-control input-sm" id="inputCantidad" name="nombre"></div>
         </div>
       </div>      
   </div>      
   <br>
   <div class="col-md-5">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <label class="checkbox-inline">
                    <input type="checkbox" id="inlineCheckbox1" value="option1" name="opt" > Administrador
                </label>
            </div>
            <div class="row">
                <label class="checkbox-inline">
                    <input type="checkbox" id="inlineCheckbox2" value="option2" name="opt"> Usuario
                </label>
            </div>
            <div class="row">
                <label class="checkbox-inline">
                    <input type="checkbox" id="inlineCheckbox3" value="option3" name="opt"> Entradas
                </label>
            </div>
            <div class="row">
                <label class="checkbox-inline">
                    <input type="checkbox" id="inlineCheckbox3" value="option3" name="opt"> Salidas
                </label>
            </div>
            </div>
        </div>
    </div>
    </div>
    <div class="row">
        <div class="col-md-2 ">
            <a class="btn btn-default btn-sm" id="button_guardar_usuario" href="#">
               Guardar &nbsp;<i class="fa fa-floppy-o"></i></a>
        </div>
    </div>
  
    
    
    
    <div class="radio">
        <label>
            <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
            Option one is this and that&mdash;be sure to include why it's great
        </label>
    </div>
    <div class="radio">
        <label>
            <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">
            Option two can be something else and selecting it will deselect option one
        </label>
    </div>
    
    
    
    
<!--    <div class="row">
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
   </div>-->
</form>



