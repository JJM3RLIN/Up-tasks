<aside class="sidebar">
    <div class="contenedor-sidebar">
        <h2>Uptask</h1>
        <div class="cerrar-menu">
           <p id="cerrar-menu">X</p>
        </div>
    </div>
    <nav class="sidebar-nav">
        <a class="<?php echo ($titulo === 'Proyectos') ? 'activo' : ''; ?>" href="/dashboard">Proyectos</a>
        <a class="<?php echo ($titulo === 'Crear proyecto') ? 'activo' : ''; ?>" href="/crear-proyecto">Crear proyectos</a>
        <a class="<?php echo ($titulo === 'Perfil') ? 'activo' : ''; ?>" href="/perfil">Perfil</a>
    </nav>
    <div class="cerrar-sesion-mobile">
        <a href="/logout" class="cerrar-sesion">Cerrar sesión</a>
    </div>
</aside>