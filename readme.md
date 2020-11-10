#Safetrade
## How to get setup
### Broadcasting
This application uses [Laravel Echo Server](https://github.com/tlaverdure/laravel-echo-server) and Redis driver.  
You also need to replace the values in the `.env` file with 
your appropriate values.
```
BROADCAST_DRIVER=redis
CACHE_DRIVER=file
QUEUE_CONNECTION=redis
SESSION_DRIVER=file
SESSION_LIFETIME=120

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```
## Database seed
The Asset and User bets need to have initial database data.  You can populate those by running the following command:
```
php artisan db:seed
```
## HOW TO TURN-ON DOCKER FOR THIS PROJECT
1. Go to the directory of the `LARADOCK FOLDER` using a terminal.
2. run the command `docker-compose up -d nginx postgres pgadmin redis memcached laravel-echo-server`.
3. `docker-compose exec workspace bash` <- you will access the container of your project virtually using this command.
4. Go to the directory of your project. Example: `cd safetrade_backend`
5. Once you're inside your virtually accessed project, you can now run ARTISAN COMMANDS. 

###Below are the 3 major commands you need to execute.
It will require 2 terminals for these commands, so you need to repeat step 3 and 4 in 
another terminal in the same directory.
6. `php artisan queue:work` <- first terminal 
7. `php artisan generate:random-bets cash` <- second terminal
8. `php artisan asset:compute-price cash` <- second terminal after you execute step 2.

We use queuing for processing background jobs so we need the queue processor with the following command:
```
php artisan queue:work
```
## Scheduling
Bots are scheduled through cronjobs. More info on [Laravel Scheduling](https://laravel.com/docs/7.x/scheduling).  
To get started, you just have to add the following line to your cron job:
```
* * * * * cd /home/vagrant/safetrade && php artisan schedule:run >> /dev/null 2>&1
```
## Summary
To sum it up, we need to run several processes:
1. `laravel-echo-server start`
1. `php artisan queue:listen`
1. add cronjob
