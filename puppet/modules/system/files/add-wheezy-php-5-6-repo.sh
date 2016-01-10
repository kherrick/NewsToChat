#!/usr/bin/env bash

echo "deb http://packages.dotdeb.org wheezy-php56 all" >> /etc/apt/sources.list.d/dotdeb.list
echo "deb-src http://packages.dotdeb.org wheezy-php56 all" >> /etc/apt/sources.list.d/dotdeb.list

wget -qO - http://www.dotdeb.org/dotdeb.gpg | sudo apt-key add -

if [ $? -ne 0 ]; then
  echo Adding Dotdeb gpg key failed.
  exit 1
else
  touch /opt/vagrant-provision/.add-wheezy-php-5.6-repo
fi