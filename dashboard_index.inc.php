<?php
$camilaWT  = new CamilaWorkTable();
$camilaWT->db = $_CAMILA['db'];

$lang = CAMILA_LANG;
$camilaTemplate = new CamilaTemplate($lang);
$params = $camilaTemplate->getParameters();

$tSheet = $camilaWT->getWorktableSheetId('TRACKER');
$sSheet = $camilaWT->getWorktableSheetId('SERVIZI');

$_CAMILA['page']->add_raw(new HAW_raw(HAW_HTML, '<div class="row">'));	
$_CAMILA['page']->add_raw(new HAW_raw(HAW_HTML, '<div class="col-xs-12 col-md-4">'));
$camilaUI->insertTitle('Tracciamenti', 'globe');
$camilaUI->insertButton('?dashboard=gmap', 'Google Maps', 'map-marker');
$camilaUI->insertButton('?dashboard=omap', 'OpenStreetMap', 'map-marker');
$camilaUI->insertButton('?dashboard=last_position', 'Ultime posizioni rilevate per dispositivo', 'screenshot');
$camilaUI->insertButton('?dashboard=last_position', 'Elenco tracciamenti', 'list');

$_CAMILA['page']->add_raw(new HAW_raw(HAW_HTML, '</div>'));
$_CAMILA['page']->add_raw(new HAW_raw(HAW_HTML, '<div class="col-xs-12 col-md-4">'));
$camilaUI->insertTitle('Dispositivi', 'phone');
$camilaUI->insertButton('?dashboard=last_tracking', 'Abbina dispositivo', 'plus');
$camilaUI->insertButton('cf_worktable'.$tSheet.'.php', 'Elenco tracker/risorse', 'phone');
$camilaUI->insertButton('cf_worktable'.$sSheet.'.php', 'Elenco servizi', 'list');
$_CAMILA['page']->add_raw(new HAW_raw(HAW_HTML, '</div>'));
$_CAMILA['page']->add_raw(new HAW_raw(HAW_HTML, '<div class="col-xs-12 col-md-4">'));
$camilaUI->insertTitle('Configurazione', 'wrench');
$camilaUI->insertButton('?dashboard=configuration', 'Impostazioni', 'list');
$camilaUI->insertDivider();
if (isset($params['URL_base_server']) && $params['URL_base_server'] != '') {
	$url = $params['URL_base_server'] . '/app/geotracker/plugins/geo-tracker/track.php';
    $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$payload = json_encode([
		"_type" => "configuration",
		"mode" => 3,
		"deviceId" => $letters[rand(0, strlen($letters) - 1)] . $letters[rand(0, strlen($letters) - 1)],
		"monitoring" => 1,
		"url" => $url
	]);
	$camilaUI->insertImage('../../lib/qrcode/image.php?msg='.urlencode('owntracks:///config?inline='.urlencode(base64_encode($payload))));
	$camilaUI->insertDivider();
} else {
	$camilaUI->insertWarning('URL Server non configurato!');
}
$_CAMILA['page']->add_raw(new HAW_raw(HAW_HTML, '</div>'));
$_CAMILA['page']->add_raw(new HAW_raw(HAW_HTML, '</div>'));

$_CAMILA['page']->add_raw(new HAW_raw(HAW_HTML, '<div class="row">'));
$_CAMILA['page']->add_raw(new HAW_raw(HAW_HTML, '<div class="col-xs-12">'));
if (isset($params['URL_base_server']) && $params['URL_base_server'] != '') {
	$url = 'URL tracciamento: ' . $params['URL_base_server'] . '/app/geotracker/plugins/geo-tracker/track.php';
	$camilaUI->insertText($url);
}
$_CAMILA['page']->add_raw(new HAW_raw(HAW_HTML, '</div>'));
$_CAMILA['page']->add_raw(new HAW_raw(HAW_HTML, '</div>'));
?>