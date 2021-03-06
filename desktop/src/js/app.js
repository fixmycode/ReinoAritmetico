var _          = require('underscore');
var q          = require('q');
var io         = require('socket.io').listen(3000, {log:false});
var createGame = require('./lib/game.js');
var ip         = require('ip');


var game = createGame();
var settings = {
    serverIpAddress: 'localhost',//'blackbirdsw.noip.me',
    serverPort: '8000'//'80'
}



angular.module('RAApp', ['ngRoute', 'timer']);

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

    $(document).bind('keydown', 'esc', function(){
        $rootScope.$broadcast('pause game');
    });

    io.on('connection', function (socket) {

        socket.on('join', function(player){
            player.socket = socket;

            if (game.waitingForFallen.length > 0) {
                // Rejoin an ongoing quest
                game.rejoin(player);
                $rootScope.$broadcast('update players');
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
            $rootScope.$broadcast('update players');
        });

        socket.on('submit answer', function(answer){
            $rootScope.$broadcast('player answered', {'socket': socket.id, 'answer': answer});
        });

        socket.on('shook', function(s) {
            game.shaken++;
            if (game.wrong_players.length === game.players.length && game.shaken == game.players.length) { // Everyone wrong
                game.shaken = 0;
                $rootScope.$broadcast('player rescued');
                game.gx.players.forEach(function(p){
                  p.relax();
                });
                game.wrong_players.length = 0; // Clear waitingPlayers
                _.each(game.players, game.sendProblem, game); // Keep playing
            }else if (game.wrong_players.length === 1 && game.shaken === game.players.length - 1) { // All those who had to shake, shook
                game.shaken = 0;
                $rootScope.$broadcast('player rescued', game.wrong_players[0]);
                game.wrong_players.length = 0; // Clear waitingPlayers
                game.gx.players.forEach(function(p){
                  p.relax();
                });
                _.each(game.players, game.sendProblem, game); // Keep playing
            }
        });

        socket.on('disconnect', function(){
            if (game.playing || game.waitingForFallen.length > 0) {
                $rootScope.$broadcast('player disconnected', socket.id);
            }else if (game.waiting) {
                var player = _.chain(game.players)
                      .filter(function(p) { return p.socket.id == socket.id; })
                      .first()
                      .value();

                game.leave(player.android_id);
                $rootScope.$broadcast('update players');
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
        if (game.playing) {
            game.end(true).then(function(){
                win.close(true);
            }).fail(function(){
                win.close(true);
            });
        }else {
            win.close(true);
        }

    });

    $(document).on('click', '.maximize-app', function () {
        win.toggleFullscreen();
        $('#actions').toggleClass('full-screen');
        $('body').toggleClass('full-screen-body');



        // if(win.isFullscreen){
        //     win.toggleFullscreen();
        // }else{
        //     if (screen.availHeight <= win.height) {
        //         win.unmaximize();
        //     }else {
        //         win.maximize();
        //     }
        // }
    });

    $(document).on('click', '.minimize-app', function () {
        win.minimize();
    });

    process.on('uncaughtException', function (err) {
      console.log('Caught exception: ' + err);
      console.dir(err);
    });

    $(document).bind('keydown', 'ctrl+d', function(){
        win.showDevTools();
    });

    $('body').on('mouseover', '.full-screen', function(){
        $(this).stop(true, true).animate({opacity: 1}, 300);
    });
    $('body').on('mouseleave', '.full-screen', function(){
        $(this).stop(true, true).animate({opacity: 0}, 300);
    });
 });
}) (jQuery);