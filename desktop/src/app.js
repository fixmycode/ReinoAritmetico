var bodyParser = require('body-parser'),
       express = require('express'),
           app = express(),
            io = require('socket.io'),
          Game = require('../game');

var defaultPort = process.argv[2] || 3000;
var game        = new Game(5, defaultPort);

var server = require('http').createServer(app),
        io = io.listen(server);

io.set('log level', 1);

io.sockets.on('connection', function (socket) {
  console.info(socket.id + ' Just connected');

  socket.on('join', function (newPlayer) {

    var exists = false;

    if ( newPlayer.name === '' || newPlayer.android_id === undefined) {
      socket.emit('error', {msg: "Error! Android ID o Name no presentes"} );
      return;
    }

    if ( ! game.waiting) {
      socket.emit('error', {msg: "Error! No hay un juego actualmente"} );
      return;
    }
    
    if ( game.players.length === game.maxPlayers) {
      socket.emit('error', {msg: "Error! el juego esta lleno"} );
      return;
    }

    for (var socketId in game.players) {
      if (game.players[socketId].android_id === newPlayer.android_id){
        exists = true;
        game.players[socket.id] = game.players[socketId];
        game.players[socket.id].name = newPlayer.name;
        if (socket.id !== socketId) {
          $('#pauseMsg').hide(); // El usuario se cayo y vuelve a unirse
          delete game.players[socketId];
        } 
        break;
      }
    }

    if ( ! exists ) {
      game.players[socket.id] = newPlayer;
      socket.emit('info', {msg: 'Te as unido a la partida como ' + newPlayer.name});
      socket.broadcast.emit('info', {msg: newPlayer.name + ' se ha unido a la partida'});
    }else {
      socket.emit('info', {msg: "Te has vuelto a unir como " + newPlayer.name} );
    }
    showPlayers();
  });

  socket.on('leave', function(player) {
    if (game.players[socket.id].android_id === player.android_id) {
      socket.broadcast.emit('info', {msg: game.players[socket.id].name + ' ha abandonado la partida'});
      status(game.players[socket.id].name + ' ha dejado la partida');
      delete game.players[socket.id];
      showPlayers();
      socket.emit('info', {msg: 'Has abandonado la partida'});
    }else {
      socket.emit('error', {msg: "Error! jugador no encontrado"});
    }

    // Si no quedan jugadores
    if (Object.keys(game.players).length === 0 && game.playing) {
      game.waiting = false;
      game.end(game, changeState);
    }
  });

  socket.on('disconnect', function(data) {
    console.info(socket.id + ' Just disconected');

    if (game.playing && game.players[socket.id] !== undefined) {
      console.log('Juego Pausado! esperando a ' + game.players[socket.id].name);
      $('#pauseMsg').show();
    }
  });
});

server.listen(defaultPort);