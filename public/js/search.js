const searchfriend  = document.getElementById('searchfriend');
const friends       = document.getElementById('friends');
// const conversations = document.getElementById('conversations');
const form = searchfriend.parentElement;
const mainDiv = document.createElement('div');
const uidhidden = document.getElementById('uidhidden');

form.onsubmit = function(e){
  e.preventDefault();
}
searchfriend.oninput = function(){
  mainDiv.innerHTML = "";
  getAjax(searchfriend.value);
  friends.style.display = "none";
}
searchfriend.addEventListener('focus',function(){
  mainDiv.innerHTML = "";
  getAjax()
  friends.style.display = "none";
});
mainDiv.onclick = function(e) {
  e.stopPropagation();
}
window.onclick = function(e){
  if( e.target != mainDiv && e.target != searchfriend){
    mainDiv.innerHTML = "";
    friends.style.display = "block";
    searchfriend.value = "";
  }
}

function createUsrList(usr){
  let a,conversation,img,h4,last_msg,time;
  
  for(user of usr){
      a = document.createElement('a');
      a.href = `index.php?id=${user.id}`;
      conversation = document.createElement('div');
      conversation.className = "conversation";
      img = document.createElement('img');
      img.src = user.avatar;
      conversation_meta_data = document.createElement('div');
      conversation_meta_data.className = "conversation-meta-data";

      h4 = document.createElement('h4');
      h4.appendChild(document.createTextNode(user.username));
      last_msg = document.createElement('div');
      last_msg.appendChild(document.createTextNode(user.bio));

      conversation_meta_data.appendChild(h4);
      conversation_meta_data.appendChild(last_msg);

      conversation.appendChild(img);
      conversation.appendChild(conversation_meta_data);
      a.appendChild(conversation);
      mainDiv.appendChild(a);      
  }
  conversations.appendChild(mainDiv);
}

function getAjax(search=""){
  const xhr = new XMLHttpRequest;

  xhr.onload = function(){
    if(xhr.status == 200){
      createUsrList(JSON.parse(xhr.responseText).data);
    }
  }
  xhr.open('GET',`backend/getusr.php?userid=${uidhidden.value}&search=${search}`,true);
  xhr.send();
}