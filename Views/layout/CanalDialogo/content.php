<main class="content px-3 py-2">
    <div class="chat-container">
        <!-- Panel de contactos -->
        <div class="contacts-panel">
            <div class="search-container">
                <input type="text" placeholder="Buscar contactos..." class="search-input">
            </div>
            <ul class="contact-list">
                <?php foreach ($data['usuarios'] as $usuario): ?>
                    <li class="contact-item">
                        <img src="https://via.placeholder.com/40" alt="<?php echo htmlspecialchars($usuario['Apodo']); ?>" class="contact-avatar">
                        <div>
                            <p class="font-semibold"><?php echo htmlspecialchars($usuario['Apodo']); ?></p>
                            <p class="text-sm text-gray-500">
                                <span style="color: #10b981;">●</span>
                                <span>En línea</span>
                            </p>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Panel de chat -->
        <div class="chat-panel">
            <div class="messages-container" id="messages">
                <?php foreach ($data['mensajesRecientes'] as $mensaje): ?>
                    <div class="message <?php echo $mensaje['emisor_id'] == $data['usuarioActual'] ? 'sent' : 'received'; ?>">
                        <p class="font-semibold"><?php echo $mensaje['emisor_id'] == $data['usuarioActual'] ? 'Tú' : htmlspecialchars($mensaje['emisor_nombre']); ?></p>
                        <p><?php echo htmlspecialchars($mensaje['contenido']); ?></p>
                        <p class="text-xs text-right mt-1 opacity-75"><?php echo date('H:i', strtotime($mensaje['fecha_envio'])); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="message-input">
                <div class="input-container">
                    <input type="text" id="message-input" placeholder="Escribe un mensaje...">
                    <button id="send-button" class="send-button">Enviar</button>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    const socket = io('http://localhost:3000'); // Cambia a tu dominio o localhost
    const messagesContainer = document.getElementById('messages');
    const messageInput = document.getElementById('message-input');
    const sendButton = document.getElementById('send-button');

    // Cargar mensajes iniciales desde el servidor
    socket.on('load messages', (messages) => {
        messages.forEach(addMessage);
    });

    // Recibir y agregar nuevo mensaje
    socket.on('chat message', (msg) => {
        addMessage(msg);
    });

    // Enviar mensaje cuando se hace clic en el botón
    sendButton.addEventListener('click', () => {
        const message = messageInput.value;
        if (message.trim()) {
            socket.emit('chat message', {
                emisor_id: <?php echo $_SESSION['user_id']; ?>,
                receptor_id: null, // Para mensajes globales
                contenido: message,
                es_global: true
            });
            messageInput.value = ''; // Limpiar campo de entrada
        }
    });

    // Función para agregar mensajes al contenedor de mensajes
    function addMessage(msg) {
        const messageElement = document.createElement('div');
        messageElement.classList.add('message', msg.emisor_id === <?php echo $_SESSION['user_id']; ?> ? 'sent' : 'received');
        messageElement.innerHTML = `
            <p class="font-semibold">${msg.emisor_id === <?php echo $_SESSION['user_id']; ?> ? 'Tú' : 'Otro usuario'}</p>
            <p>${msg.contenido}</p>
            <p class="text-xs text-right mt-1 opacity-75">${new Date(msg.fecha_envio).toLocaleTimeString()}</p>
        `;
        messagesContainer.appendChild(messageElement);
        messagesContainer.scrollTop = messagesContainer.scrollHeight; // Hacer scroll hacia el último mensaje
    }
</script>

<style>
    body, html {
        margin: 0;
        padding: 0;
        height: 100%;
        font-family: Arial, sans-serif;
    }
    .chat-container {
        display: flex;
        height: 100vh;
        background-color: #f3f4f6;
    }
    .contacts-panel {
        width: 25%;
        background-color: white;
        border-right: 1px solid #e5e7eb;
    }
    .search-container {
        padding: 1rem;
        position: relative;
    }
    .search-input {
        width: 100%;
        padding: 0.5rem 2.5rem 0.5rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
    }
    .contact-list {
        overflow-y: auto;
        height: calc(100vh - 80px);
    }
    .contact-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        cursor: pointer;
    }
    .contact-item:hover {
        background-color: #f9fafb;
    }
    .contact-avatar {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        margin-right: 0.75rem;
    }
    .chat-panel {
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .messages-container {
        flex: 1;
        overflow-y: auto;
        padding: 1rem;
    }
    .message {
        max-width: 75%;
        margin-bottom: 1rem;
        padding: 0.75rem;
        border-radius: 0.5rem;
    }
    .message.received {
        background-color: #e5e7eb;
        align-self: flex-start;
    }
    .message.sent {
        background-color: #3b82f6;
        color: white;
        align-self: flex-end;
    }
    .message-input {
        border-top: 1px solid #e5e7eb;
        padding: 1rem;
    }
    .input-container {
        display: flex;
        align-items: center;
    }
    .message-input input {
        flex: 1;
        padding: 0.5rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 9999px;
        margin-right: 0.5rem;
    }
    .send-button {
        background-color: #3b82f6;
        color: white;
        border: none;
        border-radius: 9999px;
        padding: 0.5rem;
        cursor: pointer;
    }
</style>
