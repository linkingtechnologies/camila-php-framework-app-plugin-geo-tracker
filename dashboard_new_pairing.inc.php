<?php
$worktableName = 'TRACKER';
$lang = 'it';

$camilaWT  = new CamilaWorkTable();
$camilaWT->db = $_CAMILA['db'];

$tSheet = $camilaWT->getWorktableSheetId('TRACKER');

$camilaUI->insertTitle('Abbinamento Tracker e nuova risorsa', 'phone');

$camilaUI->insertDivider();

$form = new phpform('action');

$form->submitbutton = 'Aggiungi';
$form->drawrules = true;
$form->preservecontext = true;

$key = 'tracker';
new form_textbox($form, $key, 'Tracker', true, 50, 100);
$form->fields[$key]->set_css_class('form-control');
$form->fields[$key]->value = $_GET['tracker'];

$key = 'resource';
new form_textbox($form, $key, 'Risorsa', true, 50, 100);
$form->fields[$key]->set_css_class('form-control');

$key = 'notes';
new form_textbox($form, $key, 'Note', false, 50, 100);
$form->fields[$key]->set_css_class('form-control');

$key = 'color';
new form_static_listbox($form, $key, 'Colore segnaposto mappa', 'rosso,nero,blu,verde,grigio,arancione,viola,bianco,giallo', false, '');

$key = 'letter';
new form_textbox($form, $key, 'Lettera/numero segnaposto mappa', false, 1, 1, '');

if ($form->process())
{
	$text = new CHAW_text('');
	$_CAMILA['page']->add_text($text);

	$fields = Array('Tracker','Risorsa','Colore','Lettera','Note');
	$values = Array($form->fields['tracker']->value,$form->fields['resource']->value, $form->fields['color']->value, $form->fields['letter']->value, $form->fields['notes']->value);
	
	$res = $camilaWT->insertRow($worktableName, $lang, $fields, $values);
	if ($res)
		$camilaUI->insertSuccess('I dati sono stati aggiornati correttamente!');
	else
		$camilaUI->insertError("Errore nell'inserimento dei dati");
	
	$camilaUI->insertDivider();
	
	$camilaUI->insertButton('cf_worktable'.$tSheet.'.php', 'Elenco tracker/risorse', 'phone');
	
} else {
	$form->draw();
}
?>