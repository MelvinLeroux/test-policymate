# test-policymate

This repository contains the technical test made by **Melvin Leroux** on **October 16th, 2025**, for **PolicyMate**.

## Setup

Follow these steps to set up and run the project properly:

1. **Clone the repository**
   
```bash
git clone <repository-url>
cd <repository-folder>
````

2. **Install dependencies**

```bash
composer install
```

3. **Configure environment**

Copy the example environment file:

```bash
cp .env.example .env
```

Set up your key APP_KEY
```bash
php artisan key:generate
```

Update the database settings in `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

4. **Create the database**

Create a database in MySQL or phpMyAdmin with the name specified in `.env`.

5. **Run migrations**

```bash
php artisan migrate
```

6. **Prepare CSV file**
   Create the CSV file `sales.csv` with the data inside the `storage/app` folder:

```
laravel/storage/app/sales.csv
```

7. **Start the development server**

```bash
php artisan serve
```

8. **Import data**

Open your browser and navigate to the `/import` route to import data into the database and log errors:

```
http://localhost:8001/import
```

9. **Access reports**

* **Top products by revenue (e.g., top 2 products)**

```
http://localhost:8001/report/topproducts/2
```

* **Monthly revenue summary for a given year (e.g., 2025)**

```
http://localhost:8001/report/monthly-revenue/2025
```

* **Top customers (e.g., top 3 customers)**

```
http://localhost:8001/report/top-customers/3
```

10. **Run tests**

Test the CSV import using PHPUnit:

```bash
php artisan test --filter=ImportCsvTest
```
