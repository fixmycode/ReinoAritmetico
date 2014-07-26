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

var clients = [];
app.post('/join', function(req, res){
  clients.push({name: req.body.name, android_id: req.body.android_id});
  window.location.href = 'index.html#/';
  res.end();
});

app.get('/leave', function(req, res) {
  var index;

  for (var i = 0; i < clients.length; i++) {
    if (clients.android_id == req.query.android_id) {
      index = i; 
      break;
    }
  }
  console.log(index);

  clients.splice(index, 1);

  window.location.href = 'index.html#/';
  res.end();
});