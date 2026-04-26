<?php
header('Content-Type: application/json');
require_once 'koneksi.php';

$judul = htmlspecialchars($_POST['judul'] ?? '');
$id_penulis = intval($_POST['id_penulis'] ?? 0);
$id_kategori = intval($_POST['id_kategori'] ?? 0);
$isi = htmlspecialchars($_POST['isi'] ?? '');

if (empty($judul) || $id_penulis <= 0 || $id_kategori <= 0 || empty($isi)) {
    echo json_encode(["status" => "error", "message" => "Seluruh form wajib diisi!"]);
    exit;
}

if (!isset($_FILES['gambar']) || $_FILES['gambar']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(["status" => "error", "message" => "Upload gambar wajib dilakukan!"]);
    exit;
}

if ($_FILES['gambar']['size'] > 2097152) {
    echo json_encode(["status" => "error", "message" => "Ukuran maksimal file adalah 2 MB!"]);
    exit;
}

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($_FILES['gambar']['tmp_name']);
$allowed = ['image/jpeg', 'image/png', 'image/gif'];
if (!in_array($mime, $allowed)) {
     echo json_encode(["status" => "error", "message" => "Format file harus JPG/PNG/GIF!"]);
     exit;
}

$ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
$gambar_name = uniqid('artikel_') . '.' . $ext;
if (!move_uploaded_file($_FILES['gambar']['tmp_name'], 'uploads_artikel/' . $gambar_name)) {
     echo json_encode(["status" => "error", "message" => "Gagal menyimpan file gambar!"]);
     exit;
}

date_default_timezone_set('Asia/Jakarta');
$hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
$bulan = [
 1=>'Januari', 2=>'Februari', 3=>'Maret',
 4=>'April', 5=>'Mei', 6=>'Juni',
 7=>'Juli', 8=>'Agustus', 9=>'September',
 10=>'Oktober',11=>'November',12=>'Desember'
];
$sekarang = new DateTime();
$nama_hari = $hari[(int)$sekarang->format('w')];
$tanggal = $sekarang->format('j');
$nama_bulan = $bulan[(int)$sekarang->format('n')];
$tahun = $sekarang->format('Y');
$jam = $sekarang->format('H:i');
$hari_tanggal = "$nama_hari, $tanggal $nama_bulan $tahun | $jam";

$stmt = $conn->prepare("INSERT INTO artikel (id_penulis, id_kategori, judul, isi, gambar, hari_tanggal) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iissss", $id_penulis, $id_kategori, $judul, $isi, $gambar_name, $hari_tanggal);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Data artikel berhasil disimpan!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Gagal menyimpan data artikel!"]);
}

$stmt->close();
$conn->close();
?>
