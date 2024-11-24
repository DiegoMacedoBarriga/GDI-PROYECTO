<?php
include 'db.php';

$query = $pdo->query("SELECT * FROM vehículo ORDER BY número_de_serie");
$vehiculos = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Vehículos</title>
    <link rel="stylesheet" href="styles.css?v=1.0">
</head>
<body>
    <div class="navbar">
        <a href="clientes.php" class="<?= basename($_SERVER['PHP_SELF']) === 'clientes.php' ? 'active' : '' ?>">Gestión de Clientes</a>
        <a href="vehiculos.php" class="<?= basename($_SERVER['PHP_SELF']) === 'vehiculos.php' ? 'active' : '' ?>">Gestión de Vehículos</a>
        <a href="gestores.php" class="<?= basename($_SERVER['PHP_SELF']) === 'gestores.php' ? 'active' : '' ?>">Gestión de Gestores</a>
        <a href="carta_caracteristicas.php" class="<?= basename($_SERVER['PHP_SELF']) === 'carta_caracteristicas.php' ? 'active' : '' ?>">Gestión de Cartas</a>
        <a href="contratos.php" class="<?= basename($_SERVER['PHP_SELF']) === 'contratos.php' ? 'active' : '' ?>">Gestión de Contratos</a>
    </div>
    <h1>Gestión de Vehículos</h1>

    <form action="process_vehiculos.php" method="post" id="form-vehiculo">
        <input type="hidden" name="numero_de_serie" id="numero_de_serie">
        <label for="marca">Marca:</label>
        <input type="text" name="marca" id="marca" required>
        <label for="modelo">Modelo:</label>
        <input type="text" name="modelo" id="modelo" required>
        <label for="año_modelo">Año:</label>
        <input type="number" name="año_modelo" id="año_modelo" required>
        <label for="precio">Precio:</label>
        <input type="number" step="0.01" name="precio" id="precio" required>
        <label for="color">Color:</label>
        <input type="text" name="color" id="color" required>
        <label for="motor">Motor:</label>
        <input type="text" name="motor" id="motor" required>
        <label for="carroceria">Carrocería:</label>
        <input type="text" name="carroceria" id="carroceria" required>
        <label for="combustible">Combustible:</label>
        <input type="text" name="combustible" id="combustible" required>
        <label for="asientos">Asientos:</label>
        <input type="number" name="asientos" id="asientos" required>
        <label for="chasis">Chasis:</label>
        <input type="text" name="chasis" id="chasis" required>
        <label for="id_concesionario">Concesionario:</label>
        <select name="id_concesionario" id="id_concesionario" required>
            <?php
            $concesionarios = $pdo->query("SELECT id, nombre FROM concesionario")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($concesionarios as $concesionario) {
                echo "<option value='{$concesionario['id']}'>{$concesionario['nombre']}</option>";
            }
            ?>
        </select>
        <button type="submit" name="save">Guardar</button>
    </form>

    <table border="1">
        <thead>
            <tr>
                <th>Número de Serie</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Año</th>
                <th>Precio</th>
                <th>Color</th>
                <th>Motor</th>
                <th>Carrocería</th>
                <th>Combustible</th>
                <th>Asientos</th>
                <th>Chasis</th>
                <th>Concesionario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($vehiculos)): ?>
                <?php foreach ($vehiculos as $vehiculo): ?>
                    <tr>
                        <td><?php echo $vehiculo['número_de_serie']; ?></td>
                        <td><?php echo $vehiculo['marca']; ?></td>
                        <td><?php echo $vehiculo['modelo']; ?></td>
                        <td><?php echo $vehiculo['año_modelo']; ?></td>
                        <td><?php echo $vehiculo['precio']; ?></td>
                        <td><?php echo $vehiculo['color']; ?></td>
                        <td><?php echo $vehiculo['motor']; ?></td>
                        <td><?php echo $vehiculo['carrocería']; ?></td>
                        <td><?php echo $vehiculo['combustible']; ?></td>
                        <td><?php echo $vehiculo['asientos']; ?></td>
                        <td><?php echo $vehiculo['chasis']; ?></td>
                        <td><?php echo $vehiculo['id_concesionario']; ?></td>
                        <td>
                            <a href="#" class="editar" data-id="<?php echo $vehiculo['número_de_serie']; ?>">Editar</a>
                            <a href="process_vehiculos.php?delete=<?php echo $vehiculo['número_de_serie']; ?>" onclick="return confirm('¿Está seguro de eliminar este vehículo?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="13">No hay vehículos registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <script>
    document.querySelectorAll('.editar').forEach(function(btn) {
        btn.addEventListener('click', function(event) {
            event.preventDefault();
            const id = this.getAttribute('data-id');

            fetch(`process_vehiculos.php?edit=${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('numero_de_serie').value = data.número_de_serie;
                    document.getElementById('marca').value = data.marca;
                    document.getElementById('modelo').value = data.modelo;
                    document.getElementById('año_modelo').value = data.año_modelo;
                    document.getElementById('precio').value = data.precio;
                    document.getElementById('color').value = data.color;
                    document.getElementById('motor').value = data.motor;
                    document.getElementById('carroceria').value = data.carrocería;
                    document.getElementById('combustible').value = data.combustible;
                    document.getElementById('asientos').value = data.asientos;
                    document.getElementById('chasis').value = data.chasis;
                    document.getElementById('id_concesionario').value = data.id_concesionario;

                    document.getElementById('marca').focus();
                })
                .catch(error => {
                    console.error('Error al cargar los datos del vehículo:', error);
                });
        });
    });
    </script>
</body>
</html>
