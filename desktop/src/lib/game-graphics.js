var pos = {x: 100, y: 280};
var poses;
var images = {};
var loader;
var helmets;
var weapons;
var SPOTS = [{x: 100, y: 280}, {x: 170, y: 380}, {x:80, y: 540}]
var Player = function(game, x, y){
    this.x = x || pos.x;
    this.y = y || pos.y;
    this.game = game;
    this.pose = "relax";
    this.playerType = "wizard";
    this.helmet = null;//new Helmet(2, this);
    this.weapon = null;//new Weapon(2, this);
}

Player.prototype.render = function() {
    var self = this;
    var x = self.x - poses[self.playerType][self.pose].base.x * images[self.playerType + '_'+self.pose].width;
    var y = self.y - poses[self.playerType][self.pose].base.y * images[self.playerType + '_'+self.pose].height;

    // this.weapon.render(x + images[self.playerType + '_'+self.pose].width *poses[self.playerType][self.pose].hand.x, y + images[self.playerType + '_'+self.pose].height * poses[self.playerType][self.pose].hand.y, poses[self.playerType][self.pose].handRot);
    self.game.ctx.drawImage(images[self.playerType + '_'+self.pose], x, y);
    // this.helmet.render(x + images[self.playerType + '_'+self.pose].width *poses[self.playerType][self.pose].head.x, y + images[self.playerType + '_'+self.pose].height * poses[self.playerType][self.pose].head.y, poses[self.playerType][self.pose].headRot);
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

var Helmet = function(id, player) {
    this.id = id || 0;
    this.player = player;
    this.x = helmets[this.id].center.x;
    this.y = helmets[this.id].center.y;
    this.img = images['helmets_'+this.id];
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

var Weapon = function(id, player) {
    this.id = id || 0;
    this.player = player;
    this.x = weapons[this.id].center.x;
    this.y = weapons[this.id].center.y;
    this.img = images['weapons_'+this.id];
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
        self.players.push( new Player(self, SPOTS[i].x, SPOTS[i].y) );
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
        self.ctx.drawImage(images.stage, 0, self.gameSize.y - images.stage.height, images.stage.width, images.stage.height);
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

    // $.get('resources/items/helmets.json', function(data){
    //   helmets = data;
    //   _.each(data, function(i){
    //     images['helmets_'+i.id] = loader.addImage('resources/items/helmets/' + i.path);
    //   });
    // });

    // $.get('resources/items/weapons.json', function(data){
    //   weapons = data;
    //   _.each(data, function(i){
    //     images['weapons_'+i.id] = loader.addImage('resources/items/weapons/' + i.path);
    //   });
    // }).done(function(){
    //   loader.start();
    // });



    loader.addCompletionListener(function() {
        new Game(engine);
        console.log(images);
    });
};
