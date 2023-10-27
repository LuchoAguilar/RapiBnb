async function ofertaAplicante() {
    let reposense = await fetch(URL_PATH + '/Aplicar/table');
    let reposenseData = await reposense.json();

    const div = document.getElementById('informacion');
    div.innerHTML = '';

    const data = reposenseData.ofertasAplicantes;



    data.forEach(element => {
        const galeriaFotosStr = element.ofertaPublicada.galeriaFotos;
        const galeriaFotosArray = galeriaFotosStr.split(", ");

        let carrouselHTML = ''; // Variable para almacenar el HTML del carrusel
        galeriaFotosArray.forEach((foto, index) => {
            const activeClass = index === 0 ? 'active' : ''; // Establecer la primera imagen como activa
            carrouselHTML += `
                <div class="carousel-item ${activeClass}">
                    <img  src="${URL_PATH}/Assets/images/galeriaFotos/${foto}" style="width: 600px; height: 400px;" alt="Imagen">
                </div>
            `;
        });

        const usuarioAplicante = element.usuarioAplicante;
        let usuarioHTML = '';

        if (usuarioAplicante) {
            usuarioHTML = `
                <div class="card">
                    <div class="card-body">
                        <div class="">
                            <img src="${URL_PATH}/Assets/images/fotoPerfil/${usuarioAplicante.fotoRostro}" style="border-radius: 50%; max-width: 100px;" alt="User-Profile-Image">
                        </div>
                        <div>
                            <p style="font-weight: 600; margin-bottom: 10px;">Nombre Completo:</p>
                            <p style="font-weight: 400;">${usuarioAplicante.nombreCompleto}</p>
                        </div>
                        <div>
                            <p style="font-weight: 600; margin-bottom: 10px;">Telefono:</p>
                            <p style="font-weight: 400;">${usuarioAplicante.telefono}</p>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button onclick="AceptarOferta(${usuarioAplicante.usuarioID});" class="btn btn-info">Aceptar Oferta</button>
                        <button onclick="rechazarOferta(${usuarioAplicante.usuarioID});" class="btn btn-info">Rechazar Oferta</button>
                    </div>
                </div>
            `;
        }

        div.insertAdjacentHTML('beforeend', `
        <div class="col-8">
            <div class="card" id="ofertaPublicada">
                <div class="card-header">
                    <h3 class="text-center">Oferta publicada</h3>
                </div>
                <div class="card mb-3" style="max-width: 400px; margin:auto;">
                    
                    <div id="imageCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            ${carrouselHTML}
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Siguiente</span>
                        </button>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">${element.ofertaPublicada.titulo}</h5>
                        <p class="card-text">${element.ofertaPublicada.descripcion}</p>
                        <p class="card-text"><small class="text-muted">Estado de publicación: ${element.ofertaPublicada.estado}</small></p>
                    </div>
                    <div class="card-footer">
                        ${usuarioHTML}
                    </div>
            </div>
            </div>
        </div>
        `);
    });
}
ofertaAplicante();

function AceptarOferta(id){

}

function rechazarOferta(id){
    
}