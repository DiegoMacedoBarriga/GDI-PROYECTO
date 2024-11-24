<?php
include 'db.php';

if (isset($_POST['save'])) {
    $id = $_POST['id'];
    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];

    if ($id) {
        $query = $pdo->prepare("UPDATE clientes SET dni = ?, nombre = ?, apellido_paterno = ?, apellido_materno = ?, telefono = ?, correo = ? WHERE id = ?");
        $query->execute([$dni, $nombre, $apellido_paterno, $apellido_materno, $telefono, $correo, $id]);
    } else {
        $query = $pdo->prepare("INSERT INTO clientes (dni, nombre, apellido_paterno, apellido_materno, telefono, correo) VALUES (?, ?, ?, ?, ?, ?)");
        $query->execute([$dni, $nombre, $apellido_paterno, $apellido_materno, $telefono, $correo]);
    }
    header("Location: clientes.php");
    exit;
}


if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    try {
        $query = $pdo->prepare("DELETE FROM clientes WHERE id = ?");
        $query->execute([$id]);
        header("Location: clientes.php?deleted=1");
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() === '23503') {
            header("Location: clientes.php?error=dependencia");
            exit;
        } else {
            die("Error al eliminar el cliente: " . $e->getMessage());
        }
    }
}



if (isset($_GET['edit'])) {
    $id = $_GET['edit'];

    $query = $pdo->prepare("SELECT * FROM clientes WHERE id = ?");
    $query->execute([$id]);
    $cliente = $query->fetch(PDO::FETCH_ASSOC);

    echo json_encode($cliente);
    exit;
}


?>
