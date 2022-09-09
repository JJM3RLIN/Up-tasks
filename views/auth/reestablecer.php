<div class="contenedor reestablecer">
<?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>
<?php if($mostrar){?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Coloca tu nuevo password</p>
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>
        <form class="formulario" method="POST">

            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="Tu password"/>
            </div>
            <button class="btn">Guardar password</button>
        </form>
<?php }?>
        <div class="acciones">
            <a href="/crear">Aún no tienes una cuenta?, obtener una</a>
            <a href="/olvide">¿Olvidaste tu contraseña?</a>
        </div>
    </div>
</div>