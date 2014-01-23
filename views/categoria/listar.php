<div class="link"><a href="app.php/Categoria/nuevo"><i class="fa fa-plus"></i> Nueva Categoria </a><br></div>
<br>

<div id="viewCategoria" class="viewList">
<table id="listaProductos" class="table table-bordered">
    <thead>
        <tr>
            <th>Código</th>
            <th>Descripción de la categoría</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($this->getListaEntidades() as $categoria):?>
        <tr id='Categoria_<?php echo $categoria->getId()?>'>
            <td class='td_id text-center'><?php echo $categoria->getId() ?></td>
            <td class='td_descripcion'><?php echo $categoria->getDescripcion() ?></td>
            <td class='text-center'>
                <span onclick="editEntity(<?php echo $categoria->getId()?>,'Categoria')" class="accion editar"  data-original-title="Editar el proyecto">
                    <i class="fa fa-pencil-square-o fa-2x fa-fw text-IconEditar"></i>
                </span>
                <span onclick="eliminarEntity(<?php echo $categoria->getId()?>,'Categoria')" class="accion editar"  data-original-title="Editar el proyecto">
                    <i class="fa fa fa-trash-o fa-2x fa-fw text-IconEliminar"></i>
                </span>
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
        <h4 class="modal-title" id="myModalLabel">Editar Categoria</h4>
      </div>
      <div class="modal-body">
          
          <form role="form" method="post" id="form_edit">
              <input type="hidden" value="Categoria" id="name_entity">
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
