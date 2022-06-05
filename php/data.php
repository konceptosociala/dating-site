<?php
	for($i = 0; $i < count($users); $i++){
		$row = $users[$i];
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
        ($row['status'] == "Offline") ? $offline = "offline" : $offline = "";
        ($outgoing_id == $row['unique_id']) ? $hid_me = "hide" : $hid_me = "";

		if ($row2){
			$output .= '<a style="text-decoration: none" href="chat.php?id='. $row['unique_id'] .'">
						<div class="content">
						<img src="php/images/'. $row['img'] .'" alt="">
						<div class="details">
							<span>'. $row['name']. " (" . $row['nickname'] .')</span>
							<p>'. $you . $msg .'</p>
						</div>
						</div>
						<div class="status-dot '. $offline .'"><i class="fas fa-circle"></i></div>
					</a>';
        }
    }
?>
