<?php
header('Content-Type: application/json');
require_once 'koneksi.php';

$id = intval($_POST['id'] ?? 0);
if ($id <= 0) {
    echo json_encode(["status" => "error", "message" => "ID tidak valid!"]);
    exit;
}

$stmtGet = $conn->prepare("SELECT gambar FROM artikel WHERE id = ?");
$stmtGet->bind_param("i", $id);
$stmtGet->execute();
$resGet = $stmtGet->get_result();
if ($resGet->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Data tidak ditemukan!"]);
    exit;
}
$row = $resGet->fetch_assoc();
$gambar_name = $row['gambar'];

$stmtDel = $conn->prepare("DELETE FROM artikel WHERE id = ?");
$stmtDel->bind_param("i", $id);

if ($stmtDel->execute()) {
    if (file_exists("uploads_artikel/" . $gambar_name)) {
        @unlink("uploads_artikel/" . $gambar_name);
    }
    echo json_encode(["status" => "success", "message" => "Artikel berhasil dihapus!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Gagal menghapus artikel!"]);
}

$conn->close();
?>
