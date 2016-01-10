class system {

    file { '/opt/vagrant-provision':
        ensure => directory,
        owner => 'root',
        group => 'root',
        mode => 0755
    }

    file { '/opt/vagrant-provision/bin':
        require => File['/opt/vagrant-provision'],
        ensure => directory,
        owner => 'root',
        group => 'root',
        mode => 0755
    }

    file { '/opt/vagrant-provision/bin/vagrant-user-setup.sh':
        require => File['/opt/vagrant-provision/bin'],
        source => 'puppet:///modules/system/vagrant-user-setup.sh',
        ensure => file,
        owner => 'root',
        group => 'root',
        mode => 0755
    }

    exec { 'vagrant-user-setup':
        require => File['/opt/vagrant-provision/bin/vagrant-user-setup.sh'],
        command => '/opt/vagrant-provision/bin/vagrant-user-setup.sh',
        creates => '/opt/vagrant-provision/.vagrant-user-setup'
    }

    file { '/opt/vagrant-provision/bin/add-wheezy-php-5-6-repo.sh':
        require => File['/opt/vagrant-provision/bin'],
        source => 'puppet:///modules/system/add-wheezy-php-5-6-repo.sh',
        ensure => file,
        owner => 'root',
        group => 'root',
        mode => 0755
    }

    exec { 'add-wheezy-php-5-6-repo':
        require => File['/opt/vagrant-provision/bin/add-wheezy-php-5-6-repo.sh'],
        command => '/opt/vagrant-provision/bin/add-wheezy-php-5-6-repo.sh',
        creates => '/opt/vagrant-provision/.add-wheezy-php-5-6-repo'
    }

    exec { 'apt-get-update':
        require => Exec['add-wheezy-php-5-6-repo'],
        command => '/usr/bin/apt-get update'
    }

    package { 'git':
        require => Exec['apt-get-update'],
        ensure => installed
    }

}

