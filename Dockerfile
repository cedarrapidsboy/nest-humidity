FROM php:7.1-cli-stretch

# Install cron
RUN apt-get -q -y update &&\
    apt-get install -q -y cron

# Add PHP files
WORKDIR /opt/nest/
COPY Nest-Humidifier.php nest.class.php start.sh ./

ENV NESTUSER=''
ENV NESTPW=''
ENV THERMOSTAT_SERIAL=''
ENV HUM_40_PLUS=40
ENV HUM_30_40=40
ENV HUM_20_30=35
ENV HUM_10_20=30
ENV HUM_0_10=25
ENV HUM_10_0=20
ENV HUM_20_10=15
ENV HUM_30_20=10
ENV HUM_MINUS_30=5

# Create the log file to be able to run tail
RUN touch /var/log/cron.log &&\
    chmod +x /opt/nest/start.sh

# Run the command on container startup
CMD sh /opt/nest/start.sh && tail -f /var/log/cron.log