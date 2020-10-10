const closeSettings = document.getElementById('close-settings');
const openSettings  = document.getElementById('open-settings');
const settings      = document.getElementById('settings');

closeSettings.addEventListener('click',_=> settings.classList.remove('open'));
openSettings.addEventListener('click',_=> settings.classList.add('open'));