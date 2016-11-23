<?php

class userUtils  extends dbUtils {
   
    function userUpdatetoken($userid, $tokenid = '', $conn) {
        $query = "INSERT INTO tbToken(userid, token, sessionip, expiredate) "
                . "VALUES ('$userid','$tokenid','" . $_SERVER['REMOTE_ADDR'] . "',DATE_ADD(NOW(),INTERVAL 24 HOUR))"
                . " ON DUPLICATE KEY UPDATE expiredate = DATE_ADD(NOW(),INTERVAL 24 HOUR), token = '".$tokenid."';";
     
        $qr = mysqli_query($conn, $query);
        return $qr;
    }
    function userUpdatePassword($userid,$tokenid,$newPassword) {
        $query = "INSERT INTO tbPassword (userid,password,salt1,salt2,datechanged) "
                ."VALUES ('$userid',SHA1(CONCAT('$newPassword','".uniqid()."','".uniqid(2,true)."')'".uniqid()."','".uniqid(2,true)."',NOW()) "
                ." ON DUPLICATE KEY UPDATE password = SHA1('$newPassword',salt1,salt2)";
    }
    function checkToken($token){
        
    }
    function createToken(){
        if ( ! session_id() ) { session_start();}
        if (isset($_COOKIE['userToken'])){
                $ut = $_COOKIE['userToken'];
                
            }else {
                $ut = session_id();
            }
        return $ut;
    }
    function finduserfromtoken($token, $sessionip, $conn){
        $query = "SELECT a.username, b.expiredate from tbUser a INNER JOIN tbToken b on a.userid = b.userid WHERE b.token = '".$token."' AND b.sessionip = '".$sessionip."';";       
        $qr = mysqli_query($conn,$query);
        if (mysqli_num_rows($qr)>0){
            $arrQr = mysqli_fetch_assoc($qr);
            if($arrQr['expiredate'] < date('Y/M/D h:m:s')){
                $r =  $arrQr['username'];
            } else  {
                $r =  'expired';
            }
        } else {
            $r =  'notoken';
        }
        
        return $r;
    }
}
 
