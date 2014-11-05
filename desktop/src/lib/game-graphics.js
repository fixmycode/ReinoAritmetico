var pos = {x: 100, y: 280};
var poses;
var images = {};
var loader;
var helmets;
var weapons;
var SPOTS = [
    {x: 0.20, y: 0.70},
    {x: 0.30, y: 0.80},
    {x: 0.25, y: 0.90}
];

var Player = function(game, android_id, xp, yp){
    this.xp = xp;
    this.yp = yp;
    this.android_id = android_id;
    this.game = game;
    this.pose = "relax";
    this.playerType = "wizard";
    this.helmet = new Helmet(this);
    this.weapon = new Weapon(this);
}

Player.prototype.render = function() {
    var self = this;
    var xt = self.xp * self.game.gameSize.x;
    var yt = self.yp * self.game.gameSize.y;
    var x = xt - poses[self.playerType][self.pose].base.x * images[self.playerType + '_'+self.pose].width;
    var y = yt - poses[self.playerType][self.pose].base.y * images[self.playerType + '_'+self.pose].height;

    this.weapon.render(x + images[self.playerType + '_'+self.pose].width *poses[self.playerType][self.pose].hand.x, y + images[self.playerType + '_'+self.pose].height * poses[self.playerType][self.pose].hand.y, poses[self.playerType][self.pose].handRot);
    self.game.ctx.drawImage(images[self.playerType + '_'+self.pose], x, y);
    this.helmet.render(x + images[self.playerType + '_'+self.pose].width *poses[self.playerType][self.pose].head.x, y + images[self.playerType + '_'+self.pose].height * poses[self.playerType][self.pose].head.y, poses[self.playerType][self.pose].headRot);
};

Player.prototype.attack = function(){
    var self = this;

  self.pose = "attack1";
  self.game.update();
  setTimeout(function(){
    self.pose = "attack2";
    self.game.update();
    setTimeout(function(){
      self.pose = "attack3";
      self.game.update();
      setTimeout(function(){
        self.pose = "relax";
        self.game.update();
      }, 300);
    }, 200);
  }, 200);
}

var Helmet = function(player) {
    this.player = player;
    this.x = this.player.game.engine.resources[this.player.android_id].head.center.x || 0.5;
    this.y = this.player.game.engine.resources[this.player.android_id].head.center.y || 0.2;
    this.img = images['helmets_'+this.player.android_id];
};

Helmet.prototype.render = function(headX, headY, rot) {
    var x =  -this.img.width  * this.x;
    var y =  -this.img.height * this.y;
    var ctx = this.player.game.ctx;

    ctx.save();
    ctx.translate(headX,headY);
    ctx.rotate(rot * Math.PI/180);
    ctx.drawImage(this.img, x, y);
    ctx.restore();
}

var Weapon = function(player) {
    this.player = player;
    this.x = this.player.game.engine.resources[this.player.android_id].hand.center.x || 0.5;
    this.y = this.player.game.engine.resources[this.player.android_id].hand.center.y || 0.5;
    this.img = images['weapons_'+this.player.android_id];
};

Weapon.prototype.render = function(handX, handY, rot) {
    var x =  -this.img.width  * this.x;
    var y =  -this.img.height * this.y;
    var ctx = this.player.game.ctx;

    ctx.save();
    ctx.translate(handX,handY);
    ctx.rotate(rot * Math.PI/180);
    ctx.drawImage(this.img, x, y);
    ctx.restore();
}

var Game = function(engine) {
    engine.gx = this;
    this.engine = engine;

    var canvasElement =  window.document.getElementById("screen");
    canvasElement.style.display = "block";
    this.ctx = canvasElement.getContext('2d');
    this.gameSize = { x: this.ctx.canvas.width, y: this.ctx.canvas.height };
    var self = this;

    self.players = [];
    self.engine.players.forEach(function(p, i) {
        self.players.push( new Player(self, p.android_id, SPOTS[i].x, SPOTS[i].y) );
        self.players[i].android_id = p.android_id;
        if (p.character_type === "0"){
            self.players[i].playerType = "warrior";
        }else if (p.character_type === "1"){
            self.players[i].playerType = "wizard";
        }else{
            self.players[i].playerType = "archer";
        }
    });

    resizeCanvas();
    function update() {
        self.ctx.drawImage(images.stage, 0, (self.gameSize.y - images.stage.height)*0.7, images.stage.width, images.stage.height);
        self.players.forEach(function(p) {
            p.render();
        });
    }
    this.update = update;

    window.addEventListener('resize', resizeCanvas, false);
    function resizeCanvas(){
        self.ctx.canvas.width = window.innerWidth;
        self.ctx.canvas.height = window.innerHeight - 40;
        self.gameSize = { x: self.ctx.canvas.width, y: self.ctx.canvas.height };
        update();
    }
};

module.exports = function(engine) {
    loader = window.loader;
    $ = window.$;

    images['stage'] = loader.addImage('file://'+process.cwd()+'/resources/stage.png');

    engine.players.forEach(function(p){
        images['helmets_'+p.android_id] = loader.addImage(engine.resources[p.android_id].head.resource);
        images['weapons_'+p.android_id] = loader.addImage(engine.resources[p.android_id].hand.resource);
    });
    $.get('file://'+process.cwd()+'/resources/poses/poses.json', function(data){
      poses = JSON.parse(data);
      ["warrior", "archer", "wizard"].forEach(function(playerType, i) {
        ["relax", "attack1", "attack2", "attack3", "damage"].forEach(function(pose, j) {
          images[playerType + '_' + pose] = loader.addImage('file://'+process.cwd()+'/resources/poses/' + playerType + '/' + pose + '.png');
        });
      });
    }).done(function(){
        loader.start();
    });

    loader.addCompletionListener(function() {
        new Game(engine);
        console.log(images);
    });
};
