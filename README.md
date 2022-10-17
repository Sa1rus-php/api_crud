# How to run #

Dependencies:

* docker. See [https://docs.docker.com/engine/installation](https://docs.docker.com/engine/installation)
* docker-compose. See [docs.docker.com/compose/install](https://docs.docker.com/compose/install/)

Once you're done, simply `cd` to your project and run `docker-compose up -d`. This will initialise and start all the
containers, then leave them running in the background.

## Services exposed outside your environment ##

You can access your application via **`localhost`**. Mailhog and nginx both respond to any hostname, in case you want to
add your own hostname on your `/etc/hosts`

Service|Address outside containers
-------|--------------------------
Webserver|[localhost:35000](http://localhost:35000)
MySQL|**host:** `localhost`; **port:** `35002`

## Hosts within your environment ##

You'll need to configure your application to use any services you enabled:

Service|Hostname|Port number
------|---------|-----------
php-fpm|php-fpm|9000
MySQL|mysql|3306 (default)

# Docker compose cheatsheet #

**Note:** you need to cd first to where your docker-compose.yml file lives.

* Start containers in the background: `docker-compose up -d`
* Start containers on the foreground: `docker-compose up`. You will see a stream of logs for every container running.
  ctrl+c stops containers.
* Stop containers: `docker-compose stop`
* Kill containers: `docker-compose kill`
* View container logs: `docker-compose logs` for all containers or `docker-compose logs SERVICE_NAME` for the logs of
  all containers in `SERVICE_NAME`.
* Execute command inside of container: `docker-compose exec SERVICE_NAME COMMAND` where `COMMAND` is whatever you want
  to run. Examples:
    * Shell into the PHP container, `docker-compose exec php-fpm bash`
    * Run symfony console, `docker-compose exec php-fpm bin/console`
    * Open a mysql shell, `docker-compose exec mysql mysql -uroot -pCHOSEN_ROOT_PASSWORD`

# Application file permissions #

As in all server environments, your application needs the correct file permissions to work properly. You can change the
files throughout the container, so you won't care if the user exists or has the same ID on your host.

`docker-compose exec php-fpm chown -R www-data:www-data /application/public`

# Simple basic Xdebug configuration with integration to PHPStorm

## Xdebug 3

To configure **Xdebug 3** you need add these lines in php-fpm/php-ini-overrides.ini:

### For linux:

```
xdebug.mode = debug
xdebug.remote_connect_back = true
xdebug.start_with_request = yes
```

### For macOS and Windows:

```
xdebug.mode = debug
xdebug.remote_host = host.docker.internal
xdebug.start_with_request = yes
```

## Add the section “environment” to the php-fpm service in docker-compose.yml:

```
environment:
  PHP_IDE_CONFIG: "serverName=Docker"
```

### Create a server configuration in PHPStorm:

* In PHPStorm open Preferences | Languages & Frameworks | PHP | Servers
* Add new server
* The “Name” field should be the same as the parameter “serverName” value in “environment” in docker-compose.yml (i.e. *
  Docker* in the example above)
* A value of the "port" field should be the same as first port(before a colon) in "webserver" service in
  docker-compose.yml
* Select "Use path mappings" and set mappings between a path to your project on a host system and the Docker container.
* Finally, add “Xdebug helper” extension in your browser, set breakpoints and start debugging



