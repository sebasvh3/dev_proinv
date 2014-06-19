<div id="divlogin">
</br></br>    
<form class="form-signin" role="form" action="app.php/Index/inicio" method="post">
        <h2 class="form-signin-heading">Iniciar Sesion</h2>
        
        <div class="row show-grid">
            <input name="nickname" type="text" class="form-control" placeholder="Usuario" required autofocus>
        </div>        
        <div class="row show-grid">
            <input name="contrasena" type="password" class="form-control" placeholder="Contraseña" required>
        </div>        
        <div class="row show-grid">
            <label class="checkbox">
              <input type="checkbox" value="remember-me"> Recordar
            </label>
        </div>        
        <div class="row show-grid">
            <button class="btn btn-primary btn-block" type="submit">Iniciar</button>
        </div>        
</form>
</div>
<br><br><br>
<!--<a href="app.php/Categoria/probarGet?var=14&id=12">Probar Get</a>-->

<?php //  echo"<pre>";var_dump($this); ?>
