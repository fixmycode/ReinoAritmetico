var should = require('should');
var game = require('../lib/game.js')();

describe('Game', function(){

  describe('#players', function(){
    it('should have a players list', function(){
      game.players.should.be.an.Object;
      Object.keys(game.players).length.should.be.within(0,5);
    });
  });

  describe('#join()', function(){
    it('should add a player to the game', function(){
      var newPlayer = {
        name: 'Jon Snow',
        android_id: 'asdfawerasdf',
        socket: {
          id: '1234'
        }
      };

      game.join(newPlayer);

      game.players.should.have.keys('1234');
      game.players['1234'].name.should.be.equal('Jon Snow');
      game.players['1234'].android_id.should.be.equal('asdfawerasdf');
    });
  });

  describe('#leave()', function(){
    it('should remove a player from the game', function(){
      game.leave('1234').name.should.be.equal('Jon Snow');
      Object.keys(game.players).length.should.be.exactly(0);
    });
  });
});