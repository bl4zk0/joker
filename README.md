## ჯოკერი ონლაინ
## Joker online 

Multiplayer online card game powered by Laravel and Bootstrap.

<img src=joker.png />

## Usage:
1. Clone this repository
2. `composer install`
3. `cp .env.example .env`
4. `php artisan storage:link`
5. `php artisan migrate`
6. `php artisan db:seed` [users](database/seeds/DatabaseSeeder.php) password is _password_
7. `php artisan queue:listen`
8. `php artisan websockets:serve`
9. Step 6, or register, confirm links on localhost:8025 and Play!

or use docker:
```
$ docker build --tag=joker .
$ docker run -it --restart always -p 80:80 -p 6001:6001 -p 8025:8025 -p 33060:3306 --name=joker joker
```
## Credits

- [ame1337](https://github.com/ame1337)

## License

The MIT License (MIT). See [License File](LICENSE.md) for more information.
