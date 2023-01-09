## Intall
stop all docker container first.

- goes to dlp, do `docker-compose build && docker-compose up -d`
- `docker ps`, should see 3 container running
- `docker-compose exec app bash` enter web container
- `composer install`
- `php artisan migrate:fresh --seed`
- `http://localhost:8000/` see welcome page
- `http://localhost:8000/request-docs/` see api docmentation

## Test
- use postman, check route on doc please.
- goes to dlp, `./vendor/bin/pest`
- test dlp on form `http:8000//localhost/form`

## TroubleShoot
- when there 404 not found: `php artisan optimize`
- when there Class not found: `composer dump-autoload`