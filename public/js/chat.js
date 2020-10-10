const mainConversation = document.getElementById('main-conversation');

window.onload = function(){
  getMsg();
};
setInterval(function(){
  getMsg();
},1000);

function getMsg(){
  const xhr = new XMLHttpRequest;
  xhr.onload = function(){
    if(xhr.status === 200){
      let messages = JSON.parse(xhr.responseText);
      mainConversation.innerHTML = "";
      show_messages(messages);
    }
  }
  xhr.open('GET',`backend/get.php?from_id=${mainConversation.dataset.from}&to_id=${mainConversation.dataset.to}`,true);
  xhr.send();
}
function show_messages(msg){

  let div;
  let p;
  let span;

  for(let mg of msg){

      div = document.createElement('div');
      p = document.createElement('p');
      span = document.createElement('span');

      div.className = mg.class;
      p.appendChild(document.createTextNode(mg.msg));
      span.appendChild(document.createTextNode(mg.date));

      div.appendChild(p);
      div.appendChild(span);

      mainConversation.appendChild(div);
  }
}