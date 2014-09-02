(function($) {
  $(document).ready(function() {
    gui = require('nw.gui');
    win = gui.Window.get();

    $('.close-app').on('click', function(){
        win.close();
    });

    win.on('close', function(){
        if (game.playing) {
            game.end(function(){
                server.close();
                win.close(true);    
            });    
        }else {
            server.close();
            win.close(true);    
        }
    });

    $('.maximize-app').on('click', function () {
    
        if(win.isFullscreen){
            win.toggleFullscreen();
        }else{
            if (screen.availHeight <= win.height) {
                win.unmaximize();
            }else {
                win.maximize();
            }
        }
    });

    $('.minimize-app').on('click', function () {
        win.minimize();
    });

    process.on('uncaughtException', function (err) {
      console.log('Caught exception: ' + err);
      status("Error de comunicacion");
    });

    $(document).bind('keydown', 'ctrl+d', function(){
        win.showDevTools();
    });
 });
}) (jQuery);