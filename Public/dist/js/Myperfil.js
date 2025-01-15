document.addEventListener("DOMContentLoaded", () => {
  // Content Loading Function
  const loadContent = (url) => {
    console.log("Cargando contenido desde:", url);
    
    fetch(url)
      .then(response => {
        if (!response.ok) throw new Error("Network response was not ok");
        return response.text();
      })
      .then(content => {
        console.log("Contenido cargado correctamente");
        const profileContent = document.getElementById("profileContent");
        
        if (url === "/Myperfil.php") {
          window.location.reload();
        } else {
          profileContent.innerHTML = "";
          profileContent.insertAdjacentHTML("beforeend", content);
          console.log("Contenido de perfil actualizado");
          
          setupProfileEvents();
        }
      })
      .catch(error => console.error("Error al cargar el contenido:", error));
  };

  // Profile Event Setup
  const setupProfileEvents = () => {
    console.log("Agregando eventos en profileContent");
    
    const editProfileBtn = document.getElementById("editProfileBtn");
    if (editProfileBtn) {
      console.log("editProfileBtn encontrado");
      editProfileBtn.addEventListener("click", () => {
        console.log("Botón de editar perfil clickeado");
        loadContent("../Views/layout/Myperfil/partials/editarperfil.php");
      });
    } else {
      console.log("editProfileBtn no encontrado");
    }

    const backToProfileBtn = document.getElementById("backToProfile");
    if (backToProfileBtn) {
      backToProfileBtn.addEventListener("click", () => {
        console.log("Botón de volver al perfil clickeado");
        loadContent("/Myperfil.php");
      });
    }
  };

  // Initial Setup
  console.log("Script cargado correctamente");
  setupProfileEvents();
});