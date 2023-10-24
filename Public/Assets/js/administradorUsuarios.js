async function usuarioList(){
    let reposense = await fetch(URL_PATH + '/Administrador/table');
    let reposenseData = await reposense.json();
    
    if(reposenseData.success){
     const tablaUsuarios = document.getElementById('tablaUsuarios');
     tablaUsuarios.innerHTML = '';
     
     reposenseData.result.forEach(element => {
         // beforebegin sirve para colocar al final de cada elemento
         tablaUsuarios.insertAdjacentHTML('beforeend',`<tr>
         <th scope="row">${element.usuarioID} </th>
         <td>${element.nombreUsuario}</td>
         <td>${element.correo}</td>
         <td>${element.nombreCompleto}</td>
         <td><img width="50" src="${URL_PATH}/Assets/images/fotoPerfil/${element.fotoRostro}"></img></td>
 
         <td>${element.bio}</td>
         <td>
         <button onclick="eliminarUsuario(${element.usuarioID});"  class="btn btn-danger">Eliminar</button>
         </td>                    
         </tr>`)
     });
    }
 }
 usuarioList();
 
 //ajustar para que elimine todo lo que tenga que ver con el user
 
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