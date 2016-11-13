var app = require('http').createServer(handler);
var io = require('socket.io').listen(app);
var fs = require('fs');
var events = require('events'),
EventEmitter = events.EventEmitter,
eventEm = new EventEmitter();
var PORT_NO = 3210;

app.listen(PORT_NO, function(){
    console.log('listening on *:'+PORT_NO);
});

function handler(req,res) {  
    path = req.url == "/" ? "./index.php" : "." + req.url; 
    fs.readFile(path, function(err, data) {
        if(err) {
            res.writeHead(500);
            return res.end('Error loading page.');
        }   
        res.writeHead(200);
        res.end(data);
    });
  
}

  io.on('connection', function (socket) {
        console.log('CONNECTED');
        socket.on('disconnect', function() {
            console.log('DISCONNECTED');
        }); 
        
        socket.on("send_new_warning",function(data){
            console.log("send_new_warning recieved");
            socket.broadcast.emit("get_new_warning",data);  
        })

       // setInterval(function() {            
            socket.emit('server calling'); 
       // }, 2000);
        
        
    });