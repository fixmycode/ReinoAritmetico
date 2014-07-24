/*
 |--------------------------------------------------------------------------
 | Dependencies
 |--------------------------------------------------------------------------
 */
var express = require('express');
var app     = express();
var request = require('request');

/**
 * Server IP Address, change accordingly
 * @type {String}
 */
var serverAddress =  'http://dev.ra-server.io';

app.use(express.bodyParser());

/*
 |--------------------------------------------------------------------------
 | Start the Server
 |--------------------------------------------------------------------------
 */
var server = app.listen(3000, function(){
    $('#status').text('Listening on port ' + server.address().port);
});

server.on('close', function(){

});