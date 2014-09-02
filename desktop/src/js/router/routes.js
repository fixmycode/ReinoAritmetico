angular.module('RAApp').config(function ($routeProvider) {
    $routeProvider
        .when('/', {
            templateUrl: '_partials/welcome.html',
            controller: 'welcomeCtrl'
        }).when('/config_quest', {
            templateUrl: '_partials/config_quest.html',
            controller: 'configQuestCtrl'
        }).when('/waiting/:questIndex', {
            templateUrl: '_partials/waiting.html',
            controller: 'waitingCtrl'
        }).when('/play/:questIndex', {
            templateUrl: '_partials/play.html',
            controller: 'playCtrl'
        }).otherwise({
            redirectTo: '/'
        });
});