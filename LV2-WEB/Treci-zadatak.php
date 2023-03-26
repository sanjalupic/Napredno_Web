<?php
    //Upravljanje oznakom:
	function handle_open_element($p, $element, $attributes) {
		switch($element) {	
            case 'ID':
			case 'IME':
			case 'PREZIME':
            case 'EMAIL':
            case 'SPOL':
            case 'SLIKA':
                echo '<h2>' . $element . ': </h2>';
                echo '<div><img src="' . $element . '"/></div>';
                break;
			case 'ZIVOTOPIS':
				echo '<h2>' . $element . ': </h2>';
				break;
		}
	}
	//Rukovanje
	function handle_close_element($p, $element) {
		switch($element) {
            case 'ID':
			case 'SLIKA':
                echo '<h2>' . $element . ': </h2>';
                echo '<div><img src="' . $element . '"/></div>';
                break;
			case 'IME':
			case 'PREZIME':
			case 'EMAIL':
			case 'ZIVOTOPIS':
				echo '<br>';
				break;
		}
	}
	//Ispis sadržaja:
	function handle_character_data($p, $cdata) {
		echo $cdata;
	}

	$p = xml_parser_create(); //parser
    //Funkcija za rukovanje
	xml_set_element_handler($p, 'handle_open_element', 'handle_close_element');
	xml_set_character_data_handler($p, 'handle_character_data');
	//Čitanje datoteke
	$file = 'LV2.xml';
	$fp = @fopen($file, 'r') or die("<p>Datoteka se ne može otvoriti. '$file'.</p></body></html>");
	while ($data = fread($fp, 4096)) {
		xml_parse($p, $data, feof($fp));
	}
	xml_parser_free($p);
?>