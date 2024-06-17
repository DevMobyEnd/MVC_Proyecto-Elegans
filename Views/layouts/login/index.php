


<div class="wrapper">
    <div class="form-box login">
        <h2>iniciar sesión</h2>
        <form method="post" action="login.php"> <!-- Cambio aquí -->
            <div class="input-box">
                <span class="icon">
                    <ion-icon name="mail"></ion-icon>
                </span>
                <input class="input" name="Gmail" type="email" required> <!-- Cambio aquí -->
                <label>Gmail</label>
            </div>
            <div class="input-box">
                <span class="icon">
                    <ion-icon name="lock-closed"></ion-icon>
                </span>
                <input class="input" name="password" type="password" required> 
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