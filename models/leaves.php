<?php
    include('../database/db.php');

    function getAllDets($staffId, $year=YEAR) {
        $sql = "SELECT lr.id,lr.noofdays,s.name sname, l.name lname, lr.fromdte, lr.todte, lr.status, l.name as lv
                FROM leaverecords as lr
                INNER JOIN leaves as l ON lr.leaveid = l.id
                INNER JOIN staffs as s ON lr.staffid = s.id
                WHERE YEAR(lr.fromdte) = " . $year . " AND lr.staffid = " . $staffId;
                if ( $staffId ) $sql .= " AND lr.staffid = " . $staffId;
        $sql .= " ORDER BY lr.fromdte, 	lr.leaveid";
        // print_r($sql);die();
        return fetchRows($sql);
    }

    function getAllDetsEdit($staffId, $year=YEAR, $id="") {
        $sql = "SELECT lr.leaveid as leaveid,lr.id,lr.staffid,lr.noofdays,s.name sname, l.name lname, lr.fromdte, lr.todte, lr.status, l.name as lv
                FROM leaverecords as lr
                INNER JOIN leaves as l ON lr.leaveid = l.id
                INNER JOIN staffs as s ON lr.staffid = s.id
                WHERE YEAR(lr.fromdte) = " . $year;
                if ( $staffId ) $sql .= " AND lr.staffid = " . $staffId;
                if ( $id ) $sql .= " AND lr.id = " . $id;
        $sql .= " ORDER BY lr.fromdte, 	lr.leaveid";
        // print_r($sql);die();
        return fetchRow($sql);
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
