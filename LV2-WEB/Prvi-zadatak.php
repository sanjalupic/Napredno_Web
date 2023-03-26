<?php
    $db_name = 'lv_napredno_web'; 
    $dir = "C:/Users/Sanja/Desktop/LV2-WEB/backup/$db_name"; 

    if (!is_dir($dir)) {
        if (!@mkdir($dir)) {
            die("<p>Stvaranje direktorija nije moguuće $dir.</p>");
        }
    }

    $time = time();
    $dbc = @mysqli_connect('localhost', 'sanjalupic', 'sanjalupic', $db_name) or die("<p>Nije moguće spajanje na $db_name <p>"); //spajanje na bazu podataka
    $r = mysqli_query($dbc, 'SHOW TABLES'); //prikaz iz tablice podataka
    if (mysqli_num_rows($r) > 0) { //backuop 
        echo "<p>Backup za bazu podataka '$db_name'.</p>"; 
        while (list($table) = mysqli_fetch_array($r, MYSQLI_NUM)) {
            //Dohvaćanje podataka iz tablice
            $q = "SELECT * FROM $table";
            $r2 = mysqli_query($dbc, $q);
            $columns = $r2->fetch_fields();
            if (mysqli_num_rows($r2) > 0) {
                if ($fp = fopen("$dir/{$table}_{$time}.txt", 'w9')) {
                    //Dohvaćanje podataka:
                    while ($row = mysqli_fetch_array($r2, MYSQLI_NUM)) {
                        fwrite($fp, "INSERT INTO $db_name (");
                        foreach ($columns as $column) {
                            fwrite($fp, "$column->name");
                            if ($column != end($columns)) {
                                fwrite($fp, ", ");
                            }
                        }
                        fwrite($fp, ")\r\nVALUES (");
                        foreach ($row as $value) {
                            $value = addslashes($value);
                            fwrite($fp, "'$value'");
                            if ($value != end($row)) {
                                fwrite($fp, ", ");
                            } else {
                                fwrite($fp, ")\";");
                            }
                        }
                        fwrite($fp, "\r\n");
                    }
                    fclose($fp);
                    echo "<p>Table $table spremljena.</p>";
                    if ($fp2 = gzopen("$dir/{$table}_{$time}.sql.gz", 'w9')) {
                        gzwrite($fp2, file_get_contents("$dir/{$table}_{$time}.txt"));
                        gzclose($fp2);
                    } else {
                        echo "<p>File $dir/{$table}_{$time}.sql.gz nije moguće otvoriti. </p>";
                        break;
                    }
                } else {
                    echo "<p>File $dir/{$table}_{$time}.sql.gz nije moguće otvoriti. </p>";
                    break;
                }
            }
        }
    } else {
        echo "<p> Baza podataka nemam tablica. </p>";
    }
 ?>