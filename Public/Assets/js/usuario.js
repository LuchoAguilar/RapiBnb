async function usuarioList(){
   let reposense = await fetch(URL_PATH + '/Usuario/table');
   let reposenseData = await reposense.json();
   
   if(reposenseData.success){
    const tablaUsuarios = document.getElementById('tablaUsuarios');
        tablaUsuarios.innerHTML = '';
    

        // beforebegin sirve para colocar al final de cada elemento
        tablaUsuarios.innerHTML = `<tr>
        <th scope="row">${reposenseData.result.usuarioID} </th>
        <td>${reposenseData.result.nombreUsuario}</td>
        <td>${reposenseData.result.correo}</td>
        <td>${reposenseData.result.contrasena}</td>
        <td>${reposenseData.result.nombreCompleto}</td>
        <td><img width="50" src="${URL_PATH}/Assets/images/fotoPerfil/${reposenseData.result.fotoRostro}"></img></td>

        <td>${reposenseData.result.bio}</td>
        <td>
        <a name="" id="" class="btn btn-info" href="${URL_PATH}/usuario/edit/?id=${reposenseData.result.usuarioID}" role="button">Editar perfil</a>
        |
        <button onclick="eliminarUsuario(${reposenseData.result.usuarioID});"  class="btn btn-danger">Eliminar</button>
        </td>                    
        </tr>`;

   }
}
usuarioList();

function eliminarUsuario(id) {
    const data = new FormData();
    data.append('id', id);

    fetch(URL_PATH + '/Usuario/delete/', {
        method: 'POST',
        body: data
    }).then(response => response.json())
      .then(data => {
        if (data.success) {
            console.log(data.message);
            usuarioList();
        }
    });
}

