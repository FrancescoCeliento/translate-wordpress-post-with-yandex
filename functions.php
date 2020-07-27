<?php
//definisce se il blocco deve essere tradotto
function translatethis($text) {
    //blocchi da escludere dalla traduzione
    $exclude = ['wp:image',
                'wp:more',
                'wp:preformatted',
                'wp:html',
                'wp:gallery',
                '[youtube v='];
    $result = true;

    foreach ($exclude as $not) {
        if (strpos($text, $not) !== false)
            $result = false;
    }

    return $result;
}

function removehtmltag($text) {
    return trim(preg_replace('/<[^>]*>/', '', $text));
}

function removeheaderfooter($text) {
    $rows = explode("\n",$text);
    $result = "";
    for ($i=1; $i<sizeof($rows)-1; $i++) {
        $result = $result.$rows[$i];
    }

    return $result;
}

function getheader($text) {
    $rows = explode("\n",$text);
    return $rows[0]; 
}

function getfooter($text) {
    $rows = explode("\n",$text);
    $i = sizeof($rows)-1;
    return $rows[$i];
}

//definisce se è una lista
function islist($text) {
    $ultags = ['<ul>', //puntato
               '<ol>'];//numerico
    $iltag = "<il>";
    $aslist = false;

    foreach ($ultags as $ultag) {
        if (strpos($text, $ultag) !== false)
            $aslist = true;
    }

    return $aslist;
}

//aggiungi tag all'inizio e alla fine
function addHtmlTags($originalbox, $translatedtext) {
    $result = "";
    $originaltext = removeheaderfooter($originalbox);
    if (strpos($originaltext, '</p>') !== false) {
        $result = '<p>'.$translatedtext.'</p>';
    } else if (strpos($originaltext, '</h2>') !== false) {
        $result = '<h2>'.$translatedtext.'</h2>';
    } else if (strpos($originaltext, '</h3>') !== false) {
        $result = '<h3>'.$translatedtext.'</h3>';
    } else if (strpos($originaltext, '</ul>') !== false) {
        $result = '<ul>'.$translatedtext.'</ul>';
    } else if (strpos($originaltext, '</ol>') !== false) {
        $result = '<ol>'.$translatedtext.'</ol>';
    }
    
    return $result;
}

function execute_query($query,$type="list") {

	//settings your DB connection
	$host = '{YOUR_HOST}';
	$database = '{YOUR_DATABASE_NAME}';
	$user = '{YOUR_USER_ACCESS}';
	$password = '{YOUR_PASSWORD_ACCESS}';
	$messaggioerrore = "Connessione database di ".$service." non avvenuta";
	
	$link = mysqli_connect($host, $user, $password, $database);
	mysqli_set_charset($link, "utf8");
	if (!$link) {
		
		die($messaggioerrore);

		return false;
		
	} else {

		if ($type=="list") {
		
			$result = mysqli_query($link, $query);
		
		} else if ($type=="boolean") {
			
			$result = mysqli_query($link, $query);
		
		} else if ($type=="onerow") {
			$fetch = mysqli_query($link, $query);
			$result = mysqli_fetch_assoc($fetch);
				
		} else if ($type=="numrow") {
		
			$fetch = mysqli_query($link, $query);
			$result =  mysqli_num_rows($fetch);
		}
    		
		mysqli_close($link);
		
		return $result;

	}
}

?>