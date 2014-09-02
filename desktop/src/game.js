var http = require('http');
var querystring = require('querystring');
var   ip = require('ip');


function Game(io, maxPlayers, port, serverIpAddress, serverPort){
  var p = port || 8000;

  this.io = io;
  this.players = {};
  this.maxPlayers = maxPlayers || 5;
  this.joinCode = "none";
  this.playing = false;
  this.waiting = false;
  this.address = ip.address() + ':' + p;
  this.serverIpAddress = serverIpAddress || "127.0.0.1";
  this.serverPort = serverPort || 8000;
};

Game.prototype.init = function(maxPly, callback){
  var self = this;

  var data = querystring.stringify({
    address: self.address
  });

  var options = {
      host: self.serverIpAddress,
      port: self.serverPort,
      path: '/start',
      method: 'POST',
      headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'Content-Length': Buffer.byteLength(data)
      }
  };

  var req = http.request(options, function(res) {
      res.setEncoding('utf8');
      var body = "";

      res.on('data', function (chunk) {
        body += chunk;
      })
      .on('end', function() {
        var a = JSON.parse(body);
        console.log(a.uid);
        self.joinCode   = a.uid;
        self.maxPlayers = maxPly;
        self.waiting    = true;
        callback();
      })
      .on('error', console.log);
  });

  // Esto no funciona muy bien
  req.setTimeout(5000, function(){
    callback("No se puede comunicar con el servidor "+ self.serverIpAddress+':'+self.serverPort);
    req.abort();
  });
  
  req.write(data);
  req.end();
}

/**
 * Starts the game
 */
Game.prototype.start = function() {
  var self = this;

  self.problems = [
    {
      problem_id: 1,
      problem: '2+x = 3. x = ?',
      correct_answer: '1' 
    },
    {
      problem_id: 2,
      problem: '3*4',
      correct_answer: '12' 
    },
    {
      problem_id: 3,
      problem: '5*8',
      correct_answer: '40' 
    },
    {
      problem_id: 4,
      problem: '3+8',
      correct_answer: '11' 
    },
    {
      problem_id: 5,
      problem: '12*12',
      correct_answer: '144' 
    }
  ];

  for(socketId in self.players) {
    self.sendProblem(socketId);
  }

  self.playing = true;
}

Game.prototype.sendProblem = function(socketId) {
  var self = this;

  if (self.problems.length > 0) {
    self.players[socketId].socket.emit('solve problem', self.problems.pop());
  }else {
    self.end(changeState);
  }
}

/**
 * Ends the game
 * @param  {Function} callback 
 */
Game.prototype.end = function(callback) {
  var self = this;

  var options = {
      host: self.serverIpAddress,
      port: self.serverPort,
      path: '/end?uid=' + self.joinCode,
  };

  http.get(options, function(res){
    self.joinCode = "none";
    for (var player in self.players) delete self.players[player];
    self.playing = false;
    self.waiting = false;
    if (callback)
      callback();
    self.io.sockets.emit('info', {msg: 'El juego ha finalizado'});
  }).on('error', console.log);
}

/**
 * A plyers joins the current game
 * @param  {Object player} newPlayer android_id and name of the new player
 */
Game.prototype.join = function (socket, newPlayer, callback) {
  console.log(this);
  var self = this;
  var exists = false;

  if ( newPlayer.name === '' || newPlayer.android_id === undefined) {
    socket.emit('error', {msg: "Error! Android ID o Name no presentes"} );
    return;
  }

  if ( ! self.waiting) {
    socket.emit('error', {msg: "Error! No hay un juego actualmente"} );
    return;
  }

  if ( self.players.length === self.maxPlayers) {
    socket.emit('error', {msg: "Error! el juego esta lleno"} );
    return;
  }

  for (var socketId in self.players) {
    if (self.players[socketId].android_id === newPlayer.android_id){
      exists = true;
      self.players[socket.id] = self.players[socketId];
      self.players[socket.id].name = newPlayer.name;
      self.players[socket.id].socket = socket;
      if (socket.id !== socketId) {
        delete self.players[socketId];
        return 1;
      } 
      break;
    }
  }

  if ( ! exists ) {
    self.players[socket.id] = newPlayer;
    self.players[socket.id].socket = socket;
    self.players[socket.id].answers = [];
    socket.emit('info', {msg: 'Te as unido a la partida como ' + newPlayer.name});
    socket.broadcast.emit('info', {msg: newPlayer.name + ' se ha unido a la partida'});
  }else {
    socket.emit('info', {msg: "Te has vuelto a unir como " + newPlayer.name} );
  }
  callback();
}

/**
 * A players leaves the current game
 * @param  {Object player} player android_id and name of the player leaving
 */
Game.prototype.leave = function(socket, player) {
  var self = this;

  if (self.players[socket.id].android_id === player.android_id) {
    socket.broadcast.emit('info', {msg: self.players[socket.id].name + ' ha abandonado la partida'});
    status(self.players[socket.id].name + ' ha dejado la partida');
    delete self.players[socket.id];
    showPlayers();
    socket.emit('info', {msg: 'Has abandonado la partida'});
  }else {
    socket.emit('error', {msg: "Error! jugador no encontrado"});
  }

  // Si no quedan jugadores
  if (Object.keys(self.players).length === 0 && self.playing) {
    self.waiting = false;
    self.end(changeState);
  }
}

module.exports = Game;