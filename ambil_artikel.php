<?php
header('Content-Type: application/json');
require_once 'koneksi.php';

$query = "SELECT a.id, a.judul, k.nama_kategori, CONCAT(p.nama_depan, ' ', p.nama_belakang) AS penulis, a.hari_tanggal, a.gambar, a.isi, a.id_kategori, a.id_penulis 
          FROM artikel a 
          JOIN penulis p ON a.id_penulis = p.id 
          JOIN kategori_artikel k ON a.id_kategori = k.id 
          ORDER BY a.id DESC";
$result = $conn->query($query);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
echo json_encode(["status" => "success", "data" => $data]);
$conn->close();
?>
