<?php 
include ('config.php');
$value = json_decode(file_get_contents('php://input'));
//get data user from login activity
$username = $_POST['username']; # $value->username //wizozi
$password = $_POST['password']; # $value->password //ekonom4r

$check = $db->query("SELECT * FROM users WHERE username = '$username' AND role_id = 2");
$get = mysqli_fetch_assoc($cek_hod);

$response["liaison"] = array();

if ($check) {
    if ($get['is_login'] == 1 && $get['status'] == 'E') {
        send('Account in use!');
    } elseif ($get['status'] == 'D' && $get['is_login'] == '0' || $get['is_login'] == '1') {
        send('Account disabled!');
    } else {

        if (password_verify($password, $get['password']) == true && $get['status'] == 'E' && $get['is_login'] == '0') {
            $id = $get['id'];
            $pic = $db->query("SELECT qr_code, title FROM games WHERE liaison_id = $id");
            $get_info = mysqli_fetch_assoc($pic);
            $user = array();
            $user["id"] = $get['id'];
            $user["name"] = $get['name'];
            $user['game_title'] = $get_info['title'];
            $user['qrcode'] = $get_info['qr_code'];
            $user["msg"] = "welcome";
            $update = $db->query("UPDATE users SET is_login = '1', last_login = '$time', updated_at = '$time' WHERE username = '$username'");
            if ($update) {
                array_push($response["user"], $user);
                echo json_encode($response);
            }else{
                send('Log in failed, please try again!');
            }
        } else {
            send('Incorrect password!');
        }
    }
}else{
    send('Account not found!'); //not exist
}

function send($message){
    $response["user"] = array();
    $user = array();
    $user["msg"] = $message;
    array_push($response["user"], $user);
    echo json_encode($response);
}
?>