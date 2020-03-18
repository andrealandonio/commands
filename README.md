# Yii2 Commands project

Commands project is a simple Yii2 project that allow you to perform console commands and a basic web views to solve easy operations.

## Directory structure


      assets/             contains assets definition
      commands/           contains console commands (controllers)
      components/         contains project components (behaviors)
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources
      widgets/            contains project widgets

## Requirements

The minimum requirement by this project is that your Web server supports PHP 7. 
For now, this projects is tested only on Ubuntu OS.

## Usage

First of all, after you have downloaded the repo, you need to retrieve project libraries. Simply run the following command in your project root folder (ps: you need to have "composer" already installed):

~~~
composer update
~~~

To work with all the commands you need to configure an alias to launch the Yii2 application. 
To do this you have to create a script. Here the instructions:

~~~
##### create and edit a file named "cmd" #####
sudo vi /usr/bin/cmd

##### paste this code, then save the file #####
# Executing YII commands project
/usr/bin/php /var/www/commands/yii $@

##### change file permissions #####
sudo chmod 755 /usr/bin/cmd
~~~

Now you should be able to run console commands, using `cmd` alias directly from your shell.

~~~
cmd help
~~~

## Configuration

### AWS Credentials

Credentials for the AWS account should be stored in the ".aws/credentials" file on your machine with the following syntax (you can add as many profiles as you want):

```
[default]
aws_access_key_id=XXXXX
aws_secret_access_key=XXXXX
region=XXXXX
```

### Database

For now, no database needed

## Notes

Here below a list of all the commands available from command line:

- `aws`
allow you to perform actions on a AWS account.

- `sites`
allow you to perform sites actions (especially work with users).

- `env`
allow you to perform actions on a LNMP stack (linux, nginx, mysql and php). With this command you can, start, stop, restart and
check the status of your stack (you need to install nginx, php-fpm and varnish by your hands).

- `vpn`
allow you to perform actions on a VPN using OpenVPN client (you need to install it by your hands). With this command you can, start,
stop and check the status of your VPN. It's simply managed by a configuration ".opvn" file and a credential "auth.txt" file.

- `video`
allow you to perform actions on videos.

## Testing

Tests are located in `tests` directory. They are developed with [Codeception PHP Testing Framework](http://codeception.com/).
By default there are 3 test suites:

- `unit`
- `functional`
- `acceptance`

Tests can be executed by running

```
vendor/bin/codecept run
```

The command above will execute unit and functional tests. Unit tests are testing the system components, while functional
tests are for testing user interaction. Acceptance tests are disabled by default as they require additional setup since
they perform testing in real browser. 

### Running  acceptance tests

To execute acceptance tests do the following:  

1. Rename `tests/acceptance.suite.yml.example` to `tests/acceptance.suite.yml` to enable suite configuration

2. Replace `codeception/base` package in `composer.json` with `codeception/codeception` to install full featured
   version of Codeception

3. Update dependencies with Composer 

    ```
    composer update  
    ```

4. Download [Selenium Server](http://www.seleniumhq.org/download/) and launch it:

    ```
    java -jar ~/selenium-server-standalone-x.xx.x.jar
    ```

    In case of using Selenium Server 3.0 with Firefox browser since v48 or Google Chrome since v53 you must download [GeckoDriver](https://github.com/mozilla/geckodriver/releases) or [ChromeDriver](https://sites.google.com/a/chromium.org/chromedriver/downloads) and launch Selenium with it:

    ```
    # for Firefox
    java -jar -Dwebdriver.gecko.driver=~/geckodriver ~/selenium-server-standalone-3.xx.x.jar
    
    # for Google Chrome
    java -jar -Dwebdriver.chrome.driver=~/chromedriver ~/selenium-server-standalone-3.xx.x.jar
    ``` 
    
    As an alternative way you can use already configured Docker container with older versions of Selenium and Firefox:
    
    ```
    docker run --net=host selenium/standalone-firefox:2.53.0
    ```

5. (Optional) Create `yii2_basic_tests` database and update it by applying migrations if you have them.

   ```
   tests/bin/yii migrate
   ```

   The database configuration can be found at `config/test_db.php`.


6. Start web server:

    ```
    tests/bin/yii serve
    ```

7. Now you can run all available tests

   ```
   # run all available tests
   vendor/bin/codecept run

   # run acceptance tests
   vendor/bin/codecept run acceptance

   # run only unit and functional tests
   vendor/bin/codecept run unit,functional
   ```
   
### Code coverage support

By default, code coverage is disabled in `codeception.yml` configuration file, you should uncomment needed rows to be able
to collect code coverage. You can run your tests and collect coverage with the following command:

```
#collect coverage for all tests
vendor/bin/codecept run -- --coverage-html --coverage-xml

#collect coverage only for unit tests
vendor/bin/codecept run unit -- --coverage-html --coverage-xml

#collect coverage for unit and functional tests
vendor/bin/codecept run functional,unit -- --coverage-html --coverage-xml
```

You can see code coverage output under the `tests/_output` directory.
