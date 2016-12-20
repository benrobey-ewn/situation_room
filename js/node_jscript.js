var socket = io.connect('http://localhost:3210');
	socket.on("server calling",function(data){
		 	console.log("server calling ");
		 });
	
	