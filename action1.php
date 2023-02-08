<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'salesfro_mohw');
define('DB_PW', 'VS2*}Ib#A]CL');
define('DB_NAME', 'salesfro_events');
define('DB_LANG', 'utf8');
define('DB_TIMEZONE', 'Taiwan/Taipei');

if(function_exists('date_default_timezone_set')) {
	date_default_timezone_set(DB_TIMEZONE);
} else {
	putenv("TZ=".DB_TIMEZONE); 
}

$name = trim($_GET['name']);
$email = trim($_GET['email']);
$tel = trim($_GET['tel']);
$status = "S";
$n = (int)$_GET['n'];  // 計算次數

$empty = false;
$result['success'] = false;

if(!$n || $n < 1) {
	$n = 1;
} else {
	$n++;
}
if(!$name) {
	$empty = true;
	$result['msg'][] = "姓名不可為空。\n";
}
if(!$email) {
	$empty = true;
	$result['msg'][] = "電子郵件信箱不可為空\n";
}
if(!$tel) {
	$empty = true;
	$result['msg'][] = "電話不可為空\n";
}
if($empty !== true) {
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PW, DB_NAME);
	$mysqli->set_charset(DB_LANG);
	if($mysqli->connect_error) {
		$result['msg'] = "資料庫連結失敗，錯誤訊息：" . $mysqli->connect_error;
	} else {
        $event_name = "cpami2021event";
		$sql = "insert into sf_events (event_name, full_name_zh, email, mobile) values (?, ?, ?, ?)";
		if($stmt = $mysqli->prepare($sql)) {
			$stmt->bind_param("ssss", $event_name, $name, $email, $tel);
			$stmt->execute();
			if($stmt->affected_rows) {
				$id = $mysqli->insert_id;
				$result['success'] = true;
			}
		}
		$stmt->close();
		$mysqli->close();
	}
}

echo "result(".json_encode($result).")";

?>
