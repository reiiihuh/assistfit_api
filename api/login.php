<?php

require "../config/connect.php";

// Menambahkan CORS Header
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $response = array();

    // Mengambil input JSON dari request body
    $input = json_decode(file_get_contents("php://input"), true);
    $sso = $input['sso'];
    $password = md5($input['password']); // Ganti MD5 dengan password_hash dan password_verify

    // Query untuk memeriksa pengguna
    $cek = "SELECT * FROM users WHERE sso='$sso' AND password='$password'";
    $result = mysqli_fetch_array(mysqli_query($con, $cek));

    if (isset($result)) {
        $response['value'] = 1;
        $response['message'] = "Login Berhasil";
        $response['user'] = array(
            'id' => $result['id'],
            'sso' => $result['sso'],
            'nama' => $result['nama'], // Ambil nama dari tabel
            'no_telp' => $result['no_telp'],
            'createdDate' => $result['createdDate']
        );
    } else {
        $response['value'] = 0;
        $response['message'] = "Login Gagal";
    }

    echo json_encode($response);
}
?>
