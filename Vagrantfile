# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|
  config.vm.hostname = "NewsToChat"
  config.vm.boot_timeout = 3600
  config.vm.box = "puppetlabs/debian-7.8-32-puppet-enterprise"
  config.vm.box_check_update = false

  config.vm.network :forwarded_port, guest: 8080, host: 8080

  config.vm.provider :virtualbox do |vb|
    vb.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
    vb.customize ["modifyvm", :id, "--memory", "1536"]
  end

  config.vm.provision :puppet do |puppet|
    puppet.manifests_path = "puppet/"
    puppet.manifest_file  = "site.pp"
    puppet.module_path    = "puppet/modules/"
    # puppet.options        = "--verbose --debug"
  end

end
