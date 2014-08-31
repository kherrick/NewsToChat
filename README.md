NewsToChat
========

###Download the project and run the build script to setup the required dependencies.
* Steps:
  * `$ git clone https://github.com/kherrick/NewsToChat`
  * `$ cd NewsToChat/`
  * `$ make init`

###Hosting
* Successfully hosted on:
  * Amazon AWS / Debian 7.6 / PHP 5.4.4
  * Had to `apt-get install php5-cli php5-curl php5-sqlite` to make things work properly from a default install.

###Methods for usage
* Current there are two basic commands:
  * `pullnews`: this will pull from the identified news sources, scan for duplicates in the database, and save them.
  * `pushnews`: this will push one news item to the identified chat target and mark the item as expired.

* NewsToChat is a very basic script at this point. For example, it can only be manually ran from the command line or queued up to execute via cron jobs. 
