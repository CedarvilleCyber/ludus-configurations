# README
This role aims to deploy a web app as simply as possible. At the moment, it 
supports NodeJS web apps and LAMP web apps. 

To deploy a web app on a LAMP stack, this role makes use of the following roles: 
- geerlingguy.apache
- geerlingguy.mysql
- geerlingguy.php
- geerlingguy.nodejs

This role downloads the web app as a .tar.gz archive from the internet. Simply
specify where you want to download your archived web app from (I recommend GitHub)
and this role will set it up for you. Same goes for your SQL dump file (used to 
set up a SQL database and import records into it). 
