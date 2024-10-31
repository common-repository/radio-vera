<?php
header('Content-type: application/xml');
function download_file_from_www($url)
{
            $ch = curl_init();
            @curl_setopt($ch,CURLOPT_URL,$url);
            @curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            //curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
            //передаем данные по методу post
            //curl_setopt($ch, CURLOPT_POST, 1);  
            //  задает время за которое мы должны загрузить указанный url             
            @curl_setopt($ch,CURLOPT_TIMEOUT, 2);
            //задает время на соединение с сервером
            @curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 2);
            //я не скрипт, я браузер опера
            @curl_setopt($ch, CURLOPT_USERAGENT, 'Opera 10.00');   
            //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            @curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
            @curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );        
            $data = curl_exec($ch);
            @curl_close($ch);
        return $data;
}
$content = download_file_from_www("http://radiovera.ru/programm/cur_playing.xml");

if(substr($content,1,12)=='?xml version'){
  echo $content;
}
?>