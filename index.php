<?php
include_once('util/session.php');
$sess = new session_tools;


if (!empty($_COOKIE['userToken'])) {
    $sess->currsess = $sess->checkToken($_COOKIE['userToken'], $_SERVER['REMOTE_ADDR']);
    $sess->username = $sess->currsess['username'];
   
} else {
    $sess->currsess = false;
}
?>
<html>
    <head>
        <link href="css/jquery-ui.css" rel="stylesheet">
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>


    </head>
    <body>
        
<?php
print_r($_COOKIE);
if ($sess->currsess != false) {
    //secure content here
    echo '<h1>Welcome '.$sess->username.'!</h1>';
    include_once("util/sql_query.php");
    echo' <form action="' . $_SERVER['PHP_SELF'] . '" method="post">
        <input type="hidden" value="'.$sess->currsess['token'].'" name="logout" />
    <input type="submit" value="logout" />
</form>';
} else {
    //insecure content here
    ?>
     
    <?php include_once("util/login.php");
}
?>

    </body>
</html>
