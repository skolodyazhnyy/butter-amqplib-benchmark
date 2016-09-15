echo "100000 messages, 10B each"

AMQP_URL=amqp://guest:guest@localhost/?heartbeat=100\&timeout=1
COUNT=100000
SIZE=10
QUEUE=consume-test
TOKEN=test

php -dmemory_limit=-1 src/pre-publish.php $AMQP_URL $QUEUE $COUNT $SIZE $TOKEN
time php -dmemory_limit=-1 src/consume-phpamqplib.php $AMQP_URL $QUEUE $COUNT $TOKEN

php -dmemory_limit=-1 src/pre-publish.php $AMQP_URL $QUEUE $COUNT $SIZE $TOKEN
time php -dmemory_limit=-1 src/consume-butteramqp.php $AMQP_URL $QUEUE $COUNT $TOKEN

echo "100000 messages, 10KB each"

AMQP_URL=amqp://guest:guest@localhost/?heartbeat=100\&timeout=1
COUNT=100000
SIZE=10000
QUEUE=consume-test
TOKEN=test

php -dmemory_limit=-1 src/pre-publish.php $AMQP_URL $QUEUE $COUNT $SIZE $TOKEN
time php -dmemory_limit=-1 src/consume-phpamqplib.php $AMQP_URL $QUEUE $COUNT $TOKEN

php -dmemory_limit=-1 src/pre-publish.php $AMQP_URL $QUEUE $COUNT $SIZE $TOKEN
time php -dmemory_limit=-1 src/consume-butteramqp.php $AMQP_URL $QUEUE $COUNT $TOKEN

echo "1000 messages, 10MB each"

AMQP_URL=amqp://guest:guest@localhost/?heartbeat=100\&timeout=1
COUNT=1000
SIZE=10000000
QUEUE=consume-test
TOKEN=test

php -dmemory_limit=-1 src/pre-publish.php $AMQP_URL $QUEUE $COUNT $SIZE $TOKEN
time php -dmemory_limit=-1 src/consume-phpamqplib.php $AMQP_URL $QUEUE $COUNT $TOKEN

php -dmemory_limit=-1 src/pre-publish.php $AMQP_URL $QUEUE $COUNT $SIZE $TOKEN
time php -dmemory_limit=-1 src/consume-butteramqp.php $AMQP_URL $QUEUE $COUNT $TOKEN
