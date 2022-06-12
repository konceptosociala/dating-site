<?php
	for($i = 0; $i < count($users); $i++){
		$row = $users[$i];
		$span = "";
		$nots = R::getAll("SELECT * FROM notifications WHERE adresant_id = '{$row['unique_id']}' AND adresat_id = '{$_SESSION['unique_id']}';");
		if(count($nots) > 0){
			$span = ' <span class="badge bg-danger">'.count($nots).'<span class="visually-hidden">unread messages</span></span>';
		}
        $row2 = R::getAll("SELECT * FROM messages WHERE (incoming_msg_id = {$row['unique_id']}
                OR outgoing_msg_id = {$row['unique_id']}) AND (outgoing_msg_id = {$outgoing_id} 
                OR incoming_msg_id = {$outgoing_id}) ORDER BY messages.id DESC LIMIT 1");
        ($row2) ? $result = $row2[0]['msg'] : $result ="No message available";
        (strlen($result) > 28) ? $msg =  substr($result, 0, 28) . '...' : $msg = $result;
        if(isset($row2['outgoing_msg_id'])){
            ($outgoing_id == $row2['outgoing_msg_id']) ? $you = "You: " : $you = "";
        }else{
            $you = "";
        }
        ($row['status'] == "Offline") ? $offline = "offline" : $offline = "Online";
        ($outgoing_id == $row['unique_id']) ? $hid_me = "hide" : $hid_me = "";

		if ($row2){
			if($row2[0]['msg_type'] == 'text') {
				$output .= '<a style="text-decoration: none" href="chat.php?id='. $row['unique_id'] .'">
						<div class="content">
						<img src="php/images/'. $row['img'] .'" alt="">
						<div class="details">
							<span>'. $row['name']. " (" . $row['nickname'] .')'.$span.'</span>
							<p>'. $you . $msg .'</p>
						</div>
						</div>
						<div class="status-dot '. $offline .'"><i class="fas fa-circle"></i></div>
					</a>';
			} else if($row2[0]['msg_type'] == 'sticker') {
				$output .= '<a style="text-decoration: none" href="chat.php?id='. $row['unique_id'] .'">
						<div class="content">
						<img src="php/images/'. $row['img'] .'" alt="">
						<div class="details">
							<span>'. $row['name']. " (" . $row['nickname'] .')</span>
							<p>'. $you .'STICKER</p>
						</div>
						</div>
						<div class="status-dot '. $offline .'"><i class="fas fa-circle"></i></div>
					</a>';
			} else {
				$output .= '<a style="text-decoration: none" href="chat.php?id='. $row['unique_id'] .'">
						<div class="content">
						<img src="php/images/'. $row['img'] .'" alt="">
						<div class="details">
							<span>'. $row['name']. " (" . $row['nickname'] .')</span>
							<p>'. $you . 'IMAGE</p>
						</div>
						</div>
						<div class="status-dot '. $offline .'"><i class="fas fa-circle"></i></div>
					</a>';
			}
        }
    }
?>
