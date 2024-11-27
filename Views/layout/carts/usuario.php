<style>
    .user-card {
    background-color: orange;
    border-radius: 10px;
    padding: 20px;
    display: flex;
    align-items: center;
    margin: 20px 0;
    width: 300px;
}

.user-image img {
    width: 80px;
    height: 80px;
    border-radius: 50%; /* Crea el efecto de círculo */
    object-fit: cover; /* Asegura que la imagen cubra el espacio sin deformarse */
    border: 2px solid white; /* Opcional: agrega un borde blanco alrededor de la imagen */
    margin-right: 20px;
}

.user-info h3, .user-info p {
    margin: 0;
    color: white; /* Cambia el color del texto si es necesario */
}
</style>
<div class="user-card">
    <div class="user-image">
        <img src="ruta_a_la_imagen_del_usuario.jpg" alt="Imagen del Usuario">
    </div>
    <div class="user-info">
        <h3>Nombre del Usuario</h3>
        <p>Documento: 1116233418</p>
        <p>Fecha de Nacimiento: 9/06/2004</p>
        <!-- Agrega más información según necesites -->
    </div>
</div>
