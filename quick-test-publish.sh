echo "10000 messages, 10B each"

AMQP_URL=amqp://guest:guest@localhost/?heartbeat=100\&timeout=1
COUNT=10000
SIZE=10
QUEUE=publish-test
TOKEN=test

php -dmemory_limit=-1 src/purge.php $AMQP_URL $QUEUE
php -dmemory_limit=-1 src/publish-phpamqplib.php $AMQP_URL $QUEUE $COUNT $SIZE $TOKEN

php -dmemory_limit=-1 src/purge.php $AMQP_URL $QUEUE
php -dmemory_limit=-1 src/publish-bunny.php $AMQP_URL $QUEUE $COUNT $SIZE $TOKEN

php -dmemory_limit=-1 src/purge.php $AMQP_URL $QUEUE
php -dmemory_limit=-1 src/publish-butteramqp.php $AMQP_URL $QUEUE $COUNT $SIZE $TOKEN

echo "10000 messages, 10KB each"

AMQP_URL=amqp://guest:guest@localhost/?heartbeat=100\&timeout=1
COUNT=10000
SIZE=10000
QUEUE=publish-test
TOKEN=test

php -dmemory_limit=-1 src/purge.php $AMQP_URL $QUEUE
php -dmemory_limit=-1 src/publish-phpamqplib.php $AMQP_URL $QUEUE $COUNT $SIZE $TOKEN

php -dmemory_limit=-1 src/purge.php $AMQP_URL $QUEUE
php -dmemory_limit=-1 src/publish-bunny.php $AMQP_URL $QUEUE $COUNT $SIZE $TOKEN

php -dmemory_limit=-1 src/purge.php $AMQP_URL $QUEUE
php -dmemory_limit=-1 src/publish-butteramqp.php $AMQP_URL $QUEUE $COUNT $SIZE $TOKEN

echo "100 messages, 10MB each"

AMQP_URL=amqp://guest:guest@localhost/?heartbeat=100\&timeout=1
COUNT=1
SIZE=10000000
QUEUE=publish-test
TOKEN=test

php -dmemory_limit=-1 src/purge.php $AMQP_URL $QUEUE
php -dmemory_limit=-1 src/publish-phpamqplib.php $AMQP_URL $QUEUE $COUNT $SIZE $TOKEN

php -dmemory_limit=-1 src/purge.php $AMQP_URL $QUEUE
php -dmemory_limit=-1 src/publish-bunny.php $AMQP_URL $QUEUE $COUNT $SIZE $TOKEN

php -dmemory_limit=-1 src/purge.php $AMQP_URL $QUEUE
php -dmemory_limit=-1 src/publish-butteramqp.php $AMQP_URL $QUEUE $COUNT $SIZE $TOKEN
