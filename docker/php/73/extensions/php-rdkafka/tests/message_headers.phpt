--TEST--
Message headers
--SKIPIF--
<?php
RD_KAFKA_VERSION >= 0x000b04ff || die("skip");
file_exists(__DIR__."/test_env.php") || die("skip");
--FILE--
<?php

require __DIR__."/test_env.php";

$delivered = 0;

$conf = new RdKafka\Conf();
$conf->setErrorCb(function ($producer, $err, $errstr) {
    printf("%s: %s\n", rd_kafka_err2str($err), $errstr);
    exit;
});
$conf->setDrMsgCb(function ($producer, $msg) use (&$delivered) {
    if ($msg->err) {
        throw new Exception("Message delivery failed: " . $msg->errstr());
    }
    $delivered++;
});

$producer = new RdKafka\Producer($conf);

if ($producer->addBrokers(TEST_KAFKA_BROKERS) < 1) {
    echo "Failed adding brokers\n";
    exit;
}

$topicName = sprintf("test_rdkafka_%s", uniqid());

$topic = $producer->newTopic($topicName);

if (!$producer->getMetadata(false, $topic, 2*1000)) {
    echo "Failed to get metadata, is broker down?\n";
}

$headers = [
    ['key' => 'value'],
    [
        'key1' => 'value1',
        'key2' => 'value2',
        'key3' => 'value3',
    ],
    [],
    null,
    ['key'],
];

foreach ($headers as $index => $header) {
    $topic->producev(0, 0, "message $index", null, $header);
    $producer->poll(0);
}

while ($producer->getOutQLen()) {
    $producer->poll(50);
}

printf("%d messages delivered\n", $delivered);

$consumer = new RdKafka\Consumer($conf);
$consumer->addBrokers(TEST_KAFKA_BROKERS);

$topic = $consumer->newTopic($topicName);
$topic->consumeStart(0, RD_KAFKA_OFFSET_BEGINNING);

$messages = [];

while (true) {
    $msg = $topic->consume(0, 60*1000);
    if (!$msg) {
        continue;
    }
    switch ($msg->err) {
        case RD_KAFKA_RESP_ERR_NO_ERROR:
            $headersString = $msg->headers ?? [];
            array_walk($headersString, function(&$value, $key) {
                $value = "{$key}: {$value}";
            });
            if (empty($headersString)) {
                $headersString = "none";
            } else {
                $headersString = implode(", ", $headersString);
            }
            printf("Got message: %s | Headers: %s\n", $msg->payload, $headersString);
            break;
        case RD_KAFKA_RESP_ERR__PARTITION_EOF:
            echo "EOF\n";
            break 2;
        default:
            throw new Exception($message->errstr());
    }
}
--EXPECT--
5 messages delivered
Got message: message 0 | Headers: key: value
Got message: message 1 | Headers: key1: value1, key2: value2, key3: value3
Got message: message 2 | Headers: none
Got message: message 3 | Headers: none
Got message: message 4 | Headers: none
EOF