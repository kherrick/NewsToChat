class php {

    require system

    package { 'php5-cli':
        ensure => installed
    }

    package { 'php5-curl':
        ensure => installed
    }

    package { 'php5-sqlite':
        ensure => installed
    }

}
