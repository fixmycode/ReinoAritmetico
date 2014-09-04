var _          = require('underscore');
var q          = require('q');
var io         = require('socket.io').listen(3000);
var createGame = require('./lib/game.js');

var game = createGame();
var settings = {
    serverIpAddress: '127.0.0.1',
    serverPort: '8000'
}

io.set('log level', 1);

angular.module('RAApp', ['ngRoute']);

Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};

// Global Helper Functions
function setQuests(quests) {
    localStorage.quests = JSON.stringify(quests);
}

function getQuests() {
    return JSON.parse(localStorage.quests || '[]');
}

angular.module('RAApp').run(function($rootScope) {
    // Socket comunication
    io.on('connection', function (socket) {
      console.log(socket.id + ' just conected');

      socket.on('join', function(player){
        player.socket = socket;

        if (game.waitingForFallen.length > 0) {
            // Rejoin an ongoing quest
            game.rejoin(player);
            if (game.waitingForFallen.length === 0) $rootScope.$broadcast('resume game');
        }else {
            // Normal join
            game.join(player).then(function(){
                $rootScope.$broadcast('update players');
            });
        }
      });

      socket.on('leave', function(player) {
        game.leave(player.android_id);
        $rootScope.$broadcast('update players')
      });

      socket.on('submit answer', function(answer){ 
        console.log(answer);       
        $rootScope.$broadcast('player answered', {'socket': socket.id, 'answer': answer});
      });

      socket.on('disconnect', function(){
        if (game.playing) {
            $rootScope.$broadcast('player disconnected', socket.id);
        }
      });
    });
});


// UI
(function($) {
  $(document).ready(function() {
    gui = require('nw.gui');
    win = gui.Window.get();

    $(document).on('click', '.close-app', function(){
        win.close();
    });

    win.on('close', function(){
        game.end().then(function(){
            win.close(true);    
        });
    });

    $(document).on('click', '.maximize-app', function () {
    
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

    $(document).on('click', '.minimize-app', function () {
        win.minimize();
    });

    process.on('uncaughtException', function (err) {
      console.log('Caught exception: ' + err);
    });

    $(document).bind('keydown', 'ctrl+d', function(){
        win.showDevTools();
    });
 });
}) (jQuery);