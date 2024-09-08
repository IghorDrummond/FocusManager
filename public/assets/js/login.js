/*
===============================================
Fonte: login.js
Descrição: Responsável por realizar operações na página de Login e cadastro
Data: 07/09/2024
Programador(a): Ighor Drummond
===============================================
*/
//Declaration of variables
//Elements
//Array
var Caracteres = [['ABCDEFGHIJKLMNOPQRSTUVWXYZ'], ['0123456789'] , ['@#_-%¨&;?!$()*><:Ç~´^,.=\/{}`´|[]+""£0'] ];
//Functions
export function validPassword(pass){
	let nCont, nCont2 = 0;
	let valid = false;

	if(pass.length < 6){
		return false;
	}

    for(nCont = 0; nCont <= 2; nCont++){
		for(nCont2 = 0; nCont2 <= Caracteres[nCont][0].length; nCont2++){
			if(pass.indexOf(Caracteres[nCont][0][nCont2]) != -1){
				valid = true;
				break;
			}
		}
		if(!valid){
			return false;
		}
		valid = false;
	}

	return true;
}
