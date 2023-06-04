<main class="contenedor seccion contenido-centrado">
    <h2>Iniciar Sesion</h2>

    <!-- Mostrar Mensaje de Error -->
    <?php foreach ($errores as $error):?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>
    <!-- Mostrar Mensaje de Error -->
    
    <form method="POST" class="formulario">
        <fieldset>
            <legend>Email y Password</legend>

            <label for="email">E-Mail</label>
            <input type="email" name="email" placeholder="Tu Correo" id="email">
            <label for="password">Password</label>
            <input type="password" name="password" placeholder="Tu Password" id="password">

        </fieldset>

        <input type="submit" value="Iniciar Sesion" class="boton boton-verde">

    </form>
</main>