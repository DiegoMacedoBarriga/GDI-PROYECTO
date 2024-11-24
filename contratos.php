<?php
include 'db.php';

$clientes_query = $pdo->query("SELECT id, CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno) AS nombre_completo FROM clientes");
$clientes = $clientes_query->fetchAll(PDO::FETCH_ASSOC);

$cartas_query = $pdo->query("SELECT id, CONCAT(número_de_serie_del_vehículo, ' (', año, ')') AS descripcion FROM carta_características");
$cartas = $cartas_query->fetchAll(PDO::FETCH_ASSOC);

$gestores_query = $pdo->query("SELECT id, CONCAT(nombres, ' ', apellido_paterno, ' ', apellido_materno) AS nombre_completo FROM gestor_comercial");
$gestores = $gestores_query->fetchAll(PDO::FETCH_ASSOC);

$query = $pdo->query("
    SELECT cc.id, 
           CONCAT(c.nombre, ' ', c.apellido_paterno) AS cliente, 
           CONCAT(v.marca, ' ', v.modelo) AS vehiculo, 
           CONCAT(g.nombres, ' ', g.apellido_paterno, ' ', g.apellido_materno) AS gestor, 
           cc.cuota_inicial, 
           cc.monto_a_desembolsar 
    FROM contrato_crediticio cc
    INNER JOIN clientes c ON cc.id_clientes = c.id
    INNER JOIN carta_características ca ON cc.id_carta_caracteristicas = ca.id
    INNER JOIN vehículo v ON ca.número_de_serie_del_vehículo = v.número_de_serie
    INNER JOIN gestor_comercial g ON cc.id_gestor_comercial = g.id
    ORDER BY cc.id
");

$contratos = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Contratos Crediticios</title>
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
    <h1>Gestión de Contratos Crediticios</h1>
    <form action="process_contratos.php" method="post">
        <label for="id_cliente">Cliente:</label>
        <select name="id_cliente" id="id_cliente" required>
            <?php foreach ($clientes as $cliente): ?>
                <option value="<?php echo $cliente['id']; ?>"><?php echo $cliente['nombre_completo']; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="id_carta_caracteristicas">Carta de Características:</label>
        <select name="id_carta_caracteristicas" id="id_carta_caracteristicas" required>
            <?php foreach ($cartas as $carta): ?>
                <option value="<?php echo $carta['id']; ?>"><?php echo $carta['descripcion']; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="id_gestor_comercial">Gestor Comercial:</label>
        <select name="id_gestor_comercial" id="id_gestor_comercial" required>
            <?php foreach ($gestores as $gestor): ?>
                <option value="<?php echo $gestor['id']; ?>"><?php echo $gestor['nombre_completo']; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="cuota_inicial">Cuota Inicial:</label>
        <input type="text" name="cuota_inicial" id="cuota_inicial" required>

        <button type="submit" name="save">Crear Contrato</button>
    </form>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Vehículo</th>
                <th>Gestor Comercial</th>
                <th>Cuota Inicial</th>
                <th>Monto a Desembolsar</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contratos as $contrato): ?>
                <tr>
                    <td><?php echo $contrato['id']; ?></td>
                    <td><?php echo $contrato['cliente']; ?></td>
                    <td><?php echo $contrato['vehiculo']; ?></td>
                    <td><?php echo $contrato['gestor']; ?></td>
                    <td><?php echo $contrato['cuota_inicial']; ?></td>
                    <td><?php echo $contrato['monto_a_desembolsar']; ?></td>
                    <td>
                        <a href="process_contratos.php?delete=<?php echo $contrato['id']; ?>" onclick="return confirm('¿Está seguro de eliminar este contrato?');">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
