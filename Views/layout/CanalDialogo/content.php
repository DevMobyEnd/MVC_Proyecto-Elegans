<main id="profileContent" class="content px-3 py-2">
    <div class="container-fluid">
        <div class="mb-3">
            <h4>Chat de Usuario</h4>
        </div>
        <div class="row">
            <div class="col-12 col-md-4 d-flex">
                <!-- Lista de contactos -->
                <div class="card flex-fill border-0">
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <a href="#" class="list-group-item list-group-item-action active">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">DJ Techno</h5>
                                    <small>Ahora</small>
                                </div>
                                <p class="mb-1">Última solicitud: Thunderstruck</p>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">DJ Pop</h5>
                                    <small>3 días</small>
                                </div>
                                <p class="mb-1">Última solicitud: Shape of You</p>
                            </a>
                            <!-- Más contactos aquí -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-8 d-flex">
                <!-- Ventana de chat -->
                <div class="card flex-fill border-0">
                    <div class="card-body d-flex flex-column" style="height: 600px;">
                        <!-- Encabezado del chat -->
                        <div class="chat-header mb-3">
                            <h5>Chat con DJ Techno</h5>
                        </div>
                        <!-- Mensajes del chat -->
                        <div class="chat-messages flex-grow-1 overflow-auto mb-3" style="height: 400px;">
                            <div class="message received">
                                <p>Hola, ¿qué canción te gustaría escuchar?</p>
                                <small>10:30 AM</small>
                            </div>
                            <div class="message sent">
                                <p>¡Hola! Me gustaría escuchar Thunderstruck de AC/DC</p>
                                <small>10:32 AM</small>
                            </div>
                            <div class="message received">
                                <p>¡Excelente elección! La pondré en la lista.</p>
                                <small>10:33 AM</small>
                            </div>
                            <!-- Más mensajes aquí -->
                        </div>
                        <!-- Formulario para enviar mensajes -->
                        <form class="chat-input">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Escribe un mensaje...">
                                <button class="btn btn-primary" type="submit">Enviar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<style>
    .chat-messages {
        display: flex;
        flex-direction: column;
    }
    .message {
        max-width: 70%;
        margin-bottom: 10px;
        padding: 10px;
        border-radius: 10px;
    }
    .received {
        align-self: flex-start;
        background-color: #f1f0f0;
    }
    .sent {
        align-self: flex-end;
        background-color: #dcf8c6;
    }
    .message p {
        margin-bottom: 5px;
    }
    .message small {
        display: block;
        text-align: right;
        color: #999;
    }
</style>
<!-- Modal de Términos y Condiciones -->
<div class="modal fade" id="terminosCondicionesModal" tabindex="-1" aria-labelledby="terminosCondicionesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="terminosCondicionesModalLabel">Términos y Condiciones de Uso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <h6>Introducción</h6>
                <p>1.1 Bienvenido a Elegans. Estos términos y condiciones rigen el uso de nuestra plataforma de solicitudes de música en tiempo real.</p>
                <h6>Uso de la Plataforma</h6>
                <p>2.1 Al acceder y utilizar Elegans, aceptas cumplir con estos términos y cualquier otra política que podamos publicar.</p>
                <p>2.2 Solo puedes utilizar nuestra plataforma de acuerdo con todas las leyes y regulaciones aplicables.</p>
                <h6>Solicitudes de Música</h6>
                <p>3.1 Elegans permite a los usuarios enviar solicitudes de canciones a DJs en bares o discotecas participantes.</p>
                <p>3.2 Nos reservamos el derecho de moderar y filtrar las solicitudes para mantener un ambiente adecuado y seguro.</p>
                <h6>Propiedad Intelectual</h6>
                <p>4.1 Todos los derechos de propiedad intelectual relacionados con Elegans, incluidos derechos de autor y marcas comerciales, pertenecen a nosotros o a nuestros licenciatarios.</p>
                <h6>Privacidad</h6>
                <p>5.1 La privacidad de los usuarios es importante para nosotros. Consulta nuestra Política de Privacidad para obtener información sobre cómo recopilamos, usamos y protegemos tus datos personales.</p>
                <h6>Limitación de Responsabilidad</h6>
                <p>6.1 No somos responsables de ningún daño directo, indirecto, incidental, especial, consecuente o punitivo derivado del uso de Elegans.</p>
                <h6>Modificaciones</h6>
                <p>7.1 Nos reservamos el derecho de modificar estos términos y condiciones en cualquier momento. Las modificaciones entrarán en vigor al ser publicadas en la plataforma.</p>
                <h6>Legislación Aplicable</h6>
                <p>8.1 Estos términos y condiciones se rigen por las leyes vigentes en [País o Estado].</p>
                <h6>Contacto</h6>
                <p>9.1 Para cualquier pregunta sobre estos términos y condiciones, por favor contáctanos a Admin@Elegans.com.</p>
            </div>
        </div>
    </div>
</div>