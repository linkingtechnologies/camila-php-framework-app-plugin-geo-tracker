<?php
require_once(CAMILA_VENDOR_DIR.'tinybutstrong/tinybutstrong/tbs_class.php');
require_once(CAMILA_DIR.'tbs/plugins/tbsdb_jladodb.php');

global $_CAMILA;	
$camilaWT = new CamilaWorkTable();
$camilaWT->db = $_CAMILA['db'];

$conn = $_CAMILA['db'];
global $conn;


function drawMap() {
	global $_CAMILA;
	global $camilaWT;
	global $mapName;

	$mapName = 'temp';
	
	$lang = 'it';
	$camilaTemplate = new CamilaTemplate($lang);
	$params = $camilaTemplate->getParameters();

	if ($mapName != '')
	{		
		/*$stmt = 'SELECT lat as latitude,lon AS longitude FROM geotracker_geotracking where tracker = \''.$_GET['tracker'].'\' order by tst DESC';
		
		if (isset($_GET['h'])) {
			$stmt = 'SELECT lat as latitude,lon AS longitude FROM geotracker_geotracking where tracker = \''.$_GET['tracker'].'\' AND tst >= datetime(\'now\', \'-'.$_GET['h'].' hours\') AND type=\'location\' order by tst DESC';
		}*/
		
		$stmt = 'SELECT lat as latitude,lon AS longitude FROM geotracker_geotracking where tracker = \''.$_GET['tracker'].'\' order by tst DESC';
		
		if (isset($_GET['h'])) {
			$stmt = 'SELECT lat as latitude,lon AS longitude FROM geotracker_geotracking where tracker = \''.$_GET['tracker'].'\' AND tst >= NOW() - INTERVAL '. $_GET['h'] . ' HOUR AND type=\'location\' order by tst DESC';
		}
		
		$TBS = new clsTinyButStrong();
		$TBS->SetOption(array('render'=>TBS_OUTPUT));
		$TBS->SetOption('noerr', false);
		$TBS->SetVarRefItem('apikey', $params['chiave_mappa_google']);

		$TBS->LoadTemplate(CAMILA_APP_PATH.'/plugins/'.basename(dirname(__FILE__)).'/templates/tbs/it/gmap-path.htm');
		$TBS->MergeBlock('coord','adodb',$camilaWT->parseWorktableSqlStatement($stmt));
		$_CAMILA['page']->add_userdefined(new CHAW_tbs($TBS, true));		
	}
	else
	{
		$camilaUI->insertLineBreak();
		$camilaUI->insertWarning('Nessun intervento in corso!');
	}
}

drawMap();
?>