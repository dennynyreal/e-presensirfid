<?php 
include "koneksi.php";

// Cek apakah parameter 'rfid' ada di URL
if (isset($_GET['rfid'])) {
    // Baca nomor RFID dari NodeMCU
    $rfid = $_GET['rfid'];

    // Kosongkan tabel tmprfid
    $hapus = mysqli_query($konek, "DELETE FROM tmprfid");

    if (!$hapus) {
        echo "Gagal mengosongkan tabel: " . mysqli_error($konek);
        exit;
    }

    // Simpan nomor RFID yang baru ke tabel tmprfid
    $simpan = mysqli_query($konek, "INSERT INTO tmprfid(rfid) VALUES('$rfid')");

    if ($simpan) {
        // Ambil nama dari tabel berdasarkan RFID
        $query = mysqli_query($konek, "SELECT nama FROM siswa WHERE rfid = '$rfid'");
        $row = mysqli_fetch_assoc($query);
        $nama = $row['nama'];

        if ($nama) {
            // Pesan ketika kartu dikenali
            echo "Presensi Berhasil, $nama";

            // Prepare POST data
            $postData = json_encode(['rfid' => $rfid, 'nama' => $nama]);

            // URL endpoint ESP32
            $esp32_url = 'http://192.168.12.238:80/endpoint'; // Ganti dengan IP dan PORT ESP32 Anda
            

            // Create the context for the POST request
            $context = stream_context_create([
                'http' => [
                    'method'  => 'POST',
                    'header'  => 'Content-Type: application/json',
                    'content' => $postData,
                ]
            ]);

            // Send the POST request
            $response = file_get_contents($esp32_url, false, $context);

            // Check for errors
            if ($response === false) {
                echo 'Error sending request to ESP32';
            } else {
                echo 'Success sending data to ESP32';
            }
        } else {
            // Pesan jika kartu tidak dikenali
            echo "Maaf Kartu Tidak Dikenali";
        }
    } else {
        echo "Gagal menyimpan data: " . mysqli_error($konek);
    }
} else {
    echo "Parameter RFID tidak ditemukan.";
}

// Tutup koneksi database
mysqli_close($konek);
?>
