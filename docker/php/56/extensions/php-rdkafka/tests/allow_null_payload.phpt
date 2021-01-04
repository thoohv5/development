--TEST--
Allow null payload
--SKIPIF--
<?php
file_exists(__DIR__."/test_env.php") || die("skip");
--FILE--
<?php

require __DIR__."/test_env.php";

$topicName = sprintf('test_rdkafka_%s', uniqid());

$producer = new RdKafka\Producer();
$producer->addBrokers(TEST_KAFKA_BROKERS);
$topic = $producer->newTopic($topicName);

$topic->produce(0, 0, NULL, 'message_key_1');

while ($producer->getOutQLen() > 0) {
    $producer->poll(50);
}

$consumer = new RdKafka\Consumer();
$consumer->addBrokers(TEST_KAFKA_BROKERS);

$topic = $consumer->newTopic($topicName);
$topic->consumeStart(0, RD_KAFKA_OFFSET_BEGINNING);

while (true) {
    $message = $topic->consume(0, 1000);
    if ($message === null) {
        continue;
    }
    switch ($message->err) {
        case RD_KAFKA_RESP_ERR_NO_ERROR:
            var_dump($message->payload);
            var_dump($message->key);
            break 2;
    }
}

$topic->consumeStop(0);

--EXPECTF--
NULL
string(13) "message_key_1"