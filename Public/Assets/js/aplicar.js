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
        const dataReservasDelUsuario = reposenseData.result.reservasDelUsuario;
        const dataReservasAOfertas = reposenseData.result.reservasDeOfertasP;
        const dataEsVerificado = reposenseData.result.esVerificado;
        

        //controlador de columnas para que se vea bien la page

        // Configuración inicial de clases
        

        // Luego, ajusta las clases según las condiciones
        if(dataOfertasYAplicaciones && (dataAplicacionesUsuario != null) && (dataReservasDelUsuario.length > 0 || dataReservasAOfertas.length > 0)){
            divOfertas.className = 'col-6';
            divAplicacionesYReservas.className = 'col-6';
        }else{
            if (!dataOfertasYAplicaciones) {
                divOfertas.className = '';
            } else if ((dataAplicacionesUsuario != null) && (dataReservasDelUsuario.length > 0 || dataReservasAOfertas.length > 0)) {
                divAplicacionesYReservas.className = 'col-12';
            }
            
            if (!((dataAplicacionesUsuario != null) && (dataReservasDelUsuario.length > 0 || dataReservasAOfertas.length > 0))) {
                divAplicacionesYReservas.className = '';
            } else if (dataOfertasYAplicaciones) {
                divOfertas.className = 'col-12';
            }
        }
       
        //--------------------------------------------------------------------------

        // hay que tener en cuenta que puede no venir ninguna informacion o alguna de las datas o hasta todas a la vez.
        if (dataOfertasYAplicaciones) {

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
                } else {
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

                if (numberOfObjects == 1) {
                    columnOfertas = 'col-12';
                } else if (numberOfObjects == 2) {
                    columnOfertas = 'col-6';
                } else if (numberOfObjects == 3) {
                    columnOfertas = 'col-4';
                } else if (numberOfObjects >= 4) {
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
        if (dataAplicacionesUsuario) {
            // Crea una variable para almacenar el contenido HTML
            let aplicacionesUserHTML = '';

            dataAplicacionesUsuario.forEach(element => {
                // cancelar pedido se debe mostrar solo si no está aceptada la aplicación
                let accionTb = '';
                let accion = '';
                if (element.aplicacion.estado === 'aceptado') {
                    accionTb = '<th scope="col">Cancelar pedido</th>';
                    accion = `<td><button onclick="cancelarPedido(${element.aplicacion.aplicacionID});" class="btn btn-danger">Cancelar</button></td>`;
                }

                // Agrega el contenido de cada aplicación a la variable aplicacionesUserHTML
                aplicacionesUserHTML += `
                            <div>
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
                                                        <td>${element.oferta.titulo}</td>
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

            // Inserta el contenido en el contenedor divAplicacionesUser
            divAplicacionesUser.innerHTML = aplicacionesUserHTML;
        }


        if (dataReservasDelUsuario || dataReservasAOfertas) {

            let ReservasDelUsuarioHTML = '';
            let ReservasAOfertasHTML = '';

            if (dataReservasDelUsuario.length > 0) {
                // Crear una variable para almacenar el contenido de la tabla
                let reservasUsuarioHTML = '';
                let th = '<th scope="col">Evaluar reserva</th>';
                dataReservasDelUsuario.forEach(element => {
                    // Agregar una nueva fila a la variable de contenido
                    
                    let evaluar = `
                        <td>
                            <form action="" method="post" id="envioData_${element.reservaUser.reservaID}">
                                <div class="row">
                                    <input type="hidden" name="reservaID" value="${element.reservaUser.reservaID}">
                                    <input type="hidden" name="esVerificado" value="${dataEsVerificado}">
                                    <div class="col-md-4">
                                        <input type="text" class="form-control col-md-3" name="nuevaPuntuacion" value="${element.reservaUser.puntaje}" placeholder="puntuación:">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control col-md-3" name="nuevaResena" value="${element.reservaUser.textoReserva}" placeholder="Reseña:">
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" onclick="actualizar();" class="btn btn-danger">Enviar</button>
                                    </div>
                                </div>
                            </form>
                        </td>
                    `;
                    if(element.reservaUser.textoReserva && element.reservaUser.puntaje){
                        evaluar = `
                        <td>${element.reservaUser.puntaje}</td>
                        <td>${element.reservaUser.textoReserva}</td>
                        <td>${element.reservaUser.respuesta}</td>
                        `;
                        th = `
                            <th scope="col">Puntaje</th>
                            <th scope="col">Reseña</th>
                            <th scope="col">Respuesta</th>
                        `;
                    }
                    reservasUsuarioHTML += `
                      <tr>
                        <td>${element.oferta.titulo}</td>
                        ${evaluar}
                      </tr>
                    `;
                    
                });
        
                // Insertar el contenido en la tabla completa
                ReservasDelUsuarioHTML = `
                  <div>
                    <div class="card">
                      <div class="card-header">
                        <h3 class="text-center">Reservas hechas</h3>
                      </div>
                      <div class="card-body text-center">
                        <div class="table-responsive">
                          <table class="table table-striped">
                            <thead>
                              <tr>
                                <th scope="col">Publicación</th>
                                ${th}
                              </tr>
                            </thead>
                            <tbody id="tablaUsuarios">
                              ${reservasUsuarioHTML}
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                `;
            }


            if (dataReservasAOfertas.length > 0) {
                dataReservasAOfertas.forEach(element => {
                    const publicacionTitulo = element.ofertaUser.titulo;
                    const reservas = element.reservas;

                    // Crea un contenedor para cada tabla de reservas
                    const reservasContainer = document.createElement('div');
                    reservasContainer.classList.add('variado');

                    const reservasTable = document.createElement('div');
                    reservasTable.classList.add('card');

                    const cardHeader = document.createElement('div');
                    cardHeader.classList.add('card-header');
                    cardHeader.innerHTML = `<h3 class="text-center">Reservas a publicación: ${publicacionTitulo}</h3>`;

                    const cardBody = document.createElement('div');
                    cardBody.classList.add('card-body');

                    const tableContainer = document.createElement('div');
                    tableContainer.classList.add('table-responsive');

                    const tabla = document.createElement('table');
                    tabla.classList.add('table', 'table-striped');

                    let btnContestar = '';
                    let contestarHTML = '';

                    if (element.reservas.textoReserva != '') {
                        contestarHTML = '<th scope="col">Contestar resena</th>';
                        btnContestar = `<button onclick="contestarResena(${element.ofertaUser.creadorID},${element.ofertaUser.reservaID});" class="btn btn-danger">icono</button>`;
                    }

                    const thead = document.createElement('thead');
                    thead.innerHTML = `
                                <tr>
                                    <th scope="col">Puntuación</th>
                                    <th scope="col">Reseña</th>
                                    <th scope="col">Respuesta</th>
                                    <th scope="col">Quien reservo</th>
                                    ${contestarHTML}
                                </tr>
                            `;

                    const tbody = document.createElement('tbody');
                    reservas.forEach(reserva => {
                        tbody.innerHTML += `
                                    <tr>
                                        <td>${reserva.puntaje}</td>
                                        <td>${reserva.textoReserva}</td>
                                        <td>${reserva.respuesta}</td>
                                        <td>${reserva.autorID}</td>
                                        <td>${btnContestar}</td>
                                    </tr>
                                `;
                    });

                    // Agrega todos los elementos al DOM
                    tabla.appendChild(thead);
                    tabla.appendChild(tbody);
                    tableContainer.appendChild(tabla);
                    cardBody.appendChild(tableContainer);
                    reservasTable.appendChild(cardHeader);
                    reservasTable.appendChild(cardBody);
                    reservasContainer.appendChild(reservasTable);

                    // Añade el contenedor de reservas al elemento adecuado en tu HTML

                    ReservasAOfertasHTML += reservasContainer.outerHTML;
                });
            }


            divReservas.innerHTML = `
                            <div>
                                ${ReservasDelUsuarioHTML}
                            </div>
                            <div>
                                ${ReservasAOfertasHTML}
                            </div>
                    `;

        }
    }

}

informacionDeOfertas();

function actualizar() {
    const formulario = document.getElementById('envioData');
    formulario.addEventListener('submit', (e) => {
        e.preventDefault();
        enviarInformacion(formulario);
    });
}

function enviarInformacion(formulario) {
    const formData = new FormData(formulario);

    fetch(URL_PATH + '/Usuario/create/', { method: 'POST', body: formData })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.replace(URL_PATH + '/Usuario/home');
            } else {
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

function contestarResena(duenoPublicacionID, reservaID) {

}

function resenarOferta(reservaID, esVerificado) {

}

function puntuarOferta(reservaID) {

}