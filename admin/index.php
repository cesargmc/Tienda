<?php
    //Base de datos 
    require '../includes/config/database.php';
    $db = conectarDB();
    
    session_start();

    // Verificar si el usuario ha iniciado sesión
    if (!isset($_SESSION['usuario'])) {
        header("Location: iniciosesion.php");
        exit();
    }

    // Verificar el rol del usuario
    $rol_id = $_SESSION['usuario']['rol_id'];
    if ($rol_id != 1) {
        // El usuario no es administrador
        header("Location: ../index.php");
        exit();
    }

    // Últimos registros de usuario
    $consultaUsuarios = "SELECT * FROM usuario ORDER BY id_usuario DESC LIMIT 5";
    $resultadoUsuarios = mysqli_query($db, $consultaUsuarios);

    // Ingresos totales de venta
    $consultaIngresos = "SELECT SUM(costo_total) AS total FROM venta";
    $resultadoIngresos = mysqli_query($db, $consultaIngresos);
    $filaIngresos = mysqli_fetch_assoc($resultadoIngresos);
    $totalIngresos = $filaIngresos['total'];

    // Producto con más cantidad
    $consultaMaxCantidad = "SELECT producto.nombre AS nombre_producto, talla_producto.cantidad AS cantidad FROM talla_producto INNER JOIN producto ON talla_producto.producto_id_producto = producto.id_producto ORDER BY talla_producto.cantidad DESC LIMIT 1";
    $resultadoMaxCantidad = mysqli_query($db, $consultaMaxCantidad);
    $filaMaxCantidad = mysqli_fetch_assoc($resultadoMaxCantidad);
    $productoMayorCantidad = $filaMaxCantidad['nombre_producto'];
    $maxCantidad = $filaMaxCantidad['cantidad'];

    // Producto con menos cantidad
    $consultaMinCantidad = "SELECT producto.nombre AS nombre_producto, talla_producto.cantidad AS cantidad FROM talla_producto INNER JOIN producto ON talla_producto.producto_id_producto = producto.id_producto ORDER BY talla_producto.cantidad ASC LIMIT 1";
    $resultadoMinCantidad = mysqli_query($db, $consultaMinCantidad);
    $filaMinCantidad = mysqli_fetch_assoc($resultadoMinCantidad);
    $productoMenorCantidad = $filaMinCantidad['nombre_producto'];
    $minCantidad = $filaMinCantidad['cantidad'];

    $inicio = true;
    include '../includes/templates/header.php';
?>
    
    <main class="contenedor">
        <div class ="cuadros">
            <div class="cuadro">
                <h3 class="cuadro__titulo">Últimos registros</h3>
                <?php while ($rowUsuarios = mysqli_fetch_assoc($resultadoUsuarios)) : ?>
                    <p><?php echo $rowUsuarios['email']; ?></p>
                <?php endwhile; ?>
            </div><!-- cuadro -->
        
            <div class="cuadro">
                <h3 class="cuadro__titulo">Ingresos</h3>
                <p class="cuadro__datos">$<?php echo $totalIngresos; ?></p>
            </div>
        
            <div class="cuadro">
                <h3 class="cuadro__titulo">Productos con más cantidad</h3>
                <p class="cuadro__datos"><?php echo $productoMayorCantidad; ?></p>
                <p class="cuadro__datos">Cantidad: <?php echo $maxCantidad; ?></p>
            </div>
        
            <div class="cuadro">
                <h3 class="cuadro__titulo">Productos con menos cantidad</h3>
                <p class="cuadro__datos"><?php echo $productoMenorCantidad; ?></p>
                <p class="cuadro__datos">Cantidad: <?php echo $minCantidad; ?></p>
            </div>
        </div>
    </main>

</body>
</html>