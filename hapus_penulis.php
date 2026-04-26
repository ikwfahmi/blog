<?php
header('Content-Type: application/json');
require_once 'koneksi.php';

$id = intval($_POST['id'] ?? 0);
if ($id <= 0) {
    echo json_encode(["status" => "error", "message" => "ID tidak valid!"]);
    exit;
}

$stmtGet = $conn->prepare("SELECT foto FROM penulis WHERE id = ?");
$stmtGet->bind_param("i", $id);
$stmtGet->execute();
$resGet = $stmtGet->get_result();
if ($resGet->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Data tidak ditemukan!"]);
    exit;
}
$row = $resGet->fetch_assoc();
$foto_name = $row['foto'];

try {
    $stmtDel = $conn->prepare("DELETE FROM penulis WHERE id = ?");
    $stmtDel->bind_param("i", $id);
    if ($stmtDel->execute()) {
        if ($foto_name != 'default.png' && file_exists("uploads_penulis/" . $foto_name)) {
            @unlink("uploads_penulis/" . $foto_name);
        }
        echo json_encode(["status" => "success", "message" => "Penulis berhasil dihapus!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal menghapus penulis!"]);
    }
} catch (mysqli_sql_exception $e) {
    echo json_encode(["status" => "error", "message" => "Tidak dapat menghapus penulis karena ia masih memiliki artikel!"]);
}

$conn->close();
?>
