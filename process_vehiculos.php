<?php
include 'db.php';

if (isset($_POST['save'])) {
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $año_modelo = $_POST['año_modelo'];
    $precio = $_POST['precio'];
    $color = $_POST['color'];
    $motor = $_POST['motor'];
    $carroceria = $_POST['carroceria'];
    $combustible = $_POST['combustible'];
    $asientos = $_POST['asientos'];
    $chasis = $_POST['chasis'];
    $id_concesionario = $_POST['id_concesionario'];

    $query = $pdo->prepare("INSERT INTO vehículo (
        marca, modelo, año_modelo, precio, color, motor, carrocería, combustible, asientos, chasis, id_concesionario
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $query->execute([
        $marca, $modelo, $año_modelo, $precio, $color, $motor, 
        $carroceria, $combustible, $asientos, $chasis, $id_concesionario
    ]);

    header("Location: vehiculos.php");
    exit;
}



if (isset($_GET['delete'])) {
    $número_de_serie = $_GET['delete'];

    try {
        $query = $pdo->prepare("DELETE FROM vehículo WHERE número_de_serie = ?");
        $query->execute([$número_de_serie]);
        header("Location: vehiculos.php?deleted=1");
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() === '23503') {
            header("Location: vehiculos.php?error=dependencia");
            exit;
        } else {
            die("Error al eliminar el vehículo: " . $e->getMessage());
        }
    }
}


if (isset($_GET['edit'])) {
    $numero_de_serie = $_GET['edit'];
    $query = $pdo->prepare("SELECT * FROM vehículo WHERE número_de_serie = ?");
    $query->execute([$numero_de_serie]);
    $vehiculo = $query->fetch(PDO::FETCH_ASSOC);

    echo json_encode($vehiculo);
    exit;
}
?>
