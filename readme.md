# Safetrade

## How to get setup

It is recommended to to follow [Laravel Homestead](https://laravel.com/docs/7.x/homestead) in your local development for easier setup.

## Broadcasting

This application uses [Laravel Echo Server](https://github.com/tlaverdure/laravel-echo-server) and Redis driver. Please follow the installation instruction on the link provided. You also need to replace the values in the `.env` file with your appropriate values.

```
LARAVEL_ECHO_SERVER_AUTH_HOST=http://safetrade.local
LARAVEL_ECHO_SERVER_DEBUG=1
LARAVEL_ECHO_SERVER_REDIS_HOST=127.0.0.1
LARAVEL_ECHO_SERVER_REDIS_PORT=6379
LARAVEL_ECHO_SERVER_PROTO=http
```

You can then start the server with the following command:

```
laravel-echo-server start
```

We also use queuing for processing background jobs so you also need to run the queue processor with the following command:

```
php artisan queue:work --queue=price,assets,bets,default
```

## Scheduling

Bots are scheduled through cronjobs. More info on [Laravel Scheduling](https://laravel.com/docs/7.x/scheduling).  To get started, you just have to add the following line to your cron job:

```
* * * * * cd /home/vagrant/safetrade && php artisan schedule:run >> /dev/null 2>&1
```

## Database seed
The Asset and User bets need to have initial database data.  You can populate those by running the following command:

```
php artisan db:seed
```

## Summary

To sum it up, we need to run several processes:

1. `laravel-echo-server start`
1. `php artisan queue:listen`
1. add cronjob
