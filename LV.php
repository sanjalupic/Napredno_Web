<?php
include('./simple_html_dom.php');
interface iRadovi
    {
        public function create($data);
        public function save();
        public function read();
    }

class DiplomskiRad implements iRadovi
    {
        private $naziv_rada = NULL;
        private $tekst_rada = NULL;
        private $link_rada = NULL;
        private $oib_tvrtke = NULL;

    function __construct($data)
        {
            $this->naziv_rada = $data['naziv_rada'];
            $this->tekst_rada = $data['tekst_rada'];
            $this->link_rada = $data['link_rada'];
            $this->oib_tvrtke = $data['oib_tvrtke'];
        }

    function create($data)
        {
            self::__construct($data);
        }

    function save()
        {
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "radovi";

            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $naziv = $this->naziv_rada;
            $tekst = $this->tekst_rada;
            $link = $this->link_rada;
            $oib = $this->oib_tvrtke;

            $sql = "INSERT INTO `diplomski_radovi` (`id`, `naziv_rada`, `tekst_rada`, `link_rada`, `oib_tvrtke`) VALUES ('$naziv', '$tekst', '$link', '$oib')";
            if ($conn->query($sql) === true) {
                $this->read();
            } else {
                echo "Error! Oops, something went weong." . $sql . "<br>" . $conn->error;
            }
            ;
            $conn->close();
        }

    function read()
        {
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "radovi";

            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT * FROM `diplomski_radovi`";
            $output = $conn->query($sql);
            if ($output->num_rows > 0) {
                while ($item = $output->fetch_assoc()) {
                    echo"<br><br><br>ID: " . $item["id"];
                    echo"<br><br>OIB tvrtke: " . $item["oib_tvrtke"];
                    echo"<br><br>Naziv rada: " . $item["naziv_rada"];
                    echo"<br><br>Link rada: " . $item["link_rada"];
                    echo"<br><br>Tekst rada: " . $item["tekst_rada"];
                }
            }
            $conn->close();
        }
    }

    echo"Diplomski radovi";
    $url = 'https://stup.ferit.hr/index.php/zavrsni-radovi/page/3';
    $fp = fopen($url, 'r'); //otvaranje datoteke za čitanje
    $read = fgetcsv($fp); //čitanje svakog retka pojedinačno 

    $read = file_get_html($url);                     //file_get_html se nalazi u biblioteki navedenoj na početku 
    foreach ($read->find('article') as $article) {

        foreach ($article->find('ul.slides img') as $img) {
        }
        foreach ($article->find('h2.entry-title a') as $link) {
            $html = file_get_html($link->href);
            foreach ($html->find('.post-content') as $text) {
            }
        
            $rad = array(
                'naziv_rada' => $link->text,
                'tekst_rada' => $text->text,
                'link_rada' => $link->href,
                'oib_tvrtke' => preg_replace('/[^0-9]/', '', $img->src)
            );
            $newRad = new DiplomskiRad($rad);
            $newRad->save();
        }
        fclose($fp);
    }

?>