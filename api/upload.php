<?php
require "../config/connect.php";

// Mengatur header untuk mengizinkan akses dari domain mana saja
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $upload_dir = '../uploads/';
        
        // Memeriksa apakah direktori upload ada, jika tidak, buat direktori tersebut
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_path = $upload_dir . basename($file_name);

        // Memindahkan file yang diunggah ke direktori tujuan
        if (move_uploaded_file($file_tmp, $file_path)) {
            // Membaca konten file
            $pdf_content = file_get_contents($file_path);
            
            // Menyisipkan data file ke dalam database
            $stmt = $con->prepare("INSERT INTO dokumen (file_name, file_content) VALUES (?, ?)");
            $stmt->bind_param("sb", $file_name, $null);
            $stmt->send_long_data(1, $pdf_content);
            if ($stmt->execute()) {
                $response = array('value' => 1, 'message' => 'File berhasil diunggah');
            } else {
                $response = array('value' => 0, 'message' => 'Gagal menyimpan info file ke database');
            }
            $stmt->close();
            
            // Menghapus file yang diunggah setelah membaca kontennya
            unlink($file_path);
        } else {
            $response = array('value' => 0, 'message' => 'Gagal mengunggah file');
        }
    } else {
        $response = array('value' => 0, 'message' => 'Permintaan tidak valid');
    }
    echo json_encode($response);
} else {
    $response = array('value' => 0, 'message' => 'Metode permintaan tidak valid');
    echo json_encode($response);
}
?>
