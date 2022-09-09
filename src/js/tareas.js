//IIFE es una función que se invoca inmediatamente

//lo de dentro de la función se protege
(function(){

    let tareas = [];
    let filtradas = [];
   obtenerTareas();

//boton para mostrar el modal de agregar tarea
const nuevaTareaBtn = document.querySelector('#agregar-tarea');
nuevaTareaBtn.addEventListener('click', ()=>mostrarFormulario());

//Filtros de busqueda
const filtros = document.querySelectorAll('#filtros input[type="radio"]');
filtros.forEach(radio=>{
    radio.addEventListener('input', filtrarTareas);
});
function filtrarTareas(e){
    const filtro = e.target.value;

    if(filtro !== ''){

        filtradas = tareas.filter(tarea => tarea.estado === filtro);

    }else{
        //se muestran todas las tareas
        filtradas = [];
    }
    mostrarTareas();
}
async function obtenerTareas(){
  try {
    const id = proyectoActual();
    const url = `/api/tareas?id=${id}`;
    const respuesta = await fetch(url);
    const resultado = await respuesta.json();
    tareas = resultado;
    mostrarTareas();
  } catch (error) {
    console.log(error);
  }
}
function mostrarTareas(){

    //Para que itere sobre este
    const arrayAux = filtradas.length > 0 ? filtradas : tareas;
    limpiarHtmlTareas();
    //calcular cuantas tareas pendientes y completas hay para evitar error de traer todas cuando  todas sean completadas o pendientes
    totalPendientes();
    totalCompletadas();
    if(arrayAux.length === 0){
        const contenedorTareas = document.querySelector('#listado-tareas');
        const textoNoTareas = document.createElement('LI');
        textoNoTareas.textContent = 'No hay tareas';
        textoNoTareas.classList.add('no-tareas');
        contenedorTareas.appendChild(textoNoTareas);
        return;
    }
    const estados = {
        0: 'Pendiente',
        1: 'Completada'
    }
    arrayAux.forEach(tarea=>{
        const {estado, nombre, id} = tarea;
        const contenedorTarea = document.createElement('LI');
        contenedorTarea.dataset.tareaId = id;  
        contenedorTarea.classList.add('tarea');
        const nombreTarea = document.createElement('P');
        nombreTarea.textContent = nombre;
        nombreTarea.ondblclick = function(){
            mostrarFormulario(true, {...tarea});
        }

        const opcionesDiv = document.createElement('DIV');
        opcionesDiv.classList.add('opciones');

        //BOTONES
        const btnEstadoTarea = document.createElement('BUTTON');
        btnEstadoTarea.classList.add('estado-tarea');
        btnEstadoTarea.classList.add(`${estados[estado].toLowerCase()}`);
        btnEstadoTarea.textContent =estados[estado];
        btnEstadoTarea.dataset.estadoTarea = estado;
        btnEstadoTarea.ondblclick = function(){
            cambiarEstadoTarea({...tarea});
        }
        const btnEliminarTarea = document.createElement('BUTTON');
        btnEliminarTarea.classList.add('eliminar-tarea');
        btnEliminarTarea.dataset.idTarea = id;
        btnEliminarTarea.textContent = 'Eliminar';
        btnEliminarTarea.ondblclick = function (){
            confirmarEliminarTarea(tarea);
        }
        opcionesDiv.appendChild(btnEstadoTarea);
        opcionesDiv.appendChild(btnEliminarTarea);
        contenedorTarea.appendChild(nombreTarea);
        contenedorTarea.appendChild(opcionesDiv);

        const listadoTareas = document.querySelector('#listado-tareas');
        listadoTareas.appendChild(contenedorTarea);
    });
}
function totalPendientes(){
    //revisar que haya tareas pendientes y si no hay desabilitar el btn
    const totalPendientes = tareas.filter(tarea=> tarea.estado === '0');
    const radioPendiente = document.querySelector('#pendientes');
    const labelPendiente = document.querySelector('label[for="pendientes"]')
    if(totalPendientes.length === 0){
        radioPendiente.disabled = true;
        labelPendiente.style.color = '#6b7280';
    }else{
        radioPendiente.disabled = false;
        if(document.querySelector('.dashboard').style.backgroundColor!='')
        labelPendiente.style.color = 'black';
        else labelPendiente.style.color = 'white';
    }
}
function totalCompletadas(){
    //revisar que haya tareas pendientes y si no hay desabilitar el btn
    const totalCompletadas = tareas.filter(tarea=> tarea.estado === '1');
    const radioCompletada = document.querySelector('#completadas');
    const labelCompletada = document.querySelector('label[for="completadas"]')
    if(totalCompletadas.length === 0){
        radioCompletada.disabled = true;
        labelCompletada.style.color = '#6b7280';
    }else{
        radioCompletada.disabled = false;
        if(document.querySelector('.dashboard').style.backgroundColor!='')
         labelCompletada.style.color = 'black';
    }
}
function mostrarFormulario(editar = false, tarea = {}){
    const modal = document.createElement('DIV');
    modal.classList.add('modal');
    modal.innerHTML = `
       <form class="formulario nueva-tarea">
       <legend>${editar ? 'Editar Tarea' : 'Añade una nueva tarea' }</legend>
       <div class="campo">
        <label for="tarea">Tarea</label>
        <input 
        type="text" 
        name="tarea" 
        placeholder="${editar ? 'Edita la tarea' : 'Añadir tarea al proyecto actual'}" 
        id="tarea" 
        value="${tarea.nombre ? tarea.nombre :  ''}"
        />
       </div>

       <div class="opciones">
       <input type="submit" class="submit-nueva-tarea" value="${editar ? "Guardar cambios" : "Añadir tarea"}"/>
       <button type="button" class="cerrar-modal">Cancelar</button>
       </div>
       </form>
    `;
    document.querySelector('.dashboard').appendChild(modal);

    //Modelo de concurrencia y loop de eventis
    setTimeout(()=>{
        document.querySelector('.formulario').classList.add('animar')
    }, 500)

    //delegation => identificar a que le damos click, en base al elemento realizar una accion
    modal.addEventListener('click', (e)=>{
        e.preventDefault();
        if(e.target.classList.contains('cerrar-modal')){
            document.querySelector('.formulario').classList.add('cerrar')
            setTimeout(()=>{
                modal.remove();
            }, 500)
          
        }
        if(e.target.classList.contains('submit-nueva-tarea')){
            const nombreTarea = document.querySelector('#tarea').value.trim();
            if(nombreTarea===''){
     
        //mostrar una alerta de error
        mostrarAlerta('El nombre de la tarea es obligatorio', 'error');

        return;
    }
           if(editar){
            tarea.nombre = nombreTarea;
                actualizarTarea(tarea);
           }else{
            agregarTarea(nombreTarea);
           }
        }
    })
    
}

    //Muestra un mensaje en la interfaz
    function mostrarAlerta(mensaje, tipo){
     
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            didOpen: (toast) => {
              toast.addEventListener('mouseenter', Swal.stopTimer)
              toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
          })
          
          Toast.fire({
            icon: tipo,
            title: mensaje
          })
    }

    //consultar el servidor para añadir una tarea
  async function agregarTarea(tarea){
        const datos = new FormData();
    
        datos.append('nombre', tarea);
        datos.append('proyectoId', proyectoActual());
        try {
            const url = 'http://localhost:3000/api/tarea';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });
            const resultado = await respuesta.json();
            mostrarAlerta(resultado.mensaje, resultado.tipo )
        if(resultado.tipo === 'success'){
           setTimeout(() => {
            document.querySelector('.modal').remove();
           }, 2500);

           //Agregar el objeto de tarea al global de tareas
           const tareaObjeto ={
            id: String(resultado.id),
            nombre: tarea,
            estado: "0",
            proyectoId: resultado.proyectoId
           }

           //sincronizando lo que hay en el servido con el cliente para evitar que se recargue la pagina
           tareas = [...tareas, tareaObjeto];
           //por el virtual dom tenemos que volver a generar el html
             mostrarTareas();
           
        }

        } catch (error) {
            console.log(error);
        }
   }
   function cambiarEstadoTarea(tarea){
    const nuevoEstado = tarea.estado === '1' ? '0' : '1';
    tarea.estado = nuevoEstado;
    actualizarTarea(tarea);
   }
  async function actualizarTarea(tarea){
    const {estado, id, nombre, proyectoId} = tarea
    const datos = new FormData();
    datos.append('id', id);
    datos.append('nombre', nombre);
    datos.append('estado', estado);
    datos.append('proyectoId',proyectoActual());
    try {
        const url = '/api/tarea/actualizar';
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });
        const resultado = await respuesta.json();
        if(resultado.tipo === 'success'){
            mostrarAlerta( resultado.mensaje, resultado.tipo);

            const modal = document.querySelector('.modal');
            if(modal){
                modal.remove();
            }
           
            tareas = tareas.map(tareaMemoria=>{
                if(tareaMemoria.id == id){
                     tareaMemoria.estado = estado;
                     tareaMemoria.nombre = nombre;
                }
                return tareaMemoria;
            });
            mostrarTareas();
        } 
    } catch (error) {
        console.log(error);
    }
   }
   function confirmarEliminarTarea(tarea){
    Swal.fire({
        title: 'Elimnar tarea?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          //Eliminar tarea
          eliminarTarea(tarea);
        }
      })
   }
   async function eliminarTarea(tarea){
    const {id, nombre, estado} = tarea;
    const datos = new FormData();
    datos.append('id', id);
    datos.append('nombre', nombre);
    datos.append('estado', estado);
    datos.append('proyectoId',proyectoActual());
    try {
        const url = '/api/tarea/eliminar';
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });
        const resultado = await respuesta.json();
        if(resultado.resultado){
            mostrarAlerta( resultado.mensaje, resultado.tipo);
            tareas = tareas.filter(tareaMemoria=>tareaMemoria.id !== id);
            mostrarTareas();
        } 
    } catch (error) {
        console.log(error);
    }

   }
   function proyectoActual(){
    const proyectoParams = new URLSearchParams(window.location.search);
    //El método itera en un objeto, obtener lo que tiene objeto
     const proyecto = Object.fromEntries(proyectoParams.entries());
     return proyecto.id;
   }
   function limpiarHtmlTareas(){
    const listadoTareas = document.querySelector('#listado-tareas');

    //Forma mas rapida de eliminar tareas que haciendolo con el innerHtml
    while(listadoTareas.firstChild){
        listadoTareas.removeChild(listadoTareas.firstChild)
    }
   }
})(); //los ultimos parentesis hacen que se ejecute automaticamente