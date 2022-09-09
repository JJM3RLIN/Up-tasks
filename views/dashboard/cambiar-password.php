<?php include_once __DIR__ . '/header-dashboard.php'; ?>
<div class="contenedor-sm">
    <?php include_once __DIR__ . '/../templates/alertas.php';?>
    <a href="/perfil" class="enlace">Regresar al perfil</a>
    <form method="POST" action="/cambiar-password" class="formulario">
        <div class="campo">
            <label for="passwordActual">Password actual</label>
            <input 
            type="password" 
            value=""
            id="passwordActual"
            name="passwordActual"
            placeholder="Tu password actual"
            />
        </div>

        <div class="campo">
            <label for="passwordNuevo">Nuevo password</label>
            <input 
            type="password" 
            value=""
            id="passwordNuevo"
            name="passwordNuevo"
            placeholder="Tu nuevo password"
            />
        </div>
        <input type="submit" value="Guardar cambios" />
    </form>
</div>
<?php include_once __DIR__ . '/footer-dashboard.php'; ?>