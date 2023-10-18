const SignUp = document.getElementById('usuarioSignUp');
SignUp.addEventListener('submit', (e) => {
    e.preventDefault();
    usuarioSubmit();
});

function usuarioSubmit() {
    const formulario = document.getElementById('usuarioSignUp');
    const formData = new FormData(formulario);
    
    fetch(URL_PATH + '/Usuario/create/', {method: 'POST', body: formData}
    ).then(response => response.json()
    ).then(data => {
        if(data.success){
            window.location.replace(URL_PATH + '/Usuario/home');
        }
    });
    
}
