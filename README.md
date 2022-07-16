## About This Project

This task is to build an internal API for a fake financial institution using PHP and
Laravel.

## Brief

While modern banks have evolved to serve a plethora of functions, at their core, banks must
provide certain basic features. Today, your task is to build the basic HTTP API for one of
those banks! Imagine you are designing a backend API for bank employees. It could
ultimately be consumed by multiple frontends (web, iOS, Android etc).

## Requirements for this project
You should have these requirements:
#### PHP > 8.1
#### Laravel > 9.19
#### MYSQL > 8.0.20

## How to install this project

To do that, at first you should set your env variables,
for this part you can use this command:
#### cp .env.example .env

Next step is filling the variables with .env file according to
what you have on your device.

These environment variables are required:
#### DB_HOST
#### DB_PORT
#### DB_DATABASE
#### DB_USERNAME
#### DB_PASSWORD

#### TEST_USER_NAME
#### TEST_USER_EMAIL
#### TEST_USER_PASSWORD

#### ACCESS_TOKEN_NAME
#### CARD_NUMBER_PREFIX

After setting the variables you should run migration with this command: 
#### php artisan migrate

Then run the seeder with this command:
#### php artisan db:seed

Finally run this command to serve the project
#### php artisan serve

## API documentation
To see the documentation using swagger please run this command:
#### php artisan l5-swagger:generate
after running this command you can see the api documentation at http://your-domain/api/documentation

Also you can see the api documentation via curls, to see that you should go thorough the public folder and see them

## Get the bearer access token
To get that please run this command
#### php artisan access-token:create

## Tests

To run the tests, at first you should set env variables in .env.testing
to do that you can run this command: 
#### cp .env.testing.example .env.testing
after that you have to set these env variables
#### DB_HOST
#### DB_PORT
#### DB_DATABASE
#### DB_USERNAME
#### DB_PASSWORD

#### TEST_USER_NAME
#### TEST_USER_EMAIL
#### TEST_USER_PASSWORD

#### ACCESS_TOKEN_NAME
#### CARD_NUMBER_PREFIX

ok cool, now you can run the tests with this command
#### php artisan test


## The end
I hope you enjoy from this project, please tell me your points about this project.

#### Amir Zandieh
#### Amirzandieh1@gamil.com

#### Thanks
