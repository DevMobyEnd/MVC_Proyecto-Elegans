<?php
// Asegúrate de incluir el modelo UsuarioModel
require_once '../Models/UsuarioModel.php';

// Crea una instancia del modelo UsuarioModel
$usuarioModel = new UsuarioModel();

// Verifica si el parámetro 'doc' está presente en la URL
if (isset($_GET['doc'])) {
    // Utiliza la función retornadorDato para obtener el valor deseado
    // Suponiendo que 'doc' es un documento y quieres obtener el correo asociado a ese documento
    $valor = $usuarioModel->retornadorDato(2, $_GET['doc']); // Asumiendo que '2' es el descriptor para buscar por documento y obtener el correo
    $valorInput = $valor ? $valor : 'No encontrado';
} else {
    $valorInput = 'Voy a insertar';
}
?>


<div class="wrapper">
    <div class="form-box login">
        <h2>iniciar sesión</h2>
        <form method="post" action="login.php"> <!-- Cambio aquí -->
            <div class="input-box">
                <span class="icon">
                    <ion-icon name="mail"></ion-icon>
                </span>
                <input class="input" name="Gmail" type="email" value="<?php echo $valorInput; ?>" required> <!-- Cambio aquí -->
                <label>Gmail</label>
            </div>
            <div class="input-box">
                <span class="icon">
                    <ion-icon name="lock-closed"></ion-icon>
                </span>
                <input class="input" name="password" type="password"required> 
                <label>Password</label>
            </div>
            <div class="remember-forgot">
                <label for=""><input type="checkbox">Remember me</label>
                <a href="#">Forgot Password?</a>
            </div>
            <button name="btningresar" type="submit" class="btn2">Login</button>
            <div class="login-register">
            <p>Don't have an account?<a href="/./Views/register.php" class="register-link">Register</a></p>
        </form>
    </div>
</div>