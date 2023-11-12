#!/bin/sh

echo "CREATING CONTAINERS..."
echo "---------"
docker-compose up -d --force-recreate --remove-orphan
echo

echo "ENSURING SERVICES HAVE TIME TO START..."
echo "---------"
sleep 15

echo "---------"
echo 'DONE.'
