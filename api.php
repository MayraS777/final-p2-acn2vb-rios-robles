<?php
header('Content-Type: application/json');

require_once 'db.php';

$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 4;
$offset = ($page - 1) * $perPage;

$nombre = $_GET['nombre'] ?? '';
$categoria = $_GET['categoria'] ?? '';

$sql = "SELECT * FROM items WHERE 1=1";
$params = [];

if ($nombre !== '') {
    $sql .= " AND name LIKE :nombre";
    $params[':nombre'] = "%$nombre%";
}

if ($categoria !== '') {
    $sql .= " AND categoria = :categoria";
    $params[':categoria'] = $categoria;
}

$sql .= " LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);

foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}

$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "items" => $items
]);