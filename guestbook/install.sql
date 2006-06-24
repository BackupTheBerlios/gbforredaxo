CREATE TABLE `rex_9_gbook` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `status` enum('0','1') NOT NULL default '1',
  `author` varchar(50) NOT NULL default '',
  `message` text NOT NULL,
  `url` varchar(200) default NULL,
  `email` varchar(50) default NULL,
  `city` varchar(50) default NULL,
  `created` int(10) unsigned NOT NULL default '0',
  `reply` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM


INSERT INTO `rex_modultyp` (`id`, `label`, `name`, `category_id`, `ausgabe`, `bausgabe`, `eingabe`, `func`, `php_enable`, `html_enable`, `perm_category`, `createuser`, `updateuser`, `createdate`, `updatedate`) 
VALUES (NULL, '', 'Gästebuch - Formular', 0, '<?php\r\nrequire_once $REX[''INCLUDE_PATH''].''/addons/guestbook/modules/module.form.inc.php'';\r\n\r\n$f1 = <<<EOD\r\nREX_VALUE[1]\r\nEOD;\r\n\r\n$f2 = <<<EOD\r\nREX_VALUE[2]\r\nEOD;\r\n\r\ngbook_form_output($f1, $f2);\r\n?>', '', '<?php\r\nrequire_once $REX[''INCLUDE_PATH''].''/addons/guestbook/modules/module.form.inc.php'';\r\n\r\n$f1 = <<<EOD\r\nREX_VALUE[1]\r\nEOD;\r\n\r\n$f2 = <<<EOD\r\nREX_VALUE[2]\r\nEOD;\r\n\r\ngbook_form_input($f1, $f2);\r\n?>', '', 0, 0, '', '', '', UNIX_TIMESTAMP(), 0);

INSERT INTO `rex_modultyp` (`id`, `label`, `name`, `category_id`, `ausgabe`, `bausgabe`, `eingabe`, `func`, `php_enable`, `html_enable`, `perm_category`, `createuser`, `updateuser`, `createdate`, `updatedate`) 
VALUES (NULL, '', 'Gästebuch - Eintragsliste', 0, '<?php\r\nrequire_once $REX[''INCLUDE_PATH''].''/addons/guestbook/modules/module.list.inc.php'';\r\n\r\n$f1 = <<<EOD\r\nREX_VALUE[1]\r\nEOD;\r\n\r\n$f2 = <<<EOD\r\nREX_VALUE[2]\r\nEOD;\r\n\r\n$f3 = <<<EOD\r\nREX_VALUE[3]\r\nEOD;\r\n\r\n$f4 = <<<EOD\r\nREX_VALUE[4]\r\nEOD;\r\n\r\n$f5 = <<<EOD\r\nREX_VALUE[5]\r\nEOD;\r\n\r\n$f6 = <<<EOD\r\nREX_VALUE[6]\r\nEOD;\r\n\r\ngbook_list_output($f1, $f2, $f3, $f4, $f5, $f6);\r\n?>', '', '<?php\r\nrequire_once $REX[''INCLUDE_PATH''].''/addons/guestbook/modules/module.list.inc.php'';\r\n\r\n$f1 = <<<EOD\r\nREX_VALUE[1]\r\nEOD;\r\n\r\n$f2 = <<<EOD\r\nREX_VALUE[2]\r\nEOD;\r\n\r\n$f3 = <<<EOD\r\nREX_VALUE[3]\r\nEOD;\r\n\r\n$f4 = <<<EOD\r\nREX_VALUE[4]\r\nEOD;\r\n\r\n$f5 = <<<EOD\r\nREX_VALUE[5]\r\nEOD;\r\n\r\n$f6 = <<<EOD\r\nREX_VALUE[6]\r\nEOD;\r\n\r\nif ( $f1 == '''') $f1 = 5; \r\nif ( $f2 == '''') $f2 = 5; \r\nif ( $f3 == '''') $f3 = ''%d. %b. %Y - %H:%M''; \r\nif ( $f4 == '''') $f4 = ''%to%@%domain%.%tldomain%''; \r\nif ( $f5 == '''') $f5 = 1;  \r\nif ( $f6 == '''') $f6 = 1; \r\n\r\ngbook_list_input($f1, $f2, $f3, $f4, $f5, $f6);\r\n?>', '', 0, 0, '', '', '', UNIX_TIMESTAMP(), 0),

INSERT INTO `rex_action` (`id`, `name`, `action`, `prepost`, `sadd`, `sedit`, `sdelete`) 
VALUES (NULL, 'Gästebuch - Eintragsliste StatusPerDatei', '<?php\r\n// rufe die Funktion zum setzen des Defaultwertes für den Status auf\r\n\r\n$errmsg = '''';\r\nif ($REX_ACTION[''VALUE''][6] == 0 or $REX_ACTION[''VALUE''][6] == 1) {\r\n  require_once $REX[''INCLUDE_PATH''].''/addons/guestbook/functions/function_gbook_file.php'';\r\n  // speichere Status in Datei\r\n  // im Fehlerfall gib eine Meldung zurück\r\n  if (!gbook_saveStatusInFile($REX_ACTION[''VALUE''][6])) {\r\n    $errmsg = $I18N_GBOOK->msg("saveStatusInDatei_Fehler");\r\n  }\r\n} else {\r\n  $errmsg = $I18N_GBOOK->msg("saveStatusInDatei_FalscherStatus");\r\n}\r\n\r\nif (isset ($errmsg) and $errmsg != '''') {\r\n  echo ''<table cellpadding="5" cellspacing="1" width="770"><tr><td class="warning">''.$errmsg.''</td></tr></table><br />'';\r\n}\r\n?>', 1, 0, 1, 0);
