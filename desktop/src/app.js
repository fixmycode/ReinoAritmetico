var bodyParser = require('body-parser')
       express = require('express'),
           app = express(),
          http = require('http').Server(app),
            io = require('socket.io')(http);
var defaultPort = process.argv[2] || 3000;

var game = require('../game');
game.setup(defaultPort, '10.0.0.100', 80);

app.use( bodyParser.json() );       // to support JSON-encoded bodies
app.use( bodyParser.urlencoded({extended: true}) ); // to support URL-encoded bodies

var theSocket = null;

app.post('/join', function (req, res) {
  var newPlayer = {name: req.body.name, android_id: req.body.android_id};
  var exists = false;

  if ( newPlayer.name === undefined || newPlayer.android_id === undefined) {
    res.status(500).end("Error! Android ID or Name is missing");
    return;
  }

  if ( ! game.playing) {
    res.status(500).end("Error! No game currently going on");
    return;
  }
  console.log('JA!');

  for (var i = 0; i < game.players.length; i++) {
    if (game.players[i].name === newPlayer.name || game.players[i].android_id === newPlayer.android_id) {
      exists = true;
      break;
    }
  }
  
  if ( ! exists ) {
    game.players.push(newPlayer);
    theSocket.emit('new player', newPlayer);
    console.log(newPlayer);
    console.log("==> New Player joined the game");
    res.end();
  }else {
    res.status(500).end("Error! The player\'s name or Android ID already exists");
  }
});

app.get('/leave', function(req, res) {
  var index;

  for (var i = 0; i < game.players.length; i++) {
    if (game.players[i].android_id == req.query.android_id) {
      index = i;
      break;
    }
  }
  if (index !== undefined) {
    console.log("==> Player "+ index + " is leaving the game...");

    game.players.splice(index, 1);

    theSocket.emit('players list', game.players);
    res.end();
  }else {
    res.status(404).end("Error! player not found");
  }
});

app.get('/clearPlayers', function(req,res) {
  players.length = 0;
  io.sockets.emit('players list', game.players);
  res.end();
});

io.on('connection', function(socket){
  console.log('==> A user connected');
  console.log(game.joinCode);
  console.log(game.maxPlayers);
  
  theSocket = socket;

  socket.emit('join code', game.joinCode);
  socket.emit('players list', game.players);
  socket.emit('max players', game.maxPlayers);

  socket.on('disconnect', function(){
    console.log('==> A user disconnected');
  });

  socket.on('new game', function(ply) {
    game.init(game, ply, function(game){
      socket.emit('players list', game.players);
      socket.emit('join code', game.joinCode);
      socket.emit('max players', game.maxPlayers);
      console.log('==> Waiting players');
    });
  });

  socket.on('end game', function(){
    game.end(game, function(game){
      socket.emit('players list', game.players);
      socket.emit('join code', game.joinCode);
      socket.emit('max players', game.maxPlayers);
    });
  });
});
  
var server = http.listen(defaultPort, function () {
  console.log("==> Listening on port " + defaultPort);
});