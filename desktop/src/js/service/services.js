angular.module('RAApp').factory('movieStubFactory', function ($resource) {
    return $resource('http://moviestub.cloudno.de/movies');
});

angular.module('RAApp').factory('movieStubBookingsFactory', function ($resource) {
    return $resource('http://moviestub.cloudno.de/bookings');
});


angular.module('RAApp').filter('range', function() {
  return function(val, range) {
    range = parseInt(range);
    for (var i = 0; i < range; i++)
      val.push(i);
    return val;
  };
});

angular.module('RAApp').factory('GameService', function(){
  return {
    game: game
  }
});

angular.module('RAApp').filter('range', function() {
  return function(val, range) {
    range = parseInt(range);
    for (var i=0; i<range; i++)
      val.push(i);
    return val;
  };
});