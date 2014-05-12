<div class="row show-grid">
    <div class="col-lg-3 ">
        <label clas="descripcion_mov">Numero Averias:</label>
        <!--Numero Averias:-->
    </div>
    <div class="col-lg-1 ">
        <input type="text" class="form-control input-sm" disabled value="<?php echo number_format($aProdBodegas[$bodegaTercerizado]->getAverias(),0,".","") ?>">
    </div>
</div>
<div class="row show-grid">
    <div class="col-lg-3 ">
        <label clas="descripcion_mov">Numero Devoluciones:</label>
        <!--Numero Devoluciones:-->
    </div>
    <div class="col-lg-1 ">
        <input type="text" class="form-control input-sm" disabled value="<?php echo number_format($aProdBodegas[$bodegaTercerizado]->getDevs(),0,".","") ?>">
    </div>
</div>
<div class="row show-grid">
    <div class="col-lg-3 ">
        <label clas="descripcion_mov">Productos en Bodega:</label>
        <!--Productos en Bodega:-->
    </div>
    <div class="col-lg-1 ">
        <input type="text" class="form-control input-sm" disabled value="<?php echo number_format($aProdBodegas[$bodegaTercerizado]->getExistencia(),0,".","") ?>">
    </div>
</div><br><br>

<div class="panel panel-default">
    <div class="panel-heading">
        <label>Movimientos</label>
    </div>
    <div class="panel-body">
        <table class="table table-bordered tablaCondensada" id="tablaMovBod2">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Documento</th>
                    <th>Transaccion</th>
                    <th>Cantidad</th>
                    <th>Usuario</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
