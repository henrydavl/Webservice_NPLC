<?php 
include('config.php');
$value = json_decode(file_get_contents('php://input'));

$user_id = $_POST['user_id']; #$value->id;

$query = $db->query("SELECT status FROM users WHERE id = $user_id");
$as = mysqli_fetch_assoc($query);
$stat = $as['status'];
if ($stat == 'E') {
	send("ok");
} elseif ($stat == 'D') {
	forceLogout($user_id);
	send('Account Disabled');
} 


function send($message){	
    $response["user"] = array();
	$user = array();
	$user["msg"] = $message;
	array_push($response["user"], $user);
	echo json_encode($response);
}

function forceLogout($id){
	$update = $db->query("UPDATE users SET is_login = '0', last_logout = '$time', updated_at = '$time' WHERE id = $user_id ");
	if ($update) {
		send("out_ok");
	}else{
		send("out_no");
	}
}

?>