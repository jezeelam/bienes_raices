<main class="contenedor seccion contenido-centrado">
    <h2><?php echo $propiedad->titulo; ?></h2>

    <img loading="lazy" src="./imagenes/<?php echo $propiedad->imagen; ?>" alt="Imagen Anuncio 1" class="imagen-anuncio">

    <div class="resumen-propiedad">
        <p class="precio">$ <?php echo $propiedad->precio; ?></p>
        <ul class="iconos-caracteristicas">
            <li>
                <img class="icono" loading="lazy" src="build/img/icono_wc.svg" alt="Icono Wc">
                <p><?php echo $propiedad->wc; ?></p>
            </li>
            <li>
                <img class="icono" loading="lazy" src="build/img/icono_estacionamiento.svg" alt="Icono Estacionamiento">
                <p><?php echo $propiedad->estacionamiento; ?></p>
            </li>
            <li>
                <img class="icono" loading="lazy" src="build/img/icono_dormitorio.svg" alt="Icono Habitaciones">
                <p><?php echo $propiedad->habitaciones; ?></p>
            </li>

        </ul>

        <?php echo $propiedad->descripcion; ?>

    </div>
</main>