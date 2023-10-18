const updateForm = document.getElementById('usuarioEdit');
updateForm.addEventListener('submit',(e)=>{
    e.preventDefault();
    usuarioUpdate();
});
function usuarioUpdate(){
    const formulario = document.getElementById('usuarioEdit');
    const formData = new FormData(formulario);
    
    fetch(URL_PATH + '/Usuario/update/', {method: 'POST', body: formData}
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