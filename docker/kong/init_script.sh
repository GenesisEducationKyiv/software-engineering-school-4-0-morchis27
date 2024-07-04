sleep 10
until curl -s http://kong:8001/status | grep -q '"database":{"reachable":true}'
do
  sleep 10
done

echo "kong is up"
sleep 10

until curl -i -X POST --url http://kong:8001/services/ \
    --data 'name=currency-service' \
    --data 'url=http://currency_nginx:80'
do
  sleep 3
done

until  curl -i -X POST --url http://kong:8001/services/currency-service/routes \
          --data 'paths[]=/api/rate' \
          --data 'strip_path=false'
do
   sleep 3
done

printf "\n"
echo "currency-service is up"
printf "\n"

until curl -i -X POST --url http://kong:8001/services/ \
    --data 'name=subscription-service' \
    --data 'url=http://subscription_nginx:80'
do
  sleep 3
done

until  curl -i -X POST --url http://kong:8001/services/subscription-service/routes \
          --data 'paths[]=/api/subscribe'\
          --data 'strip_path=false'
do
   sleep 3
done

printf "\n"
echo "subscription-service is up"
printf "\n"
