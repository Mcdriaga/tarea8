<?php
session_start();

$_SESSION['username'] = 'Pablo';



?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="Description" content="Enter your description here"/>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>Agencia de Viajes</title>
</head>
<body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/js/bootstrap.min.js"></script>
    <h2>Bienvenidos a Vuelos desde Chile</h2>
</body>
</html>

<style>
    
    .buscador-principal {
        background: #ffffffad;
    }
    .tarjeta-tabla {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid rgba(0, 0, 0, .125);
    border-radius: .25rem;
    padding: 15px;
}


.cartCarrito {
    border: 1px solid #ccc;
    padding: 10px;
    margin-top: 20px;
    width: 300px;
    position: absolute;
    top: 20px;
    right: 20px;
    display: none;
    background: white;
    z-index: 1000;
}

.btn-group-sm>.btn, .btnmenos {

    background: #c1c1c1;
}


.btn-group-sm>.btn, .btnmas{
    background: #c1c1c1;
}

.btn-group-sm>.btn, .btneliminar{
    background: #c1c1c1;
}


</style>

<?php
$server = 'localhost';
$user = 'root';
$pass = 'Un4given.2';
$db = 'db_viajes';
$conexion = new mysqli($server, $user, $pass, $db);
if ($conexion->connect_errno) {
    die("Conexión Fallida" . $conexion->connect_errno);
}

// Declaración de todos los POST, para evitar errores cuando estén vacíos
$post_vars = ['buscar', 'buscaCiudad', 'buscafechadesde', 'buscafechahasta', 'buscapreciodesde', 'buscapreciohasta', 'orden', 'servicio', 'duracionViaje', 'hotel'];
foreach ($post_vars as $var) {
    if (!isset($_POST[$var])) {
        $_POST[$var] = '';
    }
}
?>
echo "<a href='indexadm.php'>Volver</a>";

<div class="cart-icon" onclick="mostrarCarrito()">
    <span id="carrito-icono">0</span>
</div>

<div class="cart cartCarrito" id="cart-container" style="display: none;">
    <h3>Carrito de Compras</h3>
    <ul id="carrito"></ul>
    <p>Total: $<span id="total">0</span></p>
    <br>
    <form id="pago-form" method="POST" action="pago.php">
        <input type="hidden" name="carrito" id="carrito-input">
        <button type="submit" class="checkout-btn">Pagar</button>
    </form>
    
</div>

<script>
    // Obtener el carrito del localStorage o inicializarlo vacío
    let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    // Calcular el total del carrito
    let total = carrito.reduce((acc, item) => acc + parseFloat(item.precio) * item.cantidad, 0);

    // Función para agregar un producto al carrito
    function agregarAlCarrito(id, pais, ciudad, servicio, hotel, fechaViaje, precio) {
        let encontrado = carrito.find(item => item.id === id);

        if (encontrado) {
            // Si el producto ya está en el carrito, incrementar la cantidad
            encontrado.cantidad++;
        } else {
            // Si el producto no está en el carrito, agregarlo
            carrito.push({ id, pais, ciudad, servicio, hotel, fechaViaje, precio: parseFloat(precio), cantidad: 1 });
        }

        // Incrementar el total
        total += parseFloat(precio);
        // Actualizar el carrito y el icono del carrito
        actualizarCarrito();
        actualizarIconoCarrito();
        // Guardar el carrito en el localStorage
        guardarCarrito();
    }

    // Función para actualizar el contenido del carrito
    function actualizarCarrito() {
        const carritoElement = document.getElementById('carrito');
        const totalElement = document.getElementById('total');

        carritoElement.innerHTML = '';
        carrito.forEach(item => {
            const li = document.createElement('li');
            li.innerHTML = `
                <div class="cart-item">
                    <span>Producto ${item.id}: ${item.pais} - ${item.ciudad} - ${item.servicio} - ${item.hotel} - ${item.fechaViaje} - $${item.precio} x ${item.cantidad}</span>
                    <div class="item-actions">
                        <button class="btn btn-sm btnmenos" onclick="restarCantidad(${item.id}, ${item.precio})">-</button>
                        <span class="quantity">${item.cantidad}</span>
                        <button class="btn btn-sm btnmas" onclick="sumarCantidad(${item.id}, ${item.precio})">+</button>&nbsp
                        <button class="btn btn-sm remove-btn btneliminar" onclick="eliminarDelCarrito(${item.id}, ${item.precio}, ${item.cantidad})">Eliminar</button>
                    </div>
                </div>
            `;
            carritoElement.appendChild(li);
        });

        totalElement.textContent = total.toFixed(2);
    }

    // Función para incrementar la cantidad de un producto en el carrito
    function sumarCantidad(id, precio) {
        let encontrado = carrito.find(item => item.id === id);
        encontrado.cantidad++;
        total += parseFloat(precio);
        actualizarCarrito();
        actualizarIconoCarrito();
        guardarCarrito();
    }

    // Función para decrementar la cantidad de un producto en el carrito
    function restarCantidad(id, precio) {
        let encontrado = carrito.find(item => item.id === id);
        if (encontrado.cantidad > 1) {
            encontrado.cantidad--;
            total -= parseFloat(precio);
            actualizarCarrito();
            actualizarIconoCarrito();
            guardarCarrito();
        }
    }

    // Función para eliminar un producto del carrito
    function eliminarDelCarrito(id, precio, cantidad) {
        let encontradoIndex = carrito.findIndex(item => item.id === id);
        carrito.splice(encontradoIndex, 1);
        total -= parseFloat(precio) * cantidad;
        actualizarCarrito();
        actualizarIconoCarrito();
        guardarCarrito();
    }

    // Función para mostrar/ocultar el carrito
    function mostrarCarrito() {
        const cartContainer = document.getElementById('cart-container');
        if (cartContainer.style.display === 'none' || cartContainer.style.display === '') {
            cartContainer.style.display = 'block';
        } else {
            cartContainer.style.display = 'none';
        }
    }

    // Función para actualizar el icono del carrito con la cantidad total de productos
    function actualizarIconoCarrito() {
        const iconoCarrito = document.getElementById('carrito-icono');
        iconoCarrito.textContent = carrito.reduce((total, item) => total + item.cantidad, 0);
    }

    // Función para guardar el carrito en el localStorage
    function guardarCarrito() {
        localStorage.setItem('carrito', JSON.stringify(carrito));
    }

    // Inicializar carrito e icono al cargar la página
    actualizarCarrito();
    actualizarIconoCarrito();

    // Función para enviar el carrito al formulario de pago
    document.getElementById('pago-form').addEventListener('submit', function() {
        document.getElementById('carrito-input').value = JSON.stringify(carrito);
    });
</script>


<!-- Formulario interactivo -->
<div class="container mt-5">
    <h1>Cliente en sesion: <?php echo $_SESSION['username']; ?></h1>
    <div class="col-12">
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card buscador-principal">
                    <div class="card-body">
                        <h4 class="card-title">Buscador</h4>
                        <form id="form2" name="form2" method="POST" action="index.php">
                            <div class="col-12 row">
                                <div class="mb-3">
                                    <label class="form-label">País a buscar</label>
                                    <input type="text" class="form-control" id="buscar" name="buscar" value="<?php echo $_POST['buscar'] ?>">
                                </div>
                                <h4 class="card-title">Filtro de búsqueda</h4>
                                <div class="col-11">
                                    <table class="table">
                                        <thead>
                                            <tr class="filters">
                                                <th>
                                                    Ciudad
                                                    <input type="text" class="form-control" id="buscaCiudad" name="buscaCiudad" value="<?php echo $_POST['buscaCiudad'] ?>">
                                                </th>
                                                <th>
                                                    Fecha Desde
                                                    <input type="date" class="form-control" id="buscafechadesde" name="buscafechadesde" value="<?php echo $_POST['buscafechadesde'] ?>">
                                                </th>
                                                <th>
                                                    Fecha Hasta
                                                    <input type="date" class="form-control" id="buscafechahasta" name="buscafechahasta" value="<?php echo $_POST['buscafechahasta'] ?>">
                                                </th>
                                                <th>
                                                    Precio Desde
                                                    <input type="number" class="form-control" id="buscapreciodesde" name="buscapreciodesde" value="<?php echo $_POST['buscapreciodesde'] ?>">
                                                </th>
                                                <th>
                                                    Precio Hasta
                                                    <input type="number" class="form-control" id="buscapreciohasta" name="buscapreciohasta" value="<?php echo $_POST['buscapreciohasta'] ?>">
                                                </th>
                                                <th>
                                                    Tipo de Servicio
                                                    <input type="text" class="form-control" id="servicio" name="servicio" value="<?php echo $_POST['servicio'] ?>">
                                                </th>
                                                <th>
                                                    Duración del Viaje
                                                    <input type="text" class="form-control" id="duracionViaje" name="duracionViaje" value="<?php echo $_POST['duracionViaje'] ?>">
                                                </th>
                                                <th>
                                                    Hotel
                                                    <input type="text" class="form-control" id="hotel" name="hotel" value="<?php echo $_POST['hotel'] ?>">
                                                </th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                            <div class="col-12 row">
                                <div class="col-9"></div>
                                <div class="col-1">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block" id="btn_buscar">Buscar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div id="notifications-container"></div>
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Resultados</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr style="background-color: purple; color:#FFFFFF;">
                                        <th>País</th>
                                        <th>Ciudad</th>
                                        <th>Servicio</th>
                                        <th>Hotel</th>
                                        <th>Fecha Viaje</th>
                                        <th>Duración del Viaje</th>
                                        <th>Precio</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM detalle_viaje WHERE 1=1";
                                    if (!empty($_POST['buscar'])) $sql .= " AND Pais LIKE '%" . $_POST['buscar'] . "%'";
                                    if (!empty($_POST['buscaCiudad'])) $sql .= " AND Ciudad LIKE '%" . $_POST['buscaCiudad'] . "%'";
                                    if (!empty($_POST['buscafechadesde'])) $sql .= " AND Fecha_Viaje >= '" . $_POST['buscafechadesde'] . "'";
                                    if (!empty($_POST['buscafechahasta'])) $sql .= " AND Fecha_Viaje <= '" . $_POST['buscafechahasta'] . "'";
                                    if (!empty($_POST['buscapreciodesde'])) $sql .= " AND Precio >= '" . $_POST['buscapreciodesde'] . "'";
                                    if (!empty($_POST['buscapreciohasta'])) $sql .= " AND Precio <= '" . $_POST['buscapreciohasta'] . "'";
                                    if (!empty($_POST['servicio'])) $sql .= " AND Servicio LIKE '%" . $_POST['servicio'] . "%'";
                                    if (!empty($_POST['duracionViaje'])) $sql .= " AND Duracion_Viaje LIKE '%" . $_POST['duracionViaje'] . "%'";
                                    if (!empty($_POST['hotel'])) $sql .= " AND Hotel LIKE '%" . $_POST['hotel'] . "%'";
                                    if (!empty($_POST['orden'])) $sql .= " ORDER BY " . $_POST['orden'];

                                    $resultado = $conexion->query($sql);
                                    while ($fila = $resultado->fetch_assoc()) {
                                        $id = $fila['ID'];
                                        $pais = $fila['Pais'];
                                        $ciudad = $fila['Ciudad'];
                                        $servicio = $fila['Servicio'];
                                        $hotel = $fila['Hotel'];
                                        $fechaViaje = $fila['Fecha_Viaje'];
                                        $precio = $fila['Precio'];

                                        echo "<tr>
                                                <td>$pais</td>
                                                <td>$ciudad</td>
                                                <td>$servicio</td>
                                                <td>$hotel</td>
                                                <td>$fechaViaje</td>
                                                <td>" . $fila['Duracion_Viaje'] . "</td>
                                                <td>$precio</td>
                                                <td><button class='btn btn-primary' onclick='agregarAlCarrito($id, \"$pais\", \"$ciudad\", \"$servicio\", \"$hotel\", \"$fechaViaje\", $precio)'>Agregar al carrito</button></td>
                                            </tr>";
                                    }
                                    $conexion->close();
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>



<script src="script.js"></script>
<footer>
    Agencia de Viajes 2024.
</footer>
