 $(function(){
    gui = require('nw.gui');
    win = gui.Window.get();

    $('.close-app').on('click', function(){
        win.close();
    });

    win.on('close', function(){
        server.close();
        win.close(true);
    });

    $('.maximize-app').on('click', function () {
        if(win.isFullscreen){
            win.toggleFullscreen();
        }else{
            if (screen.availHeight <= win.height) {
                win.unmaximize();
            }else {
                win.maximize();
            }
        }
    });

    $('.minimize-app').on('click', function () {
        win.minimize();
    }); 
});

var app = angular.module('ReinoAritmetico', [
  'btford.socket-io',
  'pageslide-directive'
]);

app.factory('socket', function (socketFactory) {
  return socketFactory({
    ioSocket: io.connect('http://127.0.0.1:3000')
  });
});

app.controller('MainCtrl', function ($scope, socket) {
  $scope.players      = [];
  $scope.totalPlayers = 5;
  $scope.joinCode     = 'none';
  $scope.playing      = false;
  socket.on('join code', function(msg){
    $scope.joinCode = msg;
    if (msg !== 'none') {
      $scope.playing = true;
      window.location.href = "#/waiting";
    }else {
      window.location.href = "#/";
    }
  });
  socket.on('new player', function(player) {
    $scope.players.push(player);
  });
  socket.on('players list', function(ply) {
      $scope.players = ply;
  });
  socket.on('max players', function(maxPly) {
    $scope.totalPlayers = maxPly;
  });
  $scope.initGame = function() {
    if ($scope.numberPlayers === undefined) { return; }
    socket.emit('new game', $scope.numberPlayers);
    $scope.numberPlayers = '';
    $scope.playing = true;
  };
  $scope.stopGame = function() {
    socket.emit('end game');
    $scope.playing = false;
  };
});

app.filter('range', function() {
  return function(val, range) {
    range = parseInt(range);
    for (var i=0; i<range; i++)
      val.push(i);
    return val;
  };
});