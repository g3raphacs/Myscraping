import { AjaxRequest } from './class/AjaxRequest.js'

const loginButton = document.querySelector('.login-button');
const form = document.querySelector('form');
const message = document.querySelector('#error-message')

loginButton.addEventListener('click', async ()=>{
    signUp()
})

const signUp = async ()=>{
    const Ajax =  new AjaxRequest();
    const response = await Ajax.ex(form , 'signup')
    if(response.status == 'OK'){
        confirmInscription()
    }else{
        errorMessage(response.status)
    }
}

const confirmInscription = ()=>{
    const popup = document.getElementById('insc-window')
    popup.innerHTML = '<p class="f-14 f-bold" style="color:green;">Inscription confirmée! Vous pouvez maintenant vous connecter!</p>'
    setTimeout(function(){ window.location = './' }, 1000)
}
const errorMessage = (msg)=>{
    message.style.display = "block"
    message.innerHTML = msg
}