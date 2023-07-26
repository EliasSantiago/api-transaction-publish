# API Transaction

API Transaction.

## About the project

- Laravel 8
- Requires PHP 7.3+
- Postgres

## How to run the api?

**Step 1: Clone the project, run the following commands:**

**Step 2: Create file .env**
- cp .env.example .env
- Set APP_URL in .env: APP_URL=localhost:8081/api/
- Set database settings: <br>

DB_CONNECTION=pgsql <br>
DB_HOST=postgres <br>
DB_PORT=5432 <br>
DB_DATABASE=db_apitransaction <br>
DB_USERNAME=postgres <br>
DB_PASSWORD=postgres <br>


**Step 3: Run docker**
- docker-composer up -d

**Step 4: Install dependences:**
- docker-compose exec app composer install

**Step 5: Generate key in .env**
- docker-compose exec app php artisan key:generate

**Step 6: Generate tables**
- docker-compose exec app php artisan migrate

** Documentation API **
- http://localhost:8081/api/documentation

** Dashboard RabbitMQ **
- http://container_id:15672