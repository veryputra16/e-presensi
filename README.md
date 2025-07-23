# Absensi Online Laravel 11 dengan Tanda Tangan & Qr Code

### Installation Steps

1. Clone the repository:

    ```bash
    git clone (URL REPOSITORY GITHUB)
    ```

2. Navigate into the project directory:

    ```bash
    cd (lokasi path folder project)
    ```

3. Install PHP dependencies:

    ```bash
    composer install
    ```

4. Install JavaScript dependencies:

    ```bash
    npm install && npm run dev
    ```

5. Copy the `.env.example` file and rename it to `.env`:

    ```bash
    cp .env.example .env
    ```

6. Generate application key:

    ```bash
    php artisan key:generate
    ```

7. Run database migrations then use seeder `--seed`:

    ```bash
    php artisan migrate
    ```

    ```bash
    php artisan migrate:refresh --seed
    ```

8. Start the development server:
    ```bash
    php artisan serve
    ```
