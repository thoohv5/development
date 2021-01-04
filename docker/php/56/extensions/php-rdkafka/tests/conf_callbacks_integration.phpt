--TEST--
RdKafka\Conf
--SKIPIF--
<?php
RD_KAFKA_VERSION >= 0x090000 || die("skip");
file_exists(__DIR__."/test_env.php") || die("skip");
--FILE--
<?php

require __DIR__."/test_env.php";

$conf = new RdKafka\Conf();

$topicConf = new RdKafka\TopicConf();
$topicConf->set('auto.offset.reset', 'smallest');

$conf->setDefaultTopicConf($topicConf);
$conf->set('metadata.broker.list', TEST_KAFKA_BROKERS);
$conf->set('group.id', sprintf("test_rdkafka_group_%s", uniqid()));
$conf->set('statistics.interval.ms', 10);

$conf->setOffsetCommitCb(function ($consumer, $error, $topicPartitions) {
    echo "Offset " . $topicPartitions[0]->getOffset() . " committed.\n";
});

$consumerLagFound = false;
$conf->setStatsCb(function ($consumer, $json) use (&$consumerLagFound) {
    if ($consumerLagFound) {
        return;
    }

    // At some point there should be a consumer lag of 9
    if (false !== strpos($json, 'consumer_lag":9')) {
        $consumerLagFound = true;
    }
});

$producer = new RdKafka\Producer($conf);

$topicName = sprintf("test_rdkafka_%s", uniqid());
$topic = $producer->newTopic($topicName);

for ($i = 0; $i < 10; $i++) {
    $topic->produce(0, 0, "message $i");
    $producer->poll(0);
}

while ($producer->getOutQLen()) {
    $producer->poll(50);
}

// Make sure there is enough time for the stats_cb to pick up the consumer lag
sleep(1);

$consumer = new RdKafka\KafkaConsumer($conf);
$consumer->subscribe([$topicName]);

while (true) {
    $msg = $consumer->consume(60 * 1000);

    if (!$msg) {
        continue;
    }

    switch ($msg->err) {
        case RD_KAFKA_RESP_ERR_NO_ERROR:
            $consumer->commit($msg);

            break;
        case RD_KAFKA_RESP_ERR__PARTITION_EOF:
            break 2;
        default:
            throw new Exception($msg->errstr());
    }
}

var_dump($consumerLagFound);

--EXPECT--
Offset 1 committed.
Offset 2 committed.
Offset 3 committed.
Offset 4 committed.
Offset 5 committed.
Offset 6 committed.
Offset 7 committed.
Offset 8 committed.
Offset 9 committed.
Offset 10 committed.
bool(true)
