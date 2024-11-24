<?php
include 'db.php';

$query = $pdo->query("
    SELECT 
        cc.id, 
        CONCAT(c.nombre, ' ', c.apellido_paterno, ' ', c.apellido_materno) AS cliente_nombre_completo, 
        v.marca AS vehiculo_marca, 
        v.modelo AS vehiculo_modelo,
        cc.día, 
        cc.mes, 
        cc.año 
    FROM carta_características cc
    INNER JOIN clientes c ON cc.id_clientes = c.id
    INNER JOIN vehículo v ON cc.número_de_serie_del_vehículo = v.número_de_serie
    ORDER BY cc.id
");
$cartas = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Cartas Características</title>
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
    <h1>Gestión de Cartas Características</h1>

    <form action="process_carta_caracteristicas.php" method="post" id="form-carta">
        <input type="hidden" name="id" id="id">
        <label for="id_clientes">Cliente:</label>
        <select name="id_clientes" id="id_clientes" required>
            <?php
            $clientes = $pdo->query("SELECT id, CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno) AS nombre_completo FROM clientes")->fetchAll(PDO::FETCH_ASSOC);

            foreach ($clientes as $cliente) {
                echo "<option value='{$cliente['id']}'>{$cliente['nombre_completo']}</option>";
            }
            ?>
        </select>
        <label for="número_de_serie_del_vehículo">Vehículo:</label>
        <select name="número_de_serie_del_vehículo" id="número_de_serie_del_vehículo" required>
            <?php
            $vehiculos = $pdo->query("SELECT número_de_serie, marca, modelo FROM vehículo")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($vehiculos as $vehiculo) {
                echo "<option value='{$vehiculo['número_de_serie']}'>{$vehiculo['marca']} - {$vehiculo['modelo']}</option>";
            }
            ?>
        </select>
        <label for="id_concesionario">Concesionario:</label>
        <select name="id_concesionario" id="id_concesionario" required>
            <?php
            $concesionarios = $pdo->query("SELECT id, nombre FROM concesionario")->fetchAll(PDO::FETCH_ASSOC);

            foreach ($concesionarios as $concesionario) {
                echo "<option value='{$concesionario['id']}'>{$concesionario['nombre']}</option>";
            }
            ?>
        </select>

        <label for="día">Día:</label>
        <input type="text" name="día" id="día" required>
        <label for="mes">Mes:</label>
        <input type="text" name="mes" id="mes" required>
        <label for="año">Año:</label>
        <input type="text" name="año" id="año" required>
        <button type="submit" name="save">Guardar</button>
    </form>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Vehículo</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($cartas)): ?>
                <?php foreach ($cartas as $carta): ?>
                    <tr>
                        <td><?php echo $carta['id']; ?></td>
                        <td><?php echo $carta['cliente_nombre_completo']; ?></td>
                        <td><?php echo "{$carta['vehiculo_marca']} - {$carta['vehiculo_modelo']}"; ?></td>
                        <td><?php echo "{$carta['día']}/{$carta['mes']}/{$carta['año']}"; ?></td>
                        <td>
                            <a href="#" class="editar" data-id="<?php echo $carta['id']; ?>">Editar</a>
                            <a href="process_carta_caracteristicas.php?delete=<?php echo $carta['id']; ?>" onclick="return confirm('¿Está seguro de eliminar esta carta?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No hay cartas características registradas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <script>
    document.querySelectorAll('.editar').forEach(function(btn) {
        btn.addEventListener('click', function(event) {
            event.preventDefault();
            const id = this.getAttribute('data-id');

            fetch(`process_carta_caracteristicas.php?edit=${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('id').value = data.id;
                    document.getElementById('id_clientes').value = data.id_clientes; 
                    document.getElementById('número_de_serie_del_vehículo').value = data.número_de_serie_del_vehículo;
                    document.getElementById('día').value = data.día;
                    document.getElementById('mes').value = data.mes;
                    document.getElementById('año').value = data.año;

                    document.getElementById('id_clientes').focus();
                })
                .catch(error => {
                    console.error('Error al cargar los datos de la carta:', error);
                });
        });
    });
    </script>
</body>
</html>
