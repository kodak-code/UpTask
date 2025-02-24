(function () {

    obtenerTareas();

    // Globales
    let tareas = [];

    // To.do lo que este en esta funcion se va a proteger, no sale de este archivo

    // Boton para mostrar el Modal de Agregar Tarea
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click', mostrarFormulario);

    async function obtenerTareas() {
        
        try {
            const id = obtenerProyecto();
            const url = `/api/tareas?id=${id}`;

            const respuesta = await fetch(url);
            const resultado = await respuesta.json();
        
            tareas = resultado.tareas;

            mostrarTareas();
            
        } catch (error) {
            console.log(error);
        }
    }

    function mostrarTareas() {

        limpiarTareas();
        
        if(tareas.lenght === 0) {
            const contenedorTareas = document.querySelector('#listado-tareas');

            const textoNoTareas = document.createElement('LI');
            textoNoTareas.textContent =  'No Hay Tareas';
            textoNoTareas.classList.add('no-tareas');

            contenedorTareas.appendChild(textoNoTareas);
            return;
        }

        // Diccionario de estado
        const estados = {
            0: 'Pendiente',
            1: 'Completa'
        }

        tareas.forEach(tarea => {
            const contenedorTarea = document.createElement('LI');
            contenedorTarea.dataset.tareaId = tarea.id;
            contenedorTarea.classList.add('tarea');
            
            const nombreTarea = document.createElement('P');
            nombreTarea.textContent = tarea.nombre;

            const opcionesDiv = document.createElement('DIV');
            opcionesDiv.classList.add('opciones');

            // Botones
            const btnEstadoTarea = document.createElement('BUTTON');
            btnEstadoTarea.classList.add('estado-tarea');
            btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`);
            btnEstadoTarea.textContent = estados[tarea.estado];
            btnEstadoTarea.dataset.estadoTarea = tarea.estado;
            btnEstadoTarea.ondblclick = function() {
                cambiarEstadoTarea({...tarea});
            }

            const btnEliminarTarea = document.createElement('BUTTON');
            btnEliminarTarea.classList.add('eliminar-tarea');
            btnEliminarTarea.dataset.idTarea = tarea.id;
            btnEliminarTarea.textContent = 'Eliminar';
            btnEliminarTarea.ondblclick = function() {
                confirmarEliminarTarea({...tarea});
            }

            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btnEliminarTarea);
            
            contenedorTarea.appendChild(nombreTarea);
            contenedorTarea.appendChild(opcionesDiv);
            
            const listadoTareas = document.querySelector('#listado-tareas');
            listadoTareas.appendChild(contenedorTarea);
        });
        
    }

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

        modal.addEventListener('click', function (e) {
            e.preventDefault();

            if (e.target.classList.contains('cerrar-modal')) {

                const formulario = document.querySelector('.formulario');
                formulario.classList.add('cerrar');

                setTimeout(() => {
                    modal.remove();
                }, 500);
            }
            if (e.target.classList.contains('submit-nueva-tarea')) {

                submitFormularioNuevaTarea();
            }
        });

        document.querySelector('.dashboard').appendChild(modal);
    }

    function submitFormularioNuevaTarea() {

        const tarea = document.querySelector('#tarea').value.trim();

        if (tarea === '') {
            //Mostrar alerta de error
            mostrarAlerta('El nombre de la tarea es obligatorio', 'error',
                document.querySelector('.formulario legend'));
            return;
        }

        agregarTarea(tarea);
    }

    // Muestra un mensaje en la interfaz
    function mostrarAlerta(mensaje, tipo, referencia) {

        // Previene la creacion de multiples alertas
        const alertaPrevia = document.querySelector('.alerta');
        if (alertaPrevia) {
            alertaPrevia.remove();
        }

        const alerta = document.createElement('DIV');
        alerta.classList.add('alerta', tipo);
        alerta.textContent = mensaje;

        // Inserta la alerta antes del legend
        referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);

        // Eliminar alertas despues de 5sg
        setTimeout(() => {
            alerta.remove();
        }, 3000);
    }

    // Consultar al servidar para añadir una nueva tarea
    async function agregarTarea(tarea) {

        // Construir la peticion
        const datos = new FormData;
        datos.append('nombre', tarea);
        datos.append('proyectoId', obtenerProyecto());

        try {
            const url = 'http://localhost:3000/api/tarea';

            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });

            const resultado = await respuesta.json();
            console.log(resultado);

            mostrarAlerta(resultado.mensaje, resultado.tipo,
                document.querySelector('.formulario legend'));

            if(resultado.tipo === 'exito') {
                const modal = document.querySelector('.modal');
                setTimeout(() => {
                    modal.remove();
                }, 1000);

                // Agregar el obj de tarea al global de tareas
                const tareaObj = {
                    id: String(resultado.id),
                    nombre: tarea,
                    estado: "0",
                    proyectoId: resultado.proyectoId
                }

                tareas = [...tareas, tareaObj]; // agrega tareas de mas, borrar las que ya estan

                mostrarTareas();
            }

        } catch (error) {
            console.log(error);

        }

    }

    function cambiarEstadoTarea(tarea) {
        
        const nuevoEstado = tarea.estado === "1" ? "0" : "1";
        tarea.estado = nuevoEstado;
        actualizarTarea(tarea);
    }

    async function actualizarTarea(tarea) {
        
        const {estado, id, nombre, proyectoId} = tarea;

        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerProyecto());

        try {
            const url = 'http://localhost:3000/api/tarea/actualizar';
        
            const  respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });

            const resultado = await respuesta.json();
            
            if(resultado.respuesta.tipo === 'exito') {
                
                mostrarAlerta(
                    resultado.respuesta.mensaje,
                    resultado.respuesta.tipo, 
                    document.querySelector('.contenedor-nueva-tarea')
                );

                tareas = tareas.map(tareaMemoria => {

                    if(tareaMemoria.id === id) {
                        tareaMemoria.estado = estado;
                    }

                    return tareaMemoria;
                });
                
                mostrarTareas();
                
            }
        
        } catch (error) {
            console.log(error);
            
        }
    }

    async function eliminarTarea(tarea) {
        const {estado, id, nombre} = tarea;

        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerProyecto());

        try {
            const url = 'http://localhost:3000/api/tarea/eliminar';
            const  respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });
            
            const resultado = await respuesta.json();
            //console.log(resultado);
            if(resultado.resultado) {
                // En lugar de mostrar alerta
                Swal.fire('Eliminado!', resultado.mensaje, 'success');

                // trae todas menos la que se dio doble click, usando el id
                tareas = tareas.filter(tareaMemoria => tareaMemoria.id !== tarea.id);
                mostrarTareas();
            }
            
        } catch (error) {
            console.log(error);
            
        }
    }

    function confirmarEliminarTarea(tarea) {
        Swal.fire({
            title: "¿Eliminar Tarea?",
            showCancelButton: true,
            confirmButtonText: "Si",
            cancelButtonText: "No"
          }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                eliminarTarea(tarea);
            } 
          });
    }

    function obtenerProyecto() {
        const proyectoParams = new URLSearchParams(window.location.search); // parte del id del proyecto actual 
        const proyecto = Object.fromEntries(proyectoParams.entries());
        return proyecto.id; //devuelve id del proyecto limpio
    }

    function limpiarTareas() {
        
        const listadoTareas = document.querySelector('#listado-tareas');

        while (listadoTareas.firstChild) {
            listadoTareas.removeChild(listadoTareas.firstChild);
        } // borra las repetidas
    }

})();