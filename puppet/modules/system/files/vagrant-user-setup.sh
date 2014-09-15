#!/usr/bin/env bash
su - vagrant -c 'echo cd /vagrant >> /home/vagrant/.bash_profile'

if [ $? -ne 0 ]; then
  echo Writing to /home/vagrant/.bash_profile failed.
  exit 1
else
  touch /opt/vagrant-provision/.vagrant-user-setup
fi

