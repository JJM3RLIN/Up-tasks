<div class="contenedor crear">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crear una cuenta en UpTask</p>
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>
        <form class="formulario" method="POST" action="/crear">
        <div class="campo">
                <label for="nombre">Nombre</label>
                <input 
                type="text" 
                id="nombre" 
                name="nombre" 
                placeholder="Tu nombre"
                value="<?php echo $usuario->email; ?>"
                />
            </div>
        
            <div class="campo">
                <label for="email">Email</label>
                <input 
                type="email" 
                id="email" 
                name="email" 
                placeholder="Tu email"
                value="<?php echo $usuario->nombre; ?>"
                />
            </div>

            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="Tu password"/>
            </div>

            <div class="campo">
                <label for="passwordR">Repetir contraseña</label>
                <input type="password" id="passwordR" name="passwordR" placeholder="Repite tu password"/>
            </div>

            <button class="btn">Crear cuenta</button>
        </form>

        <div class="acciones">
            <a href="/">Ya tienes tienes una cuenta?, inica sesión</a>
            <a href="/olvide">¿Olvidaste tu contraseña?</a>
        </div>
    </div>
</div>