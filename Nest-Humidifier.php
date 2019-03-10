<?php

require_once('nest.class.php');

// Set timezone
date_default_timezone_set('America/Chicago');

if ($argc < 3) {
	exit("ERROR: Not enough arguments.\n");
}

// Set Nest username and password.
define('USERNAME', $argv[1]);
define('PASSWORD', $argv[2]);

// Define thermostat serial
define('THERMOSTAT_SERIAL', $argc > 3 ? $argv[4] : null);// Set this if you have more than 1 thermostat

// Convert temp (F) to device scale
function tempFtoScale($f, $scale) {
	$f = (double) $f;
	return ($scale == 'C' ? round(($f-32.0)/1.8,1) : round($f,1));
}

$nest = new Nest();

try {
	// Retrieve data
	$locations = $nest->getUserLocations();
	$thermostat = $nest->getDeviceInfo(THERMOSTAT_SERIAL);
	$scale = $nest->getDeviceTemperatureScale(THERMOSTAT_SERIAL);
	$insideTemp = $thermostat->current_state->temperature;
	$outsideTemp = $locations[0]->outside_temperature;
	$insideHumidity = $thermostat->current_state->humidity;
	$targetHumidityCurrent = $thermostat->target->humidity;

	// Sanity check that we have data
	if(!is_numeric($insideTemp) || !is_numeric($outsideTemp) || !is_numeric($targetHumidityCurrent) || !is_numeric($insideHumidity))
	throw new Exception("temp(inside)={$insideTemp},temp(outside)={$outsideTemp},humidity(inside)={$insideHumidity},target(max_humidity)={$targetHumidityCurrent}");

	// Check the outside temperature and set the humidity accordingly

	// 41° or warmer...
	if($outsideTemp>tempFtoScale(41,$scale)) {
		$targetHumidityAdjusted = 40;
	}
	// 30°to 40°
	else if($outsideTemp>=tempFtoScale(30,$scale)) {
		$targetHumidityAdjusted = 40;
	}
	// 20°to 30°
	else if($outsideTemp>=tempFtoScale(20,$scale)) {
		$targetHumidityAdjusted = 35;
	}
	// 10°to 20°
	else if($outsideTemp>=tempFtoScale(10,$scale)) {
		$targetHumidityAdjusted = 30;
	}
	// 0°to 10°
	else if($outsideTemp>=tempFtoScale(0,$scale)) {
		$targetHumidityAdjusted = 25;
	}
	// 10-below to 0
	else if($outsideTemp>=tempFtoScale(-10,$scale)) {
		$targetHumidityAdjusted = 20;
	}
	// 20-below to 10-below
	else if($outsideTemp>=tempFtoScale(-20,$scale)) {
		$targetHumidityAdjusted = 15;
	}
	// 30-below to 20-below
	else if($outsideTemp>=tempFtoScale(-30,$scale)) {
		$targetHumidityAdjusted = 10;
	}
	// Any colder...
	else {
		$targetHumidityAdjusted = 5;
	}

	echo date("Y/m/d H:i:s")." INFO: IN: ".round($insideTemp,0)."{$scale}, ";
	echo "OUT: ".round($outsideTemp,0)."{$scale}, ";
	echo "CURRENT: ".round($insideHumidity,0)."%, ";
	echo "MINIMUM: ".round($targetHumidityCurrent,0)."%\n";

	if($targetHumidityAdjusted!=$targetHumidityCurrent) {
		// Adjust minimum humidity level if necessary
		echo date("Y/m/d H:i:s")." INFO: MINIMUM CHANGED: {$targetHumidityAdjusted}%.\n";
		$nest->setHumidity($targetHumidityAdjusted, THERMOSTAT_SERIAL);

	} else {
		echo date("Y/m/d H:i:s")." INFO: NO CHANGE.\n";
	}

} catch (Exception $e) {
	echo date("Y/m/d H:i:s")." ERROR: ".$e->getMessage()."\n";
}


?>
