async function ofertasAlquilerCard(pageNumber) {
    let reposense = await fetch(URL_PATH + '/Page/listarOfertasVerificados/', {
        method: 'POST',
        body: new URLSearchParams({ pageNumber: pageNumber }),
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    });

    if (reposense.ok) {
        let reposenseData = await reposense.json();

        if (reposenseData.success) {
            const ofertas = reposenseData.result;
            const divPrueba = document.getElementById('prueba');
            const dataPaginacion = document.getElementById('dataPaginacion');

            const limite = ofertas.limit;
            const paginas = ofertas.pages;
            const pagina = parseInt(ofertas.page, 10);
            botonesPaginacion(pagina, paginas, dataPaginacion, 'prueba');

            limpiarContenido(divPrueba); // Limpiar el contenido antes de agregar nuevas tarjetas

            ofertas.data.forEach(element => {

                const galeriaFotosStr = element.galeriaFotos;
                const galeriaFotosArray = galeriaFotosStr.split(", ");

                let carrouselHTML = '';
                galeriaFotosArray.forEach((foto, index) => {
                    const activeClass = index === 0 ? 'active' : '';
                    carrouselHTML += `
                                    <div class="carousel-item ${activeClass}">
                                        <img src="${URL_PATH}/Assets/images/galeriaFotos/${foto}" style="width: 350px; height: 250px;" alt="Imagen">
                                    </div>
                                `;
                });

                divPrueba.insertAdjacentHTML('beforeend', `
                    <div class="card col-3" style="max-width: 400px; max-height: 600px; margin: auto;">
                        <a href="${URL_PATH}/Page/oferta/?ofertaID=${element.ofertaID}"  class="d-block" style="text-decoration: none;>
                            <div class="card-header">
                                <div id="imageCarousel${element.ofertaID + 7}" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    ${carrouselHTML}
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel${element.ofertaID + 7}" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel${element.ofertaID + 7}" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Siguiente</span>
                                </button>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">${element.titulo}</h5>
                                <p class="card-text"><small class="text-muted">Ubicación: ${element.ubicacion}</small></p>
                            </div>
                        </a>   
                    </div> 
                `);
            });
        }
    }
}


// Llama a 'ofertasAlquilerCard' con la página inicial
ofertasAlquilerCard(1);


