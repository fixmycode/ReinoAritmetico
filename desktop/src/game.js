var http = require('http');
var querystring = require('querystring');
var   ip = require('ip');

function Game(maxPlayers){
  this.players = [];
  this.maxPlayers = maxPlayers || 5;
  this.joinCode = "none";
  this.playing = false;

  this.setup = function(port, serverAddress, serverPort) {
    this.address = ip.address() + ':' + port;
    this.serverAddress = serverAddress || "120.0.0.1";
    this.serverPort = serverPort || 80;
  };

  this.init = function($this, maxPly, callback){
    console.log("==> Doing HTTP request to server for the game join code");

    var data = querystring.stringify({
      address: $this.address
    });

    var options = {
        host: $this.serverAddress,
        port: $this.serverPort,
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
        });

        res.on('end', function() {
          var a = JSON.parse(body);
          console.log(a.uid);
          $this.joinCode   = a.uid;
          $this.maxPlayers = maxPly;
          $this.playing    = true;
          callback($this);
        });
    });

    req.write(data);
    req.end();
  };

  this.end = function($this, callback) {
    console.log("==> Telling the server to stop the game");

    var options = {
        host: $this.serverAddress,
        port: $this.serverPort,
        path: '/end?uid=' + $this.joinCode,
    };

    http.get(options, function(res){
      $this.joinCode = "none";
      $this.players.length = 0;
      $this.playing = false;
      console.log("Game Stoped");
      callback($this);
    }).on('error', console.log);
  }
};

module.exports = new Game();