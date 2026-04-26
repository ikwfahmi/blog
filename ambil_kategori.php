<?php
header('Content-Type: application/json');
require_once 'koneksi.php';

$stmt = $conn->prepare("SELECT id, nama_kategori, keterangan FROM kategori_artikel ORDER BY id DESC");
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode(["status" => "success", "data" => $data]);
$stmt->close();
$conn->close();
?>
