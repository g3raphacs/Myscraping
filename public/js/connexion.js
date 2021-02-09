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
    console.log(response.status)
    if(response.status == 'OK'){
        login(response.user)
    }else{
        errorMessage(response.status)
    }
}

const login = (user)=>{
    message.style.display = "none"
    window.location = user
}
const errorMessage = (msg)=>{
    message.style.display = "block"
    message.innerHTML = msg
}