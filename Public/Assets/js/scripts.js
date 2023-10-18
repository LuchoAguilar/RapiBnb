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
                    <div class="mt-5">
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
            acceptText : 'Confirmar',
            ...params
        });
    }
}

document.addEventListener('DOMContentLoaded', function () {
    new bootstrap.Carousel(document.getElementById('imageCarousel'), {
        interval: false  // Opciones personalizadas según tus necesidades
    });
});