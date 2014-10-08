angular.module('RAApp').controller("MainGameCtrl", function ($scope, $location) {
    $scope.headerSrc = "_partials/header.html";

    console.log("'" + ip.address() + "'");

    $scope.quests = getQuests();

    $scope.settings = settings;

    $scope.saveSettings = function(){
        settings.serverIpAddress = $scope.settings.serverIpAddress;
        settings.serverPort = $scope.settings.serverPort;
    }
});

angular.module('RAApp').controller("welcomeCtrl", function ($scope, $routeParams) {
    if (game.waiting || game.playing) {
        game.end().then(function(){
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
        dificulty: 0
    };

    $scope.submit = function(start) {
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

    $scope.$on('player answered', function(e, data) {
        var an = $scope.match.submitAnswer(data.socket, data.answer);

        if (an === 'end'){
            $scope.$apply(function(){
                $scope.match.playing = false;
                io.sockets.emit('game end');
                $scope.match.end();
            });
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
            $scope.$digest();
        }
    });

    $scope.$on('player rescued', function(e, data){
        $scope.$apply(function(){
            $scope.an = false;    
        });
    });

    $scope.$on('resume game', function(e, data){
        $scope.match.resume();
        $scope.$digest();
    });

    $scope.$on('player disconnected', function(e, socketId) {
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

