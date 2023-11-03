function limpiarContenido(contenedor) {
    contenedor.innerHTML = '';
}

function botonesPaginacion(page, pages, paginacionContainer) {
    const paginasAMostrar = 5;
    const mitad = Math.floor(paginasAMostrar / 2);

    let inicio = Math.max(1, page - mitad);
    let final = Math.min(inicio + paginasAMostrar - 1, pages); // Asegúrate de que 'final' no exceda el número total de páginas
    const anterior = (page > 1) ? page - 1 : 1;
    const siguiente = (page < final) ? page + 1 : final;
    console.log(inicio,final, page, pages);
    function createPageButton(text, pageNumber) {
        const button = document.createElement('a');
        button.href = `javascript:void(0);`;  // Evita que el enlace cargue una nueva página
        button.textContent = text;
        button.classList.add('btn', 'btn-primary');
        if (pageNumber === page) {
            button.classList.add('active');
        }
        button.addEventListener('click', (event) => {
            event.preventDefault();
            // Envía el número de página al servidor
            envioDePagina(pageNumber);
        });
        return button;
    }

    paginacionContainer.innerHTML = '';
    paginacionContainer.appendChild(createPageButton('Anterior', anterior));

    for (let i = inicio; i <= final; i++) {
        const button = createPageButton(i, i);
        paginacionContainer.appendChild(button);
    }

    paginacionContainer.appendChild(createPageButton('Siguiente', siguiente));
}



async function ofertasAlquilerCard(pageNumber) {
    let reposense = await fetch(URL_PATH + '/Page/listarOfertas/', {
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
            botonesPaginacion(pagina, paginas, dataPaginacion);

            limpiarContenido(divPrueba); // Limpiar el contenido antes de agregar nuevas tarjetas

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

                divPrueba.insertAdjacentHTML('beforeend', `
                    <div class="card col-3" style="max-width: 400px; max-height: 800px; margin: auto;">
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

async function envioDePagina(pageNumber) {
    const data = new FormData();
    data.append('pageNumber', pageNumber);
    fetch(URL_PATH + '/Page/listarOfertas/', {
        method: 'POST',
        body: data
    }).then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log(data.message);
                ofertasAlquilerCard(pageNumber); // Actualiza el contenido con la nueva página
            }
        });
}

// Llama a 'ofertasAlquilerCard' con la página inicial
ofertasAlquilerCard(1);


