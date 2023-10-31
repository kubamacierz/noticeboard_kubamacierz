noticeboard
===========

This is a project of Notice Board. Created in Symfony 3.4 on php 7.3.

<p><b>Short description:</b>gi</p>
There is a possibility to add a notice on board which is possible to browse both by logged and not-logged users. When you log in you can also add new notice which is valid for one week. As regular user you can also browse, edit (except of expiration date), and your notices. As admin user additionally you can browse (including expired), edit(including expiration date) and delete all notices. Admin user has also a possibility to manage all users.

<b>Requirements:</b>
<p>-php 7.3</p>
<p>-composer</p>
<p>-mySQL</p> 

<p><b>How to install on Linux-Ubuntu:</b></p>
<p>-download this repo</p>
<p>-run composer install</p>
<p>-fill ./app/config/parameters.yml file (especially "database_name", "database_user" and "database_password")</p>
<p>-hit command "bin/console doctrine:database:create"</p>
<p>-hit command "bin/console doctrine:schema:update --force"</p>
<p>-hit command "bin/console server:start"</p>
<p>-Have fun using this app</p>

A Symfony project created on August 10, 2019, 10:49 am.
