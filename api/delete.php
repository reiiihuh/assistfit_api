<?php
require "../config/connect.php";

// Mengatur header untuk mengizinkan akses dari domain mana saja
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Menerima data JSON dari body permintaan
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['id'])) {
        $id = $input['id'];

        // Mulai transaksi
        if ($con->begin_transaction()) {
            try {
                // Menghapus catatan dari database berdasarkan ID
                $stmt = $con->prepare("DELETE FROM dokumen WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    // Commit transaksi jika berhasil menghapus catatan
                    $con->commit();
                    $response = array('value' => 1, 'message' => 'Catatan berhasil dihapus dari database');
                } else {
                    // Rollback transaksi jika gagal menghapus catatan
                    $con->rollback();
                    $response = array('value' => 0, 'message' => 'Gagal menghapus catatan dari database');
                }

                $stmt->close();
            } catch (Exception $e) {
                // Rollback transaksi jika terjadi kesalahan
                $con->rollback();
                $response = array('value' => 0, 'message' => 'Terjadi kesalahan: ' . $e->getMessage());
            }
        } else {
            // Jika gagal memulai transaksi
            $response = array('value' => 0, 'message' => 'Gagal memulai transaksi');
        }
    } else {
        // Jika ID tidak disediakan
        $response = array('value' => 0, 'message' => 'ID tidak disediakan');
    }
    echo json_encode($response);
} else {
    // Jika metode permintaan tidak valid
    $response = array('value' => 0, 'message' => 'Metode permintaan tidak valid');
    echo json_encode($response);
}
?>
