document.addEventListener("DOMContentLoaded",function(){let t=document.querySelectorAll(".sidebar-link"),e=document.getElementById("content-area");function r(t=1,e=10){$.ajax({url:"/Admin.php?action=obtenerUsuarios",method:"GET",data:{page:t,limit:e},success:function(t){t&&t.usuarios?a(t):console.error("Error en la respuesta del servidor:",t)},error:function(t,e,r){console.error("Error al cargar usuarios:",r)}})}function a(t){let e=`
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Fecha de Registro</th>
                <th>Rol</th>
            </tr>
        </thead>
        <tbody>`;t.usuarios.forEach(function(t){e+=`
        <tr>
            <td>${t.id||"N/A"}</td>
            <td>${t.nombre||"N/A"}</td>
            <td>${t.email||"N/A"}</td>
            <td>${t.fecha_registro||"N/A"}</td>
            <td>${t.rol||"N/A"}</td>
        </tr>`}),e+="</tbody></table>",$("#obtenerUsuariosResult").html(e)}t.forEach(t=>{t.addEventListener("click",function(t){t.preventDefault();let a=this.getAttribute("data-page");a&&function t(a){fetch(`/Views/layout/Admin/partials/${a}.php`).then(t=>t.text()).then(t=>{var o;e.innerHTML=t,"lista_usuarios"===(o=a)&&r()}).catch(t=>{console.error("Error:",t),e.innerHTML="<p>Error al cargar el contenido.</p>"})}(a)})}),document.getElementById("obtenerUsuariosForm").addEventListener("submit",function(t){t.preventDefault();let e=document.getElementById("page").value,a=document.getElementById("limit").value;r(e,a)}),$("#obtenerUsuariosForm").submit(function(t){t.preventDefault(),$("#loaderOverlay").fadeIn(),$.ajax({url:"Admin.php?action=obtenerUsuarios",method:"GET",data:$(this).serialize(),success:function(t){a(t)},complete:function(){$("#loaderOverlay").fadeOut()}})}),$(document).ready(function(){$("#obtenerUsuariosForm").submit(function(t){t.preventDefault(),$("#loaderOverlay").fadeIn(),$.get("Admin.php?action=obtenerUsuarios&"+$(this).serialize(),function(t){let e='<table class="table table-striped">';e+="<thead><tr><th>ID</th><th>Nombre</th><th>Email</th><th>Fecha de Registro</th> <th>Rol</th></tr></thead>",e+="<tbody>",t.usuarios.forEach(function(t){e+=`<tr>
            <td>${t.id||"N/A"}</td>
            <td>${(t.nombres||"")+" "+(t.apellidos||"")}</td>
            <td>${t.Gmail||"N/A"}</td>
            <td>${function t(e){if(!e)return"N/A";let r=new Date(e);return r.toLocaleString("es-ES",{year:"numeric",month:"long",day:"numeric",hour:"2-digit",minute:"2-digit"})}(t.fecha_creacion)}</td>
            <td>
                <button class="btn btn-info role-btn" data-user-id="${t.id}" data-role="${t.rol}" data-bs-toggle="modal" data-bs-target="#actualizarRolModal">
                    <i class="bi bi-pencil-fill"></i> ${t.rol||"Sin rol asignado"}
                </button>
            </td>
        </tr>`}),e+="</tbody></table>",e+=`<p>Total de usuarios: ${t.total}</p>`,$("#obtenerUsuariosResult").html(e),$("#loaderOverlay").fadeOut()})}),$("#buscarUsuariosForm").submit(function(t){t.preventDefault(),$("#loaderOverlay").fadeIn(),$.get("Admin.php?action=buscarUsuarios&"+$(this).serialize(),function(t){let e='<table class="table table-striped">';e+="<thead><tr><th>ID</th><th>Nombre</th><th>Email</th><th>Fecha de Registro</th></tr></thead>",e+="<tbody>",t.usuarios.forEach(function(t){e+=`<tr>
            <td>${t.id}</td>
            <td>${t.nombres} ${t.apellidos}</td>
            <td>${t.Gmail}</td>
            <td>${t.fecha_creacion}</td>
        </tr>`}),e+="</tbody></table>",$("#buscarUsuariosResult").html(e),$("#loaderOverlay").fadeOut()})}),$("#actualizarRolForm").submit(function(t){t.preventDefault(),$("#loaderOverlay").fadeIn();let e=$("#nuevoRol option:selected").text(),r={usuario_id:$("#usuarioId").val(),nombre_rol:e};$.ajax({url:"Admin.php?action=actualizarRol",method:"POST",data:r,success:function(t){t.success?(alert("Rol actualizado correctamente"),$("#actualizarRolModal").modal("hide"),$("#obtenerUsuariosForm").submit()):alert("Error al actualizar el rol: "+(t.error||"Error desconocido"))},error:function(t,e,r){alert("Error al actualizar el rol: "+r)},complete:function(){$("#loaderOverlay").fadeOut()}})}),$("#obtenerRegistrosPorMes").click(function(){$("#loaderOverlay").fadeIn(),$.get("Admin.php?action=obtenerRegistrosPorMes",function(t){let e=document.getElementById("registrosPorMesChart").getContext("2d");new Chart(e,{type:"line",data:{labels:t.registrosPorMes.map(t=>t.mes),datasets:[{label:"Registros por Mes",data:t.registrosPorMes.map(t=>t.total),borderColor:"rgb(75, 192, 192)",tension:.1}]}}),$("#loaderOverlay").fadeOut()})})})});