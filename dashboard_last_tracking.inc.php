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

$table = 'geotracker_geotracking';
$mapping = 'tst=Data/Ora#tracker=Tracker#lat=Lat.#lon=Lon.#t=Ev.#id=Id#gmaps=Google Maps#acc=Accuratezza (m)#batt=Batteria (%)';
$title = 'Ultimi tracciamenti';
$report_fields  = 't.tst,t.tracker,${tracker.risorsa} as Risorsa,t.t,'.$_CAMILA['db']->Concat('t.lat', "','", 't.lon').' as Coordinate, t.id as gmaps,t.id,t.acc,t.batt';
$stmt = 'select ' . $report_fields . ' from ' . $table . ' t LEFT OUTER JOIN ${TRACKER} ON ${tracker.tracker}=t.tracker WHERE t.type=\'location\'';
$stmt = $camilaWT->parseWorktableSqlStatement($stmt);
$report = new report($stmt, $title, 'tst', 'desc', $mapping);
$funzioniCustom = [
    'gmaps' => function($row, $val, $record) {
		$tracker = $row->column[4]->text;
		$link = "https://www.google.com/maps?q={$tracker}";
		$l = new CHAW_link('Google Maps', $link);
        $row->add_column($l);
    }
];
$report->customFunctions = $funzioniCustom;
$report->canupdate = false;
$report->candelete = false;
$report->process();
$report->draw();

/*$stmt = 'SELECT DISTINCT g.tracker, g.lat, g.lon, g.tst FROM geotracker_geotracking g JOIN ( SELECT tracker, MAX(tst) AS max_tst FROM geotracker_geotracking GROUP BY tracker ) latest ON g.tracker = latest.tracker AND g.tst = latest.max_tst';
$report = new report($stmt, 'Titolo', 'tst', 'desc');
$report->canupdate = false;
$report->candelete = false;
$report->process();
$report->draw();*/




$_CAMILA['page']->camila_export_enabled = true;

?>