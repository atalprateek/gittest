<?php	
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = 'root';
$DB_NAME = 'db_dessert';
$backup_name="";
echo "<pre>";
$tables=CHECK_TABLES($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME);

print_r($tables);

function CHECK_TABLES($host,$user,$pass,$name){ 
	$tables=array();
	set_time_limit(3000); $mysqli = new mysqli($host,$user,$pass,$name); $mysqli->select_db($name); $mysqli->query("SET NAMES 'utf8'");
	$queryTables = $mysqli->query('SHOW TABLES'); 
	while($row = $queryTables->fetch_row()) { $target_tables[] = $row[0]; }	
	$children=array();
	foreach($target_tables as $table){
		if (empty($table)){ continue; } 
		$res = $mysqli->query('SHOW CREATE TABLE '.$table);	
		$TableMLine=$res->fetch_row(); 
		$create_query=$TableMLine[1];
		$to_continue=false;
		if(preg_match("/(FOREIGN\sKEY)/", $create_query)){
			//(?<=REFERENCES `)[a-zA-Z_]+
			$matches=array();
			preg_match_all("/(?<=REFERENCES `)[a-zA-Z_]+/", $create_query,$matches);
			if(is_array($matches[0])){
				foreach($matches[0] as $match){
					if(array_search($match,$tables)===false){
						$children[$TableMLine[0]][]=$match;
						$to_continue=true;
					}
				}
			}
		}
		if($to_continue){ continue; }
		$tables[]=$TableMLine[0];
	}
	if(!empty($children)){
		$tables=arrange_array($tables,$children);
	}
	return $tables;
}

function arrange_array($array,$children){
	print_r($children);
}
?>