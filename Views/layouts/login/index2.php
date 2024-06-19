<div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Iniciar Sesión
                    </div>
                    <div class="card-body">
                        <form action="/./Views/pruebalogins.php" method="POST">
                            <div class="form-group">
                                <label for="nombre">Documento </label>
                                <input type="text" class="form-control" id="documento" name="documento " required>
                            </div>
                            <div class="form-group">
                                <label for="contraseña">contraseña</label>
                                <input type="password" class="form-control" id="password" name="contraseña" required>
                            </div>
                            <button type="submit" name="btningresar" class="btn btn-primary">Ingresar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>