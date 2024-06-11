<?php

require "../config/connect.php";

if ($_SERVER['REQUEST_METHOD']=="POST"){

    $response = array();
    $sso =$_POST['sso'];
    $password =md5($_POST['password']);
    $nama =$_POST['nama'];
    $no_telp =$_POST['no_telp'];

    $cek = "SELECT * FROM users WHERE sso='$sso'";
    $result = mysqli_fetch_array(mysqli_query($con, $cek));

    if (isset($result)) {
        # code...
        $response['value']=2;
        $response['message']="Email telah terdaftar";
        echo json_encode($response);
    } else {
        # code...
        $insert = "INSERT INTO users VALUE(NULL,'$sso','$password','$nama','$no_telp',NOW())";
        if (mysqli_query($con, $insert)) {
            # code...
            $response['value']=1;
            $response['message']="Berhasil didaftarkan";
            echo json_encode($response);
    
        } else {
            # code...
            $response['value']=1;
            $response['message']="Gagal didaftarkan";
            echo json_encode($response);
        }
    }
    


    
}

?>