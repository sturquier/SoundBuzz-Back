SoundBuzz-Back
===

### Run l'API en local

* `git clone https://github.com/sturquier/SoundBuzz-Back.git`
* `cd SoundBuzz-Back`
* `composer install`
* Editer la configuration de la BDD dans le fichier .env
	* Typiquement `DATABASE_URL=mysql://root:root@127.0.0.1:3306/db_soundbuzz`
* `php bin/console doctrine:database:create`
* `php bin/console doctrine:migrations:migrate`
* `php bin/console doctrine:fixtures:load`
* `php -S localhost:8080 -t public/` 