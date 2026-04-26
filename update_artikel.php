<?php
header('Content-Type: application/json');
require_once 'koneksi.php';

$id = intval($_POST['id'] ?? 0);
$judul = htmlspecialchars($_POST['judul'] ?? '');
$id_penulis = intval($_POST['id_penulis'] ?? 0);
$id_kategori = intval($_POST['id_kategori'] ?? 0);
$isi = htmlspecialchars($_POST['isi'] ?? '');

if ($id <= 0 || empty($judul) || $id_penulis <= 0 || $id_kategori <= 0 || empty($isi)) {
    echo json_encode(["status" => "error", "message" => "Form data tidak lengkap!"]);
    exit;
}

$stmtGet = $conn->prepare("SELECT gambar FROM artikel WHERE id = ?");
$stmtGet->bind_param("i", $id);
$stmtGet->execute();
$resGet = $stmtGet->get_result();
if ($resGet->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Data tidak ditemukan"]);
    exit;
}
$currData = $resGet->fetch_assoc();
$gambar_name = $currData['gambar'];

if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
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
    $new_foto = uniqid('artikel_') . '.' . $ext;
    if (move_uploaded_file($_FILES['gambar']['tmp_name'], 'uploads_artikel/' . $new_foto)) {
         if (file_exists('uploads_artikel/' . $gambar_name)) {
             @unlink('uploads_artikel/' . $gambar_name);
         }
         $gambar_name = $new_foto;
    }
}

$stmt = $conn->prepare("UPDATE artikel SET id_penulis=?, id_kategori=?, judul=?, isi=?, gambar=? WHERE id=?");
$stmt->bind_param("iisssi", $id_penulis, $id_kategori, $judul, $isi, $gambar_name, $id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Artikel berhasil diperbarui!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Gagal memperbarui data!"]);
}

$stmt->close();
$conn->close();
?>
