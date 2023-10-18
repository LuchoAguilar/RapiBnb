const createOfertaForm = document.getElementById('ofertaAlquilerCreate');
createOfertaForm.addEventListener('submit',(e)=>{
    e.preventDefault();
    usuarioUpdate();
});
function usuarioUpdate(){
    const formulario = document.getElementById('ofertaAlquilerCreate');
    const formData = new FormData(formulario);
    
    fetch(URL_PATH + '/OfertaAlquiler/create/', {method: 'POST', body: formData}
    ).then(response => response.json()
    ).then(data => {
        if(data.success){
            console.log(data.message);
            window.location.replace(URL_PATH + '/OfertaAlquiler/home');
        }else{
            console.log(data.message);
        }
    });
}