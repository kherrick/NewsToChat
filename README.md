NewsToChat
========

###Development
* Get the required dependencies:
  * `Download Vagrant from:`
    * [http://www.vagrantup.com/downloads.html](http://www.vagrantup.com/downloads.html)
  * `Download VirtualBox and the Extension Pack from:`
    * [https://www.virtualbox.org/](https://www.virtualbox.org/)

* Clone the repository and start the virtual machine (the first time will take awhile to boot)
  * `$ git clone https://github.com/kherrick/NewsToChat`
  * `$ cd NewsToChat/`
  * `$ bin/vm start`
  * `$ bin/vm make init`

* To turn off the virtual machine
  * `$ bin/vm stop`

* To login to the virtual machine
  * `$ bin/vm ssh`

###Hosting
* Successfully hosted on:
  * Amazon AWS / Debian 7.6 / PHP 5.4.4
  * Had to `apt-get install php5-cli php5-curl php5-sqlite` to make things work properly from a default install.

###Methods for usage
* There are three basic commands:
  * `pullnews`: pull from the identified news sources, scan for duplicates in the database, and save them.
  * `pushnews`: push one news item to the identified chat target and mark the item as expired.
  * `maintenance`: perform maintenance on the pool of news articles in the database.
* Example using the vagrant setup: `bin/vm ./newstochat.php pushnews -e true`

* NewsToChat is a very basic script at this point, for example, it can be ran manually or queued up to execute via [cron jobs](https://github.com/kherrick/NewsToChat/blob/master/bin/cron).
