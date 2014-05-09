<?php $this->addRutaJs("movimiento/verMovimiento.js");  ?>
<pre>
    <?php
    $this->producto->getProductoBodegaCollection();
    print_r($this->producto);
    ?>
</pre>
<div id="" style="width: 95%; margin:auto">
    <h3>Movimiento</h3>
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
                    <h4><?php echo $this->producto->getDescripcion() ?></h4><br>
                    <?php require_once(rutaVistas."movimiento/bodega1.php"); ?>
                </div>    

                <div id="gestionar_movimiento" class="tab-pane fade">
                    <h4><?php echo $this->producto->getDescripcion() ?></h4><br>
                    <?php require_once(rutaVistas."movimiento/bodega1.php"); ?>    
                </div>
            </div>
        </div>
    </div>
</div>

