<?php

error_reporting(E_ALL);
include_once("../../util/dbConn.php");
include_once("../../util/usertoken.php");

class userCheck extends userUtils {

    var $conn;
    var $ut;

    public function __construct() {
        $this->conn = dbUtils::dbOpen();
        $this->ut = userUtils::createToken();
        
    }

    function userVerify($username, $password, $remember, $login = true) {
        if ($login == true) {
            
            $query = "SELECT a.userid, '".$this->ut."' as 'token',NOW() as 'timestamp', a.username FROM tbUser a INNER JOIN tbPassword b ON a.userid = b.userid WHERE a.username = '" . trim($username) . "' AND b.password = SHA1(CONCAT('" . trim($password) . "' , b.salt1,b.salt2))";
            #echo $query;

            $qr = mysqli_query($this->conn, $query);

            $arrResult = mysqli_fetch_assoc($qr);
            if (mysqli_num_rows($qr) > 0) {
                if (userUtils::userUpdatetoken($arrResult['userid'], $arrResult['token'], $this->conn)) {
                    $_SESSION['token'] = $arrResult['token'];
                    $_SESSION['userid'] = $arrResult['userid'];
                    if ($remember == true) {
                        $arrResult["remember"]=true;
                    }
                    return $arrResult;
                } else {
                    $_SESSION['token'] = null;
                    $_SESSION['userid'] = null;
                    return false;
                }
            }
        else {
            $_SESSION['token'] = null;
            $_SESSION['userid'] = null;
            return false;
        }
        dbUtils::dbClose($this->conn);
    } else {return false;}
    }

}

$ut = new userCheck;

$user = json_encode($ut->userVerify($_POST['username'], $_POST['password'], $_POST['remember']));

echo $user;
?>
