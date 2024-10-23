document.addEventListener("DOMContentLoaded",function(){console.log("DOMContentLoaded event fired");let e=localStorage.getItem("theme")||"light";function t(){let e=document.documentElement.getAttribute("data-bs-theme");console.log("Current theme before toggle:",e);let t="dark"==e?"light":"dark";document.documentElement.setAttribute("data-bs-theme",t),console.log("Theme toggled to:",t),setTimeout(()=>{l(t),console.log("Image updated after timeout")},0)}function l(e){let t=document.getElementById("modeImage");t?(t.src="dark"===e?"../Public/dist/img/dark.png":"../Public/dist/img/light.png",console.log("Image updated to:",t.src)):console.log("modeImage element not found")}console.log("Saved theme:",e),document.documentElement.setAttribute("data-bs-theme",e),console.log("Initial theme set to:",document.documentElement.getAttribute("data-bs-theme")),l(e),document.querySelector(".theme-toggle").addEventListener("click",()=>{console.log("Theme toggle clicked"),function e(){let t="light"===document.documentElement.getAttribute("data-bs-theme")?"light":"dark";localStorage.setItem("theme",t),console.log("Theme saved to localStorage:",t)}(),t()}),l(document.documentElement.getAttribute("data-bs-theme")),window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change",e=>{console.log("Preferred color scheme changed:",e.matches?"dark":"light"),l(e.matches?"dark":"light")}),function e(){let t=localStorage.getItem("theme");return console.log("Current theme in localStorage:",t),"light"===t}()&&(console.log("Initial theme is light, toggling to dark"),t());let o=document.querySelectorAll('input[type="radio"]');o.forEach(e=>{e.addEventListener("click",function(){var e;e=this,e===lastChecked?(e.checked=!1,lastChecked=null):lastChecked=e})})});