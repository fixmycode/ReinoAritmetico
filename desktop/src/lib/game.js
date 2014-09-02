function Game() {
  this.players = {};
}


/**
 * Adds a player to the current game
 * @param  {object} newPlayer player object {name, android_id, socket}
 */
Game.prototype.join = function(newPlayer){
  var self = this;

  self.players[newPlayer.socket.id] = newPlayer;
}

Game.prototype.leave = function(socketId) {
  var self = this;

  var player = self.players[socketId];

  delete self.players[socketId];

  return player; 
}


module.exports = function(){
  return new Game();
}