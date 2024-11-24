<?php
include 'db.php';

if (isset($_POST['save'])) {
    $id = $_POST['id'];
    $dni = $_POST['dni'];
    $nombres = $_POST['nombres'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $fecha_de_contratacion = $_POST['fecha_de_contratacion'];
    $id_concesionario = $_POST['id_concesionario'];

    if ($id) {
        $query = $pdo->prepare("UPDATE gestor_comercial SET 
            dni = ?, nombres = ?, apellido_paterno = ?, apellido_materno = ?, 
            telefono = ?, correo = ?, fecha_de_contratación = ?, id_concesionario = ?
            WHERE id = ?");
        $query->execute([
            $dni, $nombres, $apellido_paterno, $apellido_materno,
            $telefono, $correo, $fecha_de_contratacion, $id_concesionario, $id
        ]);
    } else {
        $query = $pdo->prepare("INSERT INTO gestor_comercial (
            dni, nombres, apellido_paterno, apellido_materno, telefono, correo, fecha_de_contratación, id_concesionario
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $query->execute([
            $dni, $nombres, $apellido_paterno, $apellido_materno,
            $telefono, $correo, $fecha_de_contratacion, $id_concesionario
        ]);
    }
    header("Location: gestores.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    try {
        $query = $pdo->prepare("DELETE FROM gestor_comercial WHERE id = ?");
        $query->execute([$id]);
        header("Location: gestores.php?deleted=1");
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() === '23503') {
            header("Location: gestores.php?error=dependencia");
            exit;
        } else {
            die("Error al eliminar el gestor: " . $e->getMessage());
        }
    }
}

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];

    $query = $pdo->prepare("SELECT * FROM gestor_comercial WHERE id = ?");
    $query->execute([$id]);
    $gestor = $query->fetch(PDO::FETCH_ASSOC);

    echo json_encode($gestor);
    exit;
}
?>
