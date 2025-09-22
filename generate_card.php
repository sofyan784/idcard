<?php
// Tampilkan semua error untuk debugging. Hapus baris ini di lingkungan produksi.
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Pastikan form telah disubmit
if (isset($_POST["submit"])) {

    // --- PENGATURAN DAN DEFINISI ---
    $font_path = __DIR__ . '/Inter-Regular.ttf';
    $font_path_bold = __DIR__ . '/Inter-Bold.ttf'; // Font untuk teks tebal
    $template_path = __DIR__ . '/card_template.jpg';

    // Periksa file yang diperlukan
    if (!file_exists($font_path)) {
        die("Error: File font 'Inter-Regular.ttf' tidak ditemukan.");
    }
    if (!file_exists($font_path_bold)) {
        die("Error: File font 'Inter-Bold.ttf' tidak ditemukan. Silakan unduh dan letakkan di folder ini.");
    }
    if (!file_exists($template_path)) {
        die("Error: File template 'card_template.jpg' tidak ditemukan.");
    }

    // Ambil data dari form
    $university_name = $_POST['university_name'];
    $name = $_POST['name'];
    $class = $_POST['class'];
    $roll = $_POST['roll'];
    $dob = date("Y-m-d", strtotime($_POST['dob'])); // DIUBAH: Format tanggal menjadi Tahun-Bulan-Tanggal
    $year = $_POST['year'];
    $address = $_POST['address'];
    $mobile = $_POST['mobile'];

    // Membuat gambar dari template
    $image = @imagecreatefromjpeg($template_path);
    if (!$image) {
        die("Error: Gagal memuat 'card_template.jpg'. Pastikan file tersebut adalah JPEG yang valid dan tidak rusak. Coba simpan ulang file dari editor gambar.");
    }

    // Definisikan warna
    $text_color_dark = imagecolorallocate($image, 0, 0, 0); // Diubah ke hitam murni
    $text_color_white = imagecolorallocate($image, 255, 255, 255);

    // --- PEMROSESAN GAMBAR YANG DIUNGGAH ---

    // Proses Logo Universitas
    if (isset($_FILES['university_logo']) && $_FILES['university_logo']['error'] == 0) {
        $logo_tmp_path = $_FILES['university_logo']['tmp_name'];
        $logo_image = @imagecreatefromstring(file_get_contents($logo_tmp_path));
        if ($logo_image) {
            imagecopyresampled($image, $logo_image, 325, 199, 0, 0, 162, 194, imagesx($logo_image), imagesy($logo_image));
            imagedestroy($logo_image);
        } else {
            die("Error: Gagal memproses file logo. Pastikan file yang diunggah adalah format gambar yang didukung (JPG, PNG, GIF) dan tidak rusak.");
        }
    }

    // Proses Foto Mahasiswa
    if (isset($_FILES['student_photo']) && $_FILES['student_photo']['error'] == 0) {
        $photo_tmp_path = $_FILES['student_photo']['tmp_name'];
        $photo_image = @imagecreatefromstring(file_get_contents($photo_tmp_path));
        if ($photo_image) {
            imagecopyresampled($image, $photo_image, 259, 527, 0, 0, 282, 318, imagesx($photo_image), imagesy($photo_image));
            imagedestroy($photo_image);
        } else {
            die("Error: Gagal memproses file foto mahasiswa. Pastikan file yang diunggah adalah format gambar yang didukung (JPG, PNG, GIF) dan tidak rusak.");
        }
    }

    // --- PENULISAN TEKS PADA GAMBAR ---
    
    // Menambahkan Nama Universitas
    $univ_font_size = 25; 
    imagettftext($image, $univ_font_size, 0, 72, 495, $text_color_dark, $font_path_bold, $university_name);
    
    // Menambahkan detail mahasiswa dengan koordinat individual
    $details_font_size = 20; 
    $details_margin_x = 200; // Margin kiri untuk semua data detail
    
    imagettftext($image, $details_font_size, 0, $details_margin_x, 905, $text_color_dark, $font_path, $name);
    imagettftext($image, $details_font_size, 0, $details_margin_x, 945, $text_color_dark, $font_path, $class);
    imagettftext($image, $details_font_size, 0, $details_margin_x, 985, $text_color_dark, $font_path, $roll);
    
    // Koordinat terpisah untuk Date of Birth
    $dob_x = 265;
    $dob_y = 1030;
    imagettftext($image, $details_font_size, 0, $dob_x, $dob_y, $text_color_dark, $font_path, $dob);
    
    imagettftext($image, $details_font_size, 0, $details_margin_x, 1070, $text_color_dark, $font_path, $year);

    // Menambahkan detail di footer dengan koordinat individual
    $footer_font_size = 20; 
    $footer_margin_x = 215; // Margin kiri untuk semua data footer

    imagettftext($image, $footer_font_size, 0, $footer_margin_x, 1163, $text_color_white, $font_path, $address);
    imagettftext($image, $footer_font_size, 0, $footer_margin_x, 1207, $text_color_white, $font_path, $mobile);


    // --- SIMPAN GAMBAR DAN ALIHKAN ---
    
    // Pastikan direktori 'generated_cards' ada
    $output_dir = __DIR__ . '/generated_cards';
    if (!is_dir($output_dir)) {
        // Coba buat direktori jika belum ada
        if (!mkdir($output_dir, 0755, true)) {
            die("Error: Gagal membuat direktori 'generated_cards'. Silakan buat secara manual.");
        }
    }

    // Buat nama file yang unik
    $filename = uniqid('card_', true) . '.jpg';
    $filepath = $output_dir . '/' . $filename;

    // Simpan gambar JPEG ke file
    imagejpeg($image, $filepath);

    // Bersihkan memori
    imagedestroy($image);

    // Alihkan ke halaman hasil dengan nama file sebagai parameter
    header('Location: result.php?card=' . urlencode($filename));
    exit();

} else {
    echo "Silakan isi formulir terlebih dahulu.";
}
?>

