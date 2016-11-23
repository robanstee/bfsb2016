<?php

error_reporting(E_ALL);

function getfile($url, $arrFields, $method = 'POST') {

    $ch = curl_init();
 
    if ($method == 'POST') {
        
        $defaults = array(
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_URL => $url,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_TIMEOUT => 4,
            CURLOPT_POSTFIELDS => $arrFields
        );
        curl_setopt_array($ch,$defaults);
    } elseif ($method == 'GET') {
        curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
        $strQry = '?';
        foreach ($arrFields as $n => $x) {
            if ($strQry != '?') {
                $strQry = $strQry . '&';
            }
            $strQry = $strQry . $n . '=' . $x;
        }
        curl_setopt($ch, CURLOPT_URL, $url . $strQry);
    } elseif ($method == 'PUT') {
        #Do put stuff later
    }

    if( ! $result = curl_exec($ch)) 
    { 
        echo (curl_error($ch)); 
    } 
    curl_close($ch); 
    return $result; 
}
#echo (getfile('http://www.bigfishsmallbarrel.com/api/v0_0/token_check.php', array('token' => $_COOKIE['userToken'], 'sessionip' => $_SERVER['REMOTE_ADDR']),'POST'));
?>