<?php
include 'db.php';

$query = $pdo->query("SELECT gc.*, c.nombre AS nombre_concesionario FROM gestor_comercial gc
                      LEFT JOIN concesionario c ON gc.id_concesionario = c.id
                      ORDER BY gc.id");
$gestores = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Gestores Comerciales</title>
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

    <h1>Gestión de Gestores Comerciales</h1>
    <?php if (isset($_GET['error']) && $_GET['error'] === 'dependencia'): ?>
        <p style="color: red;">No se pudo eliminar el gestor porque está relacionado con contratos u otros registros.</p>
    <?php endif; ?>

    <form action="process_gestores.php" method="post" id="form-gestor">
        <input type="hidden" name="id" id="id">
        <label for="dni">DNI:</label>
        <input type="text" name="dni" id="dni" required>
        <label for="nombres">Nombres:</label>
        <input type="text" name="nombres" id="nombres" required>
        <label for="apellido_paterno">Apellido Paterno:</label>
        <input type="text" name="apellido_paterno" id="apellido_paterno" required>
        <label for="apellido_materno">Apellido Materno:</label>
        <input type="text" name="apellido_materno" id="apellido_materno" required>
        <label for="telefono">Teléfono:</label>
        <input type="text" name="telefono" id="telefono" required>
        <label for="correo">Correo:</label>
        <input type="email" name="correo" id="correo" required>
        <label for="fecha_de_contratacion">Fecha de Contratación:</label>
        <input type="date" name="fecha_de_contratacion" id="fecha_de_contratacion" required>
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

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>DNI</th>
                <th>Nombres</th>
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Fecha de Contratación</th>
                <th>Concesionario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($gestores)): ?>
                <?php foreach ($gestores as $gestor): ?>
                    <tr>
                        <td><?php echo $gestor['id']; ?></td>
                        <td><?php echo $gestor['dni']; ?></td>
                        <td><?php echo $gestor['nombres']; ?></td>
                        <td><?php echo $gestor['apellido_paterno']; ?></td>
                        <td><?php echo $gestor['apellido_materno']; ?></td>
                        <td><?php echo $gestor['telefono']; ?></td>
                        <td><?php echo $gestor['correo']; ?></td>
                        <td><?php echo $gestor['fecha_de_contratación']; ?></td>
                        <td><?php echo $gestor['nombre_concesionario']; ?></td>
                        <td>
                            <a href="#" class="editar" data-id="<?php echo $gestor['id']; ?>">Editar</a>
                            <a href="process_gestores.php?delete=<?php echo $gestor['id']; ?>" onclick="return confirm('¿Está seguro de eliminar este gestor?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10">No hay gestores registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <script>
    document.querySelectorAll('.editar').forEach(function(btn) {
        btn.addEventListener('click', function(event) {
            event.preventDefault();
            const id = this.getAttribute('data-id');

            fetch(`process_gestores.php?edit=${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('id').value = data.id;
                    document.getElementById('dni').value = data.dni;
                    document.getElementById('nombres').value = data.nombres;
                    document.getElementById('apellido_paterno').value = data.apellido_paterno;
                    document.getElementById('apellido_materno').value = data.apellido_materno;
                    document.getElementById('telefono').value = data.telefono;
                    document.getElementById('correo').value = data.correo;
                    document.getElementById('fecha_de_contratacion').value = data.fecha_de_contratación;
                    document.getElementById('id_concesionario').value = data.id_concesionario;
                    document.getElementById('dni').focus();
                })
                .catch(error => {
                    console.error('Error al cargar los datos del gestor:', error);
                });
        });
    });
    </script>
</body>
</html>
