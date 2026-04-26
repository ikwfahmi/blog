<?php
header('Content-Type: application/json');
require_once 'koneksi.php';

$nama_depan = htmlspecialchars($_POST['nama_depan'] ?? '');
$nama_belakang = htmlspecialchars($_POST['nama_belakang'] ?? '');
$user_name = htmlspecialchars($_POST['user_name'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($nama_depan) || empty($nama_belakang) || empty($user_name) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "Semua field harus diisi!"]);
    exit;
}

$stmtCek = $conn->prepare("SELECT id FROM penulis WHERE user_name = ?");
$stmtCek->bind_param("s", $user_name);
$stmtCek->execute();
if ($stmtCek->get_result()->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Username sudah digunakan!"]);
    exit;
}

$hashed_password = password_hash($password, PASSWORD_BCRYPT);
$foto_name = 'default.png';

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
    $foto_name = uniqid('penulis_') . '.' . $ext;
    if (!move_uploaded_file($_FILES['foto']['tmp_name'], 'uploads_penulis/' . $foto_name)) {
         $foto_name = 'default.png';
    }
}

$stmt = $conn->prepare("INSERT INTO penulis (nama_depan, nama_belakang, user_name, password, foto) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $nama_depan, $nama_belakang, $user_name, $hashed_password, $foto_name);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Data penulis berhasil disimpan!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Gagal menyimpan data!"]);
}

$stmt->close();
$conn->close();
?>
