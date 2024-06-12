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
    $user_id = $input['user_id'];

    // Query untuk mengambil jadwal berdasarkan ID pengguna
    $cek = "SELECT * FROM honor WHERE user_id='$user_id'";
    $result = mysqli_query($con, $cek);

    if (mysqli_num_rows($result) > 0) {
        $honor = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $honor[] = $row;
        }
        $response['value'] = 1;
        $response['honor'] = $honor;
    } else {
        $response['value'] = 0;
        $response['message'] = "Tidak ada honor untuk pengguna ini";
    }

    echo json_encode($response);
}
?>
