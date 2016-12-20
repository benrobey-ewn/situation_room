<?php
error_reporting(E_ALL);

/* Allow the script to hang around waiting for connections. */
set_time_limit(0);

/* Turn on implicit output flushing so we see what we're getting
 * as it comes in. */
ob_implicit_flush();

/************
 *** Connection Parameters
 ***********/
$password = 'ewn2015';
$domain = 'feed1.gpats.com.au'; // OR feed2.gpats.com.au
$port = 2121;
$timeout = 4;


$server = gethostbyname($domain);
// Create the socket
if (($socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
    exit("socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n");
}

// Bind the source address

// Open the connection
if (!(@socket_connect($socket, $server, $port))) {
    exit("Could not open connection: <br> - Server" . $server . "<br> - Port: " + $port);
}

// Performt the login
$message = "AAAC".$password.chr(13);	// All passwords start with AAAC and end in the ASCII Char 13 [The carriage return key]
$result = socket_write($socket, $message, strlen($message));

echo $result;
// Read socket data and process
//while (true) {
    
//}
?>