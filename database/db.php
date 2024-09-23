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

	function getField($table, $id, $fieldName)
	{
		$$table = new $table();

		$output = $$table->getColsNSingleRow($fieldName, array('id' => $id));
		return $output[$fieldName];
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
		// echo $sql .'<br />';
		// die('bulb');
		return fetchRows($sql);
	}

	function getET($id) {
		$sql = "SELECT emptypeid FROM staffs WHERE id = " . $id;
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

	function getLeavesTaken($staffId, $year=YEAR, $leaveId="") {
		$sql = "SELECT sum(datediff(if(todte > '" . $year . "-12-31', '" . $year . "-12-31', todte), fromdte) + 1) AS taken 
				FROM leaverecords WHERE YEAR(fromdte) = " . $year . " AND leaveid = " . $leaveId . " AND staffid = " . $staffId;
		return fetchRow($sql);
	}

	function fetchRow($sql) {
		global $db_connection, $db_result;
		$db_result = mysqli_query($db_connection, $sql);
		if ( $db_result ) return mysqli_fetch_assoc($db_result);
		else return false;
	}

?>
