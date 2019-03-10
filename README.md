# nest-humidity

## Summary
Automatic humidity adjustment docker container for Nest 2nd gen+

## Description
Every hour, this container fetches the current outdoor temperature and current humidity setting from your Nest thermostat. If needed, it sends a new humidity setting (minimum indoor himidity) to the thermostat.

This automates the need to adjust indoor humidity levels to safe levels depending on the outdoor temperature. When the temperature outside drops, condensation and ice can build on your home's walls and windows causing damge ranging from mold growth to cracked window frames. Low humidity levels reduce condensation build up in cold temperatures.

## Pre-requisites
* A Nest thermostat (2nd gen or greater)
* A (whole home) humidifier controlled by the Nest
* A docker host with connectivity to the Internet
* Nest account credentials

## Docker Environment Variables
Stored as an environment variable in the container. The Docker host root user can inspect the container and see these values.
### Required
* NESTUSER=''
  * Username of the Nest account
  * Required
* NESTPW=''
  * Password of the Nest account
  * Required
### Optional
* THERMOSTAT_SERIAL=''
  * Serial of the Nest thermostat
  * Can remain blank if only one device on account
* Temerature range humidity levels (IMPORTANT: Use Farenheit values. Celsius conversion will happen in the container if needed.)
  * HUM_40_PLUS=40
    * Temperatures above 40F
  * HUM_30_40=40
  * HUM_20_30=35
  * HUM_10_20=30
  * HUM_0_10=25
  * HUM_10_0=20
  * HUM_20_10=15
  * HUM_30_20=10
  * HUM_MINUS_30=5
    * Temperatures below -30F
  
## Credit
- Uses nest.class.php from https://github.com/rbrenton/nest-api (a fork of gboudreau/nest-api)
- Levels based on: http://www.startribune.com/fixit-what-is-the-ideal-winter-indoor-humidity-level/11468916/
