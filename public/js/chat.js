const mainConversation = document.getElementById('main-conversation');

if(mainConversation != null){
  window.onload = function(){
    getMsg();
    // mainConversation.parentElement.scrollTo({
    //   top: mainConversation.parentElement.scrollHeight ,
    //   left: 0,
    //   behavior: 'smooth'
    // });
};
setInterval(function(){
  getMsg();
  // mainConversation.parentElement.scrollTo({
  //   top: mainConversation.parentElement.scrollHeight,
  //   left: 0,
  //   behavior: 'smooth'
  // });
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
    let heart;
    let img;
    for(let mg of msg){
  
        div = document.createElement('div');
        p = document.createElement('p');
        span = document.createElement('span');
        img = document.createElement('img');
        div.className = mg.class;
        if(mg.msg.length > 0){
          p.appendChild(document.createTextNode(mg.msg));
          p.setAttribute('data-id',mg.id);

          if(mg.love == 1){
            heart = document.createElement('span');
            heart.innerHTML = "&hearts;";
            heart.className = "heart";
            div.appendChild(heart);
          }
          p.addEventListener('dblclick',function(){
            const xhr = new XMLHttpRequest;
            let id = this.getAttribute('data-id');
            xhr.onload = function(){
              if(xhr.status == 200){
                console.log("good");
              }
            }
            xhr.open("POST","backend/postlove.php",true);
            xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            let heart = this.parentElement.querySelector('.heart');
            if(heart != null){
              heart.remove();
              xhr.send(`m_id=${id}&love=0`);
            }else{
              heart = document.createElement('span');
              heart.innerHTML = "&hearts;";
              heart.className = "heart";
              this.parentElement.appendChild(heart);
              xhr.send(`m_id=${id}&love=1`);
            }
          });
          div.appendChild(p);
        }
        if(mg.media != "NULL"){
          img.src = mg.media;
          div.appendChild(img);
          img.style.width = "150px";
          img.style.height = "150px";
        }
        span.appendChild(document.createTextNode(mg.date));
        div.appendChild(span);
        mainConversation.appendChild(div);
    }
  }
}