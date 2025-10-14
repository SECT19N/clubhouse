# 1. create project
composer create-project laravel/laravel clubhub "12.*"

# 2. DB + .env
#   (edit .env)

# 3. generate model + migration + controller
php artisan make:model Club -m -c -r
php artisan make:model Student -m -c -r
php artisan make:model Event -m -c -r

# 4. pivot
php artisan make:migration create_club_student_table

# 5. run migrations
php artisan migrate

# 6. factories
php artisan make:factory ClubFactory
php artisan make:factory StudentFactory
php artisan make:factory EventFactory

# 7. seeders
php artisan make:seeder ClubSeeder
php artisan make:seeder StudentSeeder
php artisan make:seeder EventSeeder

# 8. seed
php artisan db:seed

# 9. test
php artisan serve
curl http://localhost:8000/api/clubs | jq