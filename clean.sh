#!/bin/sh

echo "CLEANING CONTAINERS..."
echo "---------"
docker-compose down --rmi all -v

echo "---------"
echo 'DONE.'
