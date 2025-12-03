# Mini Inventory App

A small Laravel-based inventory API for managing products (name, price, stock). This README explains how to run the app locally, test the API, and prepare it for production.

**Requirements:** PHP 8.x, Composer, a database (MySQL/Postgres/SQLite), Node.js (optional for frontend tooling), Git.

**Quick Summary:**

- **API base:** `http://<host>/api`
- **Main resources:** `products`
- **Routes:** `POST /api/products`, `GET /api/products`, `GET /api/products/{id}`, `PUT /api/products/{id}`, `DELETE /api/products/{id}`, `GET /api/products/low-stock`

**Local Setup (development)**

- **Clone & install dependencies:**

  - `composer install`
  - `npm install` (if you use frontend tooling)

- **Environment:**

  - Copy the example environment file: `cp .env.example .env` (or on Windows PowerShell: `Copy-Item .env.example .env`).
  - Set `APP_KEY` (generate with `php artisan key:generate`).
  - Configure DB connection variables in `.env` (`DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

- **Database & migrations:**

  - Create your database and run:
    - `php artisan migrate`
    - Optional: `php artisan db:seed` (if seeders exist)

- **Run server (local):**
  - `php artisan serve --host=127.0.0.1 --port=8000`

**API Usage Examples**
Use `Accept: application/json` and `Content-Type: application/json` headers to ensure JSON responses.

- Create a product (PowerShell):

  - `Invoke-RestMethod -Method Post -Uri 'http://127.0.0.1:8000/api/products' -ContentType 'application/json' -Headers @{Accept='application/json'} -Body '{"name":"Test","price":9.99,"stock":10}'`

- Create a product (curl/bash):

  - ```bash
    curl -X POST 'http://127.0.0.1:8000/api/products' \
    	-H 'Accept: application/json' -H 'Content-Type: application/json' \
    	-d '{"name":"Test Product","price":9.99,"stock":5}'
    ```

- List products:

  - `curl 'http://127.0.0.1:8000/api/products' -H 'Accept: application/json'`

- Get single product:

  - `curl 'http://127.0.0.1:8000/api/products/1' -H 'Accept: application/json'`

- Update product (price & stock):

  - ```bash
    curl -X PUT 'http://127.0.0.1:8000/api/products/1' \
    	-H 'Accept: application/json' -H 'Content-Type: application/json' \
    	-d '{"price":12.5,"stock":10}'
    ```

- Delete product:

  - `curl -X DELETE 'http://127.0.0.1:8000/api/products/1' -H 'Accept: application/json'`

- Low-stock list (stock &lt; 10):
  - `curl 'http://127.0.0.1:8000/api/products/low-stock' -H 'Accept: application/json'`

**Notes about validation & model**

- The `products` model must allow mass assignment for `name`, `price`, `stock`. Ensure `app/Models/products.php` contains:

  - ```php
    protected $fillable = ['name','price','stock'];
    ```

  ```

  ```

- Controllers validate input; `price` is expected as numeric and `stock` as integer.

**Testing**

- If the project contains tests run:
  - `php artisan test` or `vendor/bin/phpunit`

**Production Checklist & Deployment Notes**

- **Environment:** Set strong `APP_KEY`, configure `APP_ENV=production`, `APP_DEBUG=false`.
- **Database:** Use a managed DB or secure server; set credentials in environment variables.
- **Optimize:**

  - `php artisan config:cache`
  - `php artisan route:cache`
  - `php artisan view:cache`

- **Queue & workers:** Use Supervisor (or equivalent) to manage `php artisan queue:work` processes if you use queues.
- **Static assets:** Build front-end assets (`npm run build`) and serve via CDN or local optimized webserver.
- **Webserver:** Use Nginx or Apache; example Nginx config should point `root` to the `public` directory and pass PHP requests to php-fpm.
- **File permissions:** Ensure `storage` and `bootstrap/cache` are writable by the webserver user.

**Monitoring & Logs**

- Tail logs: `tail -f storage/logs/laravel.log`
- Configure a log monitoring/rotation solution for production.

**Security notes**

- Set `APP_DEBUG=false` in production to avoid leaking stack traces.
- Use HTTPS with a valid certificate.
- Keep Composer dependencies up to date (`composer update` on maintenance branch or CI environment).

**Troubleshooting**

- If you get HTML error pages in curl responses, include `-H 'Accept: application/json'` to receive JSON; then check `storage/logs/laravel.log` for errors.
- Common errors:
  - `Class App\Http\Controllers\Request does not exist` — ensure controllers `use Illuminate\Http\Request;` when type-hinting `Request`.
  - Mass assignment exceptions — add `$fillable` to the model.

**Contributing / Development workflow**

- Fork → feature branch → PR. Run tests and linting before opening a PR.

**Contact / Support**

- For questions, open an issue in the project repository with reproduction steps and relevant logs.
