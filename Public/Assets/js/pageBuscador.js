async function ofertasBuscador(pageNumber, dataBusqueda) {
    let reposense = await fetch(URL_PATH + '/Page/listarBusqueda/', {
        method: 'POST',
        body: new URLSearchParams({ pageNumber: pageNumber }),
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    });

    if (reposense.ok) {
        let reposenseData = await reposense.json();
        console.log(reposenseData);

        if(reposenseData.success){
            let data = '';
            if(dataBusqueda){
                data = dataBusqueda;
            }else{
                data = reposenseData.result;
            }

                const divbusqueda = document.getElementById('cardBusqueda');
                const dataPaginacion = document.getElementById('busquedaPaginacion');

                const paginas = data.pages;
                const pagina = parseInt(data.page, 10);
                console.log(paginas);

                botonesPaginacion(pagina, paginas, dataPaginacion, 'cardBusqueda');

                limpiarContenido(divbusqueda);

                data.data.forEach(element => {

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

                    divbusqueda.insertAdjacentHTML('beforeend', `
                        <div class="card col-3" style="max-width: 400px; max-height: 600px; margin: auto;">
                            <div class="card-header">
                                <div id="imageCarousel${element.ofertaID + 33}" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    ${carrouselHTML}
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel${element.ofertaID + 33}" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel${element.ofertaID + 33}" data-bs-slide="next">
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

const busqueda = document.getElementById('busquedaEtiquetas');
busqueda .addEventListener('submit', (e) => {
    e.preventDefault();
    buscarOfertas();
});

function buscarOfertas() {
    const formulario = document.getElementById('busquedaEtiquetas');
    const formData = new FormData(formulario);
    
    fetch(URL_PATH + '/Page/listarBusqueda/', {method: 'POST', body: formData}
    ).then(response => response.json()
    ).then(data => {
        if(data.success){
            //window.location.replace(URL_PATH + '/Usuario/home');
            ofertasBuscador(1,data.result);
        }else{
            const divErr = document.getElementById('errores');
            divErr.innerHTML = `
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
                <strong>${data.message}</strong>
            </div>
            `;
        }
    });
    
}

ofertasBuscador(1);