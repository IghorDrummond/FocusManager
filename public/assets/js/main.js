/*
===============================================
Fonte: login.js
Descrição: Responsável por realizar operações na página de Login e cadastro
Data: 07/09/2024
Programador(a): Ighor Drummond
===============================================
*/
// Importando o módulo de express
import {validPassword} from './login.js';
import {warning, success, closeLoading, openLoading} from './script.js';

const Login = (event)=>{
    openLoading();
    event.preventDefault(); //Impede carregar a página

    let inputs = document.getElementsByTagName('input');

    //Limpa inputs em vermelhos caso houver
    inputs[0].style.borderBottom = '5px solid white';
    inputs[1].style.borderBottom = '5px solid white';

    //Valida password
    setTimeout(()=>{
        closeLoading();
        if(!validPassword(inputs[1].value)){
            inputs[0].style.borderBottom = '5px solid red';
            inputs[1].style.borderBottom = '5px solid red';
            warning('Password Not Found!');
            return null;
        }
        success('Login successed!');
    },1500);
}

//=============Events
document.getElementsByTagName('form')[0].addEventListener('submit', ()=>{
    Login(event);
});

//Functions