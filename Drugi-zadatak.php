<?php
$encrypted_files_dir = 'Upload/'; //ime kriptiranog dokumenta
$key  = 'Sanja276Lupic.';
$cipher = 'aes-256-cbc';
$iv_length = openssl_cipher_iv_length($cipher);
$options = 0; 
$iv = random_bytes($iv_length); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //Provjera ima li datoteke
    if (!empty($_FILES['file']['tmp_name'])) {
        
        $encrypted_file = 'Upload/' . $_FILES['file']['name'] . '.enc'; //ime kriptiranog dokumenta
        $temp_file = $_FILES['file']['tmp_name'];
        //otvaranje datoteke:
        $input_file = fopen($temp_file, 'rb'); 
        $output_file = fopen($encrypted_file, 'wb');
        //Kopiranje datoteke:
        fwrite($output_file, $iv);
            while (!feof($input_file)) {
                $plaintext = fread($input_file, 8192);
                $ciphertext = openssl_encrypt($plaintext, $method, $key, OPENSSL_RAW_DATA, $iv);
                fwrite($output_file, $ciphertext);
            }

            //zatvaranje datoteke
            fclose($input_file);
            fclose($output_file);

            //obavijest
            echo 'Uspješno je priptirana i uploudana datoteka.';
        } else {
            echo 'Odabrana datoteka nije upoludana.';
        }
    }
    //Postavljanje putanje za kriptiranje
    $encrypted_files_dir = 'Upload/';

    $encrypted_files = glob($encrypted_files_dir . '*.enc'); //dohvaćanje svih kriptiranih datoteka u mapi

    foreach ($encrypted_files as $encrypted_file) {

        $decrypted_file = str_replace('.enc', '', $encrypted_file); //ime dekriptirane datoteke

        $input_file = fopen($encrypted_file, 'rn');
        $output_file = fopen($decrypted_file, 'wb');

        $iv = fread($input_file, $iv_length); 
        $encrypt = fread($input_file, filesize($encrypted_file));
        $decrypt = openssl_decrypt(base64_decode($encrypt), $cipher, $key, $options, $iv);
        fwrite($output_file, $decrypt);


        fclose($input_file);
        fclose($output_file);

        echo '<a href="'.$decrypted_file.'">Preuzmi '.basename($decrypted_file).'</a><br>';//prikaz linka za preuzimanje datoteke
    }
?>
