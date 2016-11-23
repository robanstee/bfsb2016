<?php
include_once("dbUtils.php");


    if (isset($_POST['query'])) {
        $qtext = $_POST['query'];
    } else {
        $qtext = "";
    }
    ?>
    <form action="#" method ="post" >
        <p><textarea rows="4" cols="100" name="query"><?php echo $qtext; ?></textarea></p>
        <p><button type="submit">OK</button></p>
    </form>
    <?php
    if ($qtext != "") {
        $dbu = new dbutils();
        $arr = $dbu->dbQuery($qtext);
        echo $dbu->qr_to_table($arr);
    }
    ?>