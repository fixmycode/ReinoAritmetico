$content    = $('#content');
$notPlaying = $('.not-playing');
$playing    = $('.playing');
$status     = $('#status');
var statusTimeout = '';

function changeState(callback){
  if ( ! game.waiting) {
    $content.load('_partials/welcome.html', callback);
    $notPlaying.show();
    $playing.hide();
    setTimeout(function(){ $('#numberPlayers').focus(); }, 500);
  }else {
    $content.load('_partials/waitingPlayers.html', callback);
    $playing.show();
    $notPlaying.hide();
  }
}

function status(msg) {
  clearTimeout(statusTimeout);
  $status.html('==> '+msg);
  statusTimeout = setTimeout(function(){
    $status.html('');
  }, 5000);
}

function tag(number, playerName, waiting) {
  if (waiting) {
    return '<div class="client waiting">\
                <img src="img/not-connected.gif"> <br> \
                <small>Jugador ' + number + '</small> \
                <span class="player">' + playerName + '</span> \
            </div>';
  }else {
    return '<div class="client">\
                <img src="img/warrior.gif"> <br> \
                <small>Jugador ' + number + '</small> \
                <span class="player">' + playerName + '</span> \
            </div>';
  }
}

function showPlayers(){
  var playerList = "";
  var i = 0;

  for (var value in game.players){
    console.log(value + " -> " + game.players[value].name);
    playerList += tag(i+1, game.players[value].name);
    i++;
  }

  for (var j = 0; j < game.maxPlayers - i; j++) {
    playerList += tag('', 'Esperando ...', true);
  }

  $('#players').html(playerList);

  if (Object.keys(game.players).length === game.maxPlayers) {
    $('.join-code').html("Iniciar Partida").addClass('play');
  }else {
    $('.join-code').html(game.joinCode).removeClass('play');
  }
}       

// Init ui
$.slidebars();
changeState();

$(window).resize(function() {
  $('#sb-site').height($(window).height());
});

$('.serverIpAddress').val(game.serverIpAddress);
$('.serverPort').val(game.serverPort);

$(document).on('submit', '.start-room', function(e){
  e.preventDefault();
  var numberPlayers = parseInt($(this).find('#numberPlayers').val());

  if (numberPlayers <= 0 || isNaN(numberPlayers)) {
    status("Debe ingresar un número mayor que 0");
    return;
  }

  game.init(numberPlayers, function(err){
    if (err) {
      status(err);
    }else {
      changeState(function() {
        showPlayers();
      });
    }
  });
});

$(document).on('click', '.play', function(e) {
  e.preventDefault();
  $content.load('_partials/game.html');
  io.sockets.emit('info', {msg: 'El juego ha comenzado'});
  game.start();
});

$('#stopGame').on('click', function(){
  game.end(function(){
    changeState();
  });

});

$('.serverIpAddress').keyup(function(){
  game.serverIpAddress = $(this).val();
});

$('.serverPort').keyup(function(){
  game.serverPort = $(this).val();
});
