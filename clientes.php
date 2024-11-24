<?php
include 'db.php';
$query = $pdo->query("SELECT * FROM clientes ORDER BY id");
$clientes = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes</title>
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


    <h1>Gestión de Clientes</h1>
    <?php if (isset($_GET['error']) && $_GET['error'] === 'dependencia'): ?>
        <p style="color: red;">No se pudo eliminar la carta característica porque está relacionada con contratos u otros registros.</p>
    <?php endif; ?>

    <form action="process_clientes.php" method="post" id="form-cliente">
        <input type="hidden" name="id" id="id">
        <label for="dni">DNI:</label>
        <input type="text" name="dni" id="dni" required>
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required>
        <label for="apellido_paterno">Apellido Paterno:</label>
        <input type="text" name="apellido_paterno" id="apellido_paterno" required>
        <label for="apellido_materno">Apellido Materno:</label>
        <input type="text" name="apellido_materno" id="apellido_materno" required>
        <label for="telefono">Teléfono:</label>
        <input type="text" name="telefono" id="telefono" required>
        <label for="correo">Correo:</label>
        <input type="email" name="correo" id="correo" required>
        <button type="submit" name="save">Guardar</button>
    </form>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>DNI</th>
                <th>Nombre</th>
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($clientes)): ?>
                <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?php echo $cliente['id']; ?></td>
                        <td><?php echo $cliente['dni']; ?></td>
                        <td><?php echo $cliente['nombre']; ?></td>
                        <td><?php echo $cliente['apellido_paterno']; ?></td>
                        <td><?php echo $cliente['apellido_materno']; ?></td>
                        <td><?php echo $cliente['telefono']; ?></td>
                        <td><?php echo $cliente['correo']; ?></td>
                        <td>
                            <a href="#" class="editar" data-id="<?php echo $cliente['id']; ?>">Editar</a>
                            <a href="process_clientes.php?delete=<?php echo $cliente['id']; ?>" onclick="return confirm('¿Está seguro de eliminar este cliente?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            
            <?php else: ?>
                <tr>
                    <td colspan="8">No hay clientes registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <script>
    document.querySelectorAll('.editar').forEach(function(btn) {
        btn.addEventListener('click', function(event) {
            event.preventDefault();
            const id = this.getAttribute('data-id');

            fetch(`process_clientes.php?edit=${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('id').value = data.id;
                    document.getElementById('dni').value = data.dni;
                    document.getElementById('nombre').value = data.nombre;
                    document.getElementById('apellido_paterno').value = data.apellido_paterno;
                    document.getElementById('apellido_materno').value = data.apellido_materno;
                    document.getElementById('telefono').value = data.telefono;
                    document.getElementById('correo').value = data.correo;
                    document.getElementById('dni').focus();
                })
                .catch(error => {
                    console.error('Error al cargar los datos del cliente:', error);
                });
        });
    });
    </script>
</body>
</html>
