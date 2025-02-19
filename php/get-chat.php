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
			$nots = R::find('notifications', 'adresat_id = ? AND adresant_id = ?', [$outgoing_id, $incoming_id]);
			R::trashAll($nots);
			for($ir = 0; $ir < count($sql); $ir++){
				$row = $sql[$ir];
                if($row['outgoing_msg_id'] === $outgoing_id){
                    if($row['msg_type'] == 'text') {
						$output .= '<div class="chat outgoing">
                                <div class="details">
                                    <p>'. nl2br($row['msg']) .'</p>
                                </div>
                                </div>';
					} else if($row['msg_type'] == 'sticker') {
						$output .= '<img style="display: block; width: 40%; margin: 15px; margin-left:auto; border-radius: 0" src="'.$row['msg'].'">';
					} else if($row['msg_type'] == 'image') {
						$output .= '<img style="display: block; width: 60%; margin: 15px; margin-left:auto; border-radius: 15px" src="'.$row['msg'].'">';
					} else if($row['msg_type'] == 'video') {
						$output .= '<video style="width: 60%; margin: 15px; margin-left: calc(40% - 15px); border-radius: 15px" controls><source src="'.$row['msg'].'"></video>';
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
						$output .= '<img style="display: block; width: 40%; margin: 15px; border-radius: 0" src="'.$row['msg'].'">';
					} else if($row['msg_type'] == 'image') {
						$output .= '<img style="display: block; width: 60%; margin: 15px;  border-radius: 15px" src="'.$row['msg'].'">';
					} else if($row['msg_type'] == 'video') {
						$output .= '<video style="width: 60%; margin: 15px; border-radius: 15px" controls><source src="'.$row['msg'].'"></video>';
					}
                }
            }
        } else {
            $output .= '<div class="text text-center m-2">No messages found. Start chatting right now!</div>';
        }
        echo $output;
    }else{
        header("location: ../login.php");
    }

?>
