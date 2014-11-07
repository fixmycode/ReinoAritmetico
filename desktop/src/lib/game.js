var http        = require('http');
var querystring = require('querystring');
var ip          = require('ip');
var q           = require('q');
var _           = require('underscore');
var gx          = require('./game-graphics.js');

var REWARD      = 50;
var TRAPPED_ODD = 1.0;
var API         = '/api/v1';

function Game(options) {
  this.numPlayers        = options.numPlayers        || 5;
  this.serverIpAddress   = options.serverIpAddress   || '127.0.0.1';
  this.serverPort        = options.serverPort        || 8000;
  this.difficulty        = options.difficulty        || 1;
  this.problemsPerPlayer = options.problemsPerPlayer || 15;
  this.players = [];
  this.answeringPlayers = [];
  this.playersCount = 0;
  this.waiting = false;
  this.waitingForFallen = [];
  this.answers = [];
  this.address = ip.address() + ':' + '3000';
  this.j = 0;
  this.problemsCount = 0;
  this.wrong_players = [];
  this.reward = 0;
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
      path: API + '/game/start',
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
        self.id = a.id;
        self.waiting = true;
        self.reward = 0;
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

Game.prototype.end = function(failedd) {
  var failed = false | failedd;
  var defer = q.defer();
  var self = this;

  var answers = {};
  _.each(self.players, function(p){
    answers[p.android_id] = p.answers;
  });
  var data = JSON.stringify( {
    reward:  failed ? 0  : self.reward,
    players: failed ? [] : _.map(self.players, function(n) { return n.android_id }),
    answers: failed ? [] : answers,
    failed: failed
  });

  var options = {
      host: self.serverIpAddress,
      port: self.serverPort,
      path: API + '/game/end?id=' + self.id,
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Content-Length': data.length
      }
  };

  var req = http.request(options, function(res) {
      res.setEncoding('utf8');
      var body = "";

      res.on('data', function (chunk) {
        body += chunk;
      })
      .on('end', function() {
        console.log(body);
        delete self.joinCode;
        delete self.id;
        self.players.length = 0;
        defer.resolve();
      })
      .on('error', defer.reject);
  });

  self.playing = false;

  req.write(data);
  req.end();

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
  newPlayer.character_type = newPlayer.character_type.toString();
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
  players = [];
  self.players.forEach(function(p){
    players.push(p.android_id);
  });
  var data = JSON.stringify({
    players: players
  });
  var options = {
      host: self.serverIpAddress,
      port: self.serverPort,
      path: API + '/game/go?quantity='+ parseInt(self.problemsPerPlayer) +'&difficulty='+ self.difficulty,
      method: 'POST',
      headers: {
          'Content-Type': 'application/json',
          'Content-Length': data.length
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
        self.problems = a.problems;
        self.resources = a.players.players;
        self.j = 0;
        self.offset = 1;
        self.problemsCount = 0;
        self.shaken = 0;
        self.reward = REWARD;
        gx(self);
        self.playing = true;
        self.waiting = false;
        for(var i = 0; i < self.players.length; i++) {
          self.players[i].j = i;
        }
        _.each(self.players, self.sendProblem, self);
        defer.resolve();
      })
      .on('error', defer.reject);
  });

  req.on('error', function(e) {
    defer.reject();
  });

  req.write(data);
  req.end();

  return defer.promise;
}

Game.prototype.sendProblem = function(player) {
  var self = this;

  player.current_problem = self.problems[player.j];
  player.j = (player.j + 1)%self.problems.length;

  player.socket.emit('solve problem', player.current_problem);

  self.answeringPlayers.push(player);
  self.problemsCount++;
}

Game.prototype.submitAnswer = function(socketId, answer) {
  var self = this;

  var player = _.chain(self.players)
                .filter(function(p) { return p.socket.id == socketId; })
                .first()
                .value();

  if (answer.answer.toString() === answer.correct_answer.toString()) {
    self.gx.players.forEach(function(p){
      if (p.android_id === player.android_id){
        p.attack();
      }
    });
  }

  self.answeringPlayers = _.without(self.answeringPlayers, player); // Remove player from waiting list

  player.answers.push(answer);
  answer.player_name = player.name;
  answer.player_android_id = player.android_id;
  self.answers.push(answer);

  /* Analyse wrong answres */
  if (answer.answer.toString() !== answer.correct_answer.toString()) {
    self.wrong_players.push(player);
  }

  if (self.answeringPlayers.length === 0) {
    if (self.wrong_players.length === self.players.length) {
      self.reward -= 5; // -5 coins each for being all wrong
    }
    if (self.problemsCount === self.players.length * self.problemsPerPlayer) { // Did the game end?
      return 'end';
    }
    // Nop, it didn't
    if (self.wrong_players.length === 1 && Math.random() <= TRAPPED_ODD) { // Trap someone!
      self.wrong_players[0].socket.broadcast.emit('shake', {msg: '¡Rápido! Sacude para salvar a '+ self.wrong_players[0].name});
      self.wrong_players[0].socket.emit('trapped', {msg: 'Has sido atrapado! pidele ayuda a tus amigos!'});
      self.gx.players.forEach(function(p){
        if (self.wrong_players[0].android_id === p.android_id){
          p.damage();
          console.log("d1");
        }
      });
      return 'trapped';
    }else if(self.wrong_players.length === self.players.length){ //Everyone got it wrong!!
      self.wrong_players[0].socket.broadcast.emit('shake', {msg: '¡Te están atacando! ¡Sacude!'});
      self.wrong_players[0].socket.emit('shake', {msg: '¡Te están atacando! ¡Sacude!'});
      self.gx.players.forEach(function(p){
          p.damage();
          console.log("d2");
      });
      return 'defend-yourselvs';
    }
    self.wrong_players.length = 0;
    _.each(self.players, self.sendProblem, self);
    self.j += self.offset++;
  }
  return false;
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

Game.prototype.rejoin = function(data){
  var self = this;

  var player = _.chain(self.waitingForFallen)
      .filter(function(p) { return p.android_id == data.android_id; })
      .first()
      .value();

  player.socket = data.socket;
  self.waitingForFallen = _.without(self.waitingForFallen, player);
  self.players.push(player);
  player.socket.emit('solve problem', player.current_problem);
}

Game.prototype.resume = function(){
  var self = this;

  self.playing = true;
}

module.exports = function(options){
  return new Game(options || {});
}