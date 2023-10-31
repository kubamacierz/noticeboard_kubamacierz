noticeboard
===========

This is a project of Notice Board. Created in Symfony 3.4 on php 7.3.

Short description:
There is a possibility to add a notice on board which is possible to browse both by logged and not-logged users. When you log in you can also add new notice which is valid for one week. As regular user you can also browse, edit (except of expiration date), and your notices. As admin user additionally you can browse (including expired), edit(including expiration date) and delete all notices. Admin user has also a possibility to manage all users.

Requirements:
-php 7.3
-composer
-mySQL 

How to install on Linux-Ubuntu:
-download this repo
-run composer install
-fill ./app/config/parameters.yml file (especially "database_name", "database_user" and "database_password")
-hit command "bin/console doctrine:database:create"
-hit command "bin/console doctrine:schema:update --force"
-hit command "bin/console server:start"
-Have fun using this app

A Symfony project created on August 10, 2019, 10:49 am.
