<?php
include 'db.php';

if (isset($_POST['save'])) {
    $id_cliente = $_POST['id_cliente'];
    $id_carta_caracteristicas = $_POST['id_carta_caracteristicas'];
    $id_gestor_comercial = $_POST['id_gestor_comercial'];
    $cuota_inicial = $_POST['cuota_inicial'];

    try {
        $query = $pdo->prepare("
            INSERT INTO contrato_crediticio (año, mes, día, id_carta_caracteristicas, id_gestor_comercial, cuota_inicial, monto_a_desembolsar, id_clientes)
            VALUES (
                TO_CHAR(CURRENT_DATE, 'YYYY'),
                TO_CHAR(CURRENT_DATE, 'MM'),
                TO_CHAR(CURRENT_DATE, 'DD'),
                :id_carta_caracteristicas,
                :id_gestor_comercial,
                :cuota_inicial,
                (SELECT precio - :cuota_inicial 
                 FROM carta_características 
                 INNER JOIN vehículo ON carta_características.número_de_serie_del_vehículo = vehículo.número_de_serie 
                 WHERE carta_características.id = :id_carta_caracteristicas),
                :id_cliente
            )
        ");
        $query->execute([
            ':id_cliente' => $id_cliente,
            ':id_carta_caracteristicas' => $id_carta_caracteristicas,
            ':id_gestor_comercial' => $id_gestor_comercial,
            ':cuota_inicial' => $cuota_inicial,
        ]);
        header("Location: contratos.php");
        exit;
    } catch (PDOException $e) {
        die("Error al crear el contrato: " . $e->getMessage());
    }
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    try {
        $query = $pdo->prepare("DELETE FROM contrato_crediticio WHERE id = ?");
        $query->execute([$id]);
        header("Location: contratos.php");
        exit;
    } catch (PDOException $e) {
        die("Error al eliminar el contrato: " . $e->getMessage());
    }
}
