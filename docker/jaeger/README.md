# jaeger

```
docker-compose up -d

docker-compose restart elasticsearch
docker-compose restart collector
docker-compose restart agent
docker-compose restart query

docker-compose down

# 测试
http://127.0.0.1:8080

# query
http://127.0.0.1:16686

```

```

备注：
虽然有depends_on,但是由于elasticsearch启动的原因，导致query, collector连不上直接挂掉，agent虽然没挂但是也连不上，所以我们需要手动重启query,collector,agent,这个时候再查看应该是都正常启动了。

```