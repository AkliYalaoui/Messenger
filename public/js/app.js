const closeSettings  = document.getElementById('close-settings');
const openSettings   = document.getElementById('open-settings');
const settings       = document.getElementById('settings');
const infoDiscussion = document.getElementById('infoDiscussion');
const popup          = document.getElementById('popup');
const darkMode       = document.getElementById('darkMode');
const friend         = document.querySelector('#friends > a');
const logout         = document.getElementById('logout');
const colors         = Array.from(document.querySelectorAll("#discussion-icons span"));

colors.forEach(clr => {
  clr.addEventListener('click',function(){
    localStorage.setItem('--icon-color',this.dataset.color);
    document.documentElement.style.setProperty('--icon-color',this.dataset.color);
  });
});

if(logout != null){
  logout.onclick = function(){
    sessionStorage.clear();
  }
}
if(friend != null && !sessionStorage.getItem('now')){
  friend.click();
  sessionStorage.setItem('now',true);
}
window.addEventListener('load',function(){
  if(localStorage.getItem('enabled')){
    document.documentElement.style.setProperty('--msg-color',localStorage.getItem('--msg-color'));
    document.documentElement.style.setProperty('--bg-color',localStorage.getItem('--bg-color'));
    document.documentElement.style.setProperty('--txt-color',localStorage.getItem('--txt-color'));
    document.documentElement.style.setProperty('--in-color',localStorage.getItem('--in-color'));
    document.documentElement.style.setProperty('--hover-color',localStorage.getItem('--hover-color'));
    document.documentElement.style.setProperty('--border-color',localStorage.getItem('--border-color'));
  }
  if(localStorage.getItem('--icon-color')){
    document.documentElement.style.setProperty('--icon-color',localStorage.getItem('--icon-color'));
  }
  if(localStorage.getItem('mode') == "dark"){
    darkMode.checked = true;
  }else{
    darkMode.checked = false;
  }
});
darkMode.addEventListener('change',function(){
  if(this.checked){
    document.documentElement.style.setProperty('--msg-color',"#18191a");
    document.documentElement.style.setProperty('--bg-color',"#242526");
    document.documentElement.style.setProperty('--txt-color',"#fff");
    document.documentElement.style.setProperty('--in-color',"#373737");
    document.documentElement.style.setProperty('--hover-color',"#18191a");
    document.documentElement.style.setProperty('--border-color',"#373839");
    localStorage.setItem('mode','dark');
    localStorage.setItem('--msg-color','#18191a');
    localStorage.setItem('--bg-color','#242526');
    localStorage.setItem('--txt-color','#fff');
    localStorage.setItem('--in-color','#373737');
    localStorage.setItem('--hover-color','#18191a');
    localStorage.setItem('--border-color','#373839');
  }else{
    document.documentElement.style.setProperty('--msg-color',"#fff");
    document.documentElement.style.setProperty('--bg-color',"#fff");
    document.documentElement.style.setProperty('--txt-color',"#000");
    document.documentElement.style.setProperty('--in-color',"#f1f0f0");
    document.documentElement.style.setProperty('--hover-color',"#f2f2f2");
    document.documentElement.style.setProperty('--border-color',"#ddd");
    localStorage.setItem('mode','light');
    localStorage.setItem('--msg-color','#fff');
    localStorage.setItem('--bg-color','#fff');
    localStorage.setItem('--txt-color','#000');
    localStorage.setItem('--in-color','#F1F0F0');
    localStorage.setItem('--hover-color','#F2F2F2');
    localStorage.setItem('--border-color','#ddd');
  }
});

if(infoDiscussion != null)
  infoDiscussion.addEventListener('click',_=> popup.classList.toggle('open'));
if(closeSettings != null)  
  closeSettings.addEventListener('click',_=> settings.classList.remove('open'));
if(openSettings != null)
  openSettings.addEventListener('click',_=> settings.classList.add('open'));