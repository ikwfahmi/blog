<?php
header('Content-Type: application/json');
require_once 'koneksi.php';

$id = intval($_POST['id'] ?? 0);
$nama_kategori = htmlspecialchars($_POST['nama_kategori'] ?? '');
$keterangan = htmlspecialchars($_POST['keterangan'] ?? '');

if ($id <= 0 || empty($nama_kategori)) {
    echo json_encode(["status" => "error", "message" => "Data wajib tidak valid!"]);
    exit;
}

$stmtCek = $conn->prepare("SELECT id FROM kategori_artikel WHERE nama_kategori = ? AND id != ?");
$stmtCek->bind_param("si", $nama_kategori, $id);
$stmtCek->execute();
if ($stmtCek->get_result()->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Nama kategori sudah ada!"]);
    exit;
}

$stmt = $conn->prepare("UPDATE kategori_artikel SET nama_kategori = ?, keterangan = ? WHERE id = ?");
$stmt->bind_param("ssi", $nama_kategori, $keterangan, $id);
if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Kategori berhasil diperbarui!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Gagal memperbarui kategori!"]);
}

$stmt->close();
$conn->close();
?>
