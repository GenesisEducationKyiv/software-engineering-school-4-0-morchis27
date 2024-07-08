#!/bin/bash
while ! nc -z kafka 9092;
do
        sleep 1;
done;
echo 'kafka is up';

kafka-topics --create --if-not-exists --bootstrap-server kafka:9092 --topic mail-requests --partitions 3 --replication-factor 1;
