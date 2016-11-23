<?php

class dbUtils {

    public function dbOpen() {
        if (file_exists('../pw')) {
            $arrdb = file('../pw', FILE_IGNORE_NEW_LINES);
        } elseif (file_exists('../../pw')) {
            $arrdb = file('../../pw', FILE_IGNORE_NEW_LINES);
        } elseif (file_exists('../../../pw')) {
            $arrdb = file('../../../pw', FILE_IGNORE_NEW_LINES);
        }

        $conn = mysqli_connect($arrdb[0], $arrdb[1], $arrdb[2], $arrdb[3]);
        return $conn;
    }

    public function dbQuery($query) {
        $conn = $this->dbOpen();
        $qr = mysqli_query($conn, $query);
        
        $this->dbClose($conn);
        return $qr;
    }

    public function dbClose($conn) {
        mysqli_close($conn);
        return true;
    }
    function qr_to_table($arr) {
    $result = '<table border = "1" style ="padding:5px;">';
    $n = 0;
    foreach ($arr as $i) {
        $result .= '<tr>';

        if ($n == 0) {
            foreach (array_keys($i) as $label) {
                $result .= '<td>' . $label . '</td>';
            }
            $result .= '</tr><tr>';
        }
            foreach ($i as $cell) {
                $result .= '<td>' . $cell . '</td>';
            }
            $result .= '</tr>';
            $n++;
        }
        $result .= '</table><p>' . $n . ' Rows Received.</p>';
        return $result;
    }


}

?>