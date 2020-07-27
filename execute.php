<?php

//if (parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) == 'www.selectallfromdual.com') {
if (true) {

        header('Content-Type: text/plain; charset=utf-8');
        include("functions.php");
        include("translate.php");
        $idpost = $_GET['idpost'];
        $query = 'SELECT * FROM blog_posts where id = '.$idpost; //example 111 e 577

        $result = execute_query($query,'onerow');

        //echo $result['post_title'].'<br/><br/>';
        //echo str_replace("\n\n","\n\naaa",$result['post_content']);
        $originalelement = explode("\n\n",$result['post_content']);
        $translateelement = [];

        foreach ($originalelement as $element) {
            if (translatethis($element)) {
                if (islist($element)) {
                    //preparo il contenuto da estrarre
                    $header = getheader($element);
                    $originallist = removeheaderfooter($element);
                    $footer = getfooter($element);

                    //rimuovo gli indici ed esplodo la lista
                    $originallist = str_replace('<ul>','',$originallist);
                    $originallist = str_replace('</ul>','',$originallist);
                    $originallist = str_replace('<ol>','',$originallist);
                    $originallist = str_replace('</ol>','',$originallist);
                    $arrayoflist = explode ('</li>',$originallist);
                    $carrayoflist = [];

                    //traduco la lista
                    for ($i=0; $i<sizeof($arrayoflist); $i++) {
                        $arratext = $arrayoflist[$i];
                        $arratext = str_replace('<li>','</li>',$arratext);
                        $arratext = removehtmltag($arratext);
                        $arratext = translatetext($arratext,'it','en');
                        if (isset($arratext) && $arratext!="") {
                            $arratext = '<li>'.$arratext.'</li>';
                            array_push($carrayoflist,$arratext);
                        }
                    }

                    //ricompongo la lista
                    $nexboxlist = "";
                    foreach ($carrayoflist as $elist) {
                        $nexboxlist = $nexboxlist.$elist;
                    }
                    $nexboxlist = addHtmlTags($element,$nexboxlist);
                    array_push($translateelement,$header."\n".$nexboxlist."\n".$footer."\n");

                } else  {
                    
                    $originaltext =  removehtmltag(removeheaderfooter($element));

                    $header = getheader($element);
                    $translatedtext = translatetext($originaltext,'it','en');
                    $addtext = addHtmlTags($element, $translatedtext);
                    
                    $footer = getfooter($element);
                    array_push($translateelement,$header."\n".$addtext."\n".$footer."\n");
                }
            } else {
                array_push($translateelement,$element);
            }
        }


        echo "=== TITOLO =======\n";
        echo translatetext($result['post_title'],'it','en')."\n\n";

        echo "=== BODY ========\n";
        foreach ($translateelement as $telement) {
            echo $telement."\n\n";
        }

}

?>