<?php
$camilaUI->insertTitle('Dati intervento', 'calendar');

$lang = 'it';
$camilaTemplate = new CamilaTemplate($lang);
$params = $camilaTemplate->getParameters();

$form = new phpform('templeteparams');
$form->submitbutton = 'Aggiorna';
$form->drawrules = true;
$form->preservecontext = true;

foreach ($params as $key => $val) {
	new form_textbox($form, $key, ucfirst(str_replace("_"," ",$key)), false, 50, 100);
	$form->fields[$key]->set_br(2);
	$form->fields[$key]->set_css_class('form-control');
	$form->fields[$key]->value = $val;
}

if ($form->process())
{
	foreach ($params as $key => $val) {
		$camilaTemplate->setParameter($key, $form->fields[$key]->value);
	}
	
	$text = new CHAW_text('');
	$_CAMILA['page']->add_text($text);
	camila_information_text('I dati sono stati aggiornati correttamente!');
}
	else
		$form->draw();
?>