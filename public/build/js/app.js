const mobileMenu=document.querySelector("#mobile-menu"),cerrarMenu=document.querySelector("#cerrar-menu"),sidebar=document.querySelector(".sidebar");mobileMenu&&mobileMenu.addEventListener("click",()=>{sidebar.classList.add("mostrar")}),cerrarMenu&&cerrarMenu.addEventListener("click",()=>{sidebar.classList.add("ocultar"),setTimeout(()=>{sidebar.classList.remove("mostrar"),sidebar.classList.remove("ocultar")},1e3)}),window.addEventListener("resize",()=>{document.body.clientWidth;(e=>768)&&sidebar.classList.remove("mostrar")});const btnsEliminar=document.querySelectorAll(".bi");function notificacionEliminar(e){Swal.fire({title:"Eliminar Proyecto?",icon:"warning",showCancelButton:!0,confirmButtonColor:"#d33",cancelButtonColor:"#3085d6",confirmButtonText:"Eliminar",cancelButtonText:"Cancelar"}).then(t=>{t.isConfirmed&&eliminarProyecto(e)})}async function eliminarProyecto(e){const t=new FormData;t.append("id",e);try{const e="http://localhost:3000/proyecto/eliminar",i=await fetch(e,{method:"POST",body:t}),o=await i.json();Swal.mixin({toast:!0,position:"top-end",showConfirmButton:!1,timer:2e3,timerProgressBar:!0,didOpen:e=>{e.addEventListener("mouseenter",Swal.stopTimer),e.addEventListener("mouseleave",Swal.resumeTimer)}}).fire({icon:o.tipo,title:o.respuesta}),setTimeout(()=>{location.reload()},2e3)}catch(e){console.log(e)}}btnsEliminar&&btnsEliminar.forEach(e=>{e.addEventListener("click",()=>notificacionEliminar(e.id))});