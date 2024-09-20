<?php
/*  This File is part of Camila PHP Framework
    Copyright (C) 2006-2024 Umberto Bresciani

    Camila PHP Framework is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Camila PHP Framework is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Camila PHP Framework. If not, see <http://www.gnu.org/licenses/>. */

$camilaWT = new CamilaWorkTable();
$camilaWT->db = $_CAMILA['db'];

$mapping = 'tst=Data/Ora#tracker=Tracker#lat=Lat.#lon=Lon.#t=Ev.#id=Id#view_all=Tracciamento#last2=Tracciamento#last4=Tracciamento#last8=Tracciamento#gmaps=Mappa Google';
$title = 'Ultimo tracciamento per dispositivo';

$stmt = 'SELECT DISTINCT g.tracker, ${tracker.risorsa} as Risorsa,g.tracker AS last2, g.tracker AS last4, g.tracker AS last8, g.tracker AS view_all,'.$_CAMILA['db']->Concat('g.lat', "','", 'g.lon').' as Coordinate, g.tst, g.tst AS gmaps FROM geotracker_geotracking g JOIN ( SELECT tracker, MAX(tst) AS max_tst FROM geotracker_geotracking GROUP BY tracker ) latest ON g.tracker = latest.tracker AND g.tst = latest.max_tst LEFT OUTER JOIN ${TRACKER} ON ${tracker.tracker}=g.tracker WHERE g.type=\'location\'';
$stmt = $camilaWT->parseWorktableSqlStatement($stmt);
$report = new report($stmt, $title, 'tst', 'desc',$mapping);
$report->canupdate = false;
$report->candelete = false;

$funzioniCustom = [
    'view_all' => function($row, $val, $record) {
		$tracker = $row->column[0]->text;
		$link = "index.php?dashboard=map_path&tracker=".urlencode($tracker);
		$l = new CHAW_link('Complessivo', $link);
        $row->add_column($l);
    },
	'last2' => function($row, $val, $record) {
		$tracker = $row->column[0]->text;
		$link = "index.php?dashboard=map_path&tracker=".urlencode($tracker).'&h=2';
		$l = new CHAW_link('Ultime 2 ore', $link);
        $row->add_column($l);
    },
	'last4' => function($row, $val, $record) {
		$tracker = $row->column[0]->text;
		$link = "index.php?dashboard=map_path&tracker=".urlencode($tracker).'&h=4';
		$l = new CHAW_link('Ultime 4 ore', $link);
        $row->add_column($l);
    },
	'last8' => function($row, $val, $record) {
		$tracker = $row->column[0]->text;
		$link = "index.php?dashboard=map_path&tracker=".urlencode($tracker).'&h=8';
		$l = new CHAW_link('Ultime 8 ore', $link);
        $row->add_column($l);
    },
	'gmaps' => function($row, $val, $record) {
		$tracker = $row->column[6]->text;
		$link = "https://www.google.com/maps?q={$tracker}";
		$l = new CHAW_link('Google Maps', $link);
        $row->add_column($l);
    }
];
$report->customFunctions = $funzioniCustom;

$report->process();
$report->draw();

$_CAMILA['page']->camila_export_enabled = true;

?>