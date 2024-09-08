var XY = null;
var YX = null;

export function warning(msg){
	let warning = document.getElementById('warning');
    let success = document.getElementById('success');
    let nTime = 5;

    //Valida se o warning está ativo
    if(warning.style.display === 'block'){
        warning.style.display = 'none';
        clearInterval(XY);
    }

    //Valida se o pop up success está aberto
    if(success.style.display === 'block'){
        success.style.display = 'none';
        clearInterval(YX);
    }

    warning.style.display = 'block';
	warning.getElementsByTagName('span')[0].textContent = msg;

    XY = setInterval(()=>{
        nTime--;
        if(nTime <= 0){
            warning.style.display = 'none';
            clearInterval(XY);
        }
    }, 1000);
}

export function success(msg){
    let warning = document.getElementById('warning');
    let success = document.getElementById('success');
    let nTime = 5;

    //Valida se o warning está ativo
    if(warning.style.display === 'block'){
        warning.style.display = 'none';
        clearInterval(XY);
    }

    //Valida se o pop up success está aberto
    if(success.style.display === 'block'){
        success.style.display = 'none';
        clearInterval(YX);
    }

	success.style.display = 'block';
	success.getElementsByTagName('span')[0].textContent = msg;

    YX = setInterval(()=>{
        nTime--;
        if(nTime <= 0){
            success.style.display = 'none';
            clearInterval(YX);
        }
    }, 1000);
}

export function openLoading(){
    let openLoading = document.getElementById('loading');
    openLoading.style.display = 'flex';
}

export function closeLoading(){
    let closeLoading = document.getElementById('loading');
    closeLoading.style.display = 'none';
}