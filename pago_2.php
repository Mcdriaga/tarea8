<?php
session_start();

// Verificar si se ha enviado el formulario con los datos del carrito
if (isset($_POST['carrito'])) {
    // Decodificar el JSON recibido
    $carrito = json_decode($_POST['carrito'], true);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago</title>
    <link rel="stylesheet" href="styles2.css">
</head>
<body>
    <div class="container">
        <h1>Resumen de Pago</h1>
        <?php if (!empty($carrito)): ?>
            <div class="cart-summary">
                <ul>
                    <?php foreach ($carrito as $item): ?>
                        <li>
                            <div class="cart-item">
                                <span>Producto <?php echo $item['id']; ?>: <?php echo $item['pais']; ?> - <?php echo $item['ciudad']; ?> - <?php echo $item['servicio']; ?> - <?php echo $item['hotel']; ?> - <?php echo $item['fechaViaje']; ?> - $<?php echo number_format($item['precio'], 2); ?> x <?php echo $item['cantidad']; ?></span>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <p>Total: $<?php echo number_format(array_reduce($carrito, function($acc, $item) {
                    return $acc + $item['precio'] * $item['cantidad'];
                }, 0), 2); ?></p>
            </div>
            <form action="procesar_pago.php" method="POST">
                <input type="hidden" name="carrito" value='<?php echo json_encode($carrito); ?>'>
                <button type="submit" class="pay-btn">Confirmar y Pagar</button>
            </form>
        <?php else: ?>
            <p>El carrito está vacío.</p>
        <?php endif; ?>
    </div>
</body>
</html>



<script>
        let inactivityTime = 0;

        function resetInactivityTime() {
            inactivityTime = 0;
        }

        setInterval(function() {
            inactivityTime++;
            if (inactivityTime >= 5) {
                // crear logout.php
                alert('Sesión expirada, redirigiendo a la página principal...');
                window.location.href = 'logout.php';
            }
        }, 1000);

        document.onmousemove = resetInactivityTime;
        document.onkeypress = resetInactivityTime;
    </script>
