<?php
header('Content-Type: application/json');
require_once 'koneksi.php';

$nama_kategori = htmlspecialchars($_POST['nama_kategori'] ?? '');
$keterangan = htmlspecialchars($_POST['keterangan'] ?? '');

if (empty($nama_kategori)) {
    echo json_encode(["status" => "error", "message" => "Nama kategori wajib diisi!"]);
    exit;
}

$stmtCek = $conn->prepare("SELECT id FROM kategori_artikel WHERE nama_kategori = ?");
$stmtCek->bind_param("s", $nama_kategori);
$stmtCek->execute();
if ($stmtCek->get_result()->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Nama kategori sudah ada!"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO kategori_artikel (nama_kategori, keterangan) VALUES (?, ?)");
$stmt->bind_param("ss", $nama_kategori, $keterangan);
if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Kategori berhasil ditambahkan!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Gagal menyimpan kategori!"]);
}

$stmt->close();
$conn->close();
?>
