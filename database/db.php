<?php 
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	$config = array(
		'server' => 'localhost',
		'username' => 'root',
		'password' => '',
		'database' => 'fis',
	);

	global $db_connection, $db_result;

	$db_connection = false; 
	$db_connection = mysqli_connect($config['server'], $config['username'], $config['password'], $config['database']);

	mysqli_select_db($db_connection,$config['database']);
	mysqli_query($db_connection,"SET CHARACTER SET utf8"); 
	mysqli_query($db_connection,"SET NAMES 'utf8'");
	$db_result = false;

	define('YEAR', date('Y',strtotime("NOW")));

	function fetchRows($sql, $paginate=false) {
		global $db_connection, $db_result;
		global $total_pages;
		
		$total_pages = 0;
		
		$db_result = mysqli_query($db_connection,$sql);
		
		if ( $db_result ) {
		
			if ( $paginate ) {
				// implement pagination
				$page = $_GET['pg'];
				$pg_size = $_GET['pg_size'];
				if(empty($page)) $page = 1;
				if(empty($pg_size)) $pg_size = 20;
				$st = ($page-1)*$pg_size;
				$total_pages = ceil(mysqli_num_rows($db_result) / $pg_size);
				$sql .= ' LIMIT ' . $st . ', ' . $pg_size;
				
				$db_result = mysqli_query($db_connection, $sql);
			}
		
			$results = array();
			while ($row = mysqli_fetch_assoc($db_result)) {
				$results[] = $row;
			}
			return $results;
		} else return false;
	}

	function getField($id)
	{
		$sql = "SELECT id FROM Staffs WHERE userid = " . $id;
		// print_r($sql);die();
		return fetchRow($sql);
	}

	function getName($table, $id)
	{
		$$table = new $table();

		$output = $$table->getColsNSingleRow('name', array('id' => $id));
		return $output['name'];
	}


	function insert($data,$tableName) {
		$keys = '`' . implode('`, `', array_keys($data) ) . '`';
		$values = '"' . implode('", "', array_values($data) ) . '"';		
		$sql = 'insert into `' . $tableName . '` (' . $keys . ') values (' . $values . ')';
		// print_r($sql);die();
		return executeQuery($sql);
	}

	function executeQuery($sql) {
		global $db_connection, $db_result;
		$db_result = mysqli_query($db_connection, $sql);
		return $db_result;
	}

	function find($data, $sortby = 'id', $dataIsNot="", $checkWhiteSpace="",$tableName) {
		$whereClause = array();
		
		if ( is_array($data) ) {
			foreach ( $data as $id=>$val ) {
				if ( $checkWhiteSpace ){
					$whereClause[] = 'trim(replace('.$id.', " ", ""))' . ' = "' .  str_replace( " ", "", trim($val) ) . '"';					
				}else{
					$whereClause[] = $id . ' = "' . $val . '"';								
				}
			
			}
			$whereClause = implode(' and ', $whereClause);
		} else $whereClause = $data;
		
		foreach ( (array)$dataIsNot as $i=>$v ) {
			$excludeClause[] = $i . ' <> "' . $v . '"';
		}

		if ( count($excludeClause) > 1 ) $excludeClause = implode(' and ', $excludeClause);
		elseif ( $dataIsNot ) $excludeClause = ' and ' . implode(' and ', $excludeClause);
		else $excludeClause = '';
		
		$sql = 'select * from `' . strtolower($tableName) . '` where ' . $whereClause . ' ' . $excludeClause;
		if ( $sortby ) $sql .= ' order by ' . $sortby;
		return fetchRows($sql);
	}

	function getET($id) {
		$sql = "SELECT emptypeid FROM staffs WHERE userid = " . $id;
		// echo $sql .'<br />';
		// die('bulb');
		return fetchRow($sql);
	}

	function getIdName($name="",$status= 1,$id="",$compId="",$year="", $empTypeId="") {
		$sql = "select id, name, days from leaves where 1=1";
		if ($name) $sql.=" and name like '%". $name ."%'";					
		if ($status) $sql.=" and status = ". $status;
		if ( $id ) $sql .= " and id = " . $id;
		if ( $empTypeId ) $sql .= " and emptypeid = " . $empTypeId;
		if ( !$year ) $sql .= " AND yr = YEAR(CURDATE())";
		else $sql .= " AND yr = '" . $year . "'";
		$sql .= " order by name";
		// echo $sql;
		return fetchRows($sql);
	}

	function getLeavesTaken($staffId, $year=YEAR, $leaveId="",$id="") {
		$sql = "SELECT sum(datediff(if(todte > '" . $year . "-12-31', '" . $year . "-12-31', todte), fromdte) + 1) AS taken 
				FROM leaverecords INNER JOIN staffs s ON leaverecords.staffid = s.id WHERE leaverecords.status = 'Approved' AND YEAR(fromdte) = " . $year . " AND leaveid = " . $leaveId . " AND s.userid = " . $staffId;
		if ( $id ) $sql .= " and s.id  != " . $id;
		// echo $sql;die();
		return fetchRow($sql);
	}

	function fetchRow($sql) {
		global $db_connection, $db_result;
		$db_result = mysqli_query($db_connection, $sql);
		if ( $db_result ) return mysqli_fetch_assoc($db_result);
		else return false;
	}

	function delete($id, $status=0, $tableName="") {
		$sql = 'update `' . $tableName . '` set status='.$status.' where id="'.$id.'"';
		// echo $sql;die();
		return executeQuery($sql);
	}
	function real_delete($id, $tableName) {
		$sql = 'delete from `' . $tableName . '` where id="'.$id.'"';
		return executeQuery($sql);
	}

	function update($id, $data,$tableName) {
		$updateClause = array();
		foreach ( $data as $iid=>$val ) {
			$updateClause[] = '`' . $iid . '`' . ' = "' . str_replace('"', '\"',$val) . '"';
		}
		$updateClause = implode(', ', $updateClause);
		$sql = 'update `' . $tableName . '` set ' . $updateClause . ' where id = "' . $id . '"';

		// echo $sql.'<br/>';
		// die('bulb');
		return executeQuery($sql);
	}

	function getColumns($columns, $wC, $oC='',$table) {
		$sql = "SELECT " . $columns . " FROM `" . $table . "`";
		$whereClause = array();
		foreach ( $wC as $iid=>$val ) {
			$whereClause[] = '`' . $iid . '`' . ' = "' . $val . '"';
		}
		$whereClause = implode(' AND ', $whereClause);
		if ( $wC ) $sql .= " WHERE 1 = 1 AND " . $whereClause;
		if ( $oC ) $sql .= " ORDER BY " . $oC;
		return fetchRows($sql);
	}

	function getColsNSingleRow($columns, $wC,$table) {
		$sql = "SELECT " . $columns . " FROM `" . $table . "`";
		$whereClause = array();
		foreach ( $wC as $iid=>$val ) {
			$whereClause[] = '`' . $iid . '`' . ' = "' . $val . '"';
		}
		$whereClause = implode('AND ', $whereClause);
		if ( $wC ) $sql .= " WHERE 1 = 1 AND " . $whereClause;
		return fetchRow($sql);
	}

	function getUnapprovedRecords($appId="") {
		$sql = "SELECT id FROM `leaverecords` lr WHERE status != 'Approved' AND completed = '".$appId."'";
		return fetchRows($sql);
	}

	function numberToOrdinal($num) {
		$ordinals = [
			1 => 'First', 2 => 'Second', 3 => 'Third', 4 => 'Fourth', 5 => 'Fifth',
			6 => 'Sixth', 7 => 'Seventh', 8 => 'Eighth', 9 => 'Ninth', 10 => 'Tenth',
			11 => 'Eleventh', 12 => 'Twelfth', 13 => 'Thirteenth', 14 => 'Fourteenth',
			15 => 'fifteenth', 16 => 'sixteenth', 17 => 'seventeenth', 18 => 'eighteenth',
			19 => 'nineteenth', 20 => 'twentieth', 30 => 'thirtieth', 40 => 'fortieth',
			50 => 'fiftieth', 60 => 'sixtieth', 70 => 'seventieth', 80 => 'eightieth',
			90 => 'ninetieth'
		];
	
		if (isset($ordinals[$num])) {
			return $ordinals[$num];
		}
	
		if ($num < 100) {
			return $ordinals[(int)($num / 10) * 10] . ($num % 10 ? '-' . $ordinals[$num % 10] : '');
		}
	
		if ($num < 1000) {
			return numberToOrdinal((int)($num / 100)) . ' hundred' . ($num % 100 ? ' and ' . numberToOrdinal($num % 100) : '');
		}
	
		return $num . 'th';
	}



	function setNotifForPendingLeaveRequests($userId)
	{

		$appr = getColumns('approvalid as id', array('status' => 1),'','LeaveApprovals');
		
		foreach($appr as $key=>$val){
			$details = getColsNSingleRow('*', array('approvalid'=>$val['id'],'status' => 1),'ApprovalRights');
			if($details['approvalid']){
				
				$orderNo = $details['approvalid'];
				$appNO = $orderNo - 1;
				
				$dets = getUnapprovedRecords($appNO);
				$notMessage = 'You Have ' . count($dets).' '. numberToOrdinal($orderNo). ' approval For leave requests.';
				$notLink = '?module=leaves&action=leaverecords&approval='.$appNO;
				$notDate = date('Y-m-d');;

				$notifFound = find(array('link' => $notLink, 'userid' => $userId),'','','','Notifications');
				
				if (!empty($notifFound)) {
					if (!$dets) {
						real_delete($notifFound[0]['id'],'Notifications');
					} else {
						update($notifFound[0]['id'], array('notification' => $notMessage, 'seen' => 0),'Notifications');
					}
				} else {
					if ($dets) {
						find(array('link' => $notLink, 'date' => $notDate, 'notification' => $notMessage, 'userid' => $userId, 'seen' => 0),'','','','Notifications');
						if (!$notifFound) {
							$notDets['notification'] = $notMessage;
							$notDets['link'] = $notLink;
							$notDets['date'] = $notDate;
							$notDets['seen'] = 0;
							$notDets['userid'] = $userId;
							$notDets['category'] = $notCategory;
							insert($notDets,'Notifications');
						}
					}
				}
			}

		}

	}

?>
