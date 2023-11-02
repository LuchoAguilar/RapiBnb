const Modal = {
    confirm({
        title = '',
        content = '',
        type = 'primary',
        confirm = true,
        icon = 'fa-regular fa-circle-question',
        acceptText = 'Aceptar',
        cancelText = 'Cancelar',
        onAccept = () => {},
        onCancel = () => {}
    }){
        const uniqueID = document.querySelectorAll('.modal').length + 1;

        const elemento = document.createElement('div');

        const cancelBtn = confirm ? `<button type="button" id="cancel${uniqueID}" class="btn btn-${type}" data-bs-dismiss="modal">${cancelText}</button>` : '';

        elemento.innerHTML = `
            <div class="modal fade" id="staticBackdrop${uniqueID}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <div class="fs-1 text-${type}"><i class="${icon}"></i></div>
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">${title}</h1>
                        <div>
                        ${content}
                        </div>
                    </div>
                    <div class="mt-5 text-center">
                    ${cancelBtn}
                    <button type="button" id="accept${uniqueID}" class="btn btn-${type}">${acceptText}</button>
                    </div>
                </div>
                </div>
            </div>
        `;

        document.body.appendChild(elemento);
        const myModalElem = document.getElementById('staticBackdrop' + uniqueID);
        const myModal = new bootstrap.Modal(myModalElem);
        myModal.show();

        const cancelBtnFuncionalidad = document.getElementById('cancel' + uniqueID);

        if(cancelBtnFuncionalidad){
            cancelBtnFuncionalidad.addEventListener('click', (e)=>{
                e.preventDefault();
                onCancel();
                myModal.hide();
            });
        }
        

        document.getElementById('accept' + uniqueID).addEventListener('click', (e) =>{
            e.preventDefault();
            onAccept();
            myModal.hide();
        })

        myModalElem.addEventListener('hidden.bs.modal',(e)=>{
            e.preventDefault();
            elemento.remove();
        });
    },
    success(params){
        this.confirm({
            icon: 'fa-solid fa-check',
            type: 'success',
            confirm: false,
            acceptText : 'Confirmar',
            ...params
        });
    },
    warning(params){
        this.confirm({
            icon: 'fa-solid fa-triangle-exclamation',
            type: 'warning',
            confirm: false,
            acceptText : 'Confirmar',
            ...params
        });
    },
    danger(params){
        this.confirm({
            icon: 'fa-solid fa-bug',
            type: 'danger',
            confirm: false,
            acceptText : 'Aceptar',
            ...params
        });
    }
}

function botonesPaginacion(page, limit, pages, paginacionContainer) {
    const paginasAMostrar = 5;
    const mitad = Math.floor(paginasAMostrar / 2);

    let inicio = Math.max(1, page - mitad);
    let final = Math.min(pages, page + mitad);
    const anterior = (page > 1) ? page - 1 : 1;
    const siguiente = (page < pages) ? page + 1 : pages;

    function createPageButton(text, pageNumber) {
        const button = document.createElement('a');
        button.href = `?page=${pageNumber}&limit=${limit}`;
        button.textContent = text;
        button.classList.add('btn', 'btn-primary'); // Agrega clases de Bootstrap
        return button;
    }
    paginacionContainer.innerHTML = '';
    paginacionContainer.appendChild(createPageButton('Anterior', anterior));

    for (let i = inicio; i <= final; i++) {
        const button = createPageButton(i, i);
        if (i === page) {
            button.classList.add('active'); // Agrega la clase 'active' de Bootstrap
        }
        paginacionContainer.appendChild(button);
    }

    paginacionContainer.appendChild(createPageButton('Siguiente', siguiente));
}
