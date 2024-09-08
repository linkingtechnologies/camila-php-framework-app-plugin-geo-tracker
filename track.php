<?php
require('../../var/config.php');
require('../../../../lib/adodb5/adodb.inc.php');

$db = NewADOConnection(CAMILA_DB_DSN);

$json = file_get_contents('php://input');
if (!empty($json)) {
	$d = json_decode($json);

	if (json_last_error() === JSON_ERROR_NONE) {
		$rid = uniqid();
		$data = [
		'id' => $d->tid.$rid.$d->_id.$d->tst,
		'tst' => date('Y-m-d H:i:s', $d->tst),
		'inserted_at' => date('Y-m-d H:i:s', time()),
		'tracker' => $d->topic . '/' . $d->tid
		];
		addProperty($d, '_id', $data);
		addProperty($d, '_type', $data);
		addProperty($d, 'created_at', $data);
		addProperty($d, 'lat', $data);
		addProperty($d, 'lon', $data);
		addProperty($d, 'topic', $data);
		addProperty($d, 'tid', $data);
		addProperty($d, 'BSSID', $data);
		addProperty($d, 'SSID', $data);
		addProperty($d, 'acc', $data);
		addProperty($d, 'alt', $data);
		addProperty($d, 'batt', $data);
		addProperty($d, 'bs', $data);
		addProperty($d, 'conn', $data);
		addProperty($d, 'm', $data);
		addProperty($d, 't', $data);
		addProperty($d, 'vac', $data);
		addProperty($d, 'vel', $data);
		addProperty($d, 'cog', $data);
		addProperty($d, 'rad', $data);
		addProperty($d, 'p', $data);
		addProperty($d, 'poi', $data);
		addProperty($d, 'tag', $data);
		addProperty($d, 'inregions', $data);
		addProperty($d, 'inrids', $data);

		$table = CAMILA_APPLICATION_PREFIX.'geotracking';
		$result = $db->AutoExecute($table, $data, 'INSERT');

		if ($result) {
			http_response_code(200);
			echo "OK";
		} else {
			http_response_code(500);
			logError($json, $db->ErrorMsg());
		}
	} else {
		http_response_code(500);
		logError($json, json_last_error_msg());
	}
}

function addProperty($object, $property, &$data) {
    if (is_object($object) && property_exists($object, $property)) {
		if ($property == '_type') {
			$data['type'] = $object->$property;
		} if ($property == '_id') {
			$data['aid'] = $object->$property;
		} if ($property == 'created_at') {
			$data[$property] = date('Y-m-d H:i:s', $object->$property);
		} else {
			$data[$property] = $object->$property;
		}
    }
}

function logError($payload, $errorMessage, $logFile = '../../var/log/tracker_errors.txt') {
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[{$timestamp}] ERROR: {$errorMessage} | Payload: " . json_encode($payload) . PHP_EOL;
    file_put_contents($logFile, $logEntry, FILE_APPEND);
}

?>
