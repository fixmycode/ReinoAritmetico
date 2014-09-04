var http        = require('http');
var querystring = require('querystring');
var ip          = require('ip');
var q           = require('q');
var _           = require('underscore');

function Game(options) {
  this.numPlayers        = options.numPlayers        || 5;
  this.serverIpAddress   = options.serverIpAddress   || '127.0.0.1';
  this.serverPort        = options.serverPort        || 8000;
  this.dificulty         = options.dificulty         || 1;
  this.problemsPerPlayer = options.problemsPerPlayer || 15;
  this.players = [];
  this.playersCount = 0;
  this.waiting = false;
  this.waitingForFallen = [];
  this.answers = [];
  this.address = ip.address() + ':' + '3000';
}

Game.prototype.init = function(){
  var defer = q.defer();
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
        self.joinCode = a.uid;
        self.waiting = true;
        defer.resolve();
      })
      .on('error', defer.reject);
  });

  // Esto no funciona muy bien
  req.setTimeout(5000, function(){
    console.error("No se puede comunicar con el servidor "+ self.serverIpAddress+':'+self.serverPort);
    req.abort();
    defer.reject();
  });
  
  req.write(data);
  req.end();

  return defer.promise;
}

Game.prototype.end = function() {
  var defer = q.defer();
  var self = this;

  var options = {
      host: self.serverIpAddress,
      port: self.serverPort,
      path: '/end?uid=' + self.joinCode,
  };

  http.get(options, function(res) {
    delete self.joinCode
    self.players.length = 0;
    return defer.resolve();
  }).on('error', console.log);

  return defer.promise;
}

Game.prototype.join = function (newPlayer) {
  var defer = q.defer();
  var self = this;

  if ( newPlayer.name === '' || newPlayer.android_id == '') {
    newPlayer.socket.emit('error', {msg: "Error! Android ID o Name no presentes"} );
    defer.reject();
    return defer.promise;
  }

  if ( ! self.waiting) {
    newPlayer.socket.emit('error', {msg: "Error! No hay un juego actualmente"} );
    defer.reject();
    return defer.promise;
  }

  if ( self.players.length === self.numPlayers) {
    newPlayer.socket.emit('error', {msg: "Error! el juego esta lleno"} );
    defer.reject();
    return defer.promise;
  }

  if ( _.findWhere(self.players, {name: newPlayer.name})             || 
       _.findWhere(self.players, {android_id: newPlayer.android_id})
     )
  {
    newPlayer.socket.emit('error', {msg: "El nombre o android ID ya existe"} );
    defer.reject();
    return defer.promise;
  }

  newPlayer.answers = [];
  self.players.push(newPlayer);
  newPlayer.socket.emit('info', {msg: 'Te as unido a la partida como ' + newPlayer.name});
  defer.resolve();

  return defer.promise;
};

Game.prototype.leave = function(androidId) {
  var self = this;

  var player = _.findWhere(self.players, {android_id: androidId});
  self.players = _.without(self.players, player);

  player.socket.emit('info', {msg: 'Has abandonado la partida'});
}

Game.prototype.start = function() {
  var self = this;
  var defer = q.defer();

  // Fetch Problems from server
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

  _.each(self.players, self.sendProblem, self);

  self.playing = true;
}

Game.prototype.sendProblem = function(player) {
  var self = this;

  if (self.problems.length > 0) {
    player.socket.emit('solve problem', self.problems.pop());
    return false;
  }else {
    return true;
  }
}

Game.prototype.submitAnswer = function(socketId, answer) {
  var self = this;

  var player = _.chain(self.players)
      .filter(function(p) { return p.socket.id == socketId; })
      .first()
      .value();

  player.answers.push(answer);
  answer.player_name = player.name;
  self.answers.push(answer);
  return self.sendProblem(player);
}

Game.prototype.playerFell = function(socketId) {
  var self = this;

  var player = _.chain(self.players)
      .filter(function(p) { return p.socket.id == socketId; })
      .first()
      .value();

  self.players = _.without(self.players, player);

  player.socket = undefined;
  self.waitingForFallen.push(player);
  self.playing = false;
}

Game.prototype.rejoin = function(player){
  var self = this;

  for (var i = 0; i < self.waitingForFallen; i++) {
    if (self.waitingForFallen[i].android_id === player.android_id) {
      
      self.waitingForFallen[i].socket = player.socket; // Update to the new socket

      game.players.push(self.waitingForFallen[i]);

      break;
    }
  }

  self.waitingForFallen.splice(i, 1);
}

Game.prototype.resume = function(){
  var self = this;

  self.playing = true;
}

module.exports = function(options){
  return new Game(options || {});
}