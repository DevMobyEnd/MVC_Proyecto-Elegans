const express = require('express');
const app = express();
const http = require('http').createServer(app);
const io = require('socket.io')(http);
const mysql = require('mysql2/promise');

// Configuración de la conexión a la base de datos
const dbConfig = {
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'db_Pruebita'
};

io.on('connection', async (socket) => {
  console.log('Un usuario se ha conectado');

  // Obtener mensajes anteriores
  const connection = await mysql.createConnection(dbConfig);
  const [rows] = await connection.execute('SELECT * FROM mensajes ORDER BY fecha_envio DESC LIMIT 50');
  socket.emit('load messages', rows.reverse());

  socket.on('chat message', async (msg) => {
    // Guardar el mensaje en la base de datos
    const [result] = await connection.execute(
      'INSERT INTO mensajes (emisor_id, receptor_id, contenido, es_global) VALUES (?, ?, ?, ?)',
      [msg.emisor_id, msg.receptor_id, msg.contenido, msg.es_global]
    );

    // Emitir el mensaje a todos los clientes
    io.emit('chat message', { ...msg, id: result.insertId });
  });

  socket.on('disconnect', () => {
    console.log('Un usuario se ha desconectado');
  });
});

http.listen(3000, () => {
  console.log('Servidor escuchando en *:3000');
});