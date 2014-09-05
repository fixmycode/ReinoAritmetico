angular.module('RAApp').filter('range', function() {
  return function(val, range) {
    range = parseInt(range);
    for (var i = 0; i < range; i++)
      val.push(i);
    return val;
  };
});

angular.module('RAApp').filter('reverse', function() {
  return function(items) {
    return items.slice().reverse();
  };
});