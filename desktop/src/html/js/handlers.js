$content = $('#content');
$notPlaying = $('.not-playing');
$playing = $('.playing');
$status = $('#status');
var statusTimeout = '';

function changeState(callback){
  if ( ! game.playing) {
    $content.load('_partials/welcome.html', callback);
    $notPlaying.show();
    $playing.hide();
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
    wt = " waiting";
  }else {
    wt = '';
  }
  return '<div class="client'+wt+'">\
                <i class="fa fa-male"></i> <br> \
                <small>Jugador ' + number + '</small> \
                <span class="player">' + playerName + '</span> \
            </div>';
}

function showPlayers(){
  var playerList = "";
  for (var i = 0; i < game.players.length; i++) {
    playerList += tag(i+1, game.players[i].name);
  }

  for (var i = 0; i < game.maxPlayers - game.players.length; i++) {
    playerList += tag('', 'Esperando ...', true);
  }

  $('#players').html(playerList);

  if (game.players.length === game.maxPlayers) {
    $('.join-code').html("Iniciar Partida").addClass('play');
  }else {
    $('.join-code').html(game.joinCode).removeClass('play');
  }
}       

// Init ui
$.slidebars();
changeState();

$('.serverIpAddress').val(game.serverIpAddress);
$('.serverPort').val(game.serverPort);

$(document).on('submit', '.start-game', function(e){
  e.preventDefault();
  var numberPlayers = parseInt($(this).find('.numberPlayers').val());

  if (numberPlayers <= 0 || isNaN(numberPlayers)) {
    status("Debe ingresar un número mayor que 0");
    return;
  }

  game.init(game, numberPlayers, function(err){
    if (err) {
      status(err);
    }else {
      changeState(function() {
        showPlayers();
      });
    }
  });
});

$('#stopGame').on('click', function(){
  game.end(game, function(){
    changeState();
  })
});

$('.serverIpAddress').keyup(function(){
  game.serverIpAddress = $(this).val();
});

$('.serverPort').keyup(function(){
  game.serverPort = $(this).val();
});