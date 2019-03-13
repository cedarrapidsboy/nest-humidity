#!/bin/bash

DATE=$(date +"%Y%m%d%H%M%S")

#setup cron
touch /var/log/cron.log
echo "0 * * * * $(which php) /opt/nest/Nest-Humidifier.php ${NESTUSER} ${NESTPW} >> /var/log/cron.log 2>&1" > /etc/cron.d/nest-cron
echo "\n# New line because cron wants it" >> /etc/cron.d/nest-cron
chmod 0644 /etc/cron.d/nest-cron
crontab /etc/cron.d/nest-cron

#check for old log and archive it
if [ -s /var/log/cron.log ]
then
	echo "INFO: Archiving old log."
	tar cf - /var/log/cron.log | gzip -9 > /var/log/cron.$DATE.log.tar.gz
	truncate -s 0 /var/log/cron.log
else
	echo "INFO: Old log does not exist or is empty."
fi

#Run cron
echo "INFO: Starting cron."
printenv | grep "HUM_" > /etc/environment
cron

#tail it to the console
echo "INFO: Watch here for hourly updates to the thermostat..."