<?php
header('Content-Type: application/json');
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        echo json_encode([
            "success" => false,
            "message" => "No se recibieron datos"
        ]);
        exit;
    }

    echo json_encode([
        "success" => true,
        "message" => "Ítem recibido correctamente",
        "item" => $data
    ]);
    exit;
}


$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 20;
$offset = ($page - 1) * $perPage;

$nombre = $_GET['nombre'] ?? '';
$categoria = $_GET['categoria'] ?? '';

$sql = "SELECT * FROM items WHERE 1=1";

$params = [];

if ($nombre !== '') {
    $sql .= " AND name LIKE ?";
    $params[] = "%$nombre%";
}

if ($categoria !== '') {
    $sql .= " AND categoria = ?";
    $params[] = $categoria;
}

$sql .= " LIMIT $perPage OFFSET $offset";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "items" => $items
]);