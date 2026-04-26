<?php
header('Content-Type: application/json');
require_once 'koneksi.php';

$id = intval($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT id, id_penulis, id_kategori, judul, isi, gambar, hari_tanggal FROM artikel WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode(["status" => "success", "data" => $row]);
} else {
    echo json_encode(["status" => "error", "message" => "Data tidak ditemukan"]);
}
$stmt->close();
$conn->close();
?>
