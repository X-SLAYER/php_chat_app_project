<?php
$touser = p_s($_GET['id']);
$fromuser = $_SESSION['id'];

if (isset($_POST['sendmsg'])) {


	$msg = p_s($_POST['msg']);


	$me = "INSERT INTO `conv` (`id`, `user1`, `user2`, `status`) VALUES (NULL, '$fromuser', '$touser', 'mrgll');";
	$roomId = 0;
	if (mysqli_query($conn, $me)) {
		$roomId =  mysqli_insert_id($conn);
	}

	$sql = "INSERT INTO `conv_reply` (`id`, `conv_id`, `user_id`, `content`, `status`) VALUES (NULL, '$roomId', '$fromuser', '$msg', 'active');";
	if (mysqli_query($conn, $sql)) {
		header('Location: chat.php?id=' . $touser);
	}
}
?>
<div class="container">
	<div class="msgs" style="height: 60vh; overflow-y: auto">
		<?php
		// print_r(getAllMessages($fromuser, $touser));
		if (!empty(getAllMessages($fromuser, $touser))) {
			foreach (getAllMessages($fromuser, $touser) as $msg) {
				if ($msg['user_id'] == $_SESSION['id']) {
					echo '<div>
						<div  class="alert alert-info" style="width:80%;float:right">' . $msg['content'] . '</div>
					</div>';
				} else {
					echo '<div><div class="alert alert-success" style="width:80%;float:left">' . $msg['content'] . '</div></div>';
				}
			}
		}
		?>
	</div>
	<div class="sendmsg">
		<form action="" method="POST">
			<textarea class="form-control" rows="3" name="msg"></textarea>
			<button class="btn  btn-success" name="sendmsg">Send</button>
		</form>

	</div>
</div>