async function usuarioList(){
    let reposense = await fetch(URL_PATH + '/Administrador/table');
    let reposenseData = await reposense.json();
    
    if(reposenseData.success){
     const tablaUsuarios = document.getElementById('tablaUsuarios');
     tablaUsuarios.innerHTML = '';
     
     reposenseData.result.forEach(element => {
         // beforebegin sirve para colocar al final de cada elemento

         const verificado = element.verificado ? 'Verificado' : 'No verificado';

                tablaUsuarios.insertAdjacentHTML('beforeend', `
                    <tr>
                        <th scope="row">${verificado}</th>
                        <td>${element.usuario.nombreUsuario}</td>
                        <td>${element.usuario.correo}</td>
                        <td>${element.usuario.nombreCompleto}</td>
                        <td><img width="50" src="${URL_PATH}/Assets/images/fotoPerfil/${element.usuario.fotoRostro}" alt="Foto de perfil"></td>
                        <td>${element.usuario.bio}</td>
                    </tr>
                `);
     });
    }
 }
 usuarioList();

