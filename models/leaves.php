<?php
    include('../database/db.php');

    function getAllDets($staffId, $year=YEAR) {
        $sql = "SELECT lr.noofdays,s.name sname, l.name lname, lr.fromdte, lr.todte, lr.status, l.name as lv
                FROM leaverecords as lr
                INNER JOIN leaves as l ON lr.leaveid = l.id
                INNER JOIN staffs as s ON lr.staffid = s.id
                WHERE YEAR(lr.fromdte) = " . $year . " AND lr.staffid = " . $staffId;
                if ( $staffId ) $sql .= " AND lr.staffid = " . $staffId;
        $sql .= " ORDER BY lr.fromdte, 	lr.leaveid";
        return fetchRows($sql);
    }

    function getAllLeaves($name="",$status= 1,$id="",$year="") {
        $sql = "select * from Leaves where 1=1";
        if ($name) $sql.=" and name like '%". $name ."%'";					
        if ($status) $sql.=" and status = ". $status;
        if ( $id ) $sql .= " and id = " . $id;
        if ( !$year ) $sql .= " AND yr = YEAR(CURDATE())";
        else $sql .= " AND yr = '" . $year . "'";
        $sql .= " order by name";
        return fetchRows($sql);
    }
?>
