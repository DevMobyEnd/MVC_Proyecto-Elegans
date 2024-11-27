<?php
// Asegúrate de que la sesión esté iniciada y que tienes acceso al ID del usuario
session_start();
$emisorId = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : null;

if (!$emisorId) {
    // Redirigir al usuario a la página de inicio de sesión si no está autenticado
    header('Location: /login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Estilos generales */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            background-color: #f0f2f5;
        }

        #chat-container {
            display: flex;
            flex-grow: 1;
            height: 100%;
        }

        /* Lista de usuarios */
        #users-list {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            padding: 10px;
            overflow-y: auto;
        }

        .user {
            padding: 10px;
            margin: 5px 0;
            background-color: #34495e;
            border-radius: 10px;
            cursor: pointer;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .user:hover {
            background-color: #3498db;
        }

        #global-chat {
            background-color: #3498db;
            padding: 10px;
            margin: 5px 0;
            text-align: center;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #global-chat:hover {
            background-color: #2980b9;
        }

        /* Área de mensajes */
        #chat-area {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            background-color: #e5ddd5;
            padding: 20px;
            border-left: 2px solid #d1d8e0;
        }

        #chat-messages {
            flex-grow: 1;
            overflow-y: auto;
            padding: 10px;
            background-color: #e5ddd5;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
        }

        .message {
            max-width: 70%;
            padding: 10px 15px;
            margin: 5px 0;
            border-radius: 15px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            word-wrap: break-word;
        }

        .emisor {
            align-self: flex-end;
            background-color: #dcf8c6;
            color: #000;
            border-bottom-right-radius: 5px;
        }

        .receptor {
            align-self: flex-start;
            background-color: #fff;
            color: #000;
            border-bottom-left-radius: 5px;
        }

        /* Input de mensajes */
        #message-input-container {
            display: flex;
            align-items: center;
            background-color: #f0f0f0;
            padding: 10px;
            border-radius: 25px;
        }

        #message-input {
            flex-grow: 1;
            padding: 10px 15px;
            border: none;
            border-radius: 20px;
            margin-right: 10px;
            font-size: 16px;
        }

        #send-button {
            padding: 10px 20px;
            border: none;
            background-color: #3498db;
            color: white;
            border-radius: 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #send-button:hover {
            background-color: #2980b9;
        }

        /* Estilos para las reacciones */
        .reactions {
            margin-top: 5px;
            display: flex;
            justify-content: flex-end;
        }

        .reaction-btn {
            background: none;
            border: none;
            border-radius: 50%;
            padding: 5px;
            margin-left: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 14px;
        }

        .reaction-btn.active {
            background-color: rgba(0, 0, 0, 0.1);
        }

        .reaction-btn:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }


        #search-container {
            padding: 10px;
            background-color: #34495e;
        }

        #user-search {
            width: 100%;
            padding: 5px;
            border-radius: 5px;
            border: none;
        }

        #search-results {
            margin-top: 10px;
        }

        .search-result {
            padding: 5px;
            background-color: #2c3e50;
            margin-bottom: 5px;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-result:hover {
            background-color: #3498db;
        }

        /* Nuevos estilos para los indicadores de estado */
        .message {
            /* ... (estilos anteriores) ... */
            position: relative;
        }

        .message-status {
            position: absolute;
            bottom: 4px;
            right: 8px;
            font-size: 12px;
            color: #8696a0;
            display: flex;
            align-items: center;
            gap: 2px;
        }

        .message-time {
            margin-right: 4px;
            font-size: 11px;
            color: #667781;
            white-space: nowrap;
            /* Evita que el tiempo se rompa en múltiples líneas */
        }

        .checkmark {
            display: inline-block;
            transform: scale(0.8);
        }

        .checkmark.single::after {
            content: "✓";
        }

        .checkmark.double::after {
            content: "✓✓";
        }

        .checkmark.delivered {
            color: #53bdeb;
        }

        /* Ajuste para el contenedor de mensajes */
        .message-content {
            margin-right: 20px;
            /* Espacio para los checkmarks */
            margin-bottom: 15px;
            /* Espacio para el tiempo */
            word-wrap: break-word;
        }


        /* Botón de menú para móviles */
        .menu-button {
            display: none;
            position: fixed;
            left: 10px;
            top: 10px;
            z-index: 1000;
            background: #2c3e50;
            border: none;
            color: white;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .menu-button:hover {
            background: #3498db;
        }

        .menu-icon {
            font-size: 20px;
        }

        /* Botón de salida */
        .exit-button {
            display: flex;
            align-items: center;
            background-color: #e74c3c;
            color: white;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .exit-button:hover {
            background-color: #c0392b;
        }

        .exit-icon {
            margin-right: 10px;
            font-size: 20px;
        }

        /* Modificaciones al sidebar */
        #users-list {
            position: relative;
            transition: transform 0.3s ease;
        }

        /* Media query para pantallas pequeñas */
        @media screen and (max-width: 630px) {
            .menu-button {
                display: block;
            }

            #users-list {
                position: fixed;
                left: 0;
                top: 0;
                height: 100vh;
                transform: translateX(-100%);
                z-index: 999;
            }

            #users-list.active {
                transform: translateX(0);
            }

            #chat-area {
                margin-left: 0;
                width: 100%;
            }
        }

        /* Overlay para cuando el menú está abierto en móvil */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 998;
        }

        .sidebar-overlay.active {
            display: block;
        }
    </style>
</head>

<body>
    <div id="chat-container">
        <!-- Botón de menú para móviles -->
        <button id="menu-toggle" class="menu-button">
            <span class="menu-icon">☰</span>
        </button>

        <!-- Sidebar modificado -->
        <div id="users-list" class="sidebar">
            <!-- Botón de salida en la parte superior -->
            <div id="exit-button" onclick="window.location.href='index.php'" class="exit-button">
                <span class="exit-icon">🚪</span>
                <span>Salir</span>
            </div>

            <div id="search-container">
                <input type="text" id="user-search" placeholder="Buscar usuario...">
                <div id="search-results"></div>
            </div>
            <div id="global-chat">Canal Global</div>
            <div id="private-chats"></div>
        </div>

        <!-- Área de mensajes -->
        <div id="chat-area">
            <div id="chat-messages">
                <!-- Mensajes dinámicos -->
            </div>
            <div id="message-input-container">
                <input type="text" id="message-input" placeholder="Escribe un mensaje..." autocomplete="off">
                <button id="send-button">Enviar</button>
            </div>
        </div>
    </div>

    <script>
        let currentChatType = 'global';
        let currentUserId = <?php echo json_encode($emisorId); ?>; // ID del usuario actual (emisor)
        let selectedUserId = null; // Inicialmente no hay usuario seleccionado
        let privateChats = [];

        // Definir la base URL para las peticiones API
        const baseUrl = '<?php echo rtrim(dirname($_SERVER['PHP_SELF']), '/\\'); ?>';

        // Función para manejar errores
        function handleError(error, message) {
            console.error(message, error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: message,
            });
        }

        // Cargar la lista de usuarios con los que se ha tenido conversaciones privadas
        async function loadUsers() {
            try {
                const response = await fetch(`${baseUrl}/api/users.php`);
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                const users = await response.json();
                privateChats = users.filter(user => user.hasConversation);
                updatePrivateChats();
            } catch (error) {
                handleError(error, 'Error al cargar usuarios');
            }
        }

        function updatePrivateChats() {
            const privateChatsContainer = document.getElementById('private-chats');
            privateChatsContainer.innerHTML = privateChats.map(user => `
                <div class="user" role="listitem" tabindex="0" onclick="openPrivateChat(${user.id})" aria-label="Abrir chat privado con ${user.Apodo}">${user.Apodo}</div>
            `).join('');
        }

        // Implementación de debounce para la función de búsqueda
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // receptor de eventos para la entrada de búsqueda:
        document.getElementById('user-search').addEventListener('input', debounce(searchUsers, 500));

        //función de rebote para evitar demasiadas peticiones:
        function debounce(func, delay) {
            let debounceTimer;
            return function() {
                const context = this;
                const args = arguments;
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => func.apply(context, args), delay);
            }
        }

        async function searchUsers() {
            const query = document.getElementById('user-search').value.trim();
            if (query.length === 0) { // Cambia esto a 0 para que se permitan búsquedas con cualquier número de caracteres
                document.getElementById('search-results').innerHTML = '';
                return;
            }

            try {
                const response = await fetch(`api/search-users.php?query=${encodeURIComponent(query)}`);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const users = await response.json();
                displaySearchResults(users);
            } catch (error) {
                console.error('Error al buscar usuarios:', error);
                document.getElementById('search-results').innerHTML = '<p>Error al buscar usuarios. Por favor, inténtelo de nuevo.</p>';
            }
        }

        function displaySearchResults(users) {
            const searchResults = document.getElementById('search-results');
            searchResults.innerHTML = users.map(user => `
        <div class="search-result" onclick="openPrivateChat(${user.id})">${user.Apodo}</div>
    `).join('');
        }


        function startPrivateChat(userId) {
            var_dump($result); // Agrego esto para depurar
            // Verificar si ya existe un chat privado con este usuario
            if (!privateChats.some(chat => chat.id === userId)) {
                // Agregar el nuevo chat privado
                privateChats.push({
                    id: userId,
                    Apodo: 'Usuario ' + userId
                }); // Reemplazo 'Usuario ' con el apodo real
                updatePrivateChats();
            }
            openPrivateChat(userId);
        }

        function formatTimestamp(timestamp) {
            if (!timestamp) return '';

            try {
                let date;

                // Si el timestamp es una fecha MySQL (YYYY-MM-DD HH:MM:SS)
                if (typeof timestamp === 'string' && timestamp.includes('-')) {
                    date = new Date(timestamp);
                }
                // Si es un timestamp numérico
                else if (typeof timestamp === 'number') {
                    date = new Date(timestamp);
                }
                // Si ya es una hora formateada o es inválido
                else {
                    return timestamp;
                }

                // Verificar si la fecha es válida
                if (isNaN(date.getTime())) {
                    return '';
                }

                // Formatear la hora en español colombiano (12 horas)
                let hours = date.getHours();
                let minutes = date.getMinutes();
                let ampm = hours >= 12 ? 'PM' : 'AM';

                // Convertir a formato 12 horas
                hours = hours % 12;
                hours = hours ? hours : 12; // si es 0, convertir a 12

                // Agregar cero inicial a los minutos si son menos de 10
                minutes = minutes < 10 ? '0' + minutes : minutes;

                // Retornar el formato: "h:mm AM/PM"
                return `${hours}:${minutes} ${ampm}`;

            } catch (e) {
                console.error('Error formateando timestamp:', e);
                return '';
            }
        }

        // Función auxiliar para el timestamp de mensajes nuevos
        function getCurrentTime() {
            const now = new Date();
            return formatTimestamp(now);
        }

        // Cargar los mensajes (chat global o privado)
        function loadMessages() {
            try {
                if (!currentChatType) {
                    throw new Error('Tipo de chat no especificado');
                }

                let url = `/api/messages.php?chatType=${currentChatType}`;
                if (currentChatType === 'private' && selectedUserId) {
                    url += `&userId=${selectedUserId}`;
                }

                fetch(url)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok: ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        const messages = Array.isArray(data.messages) ? data.messages : [];
                        const chatMessages = document.getElementById('chat-messages');

                        if (messages.length === 0) {
                            chatMessages.innerHTML = '<p>No hay mensajes aún. ¡Sé el primero en escribir!</p>';
                        } else {
                            chatMessages.innerHTML = messages.map(msg => `
                                <div class="message ${msg.emisor_id === currentUserId ? 'emisor' : 'receptor'}" data-id="${msg.id}">
                                    <div class="message-content">
                                        <strong>${msg.emisor_nombre || 'Usuario'}</strong><br>
                                        ${msg.contenido}
                                    </div>
                                    ${msg.emisor_id === currentUserId ? `
                                        <div class="message-status">
                                            <span class="message-time">${formatTimestamp(msg.fecha_envio)}</span>
                                            <span class="checkmark double delivered"></span>
                                        </div>
                                    ` : `
                                        <div class="message-status">
                                            <span class="message-time">${formatTimestamp(msg.fecha_envio)}</span>
                                        </div>
                                    `}
                                    <div class="reactions">
                                        <button onclick="react(${msg.id}, 'like')" class="reaction-btn ${msg.user_reaction === 'like' ? 'active' : ''}" aria-label="Me gusta">
                                            👍 <span class="like-count">${msg.likes || 0}</span>
                                        </button>
                                        <button onclick="react(${msg.id}, 'dislike')" class="reaction-btn ${msg.user_reaction === 'dislike' ? 'active' : ''}" aria-label="No me gusta">
                                            👎 <span class="dislike-count">${msg.dislikes || 0}</span>
                                        </button>
                                    </div>
                                </div>
                            `).join('');
                        }

                        // Mantener el scroll en la posición correcta
                        const wasAtBottom = chatMessages.scrollHeight - chatMessages.clientHeight <= chatMessages.scrollTop + 1;
                        if (wasAtBottom) {
                            chatMessages.scrollTop = chatMessages.scrollHeight;
                        }
                    })
                    .catch(error => {
                        console.error('Error al cargar mensajes:', error);
                        document.getElementById('chat-messages').innerHTML = '<p>Error al cargar los mensajes. Por favor, intenta de nuevo.</p>';
                    });
            } catch (error) {
                console.error('Error en loadMessages:', error);
            }
        }




        async function react(messageId, reactionType) {
            // Cambiar inmediatamente la UI para mejorar la percepción de velocidad
            updateReactionUIOptimistically(messageId, reactionType);

            try {
                // Enviar la reacción al servidor
                const response = await fetch('/api/react-message.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        mensaje_id: messageId,
                        usuario_id: currentUserId,
                        tipo_reaccion: reactionType
                    })
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const result = await response.json();

                if (result.success) {
                    // Actualiza la UI con los valores correctos después de recibir la respuesta
                    updateReactionUI(messageId, reactionType, result.reacciones);
                } else {
                    console.error('Error al procesar la reacción:', result.error);
                }
            } catch (error) {
                console.error('Error al enviar la reacción:', error);
                // Si hay un error, revertir la UI a su estado anterior
                revertReactionUI(messageId);
            }
        }

        function updateReactionUIOptimistically(messageId, reactionType) {
            const messageElement = document.querySelector(`.message[data-id="${messageId}"]`);
            if (messageElement) {
                const likeBtn = messageElement.querySelector('.reaction-btn:first-child');
                const dislikeBtn = messageElement.querySelector('.reaction-btn:last-child');

                // Cambiar las clases de los botones según la reacción
                likeBtn.classList.toggle('active', reactionType === 'like');
                dislikeBtn.classList.toggle('active', reactionType === 'dislike');

                // Actualizar los contadores (incrementar/desincrementar optimísticamente)
                const likeCountElement = likeBtn.querySelector('.like-count');
                const dislikeCountElement = dislikeBtn.querySelector('.dislike-count');

                let likeCount = parseInt(likeCountElement.textContent);
                let dislikeCount = parseInt(dislikeCountElement.textContent);

                if (reactionType === 'like') {
                    if (!likeBtn.classList.contains('active')) {
                        likeCountElement.textContent = likeCount - 1;
                    } else {
                        likeCountElement.textContent = likeCount + 1;
                        if (dislikeBtn.classList.contains('active')) {
                            dislikeCountElement.textContent = dislikeCount - 1;
                            dislikeBtn.classList.remove('active');
                        }
                    }
                } else if (reactionType === 'dislike') {
                    if (!dislikeBtn.classList.contains('active')) {
                        dislikeCountElement.textContent = dislikeCount - 1;
                    } else {
                        dislikeCountElement.textContent = dislikeCount + 1;
                        if (likeBtn.classList.contains('active')) {
                            likeCountElement.textContent = likeCount - 1;
                            likeBtn.classList.remove('active');
                        }
                    }
                }
            }
        }

        function updateReactionUI(messageId, reactionType, newReactions) {
            const messageElement = document.querySelector(`.message[data-id="${messageId}"]`);
            if (messageElement) {
                const likeBtn = messageElement.querySelector('.reaction-btn:first-child');
                const dislikeBtn = messageElement.querySelector('.reaction-btn:last-child');

                // Actualizar los contadores con los valores reales del servidor
                likeBtn.querySelector('.like-count').textContent = newReactions.likes;
                dislikeBtn.querySelector('.dislike-count').textContent = newReactions.dislikes;
            }
        }

        function revertReactionUI(messageId) {
            const messageElement = document.querySelector(`.message[data-id="${messageId}"]`);
            if (messageElement) {
                const likeBtn = messageElement.querySelector('.reaction-btn:first-child');
                const dislikeBtn = messageElement.querySelector('.reaction-btn:last-child');

                // Restaurar el estado anterior de los botones
                likeBtn.classList.remove('active');
                dislikeBtn.classList.remove('active');
            }
        }




        // Abrir el chat global
        function openGlobalChat() {
            currentChatType = 'global';
            selectedUserId = <?php echo json_encode($emisorId); ?>;
            loadMessages();
        }

        // Abrir un chat privado
        function openPrivateChat(userId) {
            selectedUserId = 15;
            currentChatType = 'private';

            if (!privateChats.some(user => user.id === userId)) {
                fetch(`api/messages.php?id=${userId}`)
                    .then(response => response.json())
                    .then(user => {
                        privateChats.push(user);
                        updatePrivateChats();
                    })
                    .catch(error => handleError(error, 'Error al obtener información del usuario'));
            }

            // Clear search results and input
            document.getElementById('search-results').innerHTML = '';
            document.getElementById('user-search').value = '';
            loadMessages(); // Agrega esto para cargar los mensajes
        }

        // Enviar mensaje
        async function sendMessage() {
            try {
                const messageInput = document.getElementById('message-input');
                const message = messageInput.value.trim();

                if (!message) return;

                // Crear y mostrar el mensaje inmediatamente con estado "enviando"
                const tempId = 'msg-' + Date.now();
                const currentTime = getCurrentTime(); // Usar la nueva función

                appendMessageToChat({
                    id: tempId,
                    emisor_id: currentUserId,
                    contenido: message,
                    estado: 'sending',
                    fecha_envio: currentTime
                });

                messageInput.value = '';

                const response = await fetch('/api/send-message.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        emisor_id: currentUserId,
                        receptor_id: selectedUserId,
                        contenido: message,
                        es_global: currentChatType === 'global' ? 1 : 0
                    })
                });

                const result = await response.json();

                if (result.success) {
                    updateMessageStatus(tempId, 'sent');
                    setTimeout(() => {
                        updateMessageStatus(tempId, 'delivered');
                    }, 1000);
                } else {
                    updateMessageStatus(tempId, 'error');
                }
            } catch (error) {
                console.error('Error al enviar el mensaje:', error);
                const messageElement = document.getElementById(tempId);
                if (messageElement) {
                    updateMessageStatus(tempId, 'error');
                }
            }
        }

        function appendMessageToChat(message) {
            const chatMessages = document.getElementById('chat-messages');
            const messageElement = document.createElement('div');
            messageElement.className = `message emisor`;
            messageElement.id = message.id;

            messageElement.innerHTML = `
                <div class="message-content">
                    <strong>${message.emisor_nombre || 'Tú'}</strong><br>
                    ${message.contenido}
                </div>
                <div class="message-status">
                    <span class="message-time">${message.timestamp}</span>
                    <span class="checkmark ${message.estado === 'sending' ? 'single' : ''}"></span>
                </div>
            `;

            chatMessages.appendChild(messageElement);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function updateMessageStatus(messageId, status) {
            const messageElement = document.getElementById(messageId);
            if (!messageElement) return;

            const checkmark = messageElement.querySelector('.checkmark');

            switch (status) {
                case 'sending':
                    checkmark.className = 'checkmark single';
                    break;
                case 'sent':
                    checkmark.className = 'checkmark double';
                    break;
                case 'delivered':
                    checkmark.className = 'checkmark double delivered';
                    break;
                case 'error':
                    messageElement.style.color = '#e74c3c';
                    break;
            }
        }






        // Event listeners
        document.getElementById('send-button').addEventListener('click', sendMessage);
        document.getElementById('message-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') sendMessage();
        });
        document.getElementById('user-search').addEventListener('input', function(e) {
            const query = e.target.value.trim();
            if (query.length > 0) {
                searchUsers(query);
            } else {
                document.getElementById('search-results').innerHTML = '';
            }
        });

        // Cargar datos iniciales
        loadUsers();
        loadMessages();

        // Actualizar mensajes periódicamente
        setInterval(loadMessages, 3000);






        // Crear el overlay para el sidebar
        const overlay = document.createElement('div');
        overlay.className = 'sidebar-overlay';
        document.body.appendChild(overlay);

        // Función para manejar el toggle del menú
        function toggleSidebar() {
            const sidebar = document.getElementById('users-list');
            const menuButton = document.getElementById('menu-toggle');
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        // Event listeners para el menú
        document.getElementById('menu-toggle').addEventListener('click', toggleSidebar);

        // Cerrar el sidebar cuando se hace clic en el overlay
        overlay.addEventListener('click', toggleSidebar);

        // Cerrar el sidebar cuando la pantalla se hace más grande
        window.addEventListener('resize', () => {
            if (window.innerWidth > 630) {
                const sidebar = document.getElementById('users-list');
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            }
        });

        // Opcionalmente, cerrar el sidebar cuando se selecciona un chat en pantallas pequeñas
        function openPrivateChat(userId) {
            // Código existente de openPrivateChat...

            // Añadir esta parte:
            if (window.innerWidth <= 630) {
                toggleSidebar();
            }
        }

        // También modificar la función openGlobalChat
        function openGlobalChat() {
            // Código existente de openGlobalChat...

            // Añadir esta parte:
            if (window.innerWidth <= 630) {
                toggleSidebar();
            }
        }
    </script>
</body>

</html>