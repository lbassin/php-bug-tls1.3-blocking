<?php

$context = stream_context_create([
    'ssl' => [
        'local_cert' => '/app/certs/cert.pem',
        'local_pk' => '/app/certs/key.pem',
        'verify_peer' => false,
    ]
]);

$server = stream_socket_server(
    "tlsv1.3://0.0.0.0:1234",
    $errno,
    $errstr,
    STREAM_SERVER_BIND | STREAM_SERVER_LISTEN,
    $context
);

if (!$server) {
    die("$errstr ($errno)");
}

var_dump("Waiting client");
$client = stream_socket_accept($server);
var_dump("Accepted: " . stream_socket_get_name($client, true));

if ($client === false) {
    die("Client error");
}

sleep(5); // Wait 5 seconds before doing anything so tls can do whatever it wants

fwrite($client, 'something'); // Send data to the client

var_dump("Data sent");
while (true) {
    sleep(1); // Keep connection open
}

// fclose($client);
