# Resturant App

Restful Apis to create a quiz bulder application

## Built using
- PHP Laravel 9
- Nginx
- PHP 8.1.0
- MySQL
- Redis

## Project Setup

1 - Clone this repo
```
git clone https://github.com/shaheer4290/resturant-app.git {yourproject}
cd {yourproject}
cp src/.env.example src/.env
```

2 - Build and run containers
```
docker-compose build
docker-compose up
```

Note: You must have docker and docker-compose installed (https://docs.docker.com/get-started/08_using_compose/)

3 - You can check the running containers by following command
```
docker container ls
```

4- You will to run the following command to run migration and setup the DB and also seed the initial data
```
sh php.sh
composer install
php artisan migrate
php artisan db:seed
```
Note: You will need to make php.sh file executable 

5- To Run the tests you can run the following command
```
sh php.sh
php artisan test
```
6- After the project is setup you can access the api endpoints using the following base URL (port can be different)
```
http://localhost:8080/api/
```
7- I have used MailHog for email testing, you can access the mailbox at the following URL once the application is running
```
http://localhost:6082/
```
## API Docs

Following is the link to API Documentation of Resturant App

https://documenter.getpostman.com/view/23884862/2s8YzMYQqb



