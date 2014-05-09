<?php $this->addRutaJs("movimiento/verMovimiento.js");  ?>
<pre>
    <?php
    $bodegaPrincipal = Ambiente::$BodegaPrincipal;
    $bodegaTercerizado = Ambiente::$BodegaTercerizado;
    $aProdBodegas = $this->producto->getProductoBodegaCollection();
    
    $idProdBodp = array_key_exists($bodegaPrincipal  , $aProdBodegas) ? $aProdBodegas[$bodegaPrincipal]->getId()   : 0;
    $idProdBodt = array_key_exists($bodegaTercerizado, $aProdBodegas) ? $aProdBodegas[$bodegaTercerizado]->getId() : 0;
    
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
                asdfasdf
                <div id="gestionar_credito" class="tab-pane fade active in">
                    
                    <input type="hidden" value="<?php echo $idProdBodp ?>" id="input_principal">
                    <h4><?php echo $this->producto->getDescripcion() ?></h4><br>
                    <?php if($idProdBodp): require_once(rutaVistas."movimiento/bodega1.php"); ?>
                    <?php else: ?>
                        <div class="alert alert-danger alert-dismissable col-md-8 col-md-offset-2 mensaje-info" >
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <span class="mensaje-body">msj</span>
                        </div>
                    <?php endif; ?>
                </div>    

                <div id="gestionar_movimiento" class="tab-pane fade">
                    <input type="hidden" value="<?php echo $idProdBodt ?>" id="input_tercerizado">
                    <h4><?php echo $this->producto->getDescripcion() ?></h4><br>
                    <?php if($idProdBodp): require_once(rutaVistas."movimiento/bodega2.php"); ?>
                    <?php else: ?>
                        <div class="alert alert-danger alert-dismissable col-md-8 col-md-offset-2 mensaje-info" >
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <span class="mensaje-body">msj</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

