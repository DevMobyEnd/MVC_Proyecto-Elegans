document.addEventListener("DOMContentLoaded",function(){
  function e(e){
    let a=document.getElementById("searchResults");
    if(a.innerHTML="",!Array.isArray(e)||0===e.length){
      a.innerHTML="<p>No se encontraron resultados.</p>";
      return
    }e.forEach(e=>{
      let t=e.imagen_url||"ruta/por/defecto/a/imagen.png",n=e.nombre_cancion||"Nombre desconocido",r=e.spotify_track_id||"ID desconocido",l=e.nombre_artista||"Artista desconocido",i=document.createElement("div");
      i.classList.add("result-item"),i.innerHTML=`
    <div class="d-flex align-items-center mb-3">
        <img src="${
        t
      }" alt="${
        n
      }" class="img-thumbnail me-3" style="max-width: 100px;
      " aria-label="Imagen del \xe1lbum">
        <div>
            <p><strong>${
        n
      }</strong> - ${
        l
      }</p>
            <button class="btn btn-info selectSongButton" data-track-id="${
        r
      }" data-track-name="${
        n
      }" data-artist-name="${
        l
      }" data-image-url="${
        t
      }" aria-label="Seleccionar canci\xf3n">Seleccionar</button>
        </div>
    </div>
`,a.appendChild(i)
    }),t()
  }function t(){
    let e=new bootstrap.Modal(document.getElementById("searchModal"));
    e.show()
  }function a(){
    let e=bootstrap.Modal.getInstance(document.getElementById("searchModal"));
    e&&e.hide()
  }function e(e){
    let a=document.getElementById("searchResults");
    if(a.innerHTML="",!Array.isArray(e)||0===e.length){
      a.innerHTML='<div class="col-12"><p class="text-center">No se encontraron resultados.</p></div>';
      return
    }e.forEach(e=>{
      let t=e.imagen_url||"ruta/por/defecto/a/imagen.png",n=e.nombre_cancion||"Nombre desconocido",r=e.spotify_track_id||"ID desconocido",l=e.nombre_artista||"Artista desconocido",i=document.createElement("div");
      i.classList.add("col-md-6","col-lg-4"),i.innerHTML=`
<div class="card h-100">
    <img src="${
        t
      }" class="card-img-top" alt="${
        n
      }">
    <div class="card-body">
        <h5 class="card-title">${
        n
      }</h5>
        <p class="card-text">${
        l
      }</p>
        <button class="btn btn-primary selectSongButton" data-track-id="${
        r
      }" data-track-name="${
        n
      }" data-artist-name="${
        l
      }" data-image-url="${
        t
      }">Seleccionar</button>
    </div>
</div>
`,a.appendChild(i)
    }),t()
  }document.getElementById("searchButton").addEventListener("click",async()=>{
    let t=document.getElementById("searchSong").value.trim();
    if(""===t){
      Swal.fire({
        icon:"warning",title:"Campo vac\xedo",text:"Por favor, ingresa el nombre de una canci\xf3n."
      });
      return
    }document.getElementById("loaderOverlay").style.display="flex";
    try{
      console.log("Buscando canci\xf3n:",t);
      let a=await fetch(`/test_spotify.php?songName=${
        encodeURIComponent(t)
      }`);
      if(!a.ok)throw Error(`Error de red: ${
        a.status
      }`);
      let n=await a.text();
      console.log("Respuesta del servidor:",n);
      let r;
      try{
        r=JSON.parse(n)
      }catch(l){
        throw Error("Error al analizar JSON: "+l.message)
      }if(r.error){
        Swal.fire({
          icon:"error",title:"Error",text:`Error del servidor: ${
            r.error
          }`
        });
        return
      }e(r)
    }catch(i){
      console.error("Error al buscar la canci\xf3n:",i),Swal.fire({
        icon:"error",title:"Error",text:"Hubo un problema al buscar la canci\xf3n. Por favor, intenta de nuevo."
      })
    }finally{
      document.getElementById("loaderOverlay").style.display="none"
    }
  }),document.addEventListener("click",function(e){
    e.target.classList.contains("selectSongButton")&&function e(t){
      let n=t.target,r=n.getAttribute("data-track-id"),l=n.getAttribute("data-track-name"),i=n.getAttribute("data-artist-name"),d=n.getAttribute("data-image-url");
      document.getElementById("searchSong").value=l;
      let s=document.getElementById("selectedSongPreview");
      s.innerHTML=`
<div class="d-flex align-items-center mb-3">
    <img src="${
        d
      }" alt="${
        l
      }" class="img-thumbnail me-3" style="max-width: 100px;
      ">
    <div>
        <p><strong>${
        l
      }</strong> - ${
        i
      }</p>
    </div>
</div>
`,document.getElementById("selectedTrackId").value=r,document.getElementById("selectedTrackName").value=l,document.getElementById("selectedArtistName").value=i,document.getElementById("selectedImageUrl").value=d,a()
    }(e)
  }),document.getElementById("songRequestForm").addEventListener("submit",async e=>{
    e.preventDefault();
    let t=document.getElementById("selectedTrackName").value,a=document.getElementById("selectedArtistName").value,n=document.getElementById("selectedTrackId").value,r=document.getElementById("selectedImageUrl").value;
    if(!t||!a||!n){
      Swal.fire({
        icon:"warning",title:"Campos incompletos",text:"Por favor, selecciona una canci\xf3n antes de enviar la solicitud."
      });
      return
    }document.getElementById("loaderOverlay").style.display="flex";
    try{
      console.log("Enviando solicitud de canci\xf3n:",{
        selectedSong:t,artistName:a,trackId:n
      });
      let l=await fetch("/index.php",{
        method:"POST",body:new URLSearchParams({
          spotify_track_id:n,nombre_cancion:t,nombre_artista:a,imagen_url:r
        }),headers:{
          "Content-Type":"application/x-www-form-urlencoded"
        }
      });
      if(!l.ok)throw Error(`Error de red: ${
        l.status
      }`);
      let i=await l.text();
      console.log("Respuesta del servidor:", n); // Imprimir la respuesta en la consola
      let d;
      try{
        d=JSON.parse(i)
      }catch(s){
        throw Error("Error al analizar JSON: "+s.message)
      }d.success?(Swal.fire({
        icon:"success",title:"\xc9xito",text:"Solicitud enviada con \xe9xito."
      }),document.getElementById("songRequestForm").reset(),document.getElementById("selectedSongPreview").innerHTML=""):Swal.fire({
        icon:"error",title:"Error",text:`Hubo un problema al enviar la solicitud: ${
          d.message
        }`
      })
    }catch(o){
      console.error("Error al enviar la solicitud:",o),Swal.fire({
        icon:"error",title:"Error",text:"Hubo un problema al enviar la solicitud. Por favor, intenta de nuevo."
      })
    }finally{
      document.getElementById("loaderOverlay").style.display="none"
    }
  }),document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(e=>{
    e.addEventListener("click",a)
  }),document.addEventListener("DOMContentLoaded",function(){
    document.getElementById("searchButton"),document.getElementById("searchSong");
    let e=document.getElementById("selectedSongPreview"),t=document.getElementById("submitRequest"),a=document.getElementById("songRequestForm");
    function n(){
      let e=new bootstrap.Modal(document.getElementById("searchModal"));
      e.show()
    }function r(){
      let e=bootstrap.Modal.getInstance(document.getElementById("searchModal"));
      e&&e.hide()
    }window.selectSong=function(a,n,l,i){
      document.getElementById("selectedTrackId").value=a,document.getElementById("selectedTrackName").value=n,document.getElementById("selectedArtistName").value=l,document.getElementById("selectedImageUrl").value=i,e.innerHTML=`
    <div class="card">
        <div class="row g-0">
            <div class="col-md-4">
                <img src="${
        i
      }" class="img-fluid rounded-start" alt="${
        n
      }">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title">${
        n
      }</h5>
                    <p class="card-text">${
        l
      }</p>
                </div>
            </div>
        </div>
    </div>
`,t.disabled=!1,r()
    },a.addEventListener("submit",function(e){
      ""===document.getElementById("selectedTrackId").value&&(e.preventDefault(),alert("Por favor, selecciona una canci\xf3n antes de enviar la solicitud."))
    }),document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(e=>{
      e.addEventListener("click",r)
    }),t.disabled=!0,document.getElementById("selectedTrackId").addEventListener("input",function(){
      t.disabled=""===this.value
    })
  })
});
