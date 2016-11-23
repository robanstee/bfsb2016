<?php

include_once("dbUtils.php");
error_reporting(E_ALL);
if (!session_id()) {
    session_start();
}
if (!isset($_COOKIE['userToken'])) {
    setcookie("userToken", session_id());
}

class session_tools Extends dbUtils {
    var $loginresult ='';
    var $ut;
    var $currsess;
    var $username;
    public function __construct() {
        $this->ut = $this->createToken();
               

        if (!empty($_POST['login'])) {
            
            if (!empty($_POST['remember_me'])) {
                $rem = $_POST['remember_me'];
            } else {
                $rem = false;
            }
            
            $lstat = $this->userVerify($_POST['login'], $_POST['password'], $rem);
            if ($lstat) {
                $this->currsess = $lstat;
                $this->username = $lstat['username'];
            } else {
                $this->currsess = false;
                $this->loginresult = '<span class = "loginResult">Username / password error</span>';
            }
        }
        if (!empty($_POST['logout'])) {
            $logout = $this->logout($_COOKIE['userToken'], $_SERVER['REMOTE_ADDR']);
        }
    }

    function userUpdatetoken($userid, $tokenid = '') {
        $query = "INSERT INTO tbToken(userid, token, sessionip, expiredate) "
                . "VALUES ('$userid','$tokenid','" . $_SERVER['REMOTE_ADDR'] . "',DATE_ADD(NOW(),INTERVAL 24 HOUR))"
                . " ON DUPLICATE KEY UPDATE expiredate = DATE_ADD(NOW(),INTERVAL 24 HOUR), token = '" . $tokenid . "', sessionip = '".$_SERVER['REMOTE_ADDR'] ."';";

        $qr = dbUtils::dbQuery($query);
        return $qr;
    }

    function userUpdatePassword($userid, $tokenid, $newPassword) {
        $query = "INSERT INTO tbPassword (userid,password,salt1,salt2,datechanged) "
                . "VALUES ('$userid',SHA1(CONCAT('$newPassword','" . uniqid() . "','" . uniqid(2, true) . "')'" . uniqid() . "','" . uniqid(2, true) . "',NOW()) "
                . " ON DUPLICATE KEY UPDATE password = SHA1('$newPassword',salt1,salt2)";
        $qr = dbUtils::dbQuery($query);
        return $qr;
    }

    function checkToken($token, $ip) {
        $q = 'SELECT a.userid, token, sessionip,username from tbToken a inner join tbUser b ON a.userid = b.userid WHERE token = "' . $token . '" AND sessionip = "' . $ip . '" AND expiredate > NOW();';
        $qr = dbUtils::dbQuery($q);
        if (mysqli_num_rows($qr) > 0) {
            return mysqli_fetch_assoc($qr);
        } else {
            return false;
        }
    }

    function logout($token, $ip) {
        $query = "UPDATE tbToken SET token = '' WHERE token = '" . $token . "'";

        $qr = dbUtils::dbQuery($query);
        if ($qr) {
            session_destroy();
            return true;
        } else {
            return false;
        }
    }

    function userVerify($username, $password, $remember, $login = true) {
        if ($login == true) {

            $query = "SELECT a.userid, '" . $this->ut . "' as 'token',NOW() as 'timestamp', a.username FROM tbUser a INNER JOIN tbPassword b ON a.userid = b.userid WHERE a.username = '" . trim($username) . "' AND b.password = SHA1(CONCAT('" . trim($password) . "' , b.salt1,b.salt2))";
            #echo $query;

            $qr = dbUtils::dbQuery($query);


            if (mysqli_num_rows($qr) > 0) {
                $arrResult = mysqli_fetch_assoc($qr);
                if ($this->userUpdatetoken($arrResult['userid'], $arrResult['token'])) {
                    $_SESSION['token'] = $arrResult['token'];
                    $_SESSION['userid'] = $arrResult['userid'];
                    if ($remember == true) {
                        $arrResult["remember"] = true;
                    }
                    return $arrResult;
                } else {
                    $_SESSION['token'] = null;
                    $_SESSION['userid'] = null;
                    return false;
                }
            } else {
                $_SESSION['token'] = null;
                $_SESSION['userid'] = null;
                return false;
            }
        } else {
            return false;
        }
    }

    function createToken() {
        if (!session_id()) {
            session_start();
        }
        if (isset($_COOKIE['userToken'])) {
            $ut = $_COOKIE['userToken'];
        } else {
            $ut = session_id();
        }
        return $ut;
    }

    function finduserfromtoken($token, $sessionip, $conn) {
        $query = "SELECT a.username, b.expiredate from tbUser a INNER JOIN tbToken b on a.userid = b.userid WHERE b.token = '" . $token . "' AND b.sessionip = '" . $sessionip . "';";
        $qr = mysqli_query($conn, $query);
        if (mysqli_num_rows($qr) > 0) {
            $arrQr = mysqli_fetch_assoc($qr);
            if ($arrQr['expiredate'] < date('Y/M/D h:m:s')) {
                $r = $arrQr['username'];
            } else {
                $r = 'expired';
            }
        } else {
            $r = 'notoken';
        }

        return $r;
    }
    function createUser($username, $email, $firstname, $lastname) {
        $query = "INSERT INTO tbUser (userid, username, email, firstname, lastname)" 
            . " VALUES ('" . uniqid() . "', '" . $username . "', '" .$email . "','" . $firstname ."','" .lastname ."')";
        $qr = dbUtils::dbQuery($query);
        return $qr;
        
    }

}

?>
