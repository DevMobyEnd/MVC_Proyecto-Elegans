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
    </style>
</head>

<body>
    <div id="chat-container">
        <!-- Columna de usuarios y chat global -->
        <div id="users-list">
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
        let currentUserId = 17; // Asegúrate de que este ID sea correcto
        let selectedUserId = 15; // Asegúrate de que este ID sea correcto
        let privateChats = [];

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
                const response = await fetch('/api/users.php');
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
                const response = await fetch(`/api/search-users.php?query=${encodeURIComponent(query)}`);
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
            var_dump($result); // Agrega esto para depurar
            // Verificar si ya existe un chat privado con este usuario
            if (!privateChats.some(chat => chat.id === userId)) {
                // Agregar el nuevo chat privado
                privateChats.push({
                    id: userId,
                    Apodo: 'Usuario ' + userId
                }); // Reemplaza 'Usuario ' con el apodo real
                updatePrivateChats();
            }
            openPrivateChat(userId);
        }

        // Cargar los mensajes (chat global o privado)
        async function loadMessages() {
            try {
                // Asegúrate de que currentChatType y selectedUserId estén definidos
                if (!currentChatType) {
                    throw new Error('Tipo de chat no especificado');
                }

                let url = `/api/messages.php?chatType=${currentChatType}`;
                if (currentChatType === 'private' && selectedUserId) {
                    url += `&userId=${selectedUserId}`;
                }

                const response = await fetch(url);

                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.statusText);
                }

                const data = await response.json();

                // Verifica si la respuesta contiene un error
                if (data.error) {
                    throw new Error(data.error);
                }

                // Asegúrate de que data.messages sea un array
                const messages = Array.isArray(data.messages) ? data.messages : [];

                const chatMessages = document.getElementById('chat-messages');

                if (messages.length === 0) {
                    chatMessages.innerHTML = '<p>No hay mensajes aún. ¡Sé el primero en escribir!</p>';
                } else {
                    chatMessages.innerHTML = messages.map(msg => `
                        <div class="message ${msg.emisor_id === currentUserId ? 'emisor' : 'receptor'}" data-id="${msg.id}">
                            <strong>${msg.emisor_nombre || 'Usuario'}<br></strong> ${msg.contenido}
                            <div class="reactions">
                                <button onclick="react(${msg.id}, 'like')" class="reaction-btn ${msg.user_reaction === 'like' ? 'active' : ''}" aria-label="Me gusta">
                                    👍 <span class="like-count">${msg.likes}</span>
                                </button>
                                <button onclick="react(${msg.id}, 'dislike')" class="reaction-btn ${msg.user_reaction === 'dislike' ? 'active' : ''}" aria-label="No me gusta">
                                    👎 <span class="dislike-count">${msg.dislikes}</span>
                                </button>
                            </div>
                        </div>
                    `).join('');
                }

                // Guardar la posición actual
                const wasAtBottom = chatMessages.scrollHeight - chatMessages.clientHeight <= chatMessages.scrollTop + 1;
                // Desplazar hacia abajo solo si estaba al final
                if (wasAtBottom) {
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }
            } catch (error) {
                console.error('Error al cargar mensajes:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudieron cargar los mensajes: ' + error.message
                });
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
            selectedUserId = 17;
            loadMessages();
        }

        // Abrir un chat privado
        function openPrivateChat(userId) {
            selectedUserId = 15;
            currentChatType = 'private';

            if (!privateChats.some(user => user.id === userId)) {
                fetch(`/api/messages.php?id=${userId}`)
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
                const message = messageInput.value.trim(); // Elimina espacios en blanco

                if (message) {
                    const response = await fetch('/api/send-message.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            emisor_id: currentUserId, // Asegúrate de tener el ID del emisor
                            receptor_id: selectedUserId,
                            contenido: message,
                            es_global: currentChatType === 'global' ? 1 : 0
                        })
                    });

                    const result = await response.json();

                    if (result.success) {
                        messageInput.value = ''; // Limpiar el campo de entrada
                        loadMessages(); // Recargar los mensajes
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: result.message,
                        });
                    } else {
                        throw new Error(result.error);
                    }
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'El mensaje no puede estar vacío.',
                    });
                }
            } catch (error) {
                console.error('Error al enviar el mensaje:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al enviar el mensaje: ' + error.message,
                });
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
    </script>
</body>

</html>