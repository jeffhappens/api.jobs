
# Prototype Job Board API
This ia an opinionated Job Search API that uses Laravel 9/Breeze. Make sure your environment meets the requirements before running.

You can clone the companion frontend here: [https://github.com/jeffhappens/spa.jobs](https://github.com/jeffhappens/spa.jobs)

## Run Locally

Clone the project

```bash
  git clone https://github.com/jeffhappens/api.jobs.git
```

Go to the project directory

```bash
  cd api.jobs
```

Install dependencies

```bash
  composer install
```

Copy .env.example to .env
```
  cp .env.example .env
```

Generate an application key

```
  php artisan key:generate  
```


## Frontend Authentication
Auth is handled by Laravel Sanctum so you will need some additional configuration.

Open .env and enter the values for the following keys:
* FRONTEND_URL (http://localhost:5173)
* SESSION_DOMAIN (localhost)
* SANCTUM_STATEFUL_DOMAINS (usually localhost:5173)
* DB_DATABASE (if not 'laravel')



Run the migration and (optionally) seed the database. See database/factories and database/seeders to get a sense of the generated data.
```
php artisan migrate --seed
```

Start the server

```bash
  php artisan serve
```

Visit http://localhost:8000 to make sure the api is running.

