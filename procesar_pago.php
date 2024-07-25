<?php
// Procesar el pago (aquí deberías añadir la lógica para procesar el pago real)

if (isset($_POST['carrito'])) {
    // Decodificar el JSON recibido
    $carrito = json_decode($_POST['carrito'], true);

    // Aquí va la lógica para procesar el pago
    // ...

    // Si el pago es exitoso, limpiar el carrito
    echo "<script>
            alert('El pago ha sido exitoso.');
            localStorage.removeItem('carrito'); // Limpiar el carrito en el localStorage
            window.location.href = 'index.php'; // Redirigir al index.php
          </script>";
} else {
    echo "<script>
            alert('Hubo un problema con el pago.');
            window.location.href = 'index.php'; // Redirigir al index.php
          </script>";
}
?>
