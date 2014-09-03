#!/usr/bin/env bash

#change directory to the project root
bash_source="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd $bash_source
cd ..

declare -a errors

#check for required project resources
if [ -d vendor ]; then
    errors[${#errors[*]}]='vendor/'
else
	echo 'Running: bin/composer install'
	bin/composer install
fi

#check for a database
if [ -f databases/db.sqlite ]; then
    errors[${#errors[*]}]='databases/db.sqlite'
else
	echo 'Running: bin/doctrine/init-database.php...'
	bin/doctrine/init-database.php
fi

#display errors
if [ ${#errors[*]} -gt 0 ]; then
	IFS=$'\n'
	echo
	echo Resources not built because they already exist:
    echo

    for error in ${errors[*]}; do
        echo -e $error
    done
    echo

    exit 1
fi

exit 0
