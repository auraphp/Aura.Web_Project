export AURA_PROJECT_SERVER_HOST="localhost:8080"
php -S $AURA_PROJECT_SERVER_HOST -t ../../web/ &
PID=$!
phpunit
STATUS=$?
kill $PID
exit $STATUS
