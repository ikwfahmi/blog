<?php
header('Content-Type: application/json');
require_once 'koneksi.php';

$id = intval($_POST['id'] ?? 0);
if ($id <= 0) {
    echo json_encode(["status" => "error", "message" => "ID tidak valid!"]);
    exit;
}

try {
    $stmt = $conn->prepare("DELETE FROM kategori_artikel WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Kategori berhasil dihapus!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal menghapus kategori!"]);
    }
} catch (mysqli_sql_exception $e) {
    echo json_encode(["status" => "error", "message" => "Tidak dapat menghapus kategori karena masih memiliki artikel!"]);
}
$conn->close();
?>
