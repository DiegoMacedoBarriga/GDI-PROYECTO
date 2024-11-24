<?php
include 'db.php';

if (isset($_POST['save'])) {
    $id = $_POST['id'];
    $id_clientes = $_POST['id_clientes'];
    $id_concesionario = $_POST['id_concesionario']; 
    $número_de_serie_del_vehículo = $_POST['número_de_serie_del_vehículo'];
    $día = $_POST['día'];
    $mes = $_POST['mes'];
    $año = $_POST['año'];

    if ($id) {
        $query = $pdo->prepare("UPDATE carta_características SET id_clientes = ?, id_concesionario = ?, número_de_serie_del_vehículo = ?, día = ?, mes = ?, año = ? WHERE id = ?");
        $query->execute([$id_clientes, $id_concesionario, $número_de_serie_del_vehículo, $día, $mes, $año, $id]);
    } else {
        $query = $pdo->prepare("INSERT INTO carta_características (id_clientes, id_concesionario, número_de_serie_del_vehículo, día, mes, año) VALUES (?, ?, ?, ?, ?, ?)");
        $query->execute([$id_clientes, $id_concesionario, $número_de_serie_del_vehículo, $día, $mes, $año]);
    }
    header("Location: carta_caracteristicas.php");
    exit;
}


if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    try {
        $query = $pdo->prepare("DELETE FROM carta_características WHERE id = ?");
        $query->execute([$id]);
        header("Location: carta_caracteristicas.php?deleted=1");
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() === '23503') {
            header("Location: carta_caracteristicas.php?error=dependencia");
            exit;
        } else {
            die("Error al eliminar la carta característica: " . $e->getMessage());
        }
    }
}


if (isset($_GET['edit'])) {
    $id = $_GET['edit'];

    $query = $pdo->prepare("SELECT * FROM carta_características WHERE id = ?");
    $query->execute([$id]);
    $carta = $query->fetch(PDO::FETCH_ASSOC);
    echo json_encode($carta);
    exit;
}

?>
