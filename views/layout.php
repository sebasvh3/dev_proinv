<?php
//setlocale("LC_ALL", "en_US.utf8");
//setlocale("LC_ALL", "es_CO");
setlocale("LC_ALL", "es_CO.UTF-8");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr"> 
    <head>        
        <!--<base href="<?php // echo baseUrl;?>">-->
        <base href="http://inventario.mariscal/">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="stylesheet" type="text/css" href="resources/bootstrap3/css/bootstrap.css" media="screen"/>
        <link rel="stylesheet" type="text/css" href="resources/bootstrap3/css/bootstrap-theme.css" media="screen"/>
        <link rel="stylesheet" type="text/css" href="resources/font-awesome-4.0.3/css/font-awesome.css" media="screen"/>
        
        <link rel="stylesheet" type="text/css" href="resources/datatables/extras/ColReorder/media/css/ColReorder.css" media="screen"/>
        <link rel="stylesheet" type="text/css" href="resources/datatables/extras/ColVis/media/css/ColVis.css" media="screen"/>
        <link rel="stylesheet" type="text/css" href="resources/datatables/media/css/dataTables.bootstrap.css" media="screen"/>
        
        <link rel="stylesheet" type="text/css" href="resources/css/style.css" media="screen"/>
        
         <!--        caja de mensaje-->
        <title>Inventario</title>
    </head>

<!--    src="../recursos/imagenes/nuevo.png"
Class = "nuevo"
 Width = "22"
Height = "22"
alt="nuevo" />-->
    
    <body>
        <div id="contenedor">    
        <div id='header'>
            <!-- <img src='resources/images/CafeMariscal.jpg' width='100' alt='cafe Mariscal' >-->
            <?php if($this->getVistaAccion()!='login'): ?>
                <div  class="link"><a href="app.php"><i class="fa fa-minus"></i> Cerrar Sesión </a><br></div>    
            <?php  endif; ?>
        </div>
        
        <?php if($this->getVistaAccion()!='login'): ?>
        <?php $idTab = explode("/",$this->getVistaAccion());?>
        <input id="idtab" type="hidden" value="<?php echo $idTab[0];?>">    
        <br><br>  
            <ul class="nav nav-tabs" id="navPrincipal">
                <li id="inicio"><a href="app.php/Index/inicio">Inicio</a></li>
                <li id="producto"><a href="app.php/Producto/listar">Productos</a></li>
                <li id="categoria"><a href="app.php/Categoria/listar">Categorias</a></li>
                <li id="entrada"><a href="app.php/Index/entrada">Entrada</a></li>
                <li id="salida"><a href="app.php/Index/salida">Salida</a></li>
                
                <li class="dropdown active">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        Acciones <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Action</a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Another action</a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Something else here</a></li>
                        <li role="presentation" class="divider"></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Separated link</a></li>
                    </ul>
                </li>
            </ul>
          <br> 
        <?php endif; ?>
           
         <div class="row mensajes">
	  <div class="alert alert-success alert-dismissable col-md-8 col-md-offset-2 mensaje-success" style="display: none;">
	    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	    <span class="mensaje-body">hola que tal</span>
	  </div>

	  <div class="alert alert-info alert-dismissable col-md-8 col-md-offset-2 mensaje-info" style="display: none;">
	    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	    <span class="mensaje-body">hola que tal</span>
	  </div>

	  <div class="alert alert-warning alert-dismissable col-md-8 col-md-offset-2 mensaje-warning" style="display: none;">
	    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	    <span class="mensaje-body">hola que tal</span>
	  </div>

	  <div class="alert alert-danger alert-dismissable col-md-8 col-md-offset-2 mensaje-danger" style="display: none;">
	    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	    <span class="mensaje-body">hola que tal</span>
	  </div>
	</div>     
              
        <?php $this->getVista();?>

        
        </div><!--cierre del div id=contenedor-->
        <!--Inicio de los archivos js-->
        <script type="text/javascript" src="resources/js/jquery-1.10.2.js"></script>
        <script type="text/javascript" src="resources/bootstrap3/js/bootstrap.js"></script>
        
        <script type="text/javascript" src="resources/datatables/media/js/jquery.dataTables.js"></script>
        <script type="text/javascript" src="resources/datatables/media/js/dataTables.bootstrap.js"></script>
        <script type="text/javascript" src="resources/datatables/extras/ColReorder/media/js/ColReorder.js"></script>
        <script type="text/javascript" src="resources/datatables/extras/ColVis/media/js/ColVis.js"></script>
        <script type="text/javascript" src="resources/datatables/extras/FixedHeader/js/FixedHeader.js"></script>
        
        
        <script type="text/javascript" src="resources/js/common.js"></script>
        
        <div id="footer">    
        </div>
    </body>
</html>

<?php // $a=explode("/",$this->getVistaAccion());
//    var_dump($a[0]);
?>

<!--bundles/frontend/datatables/media/js/jquery.dataTables.js') }}"></script>
    frontend/datatables/media/js/dataTables.bootstrap.js-->