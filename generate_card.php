<?php
// Pastikan form telah disubmit
if (isset($_POST["submit"])) {

    // --- PENGATURAN DAN DEFINISI ---

    // Path ke file font TrueType. Pastikan file ini ada di direktori yang sama.
    $font_path = __DIR__ . '/Inter-Regular.otf';
    if (!file_exists($font_path)) {
        die("Error: File font tidak ditemukan di '{$font_path}'. Silakan unduh dan letakkan file font yang benar.");
    }

    // Path ke gambar template kosong Anda.
    $template_path = __DIR__ . '/card_template.jpg';
    if (!file_exists($template_path)) {
        die("Error: File template 'card_template.jpg' tidak ditemukan. Pastikan Anda telah membuat versi kosong dari kartu ID.");
    }
    
    // Ambil data dari form POST
    $university_name = $_POST['university_name'];
    $name = $_POST['name'];
    $class = $_POST['class'];
    $roll = $_POST['roll'];
    $dob = date("Y-m-d", strtotime($_POST['dob'])); // Format tanggal
    $year = $_POST['year'];
    $address = $_POST['address'];
    $mobile = $_POST['mobile'];

    // Membuat gambar dari template
    $image = imagecreatefromjpeg($template_path);
    if (!$image) {
        die("Error: Gagal memuat gambar template.");
    }

    // Mendapatkan dimensi template
    list($template_width, $template_height) = getimagesize($template_path);

    // Definisikan warna (RGB)
    $text_color_dark = imagecolorallocate($image, 31, 41, 55); // Abu-abu gelap untuk teks utama
    $text_color_white = imagecolorallocate($image, 255, 255, 255); // Putih untuk teks di footer


    // --- MEMPROSES GAMBAR YANG DIUNGGAH ---

    // Proses Logo Universitas
    if (isset($_FILES['university_logo']) && $_FILES['university_logo']['error'] == 0) {
        $logo_data = file_get_contents($_FILES['university_logo']['tmp_name']);
        $logo_image = imagecreatefromstring($logo_data);
        if ($logo_image) {
            $logo_width = imagesx($logo_image);
            $logo_height = imagesy($logo_image);
            // Koordinat untuk menempatkan logo (pusat atas)
            $logo_x = ($template_width - 100) / 2; // Asumsi lebar logo 100px
            $logo_y = 60;
            imagecopyresampled($image, $logo_image, $logo_x, $logo_y, 0, 0, 100, 100, $logo_width, $logo_height);
            imagedestroy($logo_image);
        }
    }

    // Proses Foto Mahasiswa
    if (isset($_FILES['student_photo']) && $_FILES['student_photo']['error'] == 0) {
        $photo_data = file_get_contents($_FILES['student_photo']['tmp_name']);
        $photo_image = imagecreatefromstring($photo_data);
        if ($photo_image) {
            $photo_width = imagesx($photo_image);
            $photo_height = imagesy($photo_image);
            // Koordinat untuk menempatkan foto (tengah)
            $photo_target_width = 130;
            $photo_target_height = 160;
            $photo_x = ($template_width - $photo_target_width) / 2;
            $photo_y = 225;
            imagecopyresampled($image, $photo_image, $photo_x, $photo_y, 0, 0, $photo_target_width, $photo_target_height, $photo_width, $photo_height);
            imagedestroy($photo_image);
        }
    }


    // --- MENULIS TEKS PADA GAMBAR ---

    // Menambahkan Nama Universitas
    imagettftext($image, 14, 0, 80, 190, $text_color_dark, $font_path, $university_name);
    
    // Menambahkan detail mahasiswa
    $start_x = 55;
    $start_y = 425;
    $line_height = 25;
    imagettftext($image, 11, 0, $start_x + 50, $start_y, $text_color_dark, $font_path, $name);
    imagettftext($image, 11, 0, $start_x + 50, $start_y + $line_height, $text_color_dark, $font_path, $class);
    imagettftext($image, 11, 0, $start_x + 50, $start_y + ($line_height * 2), $text_color_dark, $font_path, $roll);
    imagettftext($image, 11, 0, $start_x + 90, $start_y + ($line_height * 3), $text_color_dark, $font_path, $dob);
    imagettftext($image, 11, 0, $start_x + 50, $start_y + ($line_height * 4), $text_color_dark, $font_path, $year);

    // Menambahkan detail di footer
    imagettftext($image, 9, 0, 110, 595, $text_color_white, $font_path, $address);
    imagettftext($image, 9, 0, 95, 620, $text_color_white, $font_path, $mobile);


    // --- OUTPUT GAMBAR ---

    // Atur header untuk output sebagai gambar JPEG
    header('Content-Type: image/jpeg');
    header('Content-Disposition: inline; filename="kartu_mahasiswa_' . strtolower(str_replace(' ', '_', $name)) . '.jpg"');

    // Tampilkan gambar ke browser
    imagejpeg($image);

    // Bersihkan memori
    imagedestroy($image);
    exit();

} else {
    // Jika skrip diakses secara langsung tanpa submit form
    echo "Silakan isi formulir terlebih dahulu untuk membuat kartu ID.";
}
?>
