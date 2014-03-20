<!--<ul class="nav nav-pills nav-stacked">
  <li class="active"><a href="#"><i class="fa fa-home fa-fw"></i> Home</a></li>
  <li><a href="#"><i class="fa fa-book fa-fw"></i> Library</a></li>
  <li><a href="#"><i class="fa fa-pencil fa-fw"></i> Applications</a></li>
  <li><a href="#"><i class="fa fa-cogs fa-fw"></i> Settings</a></li>
</ul>
<i class="fa fa-spinner fa-spin"></i>
<i class="fa fa-refresh fa-spin"></i>
<i class="fa fa-cog fa-spin"></i>
<br>
<span class="fa-stack fa-lg">
  <i class="fa fa-camera fa-stack-1x"></i>
  <i class="fa fa-ban fa-stack-2x text-info"></i>
</span>-->

<?php // $this->verObj($this); ?>

<div class="link"><a href="app.php/Producto/nuevo"><i class="fa fa-plus"></i> Nuevo Producto </a><br></div>
<br>
<div id="viewProducts" class="viewList">
<table id="listaProductos" class="table table-bordered">
    <thead>
        <tr>
            <!--<th>Código</th>-->
            <!--<th>Ean 13</th>-->
            <th>Descripción Producto</th>
            <th>Bodega1</th>
            <th>Bodega2</th>
            <!--<th></th>-->
            <!--<th>Cantidad(gr)</th>-->
            <th>Existencia</th>
            <th>Categoria</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($this->getListaEntidades() as $producto):?>
        <tr id='Producto_<?php echo $producto->getId()?>'>
            <!--<td class='td_id text-center'><?php echo $producto->getId() ?></td>-->
            <!--<td class='td_codigo'><?php // echo $producto->getCodigo() ?></td>-->
            <td class='td_descripcion'><?php echo $producto->getDescripcion() ?></td>
            <td class='td_cantidad_gr'><?php echo $producto->getCantidad_gr() ?></td>
            <!--<td class='td_existencias'></td>-->
            <td class='td_existencias'></td>
            <td class='td_existencias'><?php echo $producto->getExistencia() ?></td>
            <td class='td_categoria'><?php echo $producto->getCategoriaDescripcion() ?></td>
            <td class='text-center'>
                <span onclick="editEntity(<?php echo $producto->getId()?>,'Producto')" class="accion editar"  data-original-title="Editar el proyecto">
                    <i class="fa fa-pencil-square-o fa-2x fa-fw text-IconEditar"></i>
                </span>
                <span  class="accion editar"  data-original-title="Editar el proyecto">
                    <a href="app.php/Movimiento/productomov1"><i class="fa fa-calendar fa-2x fa-fw text-IconMovimiento"></i></a>
                </span>
                <span onclick="eliminarEntity(<?php echo $producto->getId()?>,'Producto')" class="accion editar"  data-original-title="Editar el proyecto">
                    <i class="fa fa fa-trash-o fa-2x fa-fw text-IconEliminar"></i>
                </span>
            <!--<td><?php // var_dump($producto->getCategoriaDescripcion()); ?></td>-->
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>

<?php /*
 * Modal para mostrar la informacion al editar registro
 */ ?>
<!-- Modal -->
<div class="modal fade" id="ModalForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Editar Producto</h4>
      </div>
      <div class="modal-body">
          
          <form role="form" method="post" id="form_edit">
              <?php//Esta campo esta oculto para determinar desde common.js que controlador se esta trabajando?>
              <input type="hidden" value="Producto" id="name_entity">
              <div class="row">
                  <div class="form-group">
                      <div class="col-md-2"><label for="exampleInputEmail1">Código:</label></div>
                      <div class="col-md-2"><input type="text" class="form-control" id="input_id" name="id" readonly='readonly'></div>
                  </div>    
              </div>
              <div class="row">
                  <div class="form-group">
                      <div class="col-md-2"><label for="exampleInputEmail1">Descripción:</label></div>
                      <div class="col-md-6"><input type="text" class="form-control" id="input_descripcion" name="descripcion"></div>
                      <div class="col-md-2"><label for="exampleInputEmail1">Cantidad(gr):</label></div>
                      <div class="col-md-2"><input type="text" class="form-control" id="input_cantidad_gr" name="cantidad_gr"></div>
                  </div>
              </div>    
              <div class="row">
                  <div class="form-group">
                      <div class="col-md-2"><label for="exampleInputEmail1">Cod Ean 13:</label></div>
                      <div class="col-md-4"><input type="text" class="form-control" id="input_codigo" name="codigo"></div>
                      <div class="col-md-2"><label for="exampleInputEmail1">Categoría:</label></div>
                      <div class="col-md-4">
                        <select  class="form-control"  class="form-control" name="id_categoria" id="select_id_categoria">
                        </select>
                      </div>    
                  </div>
              </div>
          </form>
          
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal" id="buttonGuardarForm">Guardar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php /*
 * Modal para Notificar eliminacion del registro
 */ ?>
<!-- Modal -->
<div class="modal fade" id="ModalDeleteProd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Eliminar Producto</h4>
      </div>
      <div class="modal-body">
          <span class='text-danger'>Seguro desea eliminar el producto: <span class='idEntity'></span></span>          
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal" id='buttonEliminarReg' >Eliminar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
