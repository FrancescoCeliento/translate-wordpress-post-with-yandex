<?php 

function translatetext($text, $from, $to) {
    if (isset($text) && $text!="") {
        $api = '{YOU_API_KEY}';
        // TODO: Get your key from https://tech.yandex.com/translate/
        $url = file_get_contents('https://translate.yandex.net/api/v1.5/tr.json/translate?key='.$api.'&lang='.$from.'-'.$to.'&text='.urlencode($text));
        $json = json_decode($url);
        return $json->text[0];
    } else {
        return "";
    }
}
?>