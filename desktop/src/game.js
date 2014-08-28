var http = require('http');
var querystring = require('querystring');
var   ip = require('ip');

function Game(maxPlayers, port, serverIpAddress, serverPort){
  this.players = {};
  this.maxPlayers = maxPlayers || 5;
  this.joinCode = "none";
  this.playing = false;
  this.waiting = false;
  var p = port || 8000;
  this.address = ip.address() + ':' + p;
  this.serverIpAddress = serverIpAddress || "127.0.0.1";
  this.serverPort = serverPort || 8000;
};

Game.prototype.init = function(self, maxPly, callback){
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
  };

Game.prototype.start = function(self) {
  self.playing = true;
}

Game.prototype.end = function(self, callback) {
    var options = {
        host: self.serverIpAddress,
        port: self.serverPort,
        path: '/end?uid=' + self.joinCode,
    };

    http.get(options, function(res){
      self.joinCode = "none";
      for (var player in self.players) delete self.players[player];
      self.playing = false;
      callback();
    }).on('error', console.log);
  }

module.exports = Game;