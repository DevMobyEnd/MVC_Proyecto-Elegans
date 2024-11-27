document.addEventListener("DOMContentLoaded", () => {
    // Utility Functions
    const sanitizeHTML = (html) => {
      const tempDiv = document.createElement('div');
      tempDiv.textContent = html;
      return tempDiv.innerHTML;
    };
  
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
            
            if (url.includes("editarperfil.php")) {
              setupEditProfileForm();
            }
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
  
    // Edit Profile Form Setup
    const setupEditProfileForm = () => {
      let currentStep = 1;
  
      const updateStepDisplay = (step) => {
        // Hide all steps
        document.querySelectorAll(".register-step").forEach(stepEl => {
          stepEl.style.display = "none";
        });
  
        // Show current step
        document.getElementById(`step${step}`).style.display = "block";
  
        // Manage navigation buttons
        const prevBtn = document.getElementById("prevBtn");
        const nextBtn = document.getElementById("nextBtn");
        const updateButton = document.getElementById("updateButton");
  
        if (prevBtn) prevBtn.style.display = step > 1 ? "inline-block" : "none";
        if (nextBtn) nextBtn.style.display = step < 2 ? "inline-block" : "none";
        if (updateButton) updateButton.style.display = step === 2 ? "inline-block" : "none";
      };
  
      const nextBtn = document.getElementById("nextBtn");
      const prevBtn = document.getElementById("prevBtn");
  
      if (nextBtn) {
        nextBtn.addEventListener("click", () => {
          if (currentStep < 2) {
            currentStep++;
            updateStepDisplay(currentStep);
          }
        });
      }
  
      if (prevBtn) {
        prevBtn.addEventListener("click", () => {
          if (currentStep > 1) {
            currentStep--;
            updateStepDisplay(currentStep);
          }
        });
      }
  
      // Initial step setup
      updateStepDisplay(1);
  
      // Form Submission
      const editProfileForm = document.getElementById("editProfileForm");
      if (editProfileForm) {
        editProfileForm.addEventListener("submit", (e) => {
          e.preventDefault();
          const formData = new FormData(editProfileForm);
          const updatedData = {};
          let hasChanges = false;
  
          formData.forEach((value, key) => {
            const input = editProfileForm.elements[key];
            
            if (input.type === "file") {
              if (input.files.length > 0) {
                updatedData[key] = value;
                hasChanges = true;
              }
            } else if (input.value !== input.defaultValue) {
              updatedData[key] = value;
              hasChanges = true;
            }
          });
  
          if (hasChanges) {
            fetch("/Myperfil.php", {
              method: "POST",
              headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest"
              },
              body: JSON.stringify(updatedData)
            })
            .then(response => response.json())
            .then(result => {
              if (result.success) {
                alert("Perfil actualizado con éxito");
                loadContent("/Myperfil.php");
              } else {
                alert("Error al actualizar el perfil: " + result.message);
              }
            })
            .catch(error => {
              console.error("Error:", error);
              alert("Ocurrió un error al procesar la solicitud");
            });
          } else {
            alert("No se han realizado cambios en el perfil");
          }
        });
      }
    };
  
    // Password Strength Checker
    const checkPasswordStrength = (event) => {
      const password = event.target.value;
      let strength = 0;
      const strengthBar = document.getElementById("password-strength");
      const passwordHelp = document.getElementById("passwordHelp");
  
      // Strength criteria
      if (password.length >= 8) strength += 20;
      if (password.match(/[a-z]+/)) strength += 20;
      if (password.match(/[A-Z]+/)) strength += 20;
      if (password.match(/[0-9]+/)) strength += 20;
      if (password.match(/[$@#&!]+/)) strength += 20;
  
      if (strengthBar) {
        strengthBar.style.width = `${strength}%`;
        strengthBar.setAttribute("aria-valuenow", strength);
  
        // Update bar and help text based on strength
        if (strength < 40) {
          strengthBar.className = "progress-bar bg-danger";
          passwordHelp.textContent = "Contraseña débil";
        } else if (strength < 60) {
          strengthBar.className = "progress-bar bg-warning";
          passwordHelp.textContent = "Contraseña moderada";
        } else if (strength < 80) {
          strengthBar.className = "progress-bar bg-info";
          passwordHelp.textContent = "Contraseña fuerte";
        } else {
          strengthBar.className = "progress-bar bg-success";
          passwordHelp.textContent = "Contraseña muy fuerte";
        }
      } else {
        console.error("Elemento progress-bar no encontrado");
      }
    };
  
    // Debounce function for password strength
    const debounce = (func, delay = 300) => {
      let timeoutId;
      return (...args) => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => func.apply(null, args), delay);
      };
    };
  
    // Initial Setup
    console.log("Script cargado correctamente");
    
    // Set secure cookie
    document.cookie = "name=value; SameSite=None; Secure";
  
    // Conditional initializations
    if (window.location.pathname.includes("/Views/layout/Myperfil/partials/editarperfil.php")) {
      setupEditProfileForm();
    }
  
    setupProfileEvents();
  
    // Password input event
    const passwordInput = document.getElementById("passwordInput");
    if (passwordInput) {
      const debouncedPasswordCheck = debounce(checkPasswordStrength);
      passwordInput.addEventListener("input", debouncedPasswordCheck);
    } else {
      console.error("Elemento passwordInput no encontrado");
    }
  });