<?php
require_once(CAMILA_VENDOR_DIR.'tinybutstrong/tinybutstrong/tbs_class.php');
require_once(CAMILA_DIR.'tbs/plugins/tbsdb_jladodb.php');

$template = 'gmap';
if (isset($_GET['mt']) && $_GET['mt']=='osm') {
	$template = 'omap';
}

$camilaWT = new CamilaWorkTable();
$camilaWT->db = $_CAMILA['db'];

$conn = $_CAMILA['db'];
global $conn;

function drawMap($template) {
	global $_CAMILA;
	global $camilaWT;
	global $mapName;

	$mapName = 'temp';
	
	$lang = 'it';
	$camilaTemplate = new CamilaTemplate($lang);
	$params = $camilaTemplate->getParameters();
	
	$serviceTable = 'SERVIZI';

	if ($mapName != '')
	{
		$stmt1 = 'SELECT id as tracker, ${'.$serviceTable.'.NOME} as risorsa, ${'.$serviceTable.'.LETTERA} as lettera, ${'.$serviceTable.'.COLORE} as colore, ${'.$serviceTable.'.LATITUDINE} AS latitudine,${'.$serviceTable.'.LONGITUDINE} AS longitudine, NULL as tst,${'.$serviceTable.'.ORDINE} as ORDINE FROM ${'.$serviceTable.'} WHERE ${'.$serviceTable.'.LATITUDINE} <> ""';
		$stmt2 = 'SELECT DISTINCT g.tracker, ${tracker.risorsa} as risorsa,${tracker.lettera} as lettera,${tracker.colore} as colore, g.lat AS latitudine, g.lon AS longitudine, g.tst, ${tracker.ordine} AS ORDINE FROM geotracker_geotracking g JOIN ( SELECT tracker, MAX(tst) AS max_tst FROM geotracker_geotracking GROUP BY tracker ) latest ON g.tracker = latest.tracker AND g.tst = latest.max_tst INNER JOIN ${TRACKER} ON ${tracker.tracker}=g.tracker';
		$queryList = $stmt1.' UNION '.$stmt2;
		$stmt1.= ' ORDER BY ${'.$serviceTable.'.ORDINE},${'.$serviceTable.'.NOME}';
		$stmt2.= ' ORDER BY ${tracker.ordine},${tracker.risorsa}';

		$TBS = new clsTinyButStrong();
		$TBS->SetOption(array('render'=>TBS_OUTPUT));
		$TBS->SetOption('noerr', true);
		$TBS->SetVarRefItem('apikey', $params['chiave_mappa_google']);

		$TBS->LoadTemplate(CAMILA_APP_PATH.'/plugins/'.basename(dirname(__FILE__)).'/templates/tbs/it/'.$template.'.htm');
		$TBS->MergeBlock('res','adodb',$camilaWT->parseWorktableSqlStatement($queryList));
		$TBS->MergeBlock('res2','adodb',$camilaWT->parseWorktableSqlStatement($stmt2));
		$TBS->MergeBlock('res3','adodb',$camilaWT->parseWorktableSqlStatement($stmt1));
		
		$_CAMILA['page']->add_userdefined(new CHAW_tbs($TBS, true));

		/*$refrCode = "<script>function refreshPage() {window.location.reload();};setInterval(refreshPage, 5000);</script>";
		$_CAMILA['page']->add_raw(new HAW_raw(HAW_HTML, $refrCode));*/
	}
	else
	{
		$camilaUI->insertLineBreak();
		$camilaUI->insertWarning('Nessun intervento in corso!');
	}
}

drawMap($template);
?>