<div class="contenedor olvide">

    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Recupera tu Acceso UpTask</p>
        <form class="formulario" method="POST" action="/olvide">
            <div class="campo">
                <label for="password">Password</label>
                <input type="password" id="password" placeholder="Tu password" name="password">
            </div>
            <input type="submit" class="boton" value="Recuperar Acceso">
        </form>

        <div class="acciones">
            <a href="/">¿Ya tienes una cuenta? Iniciar Sesion!</a>
            <a href="/crear">¿Aun no tienes una cuenta? Obtener una!</a>
        </div>

    </div> <!-- .contenedor-sm --> 
    
</div>