# zadanie_rekrutacyjne

## Notes
It is configured only for dev and test purposes. That why you need to do some steps described below to run dev/test.

For production purposes will be needed some more configurations of application, configs and docker implementations (e.q. copy files to container instead of mouting volume with project files, implementing database fixtures and migrations into Dockerfiles, creating and mouting volumnes for symfony /var or databases files or nginx confs inside Dockerfile instead mouting it as docker-composer volumes etc. I hope that tasks was not a matter of recruitement task :) 

## How To Run Dev

1. Clone repository:
```git clone git@github.com:kamil-jakubowski/zadanie_rekrutacyjne.git <your_folder>``` and `cd  <your_folder>`

2. If you use some locally(host) ports 8080 for http or 33061 for mysql please change it to others in /.env file vars NGINX_PORT and MYSQL_PORT
3. If you use some audioteka_* prefixed docker containers please change in file ./env var  DOCKER_PREFIX="audioteka" 
4. There is entrypoint for "php" service that install composer dependencies and set all project and var files to 775 and 1000:www-data - its only for development purposes on windows/wsl2:linux  or linux. 1000 is default linux/unix/wsl user so you will have access to project files on the host. www-data is the container php users that runs php and nginx to proper work of services
5. There is mapped as volume SQL script and creates database 'audioteka' for dev purposes and 'audioteka_test' for test purposes
6. To log to mysql databases use .env MYSQL_PORT (on the host) user:root and password from .env -> MYSQL_ROOT_PASSWORD
7. Go to PHP container `docker-compose exec php bash`
8. into container run `bin/console doctrine:schema:create`
9. into container run `bin/console doctrine:fixtures:load`
10. confirm with `y` to load default products from the task specifications and fixtures
11. go browser on localhost:8080 (or another port if you change it). Seeing error "No matching accepted Response format could be determined" is a sign that Symfony works fine (I've restricted responses to JSON  by FOSRestBundle because it has to be API) so default symfony page is unavailable :) It's a feature man :)
12. Now you can unit/interg/func test

## How to run tests
1. Go to PHP container in project root directory `docker-compose exec php bash`
2. Run `bin/console doctrine:schema:create --env=test`
3. Run `bin/console doctrine:fixtures:load --env=test`
4. Confirm with `y`
5. run `bin/phpunit` to run all the tests
6. you should see OK (106 tests, 211 assertions)

## how to test dev manually by Postman
1. I've prepared Postman application configuration file to manually testing of an API
2. You can find in in <project root>/docs/audioteka_test.postman_collection.json, you can import in Postman app and do testing
3. Please remember that uuids in URI or params or body will be different in your environment due to generating UUIDs in domain layer. So every fixtures has different UUID. You should login Mysql database and copy proper UUIDs.
4. If you chose another port that localhost::8080 you should edit it at every Postmain endpoint