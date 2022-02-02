<?php

sleep(2); // Just in case server.php is not started yet

var_dump("Starting client");

$context = stream_context_create([
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
    ]
]);

$stream = stream_socket_client(
    "tlsv1.2://server:1234",
    $errno,
    $errstr,
    30,
    STREAM_CLIENT_CONNECT,
    $context
);

while (true) {
    var_dump("Beginning of the loop, waiting for data");

    $readableStreams = [$stream];
    $writableStreams = $ignoredStreams = [];

    $numberReadableStreams = stream_select(
        $readableStreams,
        $writableStreams,
        $ignoredStreams,
        null
    );

    var_dump("Data available. ($numberReadableStreams stream)");
    var_dump("Data: " . fread($stream, 1024));

    sleep(1);
}

// fclose($stream);
