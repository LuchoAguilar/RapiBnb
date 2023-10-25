async function verificacionList() {
    try {
        const response = await fetch(URL_PATH + '/Administrador/postulantes');
        const data = await response.json();

        if (data.success) {
            const tablaPostulantes = document.getElementById('tablaPostulantes');
            tablaPostulantes.innerHTML = '';

            data.result.forEach(item => {
                const postulante = item.postulante;
                const documentacion = item.documentacion;

                // beforebegin sirve para colocar al final de cada elemento
                tablaPostulantes.insertAdjacentHTML('beforeend', `
                    <tr>
                        <th scope="row">${postulante.nombreUsuario}</th>
                        <td>${postulante.correo}</td>
                        <td>
                            <button onclick="mostrarDocumentacion('${documentacion.documentoAdjunto}');" class="btn btn-danger">Mostrar</button>
                        </td>
                        <td>
                            <button onclick="verificarCuenta(${postulante.usuarioID});" class="btn btn-info">Si</button>
                            |
                            <button onclick="eliminarDocumentacion(${postulante.usuarioID});" class="btn btn-danger">No</button>
                        </td>
                    </tr>
                `);
            });
        } else {
            console.log(data.message);
        }
    } catch (error) {
        const errorContainer = document.getElementById('errorContainer');
        errorContainer.innerText = 'Ocurrió un error al cargar los datos.';
        console.error('Error:', error);
    }
}

verificacionList();

 function mostrarDocumentacion(documentoAdjunto){
    //mostrar en modal
 }

 function verificarCuenta(id){
    const data = new FormData();
    data.append('id', id);

    fetch(URL_PATH + '/Administrador/verificar/', {
        method: 'POST',
        body: data
    }).then(response => response.json())
      .then(data => {
        if (data.success) {
            console.log(data.message);
            verificacionList();
        }else{
            console.log(data.message);
        }
    }); 
 }

 function eliminarDocumentacion(id){
    const data = new FormData();
    data.append('id', id);

    fetch(URL_PATH + '/Administrador/borrarPostulacion/', {
        method: 'POST',
        body: data
    }).then(response => response.json())
      .then(data => {
        if (data.success) {
            console.log(data.message);
            verificacionList();
        }else{
            console.log(data.message);
        }
    });
 }