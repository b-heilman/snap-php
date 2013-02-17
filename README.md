SNAP-PHP : A plug and play framework for PHP
==================================================

Configuring for use
--------------------------------------
You need to add the Snap-PHP/src directory to your include path.  
The autoloader for the framework is located in /Snap/Lib/Core/Bootstrap.php

Database Support 
--------------------------------------
This currently uses mysqli.  If you are using Ubuntu with the apt-get installed version, you will need to run "apt-get install php5-mysql"
However, I am currently in the process of porting in Doctrine to be the ORM / db connector, so that limitation will be going away.

Configuring for use
--------------------------------------
While lacking documentation, a few demos exist to show the functionality of the framework.  For the most part, the demos are good to go as is.
However, you may need to edit demo\php\Snap\Config\Db\Mysql.php and add your local database settings, or create a user like in 'sql examples'.

Here is a list of the demos:
- www/admin.php         : The administrative console, shows a simple loading of a page
- www/compositeForm.php : Ability to stack multiple forms into one, yes perserve their independence.
- www/doctrine.php      : Uses a router, and shows how to interface with Doctrine inside the framework
- www/form.php          : A basic form submission, also contains some validation options
- www/index.php         : The basic index page, shows how to configure a view to have javascript and css associated with it
- www/mvc.php           : Shows how to configure feeds and views
- www/reflective.php    : Shows how a form can be set up to ajax submit back to itself
- www/routing           : Utilizes mod_rewrite and the \Snap\Control\Router
