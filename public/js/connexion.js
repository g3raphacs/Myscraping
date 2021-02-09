import { AjaxRequest } from './class/AjaxRequest.js'

const loginButton = document.querySelector('.login-button');
const form = document.querySelector('form');
const message = document.querySelector('#error-message')

loginButton.addEventListener('click', async ()=>{
    checklogin()
})

const checklogin = async ()=>{
    const Ajax =  new AjaxRequest();
    const response = await Ajax.ex(form , 'login')
    if(response.status === 'OK'){
        login(response.user)
    }else{
        errorMessage(response.status)
    }
}

const login = (user)=>{
    const popup = document.getElementById('insc-window')
    popup.innerHTML = '<p class="f-14 f-bold" style="color:green;">Identité confirmée!</p>'
    setTimeout(function(){ window.location = user }, 1000)
}
const errorMessage = (msg)=>{
    message.style.display = "block"
    message.innerHTML = msg
}