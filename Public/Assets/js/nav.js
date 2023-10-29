/* *mostrar el todo con condiciones especificas, esta funcion puede mostrar 
        *ofertas aplicadas, 
        *aplicaciones a las ofertas publicadas, 
        *aplicaciones a ofertas del usuario, 
        *reservas hechas por el usuario 
        *y reservas hechas a las publicaciones del usuario. 
        */
async function informacionDeOfertas() {

    let reposense = await fetch(URL_PATH + '/Aplicar/table');
    let reposenseData = await reposense.json();
    if (reposenseData.success) {

        const divOfertas = document.getElementById('ofertasYAplicaciones');
        const divAplicacionesYReservas = document.getElementById('aplicacionesYReservas');
        const divAplicacionesUser = document.getElementById('aplicacionesUser');
        const divReservas = document.getElementById('reservas');

        divOfertas.innerHTML = '';
        divAplicacionesUser.innerHTML = '';
        divReservas.innerHTML = '';

        const dataOfertasYAplicaciones = reposenseData.result.ofertasAplicantes;
        const dataAplicacionesUsuario = reposenseData.result.aplicacionesDelUsuario;
        const dataReservasDelUsuario = reposenseData.result.reservasDelUsuario.reservasUsuario;
        const dataReservasAOfertas = reposenseData.result.reservasDeOfertasP.reservasDeOfertas;

        //controlador de columnas para que se vea bien la page

        // Configuración inicial de clases
        divOfertas.className = 'col-6';
        divAplicacionesYReservas.className = 'col-6';

        // Luego, ajusta las clases según las condiciones
        if (!dataOfertasYAplicaciones) {
            divOfertas.className = '';
        }else if(!(!dataAplicacionesUsuario) && (!dataReservasDelUsuario || !dataReservasAOfertas)){
            divAplicacionesYReservas.className = 'col-12';
        }

        if ((!dataAplicacionesUsuario) && (!dataReservasDelUsuario || !dataReservasAOfertas) ){
            divAplicacionesYReservas.className = '';
        }else if(dataOfertasYAplicaciones){
            divOfertas.className = 'col-12';
        }
        //--------------------------------------------------------------------------

        // hay que tener en cuenta que puede no venir ninguna informacion o alguna de las datas o hasta todas a la vez.
        if(dataOfertasYAplicaciones){

            dataOfertasYAplicaciones.forEach(element => {
                const galeriaFotosStr = element.ofertaPublicada.galeriaFotos;
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
                                    <button onclick="AceptarOferta(${usuarioAplicante.usuarioID},${element.ofertaPublicada.ofertaID});" class="btn btn-info">Aceptar Oferta</button>
                                    <button onclick="rechazarOferta(${usuarioAplicante.usuarioID});" class="btn btn-info">Rechazar Oferta</button>
                                </div>
                            </div>
                        `;
                }else{
                    usuarioHTML = `
                    <div class="card">
                        <div class="card-header">
                            <h5 class="text-center">Aun no tiene ofertantes</h5>
                        </div>
                    </div>
                    `;
                }

                // tambien se debe corregir como se muestran las cards de ofertas y aplicantes si son varias(4 en 4)
                let columnOfertas = '';
                const numberOfObjects = dataOfertasYAplicaciones.length;
                
                if(numberOfObjects == 1){
                    columnOfertas = 'col-12';
                 }else if(numberOfObjects == 2){
                    columnOfertas = 'col-6';
                 }else if(numberOfObjects == 3){
                    columnOfertas = 'col-4';
                 }else if(numberOfObjects >= 4){
                    columnOfertas = 'col-3';
                 }

                divOfertas.insertAdjacentHTML('beforeend', `
                        <div class="row">
                            <div class="${columnOfertas}">
                                <div class="card" id="ofertaPublicada">
                                    <div class="card-header">
                                        <h3 class="text-center">Oferta/s publicada/s</h3>
                                    </div>    
                                    <div class="card-body"> 
                                        <div class="card mb-3" style="max-width: 400px; max-height: 800px; margin:auto; border-radius: 10px;">
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
                                    <div class="card-footer">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                `);
            });
            
        }
        // carga de tabla de aplicaciones del usuario
        if(dataAplicacionesUsuario){

            dataAplicacionesUsuario.forEach(element => {
                // cancelar pedido se debe mostrar solo si no esta aceptada la aplicación
                let accionTb = '';
                let accion = '';
                if(element.aplicacion.estado === 'aceptado'){
                    accionTb = '<th scope="col">Cancelar pedido</th>';
                    accion = '<td><button onclick="cancelarPedido(${element.aplicacion.aplicacionID});" class="btn btn-danger">Cancelar</button></td>';
                }
                divAplicacionesUser.innerHTML = `
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
                                                ${accionTb}
                                            </tr>
                                        </thead>
                                        <tbody id="tablaUsuarios">
                                                <tr>
                                                    <td>${element.oferta.ofertaAlquiler}</td>
                                                    <td>${element.aplicacion.fechaAplico}</td>
                                                    <td>${element.aplicacion.estado}</td>
                                                    ${accion}
                                                </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

            });

            
        }

        if(dataReservasDelUsuario || dataReservasAOfertas){
            
            let ReservasDelUsuarioHTML = '';
            let ReservasAOfertasHTML = '';

            dataReservasDelUsuario.forEach(element => {

                divReservas.innerHTML = `
                    <div class="col-4">
                        ${ReservasDelUsuarioHTML}
                    </div>
                    <div class="col-4">
                        ${ReservasAOfertasHTML}
                    </div>
                `;
            });

        }
    }
         
}

informacionDeOfertas();
/*function mostrarInformacionOferta(infoOferta){
    //infoOferta comprueba si no hay alguna oferta del user en estado publicado
    if(infoOferta === 'pepe'){
        const navElemento = document.getElementById('icon');

        navElemento.insertAdjacentHTML('afterend',`
            <a class="nav-item nav-link" href="${URL_PATH}/Page/home/">Información de Alquiler</a>
        `);
    }
    
}

mostrarInformacionOferta('no pepe'); */
