<?php 

    session_start();
    if(isset($_SESSION['unique_id'])){
        include_once "config.php";
        $outgoing_id = $_SESSION['unique_id'];
        $incoming_id = $_POST['incoming_id'];
        $output = "";
        $sql = R::getAll("SELECT * FROM messages LEFT JOIN users ON users.unique_id = messages.outgoing_msg_id
                WHERE (outgoing_msg_id = {$outgoing_id} AND incoming_msg_id = {$incoming_id})
                OR (outgoing_msg_id = {$incoming_id} AND incoming_msg_id = {$outgoing_id}) ORDER BY messages.id");
        if(!empty($sql)){
			for($ir = 0; $ir < count($sql); $ir++){
				$row = $sql[$ir];
                if($row['outgoing_msg_id'] === $outgoing_id){
                    if($row['msg_type'] == 'text') {
						$output .= '<div class="chat outgoing">
                                <div class="details">
                                    <p>'. $row['msg'] .'</p>
                                </div>
                                </div>';
					} else if($row['msg_type'] == 'sticker') {
						$output .= 'sticker_OWN';
					} else {
						$output .= 'image_OWN';
					}
                } else {
					if($row['msg_type'] == 'text') {
						$output .= '<div class="d-flex align-items-end m-2">
									<img class="chat-img" src="php/images/'.$row['img'].'" alt="">
									<div class="chat incoming">
									
									<div class="details">
										<p>'. $row['msg'] .'</p>
									</div>
									</div>
									</div>';
					} else if($row['msg_type'] == 'sticker') {
						$output .= 'sticker_ANT';
					} else {
						$output .= 'image_ANT';
					}
                }
            }
        } else {
            $output .= '<div class="text">No messages found. Start chatting right now!</div>';
        }
        echo $output;
    }else{
        header("location: ../login.php");
    }

?>
