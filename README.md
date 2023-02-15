
## Run Locally

Clone the project

```bash
  git clone https://github.com/jeffhappens.api.jobs.git
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


Open .env and enter the values for:
* FRONTEND_URL (http://localhost:5173)
* SESSION_DOMAIN (localhost)
* SANCTUM_STATEFUL_DOMAINS (usually localhost:5173)
* DB_DATABASE (If you are naming your database something other than 'laravel')



Run the migration and (optionally) seed the database
```
php artisan migrate --seed
```

Start the server

```bash
  php artisan serve
```

