# test-policymate
This repository contains the technical test made by Melvin Leroux on October 16th, 2025, for PolicyMate.

#@ setup

In order to launch the project properly follow theses steps.

-Clone the repository
-Install dependencies with `composer install`

-Configure the environment :
`cp .env.example .env`

-Update the database settings in .env

`
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
`

-Create the database in Mysql,phpMyadmin with the database name specified

-Run the migrations

`php artisan migrate`

-Start the development server

`php artisan serve`

-Fill the database
Get into the URL given from your terminal and add /import for example `http://localhost:8001/import`
It will import the data into the database and log the errors
