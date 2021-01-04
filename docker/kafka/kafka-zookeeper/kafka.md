# kafka

[TOC]

```
JMX 和 console 端口冲突

使用console之前 `unset JMX_PORT`

```

### topic
```

# list
/opt/kafka/bin/kafka-topics.sh --zookeeper zk1:2181,zk2:2181,zk3:2181 --list

# detail
/opt/kafka/bin/kafka-topics.sh --zookeeper zk1:2181,zk2:2181,zk3:2181 --topic test --describe

# create
/opt/kafka/bin/kafka-topics.sh --zookeeper zk1:2181,zk2:2181,zk3:2181 --create --topic test --replication-factor 2 --partitions 6 

# add partitions
/opt/kafka/bin/kafka-topics.sh --zookeeper zk1:2181,zk2:2181,zk3:2181  --alter --topic tesst --partitions 2

# delete
/opt/kafka/bin/kafka-topics.sh --zookeeper zk1:2181,zk2:2181,zk3:2181 --topic test --delete

```

### producer
```

/opt/kafka/bin/kafka-console-producer.sh --broker-list kafka1:9091,kafka2:9092,kafka3:9093 --topic test

```

### consumer
```

/opt/kafka/bin/kafka-console-consumer.sh --bootstrap-server kafka1:9091,kafka2:9092,kafka3:9093 --topic test --from-beginning

/opt/kafka/bin/kafka-console-consumer.sh --bootstrap-server kafka1:9091,kafka2:9092,kafka3:9093 --topic test --from-beginning --group group_test

```

### consumer group
```

/opt/kafka/bin/kafka-consumer-groups.sh --bootstrap-server kafka1:9091,kafka2:9092,kafka3:9093 --list

/opt/kafka/bin/kafka-consumer-groups.sh --bootstrap-server kafka1:9091,kafka2:9092,kafka3:9093 --group group_test --describe

/opt/kafka/bin/kafka-consumer-groups.sh --bootstrap-server kafka1:9091,kafka2:9092,kafka3:9093 --group group_test --describe --members

```