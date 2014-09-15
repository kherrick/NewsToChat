class system {

    exec { 'apt-get-update':
        command => '/usr/bin/apt-get update'
    }

    package { 'git':
        require => Exec['apt-get-update'],
        ensure => installed
    }

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

}

