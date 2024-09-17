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
6. `php artisan queue:listen`
7. `php artisan websockets:serve`
8. Register and Play!

or use docker:
```
$ docker build --tag=joker .
$ docker run -it -p 80:80 -p 443:443 -p 6001:6001 -p 8025:8025 --rm --name=joker joker
```
## Credits

- [ame1337](https://github.com/ame1337)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
