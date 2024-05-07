<!DOCTYPE html>
<html>

<head>
    <title>Aplikasi Enkripsi-Dekripsi</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Enkripsi & Dekripsi Teks</h2>

        <form method="POST" action="">
            <div class="form-group">
                <label for="plaintext">Teks biasa:</label>
                <textarea class="form-control" name="plaintext" id="plaintext" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="key">Kunci:</label>
                <input type="text" class="form-control" name="key" id="key" required>
            </div>
            <button type="submit" class="btn btn-primary" name="encrypt">Enkripsi</button>
            <button type="submit" class="btn btn-primary" name="decrypt">Dekripsi</button>
        </form>

        <?php
        // Fungsi untuk mengenkripsi teks
        function encrypt($plaintext, $key)
        {
            // Menghasilkan IV secara acak
            $ivSize = openssl_cipher_iv_length('AES-256-CBC');
            $iv = openssl_random_pseudo_bytes($ivSize);

            // Melakukan enkripsi
            $ciphertext = openssl_encrypt($plaintext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

            // Menggabungkan IV dan ciphertext yang telah dienkripsi, lalu mengenkodinya dengan base64
            $ciphertext = base64_encode($iv . $ciphertext);

            return $ciphertext;
        }

        // Fungsi untuk mendekripsi teks
        function decrypt($ciphertext, $key)
        {
            // Mendekode ciphertext dari base64
            $ciphertext = base64_decode($ciphertext);

            // Memisahkan IV dari ciphertext
            $ivSize = openssl_cipher_iv_length('AES-256-CBC');
            $iv = substr($ciphertext, 0, $ivSize);

            // Memisahkan teks yang dienkripsi dari IV
            $ciphertext = substr($ciphertext, $ivSize);

            // Melakukan dekripsi
            $plaintext = openssl_decrypt($ciphertext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

            return $plaintext;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $plaintext = $_POST['plaintext'];
            $key = $_POST['key'];

            if (isset($_POST['encrypt'])) {
                $ciphertext = encrypt($plaintext, $key);
                echo '<div class="alert alert-success mt-3" role="alert">
                    Ciphertext: ' . $ciphertext . '
                </div>';
            } elseif (isset($_POST['decrypt'])) {
                $decryptedText = decrypt($plaintext, $key);
                echo '<div class="alert alert-success mt-3" role="alert">
                    Decrypted text: ' . $decryptedText . '
                </div>';
            }
        }
        ?>

    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>