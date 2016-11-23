<?php
include_once("../../util/dbConn.php");
$this->conn = dbUtils::dbOpen();
$tid = $_POST['token'];
$query = "DELETE FROM tbToken WHERE token = '.$tid.' ";


?>