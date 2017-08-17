#!/bin/bash

rootpasswd="2755651"
mysql -uroot -p${rootpasswd} -e "drop database timetable;"
mysql -uroot -p${rootpasswd} -e "CREATE DATABASE timetable /*\!40100 DEFAULT CHARACTER SET utf8 */;"
#mysql -uroot -p${rootpasswd} -e "CREATE USER tt@localhost IDENTIFIED BY 'tt1234';"
mysql -uroot -p${rootpasswd} -e "GRANT ALL PRIVILEGES ON timetable.* TO 'tt'@'localhost';"
mysql -uroot -p${rootpasswd} -e "FLUSH PRIVILEGES;"
mysql -uroot -p${rootpasswd} timetable < /home/saad/dev/timetable/timetable.sql

