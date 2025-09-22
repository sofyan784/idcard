<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Generated ID Card</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">
    <div class="bg-white rounded-lg shadow-xl p-6 md:p-8 max-w-lg w-full text-center">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">Here's Your ID Card</h1>
        <p class="text-gray-600 mb-6">Review the generated card below. You can download it using the button.</p>
        
        <?php
        // Ambil nama file dari URL dengan aman
        $card_filename = isset($_GET['card']) ? basename($_GET['card']) : '';
        $card_path = 'generated_cards/' . $card_filename;

        if ($card_filename && file_exists($card_path)) {
            // Tampilkan gambar
            echo '<div class="mb-6 border rounded-lg overflow-hidden">';
            echo '<img src="' . htmlspecialchars($card_path, ENT_QUOTES, 'UTF-8') . '" alt="Generated ID Card" class="w-full h-auto">';
            echo '</div>';

            // Tampilkan tombol download
            echo '<a href="' . htmlspecialchars($card_path, ENT_QUOTES, 'UTF-8') . '" download="student_id_card.jpg" class="inline-block w-full bg-blue-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors duration-300 text-lg">';
            echo 'Download Card';
            echo '</a>';
            
            // DITAMBAHKAN: Tombol kembali ke halaman utama
            echo '<a href="index.html" class="mt-4 inline-block w-full bg-gray-200 text-gray-800 font-bold py-3 px-6 rounded-lg hover:bg-gray-300 transition-colors duration-300 text-lg">';
            echo 'Back to Home';
            echo '</a>';

        } else {
            // Tampilkan pesan error jika file tidak ditemukan
            echo '<p class="text-red-500 font-bold">Error: Could not find the generated card image. Please try again.</p>';
            // DITAMBAHKAN: Tombol kembali jika terjadi error
            echo '<a href="index.html" class="mt-4 inline-block w-full bg-gray-200 text-gray-800 font-bold py-3 px-6 rounded-lg hover:bg-gray-300 transition-colors duration-300 text-lg">';
            echo 'Back to Home';
            echo '</a>';
        }
        ?>
    </div>
</body>
</html>

