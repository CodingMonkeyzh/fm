<?php
// include the web sockets server script (the server is started at the far bottom of this file)
require 'class.PHPWebSocket.php';
// include the mysql database module script
require_once "mysql.class.php";
// prevent the server from timing out
set_time_limit(0);


// when a client sends data to the server
function wsOnMessage($clientID, $message, $messageLength, $binary) {
	global $Server, $users, $conn;

	$msg = json_decode($message);
	//user join chatroom , init user's information
	if($msg->type == "join"){
		init_user($clientID, $msg);
	}else{
		check($clientID);
		$msg->photo = photoExit($msg->content->user);
	}

	//The speaker has been baned, you need let him or her know
	if($users[$clientID]->ban == 1 && $msg->type != "join"){
		$return["message"] = "你已经被禁言了,请联系管理员!";
		$Server->wsSend($clientID,json_encode($return));
	}
	//The speaker is the only person in the room. Don't let them feel lonely.
	elseif ( sizeof($Server->wsClients) == 1) {
		$return["message"] = "嗨! {$users[$clientID]->userName}, 聊天室就你一个人, 你不能跟任何儿聊天, 快叫你的小伙伴加入进来吧!";
		$Server->wsSend($clientID, json_encode($return));
	} else {
		//Get user's photo information;
		$message = json_encode($msg);
		//Send the message to everyone but the person who said it
		foreach ( $Server->wsClients as $id => $client ){
			$Server->wsSend($id, $message);
		}
		if($msg->type == "msg"){
			$time = date("Y-m-d H:i:s",$msg->content->time / 1000);
			$user = $msg->content->user;
			$content = $msg->content->message;

			$sql = "insert into chatrecords(speaker,time,content) values({$user},'{$time}','{$content}')";
			if($conn->exec($sql)){
				file_put_contents('log.txt', date('Y-m-d H:i:s: ')."{$sql} \r\n", FILE_APPEND);
			}
		}
	}
}

// when a client connects
function wsOnOpen($clientID)
{
	global $Server;
	$ip = long2ip( $Server->wsClients[$clientID][6] );

	$Server->log( "$ip ($clientID) has connected." );
}

// when a client closes or lost connection
function wsOnClose($clientID, $status) {
	global $Server, $users, $conn;
	$ip = long2ip( $Server->wsClients[$clientID][6] );

	$Server->log( "$ip ($clientID) has disconnected." );

	file_put_contents('log.txt', date('Y-m-d H:i:s: ')."{$users[$clientID]->userNum}($ip) has disconnected. \r\n", FILE_APPEND);
	//Send a user left notice to everyone in the room
	foreach ( $Server->wsClients as $id => $client ){
		$msg["type"] = "exit";
		$msg["user"] = $users[$clientID]->userNum;
		$msg["userName"] = $users[$clientID]->userName;
		$Server->wsSend($id, json_encode($msg));
	}
	$sql = "update user set status=0 where userNumber={$users[$clientID]->userNum}";
	$conn->exec($sql);
}

function photoExit($name){
	global $conn;
	$sql = "select userPhoto from user where userNumber='{$name}'";
	$arr = $conn->query($sql);
	if($arr){
		return $arr[0]['userPhoto'];

	//	echo "exit\r\n";
	}else{
		return "default";
	//	echo "not exit\r\n";
	}
}
function init_user($clientID, $msg){
	global $Server, $users, $conn;
	$ip = long2ip( $Server->wsClients[$clientID][6] );	//client ip
	$users[$clientID] = new user();
	$users[$clientID]->userNum = $msg->user;
	$users[$clientID]->userName = $msg->userName;
	$sql = "update user set status=1 where userNumber='{$msg->user}'";
	$conn->exec($sql);	//update mysql.
	$sql = "select ban from user where userNumber='{$msg->user}'";
	$arr = $conn->query($sql);
	$users[$clientID]->ban = $arr[0]["ban"];//get ban info
}
function check($clientID){
	global $users, $conn;
	$sql = "select ban from user where userNumber='{$users[$clientID]->userNum}'";
	$arr = $conn->query($sql);
	$users[$clientID]->ban = $arr[0]["ban"];//get ban info
}
class user{
	var $userNum;
	var $userName;
	var $ban;
}

//connect database
$conn = new mysql();
$conn->connect("hnust");
// users array
$users = array();
// start the server
$Server = new PHPWebSocket();
$Server->bind('message', 'wsOnMessage');
$Server->bind('open', 'wsOnOpen');
$Server->bind('close', 'wsOnClose');
// for other computers to connect, you will probably need to change this to your LAN IP or external IP,
// alternatively use: gethostbyaddr(gethostbyname($_SERVER['SERVER_NAME']))


// 设置服务器IP
$Server->wsStartServer('127.0.0.1', 8888);
// gethostbyaddr(gethostbyname($_SERVER['192.168.137.173']));


?>