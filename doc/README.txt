===============
README
===============

REQUIREMENTS

* PHP 5.2.x
* mySQL 5.x
* CakePHP 1.2.x

INSTALLATION

1) Check out /www and /app directories
	- /www should be the root web dir and publicly accessible
	- /app should NOT be publicly accessible but should have permissions so the web server can access it

2) Download the CakePHP library
	- only the 'cake' directory needs to be used and should be NOT publicly accessible but should have permissions so the web server can access it

CONFIGURATION

1) Change /www/index.php
	- define 'ROOT': should point to directory that contains /app
	- define 'APP_DIR' should point to /app (relative to ROOT)
	- define 'CAKE_CORE_INCLUDE_PATH' to /cake directory
	
2) Set /app/config/database.php
	- the default database configuration needs to be set with host/login/password/database
	
3) Set /app/config/core.php
	- set 'Security.salt' to some value

DATABASE

The tables that the app expects must be present or else CakePHP will complain.  SQL statements for creating the tables are in /db

PROBLEMS

If you are having problems, try changing the 'debug' param in /app/config/core.php to '2' instead of '0'.  This will cause CakePHP to emit more helpful messages.

