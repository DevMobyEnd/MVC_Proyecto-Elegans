<div class="wrapper2">
    <div class="form-box2 register">
        <h2>Registrarse</h2>
        <form method="post" action="register.php"> <!-- Cambio aquí -->
            <div class="input-box">
                <span class="icon">
                    <ion-icon name="person"></ion-icon>
                </span>
                <input class="input" name="Nombres" type="text" required>
                <label>Nombres</label>
            </div>
            <div class="input-box">
                <span class="icon">
                    <ion-icon name="people"></ion-icon>
                </span>
                <input class="input" name="Apellidos" type="text" required>
                <label>Apellidos</label>
            </div>
            <div class="input-box">
                <span class="icon">
                    <ion-icon name="card"></ion-icon>
                </span>
                <input class="input" name="NumeroDocumento" type="text" required>
                <label>Número de Documento</label>
            </div>
            <div class="input-box">
                <span class="icon">
                    <ion-icon name="person-circle"></ion-icon>
                </span>
                <input class="input" name="Usuario" type="text" required>
                <label>Usuario</label>
            </div>
            <div class="input-box">
                <span class="icon">
                    <ion-icon name="mail"></ion-icon>
                </span>
                <input class="input" name="Gmail" type="email" required>
                <label>Correo Electrónico</label>
            </div>
            <div class="input-box">
                <span class="icon">
                    <ion-icon name="lock-closed"></ion-icon>
                </span>
                <input class="input" name="password" type="password" required>
                <label>Contraseña</label>
            </div>
            <button  name="btnregistrar" type="submit" class="btn2">Registrar</button>
            <div class="login-register">
                <p>¿Ya tienes una cuenta?<a href="/./Views/login.php" class="login-link">Iniciar sesión</a></p>
            </div>
        </form>
    </div>
</div>