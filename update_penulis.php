<?php
header('Content-Type: application/json');
require_once 'koneksi.php';

$id = intval($_POST['id'] ?? 0);
$nama_depan = htmlspecialchars($_POST['nama_depan'] ?? '');
$nama_belakang = htmlspecialchars($_POST['nama_belakang'] ?? '');
$user_name = htmlspecialchars($_POST['user_name'] ?? '');
$password = $_POST['password'] ?? '';

if ($id <= 0 || empty($nama_depan) || empty($nama_belakang) || empty($user_name)) {
    echo json_encode(["status" => "error", "message" => "Data wajib tidak lengkap!"]);
    exit;
}

$stmtCek = $conn->prepare("SELECT id FROM penulis WHERE user_name = ? AND id != ?");
$stmtCek->bind_param("si", $user_name, $id);
$stmtCek->execute();
if ($stmtCek->get_result()->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Username sudah digunakan!"]);
    exit;
}

$stmtGet = $conn->prepare("SELECT foto FROM penulis WHERE id = ?");
$stmtGet->bind_param("i", $id);
$stmtGet->execute();
$resGet = $stmtGet->get_result();
if ($resGet->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Data penulis tidak ditemukan"]);
    exit;
}
$currData = $resGet->fetch_assoc();
$foto_name = $currData['foto'];

if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    if ($_FILES['foto']['size'] > 2097152) {
        echo json_encode(["status" => "error", "message" => "Ukuran maksimal file adalah 2 MB!"]);
        exit;
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($_FILES['foto']['tmp_name']);
    $allowed = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($mime, $allowed)) {
         echo json_encode(["status" => "error", "message" => "Format file harus JPG/PNG/GIF!"]);
         exit;
    }
    
    $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $new_foto = uniqid('penulis_') . '.' . $ext;
    if (move_uploaded_file($_FILES['foto']['tmp_name'], 'uploads_penulis/' . $new_foto)) {
         if ($foto_name != 'default.png' && file_exists('uploads_penulis/' . $foto_name)) {
             @unlink('uploads_penulis/' . $foto_name);
         }
         $foto_name = $new_foto;
    }
}

if (!empty($password)) {
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("UPDATE penulis SET nama_depan=?, nama_belakang=?, user_name=?, password=?, foto=? WHERE id=?");
    $stmt->bind_param("sssssi", $nama_depan, $nama_belakang, $user_name, $hashed_password, $foto_name, $id);
} else {
    $stmt = $conn->prepare("UPDATE penulis SET nama_depan=?, nama_belakang=?, user_name=?, foto=? WHERE id=?");
    $stmt->bind_param("ssssi", $nama_depan, $nama_belakang, $user_name, $foto_name, $id);
}

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Data penulis berhasil diperbarui!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Gagal memperbarui data!"]);
}

$stmt->close();
$conn->close();
?>
