(function () {
    // To.do lo que este en esta funcion se va a proteger, no sale de este archivo

    // Boton para mostrar el Modal de Agregar Tarea
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click', mostrarFormulario);

    function mostrarFormulario() {
        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML = `

            <form class="formulario nueva-tarea">
                <legend>Añade una nueva tarea</legend>
                <div class="campo">
                    <label for="">Tarea</label>
                    <input type="text" name="tarea" id="tarea" placeholder="tarea">
                </div>
                <div class="opciones">
                    <input type="submit" class="submit-nueva-tarea" value="Añadir Tarea">
                        <button class="cerrar-modal" type="button">Cancelar</button>
                </div>
            </form>
        `;

        setTimeout(() => {
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('animar');
        }, 200);

        modal.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (e.target.classList.contains('cerrar-modal')) {
                
                const formulario = document.querySelector('.formulario');
                formulario.classList.add('cerrar');

                setTimeout(() => {
                    modal.remove();
                }, 500);
            }
        });

        document.querySelector('body').appendChild(modal);
    }

})();