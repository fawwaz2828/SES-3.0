<?php
// URL API Philips Hue (contoh: API Hue Bridge)
$bridge_ip = "192.168.1.100"; // Ganti dengan IP Bridge Hue Anda
$access_token = "your-access-token"; // Ganti dengan token akses Anda
$api_url = "http://$bridge_ip/api/$access_token/lights"; // URL untuk mengambil data lampu

// Inisialisasi cURL untuk mengambil data dari API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

// Menangani respons JSON dari API
$data = json_decode($response, true);

// Menampilkan data untuk pemeriksaan
echo json_encode($data);
?>
