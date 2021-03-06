<div class="link"><a href="app.php/Producto/nuevo"><i class="fa fa-plus"></i> Nuevo Producto </a><br></div>
<div id="viewProducts" class="viewList">
<table id="listaProductos" class="table table-bordered tablaCondensada">
    <thead>
        <tr>
            <!--<th>Código</th>-->
            <!--<th>Ean 13</th>-->
            <th>Descripción Producto</th>
            <th>Categoria</th>
            <th>Tercero</th>
            <th>Principal</th>
            <th>Tercerizado</th>
            <!--<th></th>-->
            <!--<th>Cantidad(gr)</th>-->
            <!--<th>Existencia</th>-->
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($this->getListaEntidades() as $producto): ?>
            <tr id='Producto_<?php echo $producto->getId()?>'>
                <!--<td class='td_id text-center'><?php echo $producto->getId() ?></td>-->
                <!--<td class='td_codigo'><?php // echo $producto->getCodigo() ?></td>-->
                <td class=''><?php echo $producto->getDescripcion() ?></td>
                <td class='td_categoria'><?php echo $producto->getCategoriaDescripcion() ?></td>
                <td class='td_tercero'><?php echo $producto->getTerceroDescripcion() ?></td>
                <td class=''><?php echo $producto->getExistenciaEnBodega(Ambiente::$BodegaPrincipal) ?></td>
                <!--<td class='td_existencias'></td>-->
                <td class=''><?php echo $producto->getExistenciaEnBodega(Ambiente::$BodegaTercerizado) ?></td>
                <!--<td class='td_existencias'><?php // echo $producto->getExistencia() ?></td>-->
                <td class='text-center'>
                    <span onclick="editEntity(<?php echo $producto->getId()?>,'Producto')" class="accion editar"  data-original-title="Editar el proyecto">
                        <i class="fa fa-pencil-square-o fa-2x fa-fw text-IconEditar"></i>
                    </span>
                    <span  class="accion editar"  data-original-title="Editar el proyecto">
                        <a href="app.php/Movimiento/producto/<?php echo $producto->getId() ?>"><i class="fa fa-calendar fa-2x fa-fw text-IconMovimiento"></i></a>
                    </span>
                    <span onclick="eliminarEntity(<?php echo $producto->getId()?>,'Producto')" class="accion editar"  data-original-title="Editar el proyecto">
                        <i class="fa fa fa-trash-o fa-2x fa-fw text-IconEliminar"></i>
                    </span>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>

<?php //** Modal para mostrar la informacion al editar registro.  ?>
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
              <?php//*** Esta campo esta oculto para determinar desde common.js que controlador se esta trabajando?>
              <input type="hidden" value="Producto" id="name_entity">
              <div class="row">
                  <div class="form-group">
                      <div class="col-md-2"><label for="exampleInputEmail1">Código:</label></div>
                      <div class="col-md-2"><input type="text" class="form-control input-sm" id="input_id" name="id" readonly='readonly'></div>
                  </div>    
              </div>
              <div class="row">
                  <div class="form-group">
                      <div class="col-md-2"><label for="exampleInputEmail1">Descripción:</label></div>
                      <div class="col-md-6"><input type="text" class="form-control input-sm" id="input_descripcion" name="descripcion"></div>
                  </div>
              </div>    
              <div class="row">
                  <div class="form-group">
                      <div class="col-md-2"><label for="exampleInputEmail1">Cod Ean 13:</label></div>
                      <div class="col-md-6"><input type="text" class="form-control input-sm" id="input_codigo" name="codigo"></div>
                  </div>
              </div>
              <div class="row">
                  <div class="form-group">
                      <div class="col-md-2"><label for="exampleInputEmail1">Categoría:</label></div>
                      <div class="col-md-4">
                        <select  class="form-control input-sm" name="id_categoria" id="select_id_categoria">
                        </select>
                      </div>    
                      <div class="col-md-2"><label for="exampleInputEmail1">Tercero:</label></div>
                      <div class="col-md-4">
                        <select  class="form-control input-sm " name="id_tercero" id="select_id_tercero">
                        </select>
                      </div>    
                  </div>
              </div>
          </form>
          
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success btn-sm" data-dismiss="modal" id="buttonGuardarForm">Guardar</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cerrar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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
