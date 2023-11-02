async function ofertasAlquilerCard() {
    let reposense = await fetch(URL_PATH + '/Page/listarOfertas');
    let reposenseData = await reposense.json();

    if(reposenseData.success){
        const ofertas = reposenseData.result;

        const divPrueba = document.getElementById('prueba');
        const dataPaginacion = document.getElementById('dataPaginacion');
        
        if(ofertas){
            
            const pagina = ofertas.page;
            const limite = ofertas.limit;
            const paginas = ofertas.pages;

            botonesPaginacion(pagina, limite, paginas, dataPaginacion);

            
            ofertas.data.forEach(element => {

                const galeriaFotosStr = element.galeriaFotos;
                const galeriaFotosArray = galeriaFotosStr.split(", ");

                let carrouselHTML = '';
                galeriaFotosArray.forEach((foto, index) => {
                    const activeClass = index === 0 ? 'active' : '';
                    carrouselHTML += `
                                    <div class="carousel-item ${activeClass}">
                                        <img src="${URL_PATH}/Assets/images/galeriaFotos/${foto}" style="width: 400px; height: 300px;" alt="Imagen">
                                    </div>
                                `;
                });

                /*const numberOfObjects = ofertas.length;

                if (numberOfObjects === 1) {
                    columnOfertas = 'col-md-12';
                } else if (numberOfObjects === 2) {
                    columnOfertas = 'col-md-6';
                } else if (numberOfObjects === 3) {
                    columnOfertas = 'col-md-4';
                } else if (numberOfObjects >= 4) {
                    columnOfertas = 'col-md-3';
                }*/

                divPrueba.insertAdjacentHTML('beforeend', `
                    <div class="card col-6" style="max-width: 400px; max-height: 800px; margin: auto;">
                        <div class="card-header">
                            <div id="imageCarousel${element.ofertaID}" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                ${carrouselHTML}
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel${element.ofertaID}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Anterior</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel${element.ofertaID}" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Siguiente</span>
                            </button>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">${element.titulo}</h5>
                            <p class="card-text"><small class="text-muted">Ubicación: ${element.ubicacion}</small></p>
                        </div>
                        <div class="card-footer">
                            
                        </div>
                    </div> 
                `);
            });
            
        }
        
    }
}

ofertasAlquilerCard();