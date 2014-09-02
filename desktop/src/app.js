var bodyParser = require('body-parser'),
       express = require('express'),
           app = express(),
            io = require('socket.io'),
          Game = require('../game');

var defaultPort = process.argv[2] || 3000;
var server      = require('http').createServer(app);
var io          = io.listen(server);
var game        = new Game(io, 5, defaultPort);        

io.set('log level', 1);

io.sockets.on('connection', function (socket) {
  console.info(socket.id + ' Just connected');

  socket.on('join', function(data){
    var st = game.join(socket, data, showPlayers);

    if (st === 1) {
      $('#pauseMsg').hide(); // El usuario se cayo y vuelve a unirse
    }
  });
  socket.on('leave', game.leave.bind(game, socket));

  socket.on('submit answer', function(data){
    game.players[socket.id].answers.push(data);

    game.sendProblem(socket.id);
  });

  socket.on('disconnect', function(data) {
    console.info(socket.id + ' Just disconected');

    if (game.playing && game.players[socket.id] !== undefined) {
      $('#pauseMsg .msg').html('Juego Pausado, esperando a que ' + game.players[socket.id].name + ' se vuelva a conectar')
      console.log('Juego Pausado! esperando a ' + game.players[socket.id].name);
      $('#pauseMsg').show();
    }
  });
});

server.listen(defaultPort);