const mobileMenu = document.querySelector('#mobile-menu');
const cerrarMenu = document.querySelector('#cerrar-menu');
const sidebar = document.querySelector('.sidebar');
if(mobileMenu){
    mobileMenu.addEventListener('click', ()=>{
        sidebar.classList.add('mostrar');
    })
}
if(cerrarMenu){
   cerrarMenu.addEventListener('click', ()=>{
    
    sidebar.classList.add('ocultar');
    setTimeout(()=>{
        sidebar.classList.remove('mostrar');
        sidebar.classList.remove('ocultar');
    }, 1000)
   })     
}

//Eliminar la clase de mostrar en un tamaño de tablet y superior
window.addEventListener('resize', ()=>{
    const anchoPantalla = document.body.clientWidth;
    if(anchoPantalla => 768){
        sidebar.classList.remove('mostrar')
    }
})

//Eliminar proyecto
const btnsEliminar = document.querySelectorAll('.bi');
if (btnsEliminar){
    btnsEliminar.forEach(btn=>{
        //Se le envia el id del proyecto
        btn.addEventListener('click', ()=>notificacionEliminar(btn.id))
    })
}
function notificacionEliminar(id){
    Swal.fire({
        title: 'Eliminar Proyecto?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          eliminarProyecto(id)
        }
      })
}
async function eliminarProyecto(id){
    const datos = new FormData();
    datos.append('id', id);
    try {
        const url = 'http://localhost:3000/proyecto/eliminar';
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });
        const resultado = await respuesta.json();
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: (toast) => {
              toast.addEventListener('mouseenter', Swal.stopTimer)
              toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
          })
          
          Toast.fire({
            icon: resultado.tipo,
            title: resultado.respuesta
          })
          setTimeout(()=>{
            location.reload()
          }, 2000)
    } catch (error) {
        console.log(error)
    }
    
}
