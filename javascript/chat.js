const form = document.querySelector(".typing-area"),
incoming_id = form.querySelector(".incoming_id").value,
inputField = form.querySelector(".input-field"),
sendBtn = form.querySelector("button"),
chatBox = document.querySelector(".chat-box");

form.onsubmit = (e)=>{
    e.preventDefault();
}

sendBtn.onclick = ()=>{
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/insert-chat.php", true);
    xhr.onload = ()=>{
      if(xhr.readyState === XMLHttpRequest.DONE){
          if(xhr.status === 200){
              inputField.value = "";
          }
      }
    }
    let formData = new FormData(form);
    xhr.send(formData);
}

var chatData = "";

var interval = setInterval(function () {
    
    $.ajax({
        type: "POST",
        url: 'php/get-chat.php',
        data: {incoming_id: incoming_id},
        success: function(data){
			if(chatData !== data){
                $(".chat-box").html(data);
                scrollToBottom();
            }
            chatData = data;
		}
	});

}, 1000);

function scrollToBottom(){
	chatBox.scrollTop = chatBox.scrollHeight;
}
