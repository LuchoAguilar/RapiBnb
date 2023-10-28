
async function ofertaAplicante() {
    let reposense = await fetch(URL_PATH + '/Aplicar/table');
    let reposenseData = await reposense.json();
    if (reposenseData.success) {
        const div = document.getElementById('informacion');
        div.innerHTML = '';

        const data = reposenseData.result.ofertasAplicantes;
        if (data) {
            data.forEach(element => {
                const galeriaFotosStr = element.ofertaPublicada.galeriaFotos;
                const galeriaFotosArray = galeriaFotosStr.split(", ");

                let carrouselHTML = '';
                galeriaFotosArray.forEach((foto, index) => {
                    const activeClass = index === 0 ? 'active' : '';
                    carrouselHTML += `
                            <div class="carousel-item ${activeClass}">
                                <img src="${URL_PATH}/Assets/images/galeriaFotos/${foto}" style="width: 600px; height: 400px;" alt="Imagen">
                            </div>
                        `;
                });

                const usuarioAplicante = element.usuarioAplicante;
                let usuarioHTML = '';

                if (usuarioAplicante && usuarioAplicante.length > 0) {
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
                }else{
                    usuarioHTML = `
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-center">Aun no tiene ofertantes</h3>
                        </div>
                    </div>
                    `;
                }

                // Manejo de aplicaciones del usuario
                const aplicacionesDelUsuario = reposenseData.result.aplicacionesDelUsuario.ofertasAplicadasUsuario;

                let aplicacionesUsuarioHTML = '';
                let colHTML = 'col-8';

                if (aplicacionesDelUsuario && aplicacionesDelUsuario.length > 0) {
                    aplicacionesUsuarioHTML = `
                        <div class="col-4">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="text-center">Solicitudes hechas</h3>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Oferta Alquiler</th>
                                                    <th scope="col">Fecha del pedido</th>
                                                    <th scope="col">Estado</th>
                                                    <th scope="col">Cancelar pedido</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tablaUsuarios">
                                                ${aplicacionesDelUsuario.map(aplicacion => `
                                                    <tr>
                                                        <td>${aplicacion.oferta.ofertaAlquiler}</td>
                                                        <td>${aplicacion.aplicacion.fechaAplico}</td>
                                                        <td>${aplicacion.aplicacion.estado}</td>
                                                        <td><button onclick="cancelarPedido(${aplicacion.aplicacion.aplicacionID});" class="btn btn-danger">Quitar</button></td>
                                                    </tr>
                                                `).join('')}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        `;
                }else{
                    colHTML = 'col-12';
                }
                


                div.insertAdjacentHTML('beforeend', `
                    <div class="${colHTML}">
                        <div class="card" id="ofertaPublicada">
                            <div class="card-header">
                                <h3 class="text-center">Oferta/s publicada/s</h3>
                            </div>    
                            <div class="card-body"> 
                                <div class="card mb-3" style="max-width: 600px; margin:auto">
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
                        
                                    </div>
                                </div>    
                            </div>
                            <div class="card-footer">
                                ${usuarioHTML}
                            </div>
                        </div>
                    </div>
                    ${aplicacionesUsuarioHTML}
                    `);
            });
        }

    }
}

ofertaAplicante();


function AceptarOferta(id) {

}

function rechazarOferta(id) {

}