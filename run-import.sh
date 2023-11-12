echo "RUNNING APP..."
echo "---------"
docker-compose exec app php /var/app/src/app.php || echo "Error while running app..."

echo "---------"
echo 'DONE.'