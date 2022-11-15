#!/bin/sh

if [ $# -eq 1 ]; then
	n=$1
else
	n=50
fi

php data-grabber.php -p hackerone -g security -a u -r -t
