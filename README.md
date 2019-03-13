# nest-humidity

## Summary
Automatic humidity adjustment docker container for Nest 2nd gen+

## Description
Every hour, this container fetches the current outdoor temperature and current humidity setting from your Nest thermostat. If needed, it sends a new humidity setting (minimum indoor humidity %) to the thermostat.

This automates the need to adjust indoor humidity levels to safe levels depending on the outdoor temperature. When the temperature outside drops, condensation and ice can build on your home's walls and windows causing damage ranging from mold growth to cracked window frames. Low humidity levels reduce condensation build-up in cold temperatures.

Likewise, low humidity levels can exacerbate health problems through the cracking of skin and irritation of the respiratory system. Maintaining a balance of indoor humidity is essential to a healthy body and healthy home.

## Pre-requisites
* A Nest thermostat (2nd gen or greater)
* A (whole home) humidifier *controlled* by the Nest
* A docker host with connectivity to the Internet
* Your https://home.nest.com account credentials

## Docker Environment Variables
Stored as an environment variable in the container. **Note:** The Docker host root user can inspect the container and see these values. However, if your root account has been compromised, you might have bigger problems.

### Required
* NESTUSER=''
  * Username of the Nest account
* NESTPW=''
  * Password of the Nest account

### Optional
* THERMOSTAT_SERIAL=''
  * Serial of the Nest thermostat
  * Can remain blank if only one device on account
* Temerature range humidity levels
  * Value is relative humidity in %
  * The defaults are recommended -- see credits
  * `HUM_40_PLUS=40`
    * Temperatures above 40F
  * `HUM_30_40=40`
    * Temperatures above 30F
  * `HUM_20_30=35`
    * Temperatures above 20F
  * `HUM_10_20=30`
    * Temperatures above 10F
  * `HUM_0_10=25`
    * Temperatures above 0F
  * `HUM_10_0=20`
    * Temperatures above -10F
  * `HUM_20_10=15`
    * Temperatures above -20F
  * `HUM_30_20=10`
    * Temperatures above -30F
  * `HUM_MINUS_30=5`
    * Temperatures below -30F

## Farenheit AND Celsius Support
The variable names above include Farenheit temperature ranges. Use the F scale when choosing your humidity level. If your Nest is configured for Celsius, appropriate conversion will happen in the container.

## Credit
- Uses nest.class.php from https://github.com/rbrenton/nest-api (a fork of gboudreau/nest-api)
- Levels based on: http://www.startribune.com/fixit-what-is-the-ideal-winter-indoor-humidity-level/11468916/
