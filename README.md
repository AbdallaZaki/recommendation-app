# Simple Recommendation App

This a simple Recommendation app to find your highest ranked restaurant 

## Getting Started

Please clone this app with:

git clone https://github.com/AbdallaZaki/recommendation-app.git

### Prerequisites

1- PHP 7.2+ installed on your machine

2- composer

### Installing

1- copy .env.example file to .env with this command

```
cp .env.example .env 
```
2- install packages using composer

```
composer install
```

3- run key generation command to create app secret key

```
php artisan key:generate
```
## You should set this value in your env file

Add map related values in your env file before running the seeder, this value for example:

```
MAP_AREA_LAT=30.012647 

MAP_AREA_LAN=31.210113

MAP_RADIUS=1
```
## You should run the below seeder 

you should run this seeder to create restaurants and meals with there related data:

```
php artisan db:seed --class=AddRestaurantsAndMealSeeder
```
## Running the tests

Just run this command in the project root to run tests:

```
vendor/bin/phpunit
```
## Running the project

Just run this command in the project root to run project on default port 8000:

```
php artisan serve
```

### Testing search api

Just open your browser and enter something like:

```
http://localhost:8000/

```

### Use the shown page to search

1- search with any meal exist in your db 

2- use any map loaction near your center point you created in your env file.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details