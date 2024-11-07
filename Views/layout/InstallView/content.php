<div class="vertical-center">
    <div class="card">
        <div class="card-body">
            <form id="dbConfigForm" method="POST" action="/Views/InstallView.php">
                <section>
                    <h1 class="welcome-title">
                        <span class="static-text">Configuración de Base de Datos</span>
                    </h1>
                    <div class="form-group d-flex flex-column align-items-center position-relative">
                        <input type="text" placeholder="Host de la Base de Datos" class="form-control form-control-lg" name="db_host" id="db_host" required>
                        <label class="form-label long-label">
                            <ion-icon name="server-outline"></ion-icon> Host de la Base de Datos
                        </label>
                    </div>
                    <div class="form-group d-flex flex-column align-items-center position-relative">
                        <input type="text" placeholder="Nombre de la Base de Datos" class="form-control form-control-lg" name="db_name" id="db_name" required>
                        <label class="form-label long-label">
                            <ion-icon name="folder-outline"></ion-icon> Nombre de la Base de Datos
                        </label>
                    </div>
                    <div class="form-group d-flex flex-column align-items-center position-relative">
                        <input type="text" placeholder="Usuario de la Base de Datos" class="form-control form-control-lg" name="db_username" id="db_username" required>
                        <label class="form-label long-label">
                            <ion-icon name="person-outline"></ion-icon> Usuario de la Base de Datos
                        </label>
                    </div>
                    <div class="form-group d-flex flex-column align-items-center position-relative">
                        <input type="password" placeholder="Contraseña de la Base de Datos" class="form-control form-control-lg" name="db_password" id="db_password">
                        <label class="form-label long-label">
                            <ion-icon name="lock-closed-outline"></ion-icon> Contraseña de la Base de Datos
                        </label>
                    </div>
                    <div class="d-flex justify-content-center gap-3">
                        <button type="submit" name="guardar" class="edit-profile-btn2 btn-lg fw-semibold">Guardar Configuración</button>
                        <a href="/Index.php" class="btn btn-secondary btn-lg fw-semibold">Omitir</a>
                    </div>
           
                </section>
            </form>
        </div>
    </div>
</div>

<style>
    .vertical-center {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .card {
        width: 100%;
        max-width: 800px;
    }
</style>

<?php
if (isset($_GET['error'])) {
    echo '<div class="alert alert-danger">' . htmlspecialchars($_GET['error']) . '</div>';
}
?>