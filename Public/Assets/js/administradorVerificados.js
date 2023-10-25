async function verificacionList() {
    
        const response = await fetch(URL_PATH + '/Administrador/postulantes');
        const data = await response.json();

        if (data.success) {
            const tablaPostulantes = document.getElementById('tablaPostulantes');
            tablaPostulantes.innerHTML = '';

            data.result.forEach(element => {

                tablaPostulantes.insertAdjacentHTML('beforeend', `
                    <tr>
                        <th scope="row">${element.postulante.nombreUsuario}</th>
                        <td>${element.postulante.correo}</td>
                        <td>
                            <button onclick="mostrarDocumentacion('${element.documentacion.documentoAdjunto}');" class="btn btn-danger">Mostrar</button>
                        </td>
                        <td>
                            <a name="" id="" class="btn btn-info" href="${URL_PATH}/Administrador/verificar/?id=${element.postulante.ofertaID}" role="button">Sí</a>
                            |
                            <button onclick="eliminarDocumentacion(${element.postulante.ofertaID});" class="btn btn-danger">No</button>
                        </td>
                    </tr>
                `);
            });
        } else {
            console.log(data.message);
        }
}

verificacionList();


 function mostrarDocumentacion(documentoAdjunto){
    //mostrar en modal
 }

 function eliminarDocumentacion(id){
    const data = new FormData();
    data.append('id', id);

    fetch(URL_PATH + '/Administrador/delete/', {
        method: 'POST',
        body: data
    }).then(response => response.json())
      .then(data => {
        if (data.success) {
            console.log(data.message);
            verificacionList();
        }
    });
 }