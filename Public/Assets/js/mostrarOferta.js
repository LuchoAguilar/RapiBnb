// debe enviar el id de oferta a la function que trae las reservas
async function obtenerYMostrarReservas(pageNumber) {

    const response = await fetch(URL_PATH + `/Page/mostrarReservasOferta/?id=${idOferta}&pageNumber=${pageNumber}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    });

    if (response.ok) {
        const data = await response.json();

        if (data.success) {
            const {info, page, pages, userLog}= data.result;
            const divReservas = document.getElementById('reservas');
            const dataPaginacion = document.getElementById('btnPaginacion');
            const paginas = pages;
            const pagina = parseInt(page, 10);

            botonesPaginacion(pagina, paginas, dataPaginacion, 'reservas');
            limpiarContenido(divReservas);

            info.forEach(element => {
                let resenaHTML = '&nbsp;';
                let respuestaHTML = '&nbsp;';
                if(element.reserva.textoReserva){
                    resenaHTML = `<div class="col-md-12">Reseña: ${element.reserva.textoReserva}</div>`;
                }
                if(element.reserva.respuesta){
                    respuestaHTML = `<div class="col-md-12 ">Respuesta: ${element.reserva.respuesta}</div>`;
                }
                divReservas.insertAdjacentHTML('beforeend', `
                    <div class="col-md-12">
                        <div class="row border rounded" style="margin: auto;">
                            <div class="col-md-4"><img src="${URL_PATH}/Assets/images/fotoPerfil/${element.usuario.fotoRostro}" style="border-radius: 50%; max-width: 70px;" alt="User-Profile-Image"></div>
                            <div class="col-md-4">${element.usuario.nombreUsuario}</div>
                            <div class="col-md-4">Califico: ${element.reserva.puntaje}/10 pts</div>
                            ${resenaHTML}
                            ${respuestaHTML}
                        </div>
                    </div>
                `);
            });

            const ofertar = document.getElementById('ofertar');
            ofertarHTML = '';
            if(userLog === true){
                ofertarHTML = `<button onclick="realizarRenta();"  class="btn btn-danger">Realizar Oferta</button>`;
            }

            ofertar.insertAdjacentHTML('beforeend', `
                ${ofertarHTML}
            `);
        }else{
            const valoraciones = document.getElementById('valoraciones');
            valoraciones.className = 'd-none';
            const oferta = document.getElementById('ofertaDeAlquiler');
            oferta.className = 'card col-md-12';
        }
    }    
}
obtenerYMostrarReservas(1);


function realizarRenta() {
    const data = new FormData();
    console.log(idOferta);
    data.append('ofertaID', idOferta);
        fetch(URL_PATH + '/Rentas/rentar/', {
            method: 'POST',
            body: data
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                Modal.confirm({
                    title: 'Oferta enviada con éxito',
                    confirm: false,
                });
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
// debe recibir las reservas paginadas con sus users
// probablemente envie la informacion a rentas para poder aplicar