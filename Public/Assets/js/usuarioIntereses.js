const interesesForm = document.getElementById('usuarioIntereses');
interesesForm.addEventListener('submit',(e)=>{
    e.preventDefault();
    usuarioIntereses();
});
function usuarioIntereses(){
    const formulario = document.getElementById('usuarioIntereses');
    const formData = new FormData(formulario);
    
    fetch(URL_PATH + '/Usuario/intereses/', {method: 'POST', body: formData}
    ).then(response => response.json()
    ).then(data => {
        if(data.success){
            console.log(data.message);
            window.location.replace(URL_PATH + '/Usuario/home');
        }else{
            console.log(data.message);
        }
    });
}