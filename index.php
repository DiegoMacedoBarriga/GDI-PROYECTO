<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido al Sistema de Gestión</title>
    <link rel="stylesheet" href="styles.css">

</head>
<body>
    <div class="welcome-container">
        <h1>¡Bienvenido al Sistema de Gestión!</h1>
        <p>Administra los clientes, vehículos, cartas de características, contratos y gestores comerciales.</p>
    </div>
    <div class="navigation-container">
        <form action="clientes.php" method="get">
            <button type="submit">Gestión de Clientes</button>
        </form>
        <form action="vehiculos.php" method="get">
            <button type="submit">Gestión de Vehículos</button>
        </form>
        <form action="gestores.php" method="get">
            <button type="submit">Gestión de Gestores</button>
        </form>
        <form action="carta_caracteristicas.php" method="get">
            <button type="submit">Gestión de Cartas</button>
        </form>
        <form action="contratos.php" method="get">
            <button type="submit">Gestión de Contratos</button>
        </form>
        
    </div>
</body>
</html>
