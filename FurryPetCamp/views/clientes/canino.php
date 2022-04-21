<main class="contenedor seccion">
    <h1>Crear Canino</h1>

    <?php foreach($errores as $error): ?>
        <div class="alerta error">
            <?php echo $error ?>
        </div>
    <?php endforeach; ?>

    <a href="/cliente" class="boton boton-verde">Volver</a>
    
    <form class="formulario" method="POST" enctype="multipart/form-data">
        <?php include __DIR__ . "/formulario.php"; ?>
        <input type="submit" value="Crear Canino" class="boton boton-verde">
    </form>

</main>