// debe enviar el id de oferta a la function que trae las reservas
async function obtenerYMostrarReservas(pageNumber) {

    const response = await fetch(URL_PATH + `/Page/mostrarReservasOferta/?id=${idOferta}&pageNumber=${pageNumber}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    });

    if (response.ok) {
        const data = await response.json();

        if (data.success) {
            const { result, pages, page } = data;
            const divReservas = document.getElementById('reservas');
            const dataPaginacion = document.getElementById('btnPaginacion');
            const paginas = pages;
            const pagina = parseInt(page, 10);

            botonesPaginacion(pagina, paginas, dataPaginacion, 'reservas');
            limpiarContenido(divReservas);

            result.forEach(element => {
                divReservas.insertAdjacentHTML('beforeend', `
                    <div class="col-md-12">
                        <div class="row" style="margin: auto;">
                            <div class="col-md-4"><img src="${URL_PATH}/Assets/images/fotoPerfil/${element.usuario.fotoRostro}" style="border-radius: 50%; max-width: 100px;" alt="User-Profile-Image"></div>
                            <div class="col-md-4">${element.usuario.nombreUsuario}</div>
                            <div class="col-md-4">Califico: ${element.reserva.puntaje} pts</div>
                            <div class="col-md-12">Resena: ${element.reserva.textoReserva}</div>
                            <div class="col-md-12">Respuesta: ${element.reserva.respuesta}</div>
                        </div>
                    </div>
                `);
            });
        }
    }    
}
obtenerYMostrarReservas(1);


// debe recibir las reservas paginadas con sus users
// probablemente envie la informacion a rentas para poder aplicar