<div class="contenedor reestablecer">

    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Coloca tu nuevo password</p>

        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

        <?php if ($mostrar) { ?>

            <!-- Sin action ya que contamos con un token en el GET -->
            <form class="formulario" method="POST">
                <div class="campo">
                    <label for="password">Password</label>
                    <input type="password" id="password" placeholder="Tu password" name="password">
                </div>
                <input type="submit" class="boton" value="Actualizar Password">
            </form>

        <?php } ?>

        <div class="acciones">
            <a href="/crear">¿Aun no tienes una cuenta? Obtener una!</a>
            <a href="/olvide">¿Olvidaste tu password?</a>
        </div>

    </div> <!-- .contenedor-sm -->

</div>