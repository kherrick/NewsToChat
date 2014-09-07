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
* There are three basic commands:
  * `pullnews`: pull from the identified news sources, scan for duplicates in the database, and save them.
  * `pushnews`: push one news item to the identified chat target and mark the item as expired.
  * `maintenance`: perform maintenance on the pool of news articles in the database.

* NewsToChat is a very basic script at this point, for example, it can be ran manually or queued up to execute via cron jobs.
