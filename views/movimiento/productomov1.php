<?php $this->addRutaJs("movimiento/verMovimiento.js");  ?>
<pre>
    <?php
    $this->producto->getProductoBodegaCollection();
    print_r($this->producto);
    ?>
</pre>
<div id="" style="width: 95%; margin:auto">
    <h4>Movimiento</h4>
    <div class="panel panel-default">
        <div class="panel-heading">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#gestionar_credito">Principal</a></li>
                <li class=""><a data-toggle="tab" href="#gestionar_movimiento">Tercerizado</a></li>
            </ul>
        </div>    
        <div class="panel-body">
            <div class="tab-content">
                <div id="gestionar_credito" class="tab-pane fade active in">
                    <h3><?php echo $this->producto->getDescripcion() ?></h3><br>
                    <div class="row show-grid">
                        <div class="col-lg-3 ">
                            <label clas="descripcion_mov">Numero Averias:</label>
                            <!--Numero Averias:-->
                        </div>
                        <div class="col-lg-1 ">
                            <input type="text" class="form-control input-sm" disabled value="12">
                        </div>
                    </div>
                    <div class="row show-grid">
                        <div class="col-lg-3 ">
                            <label clas="descripcion_mov">Numero Devoluciones:</label>
                            <!--Numero Devoluciones:-->
                        </div>
                        <div class="col-lg-1 ">
                            <input type="text" class="form-control input-sm" disabled value="100">
                        </div>
                    </div>
                    <div class="row show-grid">
                        <div class="col-lg-3 ">
                            <label clas="descripcion_mov">Productos en Bodega:</label>
                            <!--Productos en Bodega:-->
                        </div>
                        <div class="col-lg-1 ">
                            <input type="text" class="form-control input-sm" disabled value="300">
                        </div>
                    </div><br><br>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <label>Movimientos</label>
                        </div>
                        <div class="panel-body">
                            <table class="table table-bordered tablaCondensada" id="tablaMovBod1">
                                <thead>
                                    <tr>
                                        <th>Documento</th>
                                        <th>Cantidad</th>
                                        <th>Categoria</th>
                                        <th>Usuario</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>


                <div id="gestionar_movimiento" class="tab-pane fade">
                    <h3><?php echo $this->producto->getDescripcion() ?></h3><br>

                </div>
            </div>
        </div>
    </div>
</div>

<br>

