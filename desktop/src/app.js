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

    if ( newPlayer.name === undefined || newPlayer.android_id === undefined) {
      socket.emit('error', {msg: "Error! Android ID o Name no presentes"} );
      return;
    }

    if ( ! game.playing) {
      socket.emit('error', {msg: "Error! No hay un juego actualmente"} );
      return;
    }
    
    if ( game.players.length === game.maxPlayers) {
      socket.emit('error', {msg: "Error! el juego esta lleno"} );
      return;
    }

    for (var i = 0; i < game.players.length; i++) {
      if (game.players[i].name === newPlayer.name || game.players[i].android_id === newPlayer.android_id) {
        exists = true;
        break;
      }
    }
    
    if ( ! exists ) {
      game.players.push(newPlayer);
      showPlayers();
      socket.emit('info', {msg: 'Te as unido a la partida como ' + newPlayer.name});
      socket.broadcast.emit('info', {msg: newPlayer.name + ' se ha unido a la partida'});
    }else {
      socket.emit('error', {msg: "Error! The player\'s name or Android ID already exists"} );
    }
  });

  socket.on('leave', function(player) {
    var index;

    for (var i = 0; i < game.players.length; i++) {
      if (game.players[i].android_id == player.android_id) {
        index = i;
        break;
      }
    }
    if (index !== undefined) {
      socket.broadcast.emit('info', {msg: game.players[index].name + ' ha abandonado la partida'});
      status(game.players[index].name + ' ha dejado la partida');
      game.players.splice(index, 1);
      showPlayers();
      socket.emit('info', {msg: 'Has abandonado la partida'});
      socket.broadcast.emit('info', {msg: newPlayer.name + ' ha abandonado la partida'});
    }else {
      socket.emit('error', {msg: "Error! jugador no encontrado"});
    }
  });

  socket.on('disconnect', function(data) {
    console.info(socket.id + ' Just disconected');
  });
});

server.listen(defaultPort);