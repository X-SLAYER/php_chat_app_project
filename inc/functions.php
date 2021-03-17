<?php
function p_s($variable)
{
	global $conn;
	$variable = mysqli_real_escape_string($conn, trim($variable));
	return $variable;
}


function g_s($variable)
{
	$variable = strip_tags($variable);
	return $variable;
}



function checkIfEmailExist($email)
{
	global $conn;
	$data[] = array();
	$sql = "SELECT id FROM users WHERE email ='$email'";
	$run = mysqli_query($conn, $sql);
	$rows = mysqli_num_rows($run);
	if ($rows == 0) {
		return false;
	} else {
		return true;
	}
}


function checkPassword($email, $password)
{
	global $conn;
	$sql = "SELECT password FROM users WHERE email ='$email'";
	$run = mysqli_query($conn, $sql);
	while ($rows = mysqli_fetch_assoc($run)) {
		$hashedDBPass = $rows['password'];
	}
	if (password_verify($password, $hashedDBPass)) {
		return true;
	} else {
		return false;
	}
}



function start_conv($user1, $user2)
{
	global $conn;
	if (checkConv($user1, $user2)) {
		return checkConv($user1, $user2);
	} else {
		$sql = "INSERT INTO conv (user1,user2) VALUES ('$user1','$user2')";
		if (mysqli_query($conn, $sql)) {
			return mysqli_insert_id($conn);
		}
	}
}
function checkConv($user1, $user2)
{
	global $conn;

	$sql = "SELECT * FROM conv WHERE user1=$user1 AND user2= $user2";
	$run = mysqli_query($conn, $sql);
	$rowsnum = mysqli_num_rows($run);
	return $rowsnum;
	if (isset($rowsnum)) {
		while ($rows = mysqli_fetch_assoc($run)) {
			$msgs[] = array(
				'id' => g_s($rows['id'])
			);
		}
		return $msgs;
	}
}
function getAllConvs($user1, $user2)
{
	global $conn;

	$sql = "SELECT * FROM `conv` WHERE `user1`=$user1 AND `user2`=$user2 OR `user1`=$user2 AND `user2`=$user1";
	$run = mysqli_query($conn, $sql);
	while ($rows = mysqli_fetch_assoc($run)) {
		$msgs[] = array(
			'id' => g_s($rows['id'])
		);
	}
	return $msgs;
}
function getAllMessages($user1, $user2)
{
	global $conn;

	$allConvId = getAllConvs($user1, $user2);
	foreach ($allConvId as $item) {
		$sql = "SELECT * FROM `conv_reply` WHERE `conv_id` = " . $item['id'];
		$run = mysqli_query($conn, $sql);
		while ($rows = mysqli_fetch_assoc($run)) {
			$msgs[] = array(
				'content' => g_s($rows['content']),
				'user_id'=> g_s($rows['user_id'])
			);
		}
	}
	return $msgs;
}


function msgSeen($msgid)
{
	global $conn;
	$sql = "UPDATE conv_reply SET status = 'seen' WHERE id = '$msgid'";
	if (mysqli_query($conn, $sql)) {
	}
}

function unseenMsgs($user1, $user2)
{
	global $conn;
	if (checkConv($user1, $user2)) {
		$conv_id = checkConv($user1, $user2);
		$sql = "SELECT * FROM conv_reply WHERE status != 'seen' AND conv_id = '$conv_id' AND user_id='$user2'";
		$run = mysqli_query($conn, $sql);
		$no = mysqli_num_rows($run);
		if (isset($no)) {
			return $no;
		} else {
			return 0;
		}
	} else {
		return 0;
	}
}
