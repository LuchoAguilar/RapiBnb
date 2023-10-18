async function ofertasAlquilerCard() {
    let reposense = await fetch(URL_PATH + '/OfertaAlquiler/table');
    let reposenseData = await reposense.json();

    if (reposenseData.success) {
        const divCard = document.getElementById('divCard');
        divCard.innerHTML = '';
        const data = reposenseData.result.ofertas;
        data.forEach(element => {
            const galeriaFotosStr = element.galeriaFotos;
            const galeriaFotosArray = galeriaFotosStr.split(",");
            let galeriaHTML = ''; // Variable para almacenar el HTML del carrusel

            galeriaFotosArray.forEach(foto => {
                galeriaHTML += `
                    <div class="carousel-item">
                        <img src="${URL_PATH}/Assets/images/galeriaFotos/${foto}" class="d-block w-100" alt="Imagen">
                    </div>
                `;
            });

            divCard.innerHTML += `
                <div class="row g-0">
                    <div class="col-md-4">
                        <div id="imageCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                ${galeriaHTML}
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Anterior</span>
                            </button>
                            <button class "carousel-control-next" type="button" data-bs-target="#imageCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Siguiente</span>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">${element.titulo}</h5>
                            <p class="card-text">${element.descripcion}</p>
                            <p class="card-text"><small class="text-muted">Ubicación: ${element.ubicacion}</small></p>
                            <p class="card-text"><small class="text-muted">Costo por Día: ${element.costoAlquilerPorDia} $</small></p>
                            <p class="card-text"><small class="text-muted">Tiempo Mínimo de Permanencia: ${element.tiempoMinPermanencia} días</small></p>
                            <p class="card-text"><small class="text-muted">Tiempo Máximo de Permanencia: ${element.tiempoMaxPermanencia} días</small></p>
                            <p class="card-text"><small class="text-muted">Cupo: ${element.cupo} personas</small></p>
                            <p class="card-text"><small class="text-muted">Fecha de Inicio: ${element.fechaInicio ?? 'No especificada'}</small></p>
                            <p class="card-text"><small class="text-muted">Fecha de Fin: ${element.fechaFin ?? 'No especificada'}</small></p>
                        </div>
                    </div>
                </div>
            `;
        });
    }
}

ofertasAlquilerCard();
