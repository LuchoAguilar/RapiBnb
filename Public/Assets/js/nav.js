function mostrarInformacionOferta(infoOferta){
    //infoOferta comprueba si no hay alguna oferta del user en estado publicado
    if(infoOferta === 'pepe'){
        const navElemento = document.getElementById('icon');

        navElemento.insertAdjacentHTML('afterend',`
            <a class="nav-item nav-link" href="${URL_PATH}/Page/home/">Información de Alquiler</a>
        `);
    }
    
}

mostrarInformacionOferta('no pepe');
