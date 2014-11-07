angular.module('RAApp').controller("MainGameCtrl", function ($scope, $location) {
    $scope.headerSrc = "_partials/header.html";

    $scope.quests = getQuests();
    $scope.settings = settings;

    $scope.saveSettings = function(){
        settings.serverIpAddress = $scope.settings.serverIpAddress;
        settings.serverPort = $scope.settings.serverPort;
    }
});

angular.module('RAApp').controller("welcomeCtrl", function ($scope, $routeParams) {
    $('#screen').hide();
    $('#container').removeClass('container-playing');
    if (game.waiting || game.playing) {
        game.end(true).then(function(){
            io.sockets.emit('game end');
        });
    }
    $scope.quests = getQuests();

    $scope.removeQuest = function(quest){
        $scope.quests = _.without($scope.quests, _.findWhere($scope.quests, quest));
        setQuests($scope.quests);
        $scope.quests = getQuests();
    }
});

angular.module('RAApp').controller("configQuestCtrl", function ($scope, $location) {
    $scope.quests = getQuests();

    $scope.newQuest = {
        name: '',
        numPlayers: '',
        difficulty: 0,
        problemsPerPlayer: ''
    };
    var  error;

    $scope.submit = function(start) {
        $scope.errors = [];
        error = false;
        if ($scope.newQuest.numPlayers < 2 || $scope.newQuest.numPlayers > 5 || ! $.isNumeric($scope.newQuest.numPlayers)){
            $scope.errors.push("Deben habe entre 2 y 5 jugadores");
            error = true;
        }
        if ($scope.newQuest.name == '') {
            $scope.errors.push("Debes asignar un nombre para la misión");
            error = true;
        }
        if ( ! $.isNumeric($scope.newQuest.problemsPerPlayer) ) {
            $scope.errors.push("Debes asignar cuantas preguntas responderá cada jugador");
            error = true;
        }
        if ( parseInt($scope.newQuest.numPlayers) > parseInt($scope.newQuest.problemsPerPlayer) ) {
            $scope.errors.push("Debe haber igual o mas preguntas que jugadores");
            error = true;
        }
        if ( $scope.newQuest.difficulty == 0 ) {
            $scope.errors.push("Debes escojer una dificultad");
            error = true;
        }
        if (error){
            return;
        }
        $scope.quests.push($scope.newQuest);
        setQuests($scope.quests);

        if (start) {
            $location.path("/waiting/"+($scope.quests.length - 1));
        }else {
            $location.path("/");
        }
    }
});

angular.module('RAApp').controller("waitingCtrl", function ($scope, $routeParams, $window) {

    $scope.quests = getQuests();

    $scope.quest = $scope.quests[$routeParams.questIndex];
    $scope.quest.id = $routeParams.questIndex;

    $window.game = createGame({
        numPlayers: $scope.quest.numPlayers,
        difficulty: $scope.quest.difficulty,
        problemsPerPlayer: $scope.quest.problemsPerPlayer,
        serverIpAddress: settings.serverIpAddress,
        serverPort: settings.serverPort
    });

    $scope.match = $window.game;

    $window.game.init().then(function() {
        $scope.$digest();
    }).fail(function(){
        console.error('Ha ocurrido un error al crear el juego');
    });

    $scope.$on('update players', function(event, args) {
        $scope.$digest();
    });
});

angular.module('RAApp').controller("playCtrl", function ($scope, $routeParams, $window, $location) {

    $scope.quests = getQuests();

    $scope.quest = $scope.quests[$routeParams.questIndex];
    $scope.quest.id = $routeParams.questIndex;

    $scope.match = $window.game;

    $scope.match.start();
    $scope.an = false;
    $scope.pauseGame = false;
    $scope.gameEnded = false;

    $scope.$on('pause game', function(e, data){
        console.log('2. pausing Game');
        $scope.$apply(function(){
            $scope.pauseGame = ! $scope.pauseGame;
        });

        console.log($scope.pauseGame);
    });
    $scope.$on('player answered', function(e, data) {
        var an = $scope.match.submitAnswer(data.socket, data.answer);

        if (an === 'end'){
            setTimeout(function(){
            $scope.$apply(function(){
                    $scope.match.playing = false;
                    io.sockets.emit('game end', {reward: $scope.match.reward});
                    $scope.match.end();
                    $('#screen').hide();
                    $('#container').removeClass('container-playing');
                    $scope.gameEnded = true;
                    $scope.$broadcast('timer-stop');
                });
            }, 1000);
        }else if( an === 'trapped') {
            $scope.$apply(function(){
                $scope.an = true;
            });
        }else if( an == 'defend-yourselvs'){
            $scope.$apply(function(){
                $scope.defendYourselvs = true;
            });
        }else{
            $scope.an = false;
            $scope.defendYourselvs = false;
            $scope.$digest();
        }
    });
    $scope.$on('player rescued', function(e, data){
        $scope.$apply(function(){
            $scope.an = false;
            $scope.defendYourselvs = false;
        });
    });
    $scope.$on('resume game', function(e, data){
        $scope.$broadcast('timer-resume');
        $scope.match.resume();
        $scope.$digest();
    });
    $scope.$on('player disconnected', function(e, socketId) {
        $scope.$broadcast('timer-stop');
        $scope.match.playerFell(socketId);
        $scope.$digest();
    });
    $scope.$on('update players', function(event, args) {
        $scope.$digest();
    });
    $scope.averageTime = function(){
        var time = 0.0;
        var sum = _.reduce($scope.match.answers, function(time, num){ return time + parseFloat(num.elapsed_time); }, 0);

        if ($scope.match.answers.length === 0 ) return '-';
        return sum / $scope.match.answers.length;
    };
});

