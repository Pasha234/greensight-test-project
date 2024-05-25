Чтобы запустить этот проект локально через докер:
```bash
docker build -t my-php-app -f ./docker/php-apache.dockerfile .
docker run --rm -p 80:80 -v "$PWD":/var/www/html --name my-running-app my-php-app
```