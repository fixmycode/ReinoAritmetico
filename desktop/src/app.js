var bodyParser = require('body-parser'),
       express = require('express'),
           app = express(),
          Game = require('../game');

var defaultPort = process.argv[2] || 3000;

var game = new Game(5, defaultPort);

app.use( bodyParser.json() );       // to support JSON-encoded bodies
app.use( bodyParser.urlencoded({extended: true}) ); // to support URL-encoded bodies

var server = app.listen(defaultPort, function () {
  status("Esperando en " + game.address);
});

(function($) {
  $(document).ready(function() {
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
      
      if ( game.players.length === game.maxPlayers) {
        res.status(500).end("Error! Game is full");
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
        status(newPlayer.name + ' se ha unido a la partida');
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
        status(game.players[index].name + ' ha dejado la partida');
        game.players.splice(index, 1);
        showPlayers();
        res.end();
      }else {
        res.status(404).end("Error! player not found");
      }
    });

    app.get('/clearPlayers', function(req,res) {
      game.players.length = 0;
      status('Se ha vaciado la lista de jugadores');
      showPlayers();
      res.end();
    });
  });
}) (jQuery);