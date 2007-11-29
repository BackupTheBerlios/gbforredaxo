-- Guestbook Addon
--
-- @author redaxo[at]koalashome[dot]de Sven (Koala) Eichler
-- @package redaxo4
-- @version $Id:$

CREATE TABLE `%TABLE_PREFIX%63_gbook` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `status` tinyint(4) NOT NULL default '1',
  `author` varchar(50) NOT NULL default '',
  `message` text NOT NULL,
  `url` varchar(200) default NULL,
  `email` varchar(50) default NULL,
  `city` varchar(50) default NULL,
  `created` int(10) unsigned NOT NULL default '0',
  `reply` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;


INSERT INTO `%TABLE_PREFIX%module` (`id`, `name`, `category_id`, `ausgabe`, `eingabe`, `createuser`, `updateuser`, `createdate`, `updatedate`)
VALUES (NULL, 'Gaestebuch - Eintragsliste', 0, '<?php\r\n// setze Variable mit Addonnamen \r\nif (!isset ($AKTUELLER_ADDON_NAME) or $AKTUELLER_ADDON_NAME == '''') { $AKTUELLER_ADDON_NAME = ''guestbook''; } else {\r\n  // Diese hier definierte Variable darf noch nirgends in Redxo verwendet worden sein.\r\n  echo ''Schwerere Fehler aufegtreten! Die Variable <span style="font-style:italic;">"''.$AKTUELLER_ADDON_NAME.''"</span> ist bereits belegt. Wende dich an Modulersteller.'';\r\n}\r\n// ist das Addon aktiv?\r\nif (OOAddon::isAvailable($AKTUELLER_ADDON_NAME)) {\r\n  require_once $REX[''INCLUDE_PATH''].''/addons/''.$AKTUELLER_ADDON_NAME.''/modules/module.list.inc.php'';\r\n  \r\n  $f1 = <<<EOD\r\nREX_VALUE[1]\r\nEOD;\r\n  \r\n  $f2 = <<<EOD\r\nREX_VALUE[2]\r\nEOD;\r\n  \r\n  $f3 = <<<EOD\r\nREX_VALUE[3]\r\nEOD;\r\n  \r\n  $f4 = <<<EOD\r\nREX_VALUE[4]\r\nEOD;\r\n  \r\n  $f5 = <<<EOD\r\nREX_VALUE[5]\r\nEOD;\r\n  \r\n  $f6 = <<<EOD\r\nREX_VALUE[6]\r\nEOD;\r\n  \r\n  gbook_list_output($f1, $f2, $f3, $f4, $f5, $f6);\r\n} else {\r\n  if (!isset ($AddonInaktiv_Fehlerausgegeben)) $AddonInaktiv_Fehlerausgegeben = false;\r\n  if ($REX[''REDAXO'']) {\r\n    // Diese Information ist nur im Backend zu sehen\r\n    echo ''Addon <span style="font-style:italic;">''.$AKTUELLER_ADDON_NAME.''</span> ist nicht aktiv!'';\r\n  } elseif (!$AddonInaktiv_Fehlerausgegeben) {\r\n    // hier k�nnte z.B. ein Link auf einen Artikel rein, der auf eine nicht \r\n    // vorhandene Seite hinweist\r\n    // oder\r\n    // einfach nur einen allgemeinen Text ausgeben:\r\n    echo ''Diese Seite ist zur Zeit nicht verf�gbar.'';\r\n    $AddonInaktiv_Fehlerausgegeben = true;\r\n  }\r\n}\r\nunset ($AKTUELLER_ADDON_NAME);\r\n?>', '<?php\r\n// setze Variable mit Addonnamen \r\nif (!isset ($AKTUELLER_ADDON_NAME) or $AKTUELLER_ADDON_NAME == '''') { $AKTUELLER_ADDON_NAME = ''guestbook''; } else {\r\n  // Diese hier definierte Variable darf noch nirgends in Redxo verwendet worden sein.\r\n  echo ''Schwerere Fehler aufegtreten! Die Variable <span style="font-style:italic;">"''.$AKTUELLER_ADDON_NAME.''"</span> ist bereits belegt. Wende dich an Modulersteller.'';\r\n}\r\n// ist das Addon aktiv?\r\nif (OOAddon::isAvailable($AKTUELLER_ADDON_NAME)) {\r\n  require_once $REX[''INCLUDE_PATH''].''/addons/''.$AKTUELLER_ADDON_NAME.''/modules/module.list.inc.php'';\r\n  \r\n  $f1 = <<<EOD\r\nREX_VALUE[1]\r\nEOD;\r\n  \r\n  $f2 = <<<EOD\r\nREX_VALUE[2]\r\nEOD;\r\n  \r\n  $f3 = <<<EOD\r\nREX_VALUE[3]\r\nEOD;\r\n  \r\n  $f4 = <<<EOD\r\nREX_VALUE[4]\r\nEOD;\r\n  \r\n  $f5 = <<<EOD\r\nREX_VALUE[5]\r\nEOD;\r\n  \r\n  $f6 = <<<EOD\r\nREX_VALUE[6]\r\nEOD;\r\n  \r\n  if ( $f1 == '''') $f1 = 5; \r\n  if ( $f2 == '''') $f2 = 5; \r\n  if ( $f3 == '''') $f3 = ''%d. %b. %Y - %H:%M''; \r\n  if ( $f4 == '''') $f4 = ''%to%@%domain%.%tldomain%''; \r\n  if ( $f5 == '''') $f5 = 1;  \r\n  if ( $f6 == '''') $f6 = 1; \r\n  \r\n  gbook_list_input($f1, $f2, $f3, $f4, $f5, $f6);\r\n} else {\r\n  if ($REX[''REDAXO'']) {\r\n    // Diese Information ist nur im Backend zu sehen\r\n    echo ''Addon <span style="font-style:italic;">''.$AKTUELLER_ADDON_NAME.''</span> ist nicht aktiv!'';\r\n  } else {\r\n    // hier k�nnte z.B. ein Link auf einen Artikel rein, der auf eine nicht \r\n    // vorhandene Seite hinweist\r\n    // oder\r\n    // einfach nur einen allgemeinen Text ausgeben:\r\n    echo ''Diese Seite ist zur Zeit nicht verf�gbar.'';\r\n  }\r\n}\r\nunset ($AKTUELLER_ADDON_NAME);\r\n?>', '%USER%', '', %TIME%, '');

INSERT INTO `%TABLE_PREFIX%module` (`id`, `name`, `category_id`, `ausgabe`, `eingabe`, `createuser`, `updateuser`, `createdate`, `updatedate`)
VALUES (NULL, 'Gaestebuch - Formular', 0, '<?php\r\n// setze Variable mit Addonnamen \r\nif (!isset ($AKTUELLER_ADDON_NAME) or $AKTUELLER_ADDON_NAME == '''') { $AKTUELLER_ADDON_NAME = ''guestbook''; } else {\r\n  // Diese hier definierte Variable darf noch nirgends in Redxo verwendet worden sein.\r\n  echo ''Schwerere Fehler aufegtreten! Die Variable <span style="font-style:italic;">"''.$AKTUELLER_ADDON_NAME.''"</span> ist bereits belegt. Wende dich an Modulersteller.'';\r\n}\r\n// ist das Addon aktiv?\r\nif (OOAddon::isAvailable($AKTUELLER_ADDON_NAME)) {\r\n  require_once $REX[''INCLUDE_PATH''].''/addons/''.$AKTUELLER_ADDON_NAME.''/modules/module.form.inc.php'';\r\n  \r\n  $f1 = <<<EOD\r\nREX_VALUE[1]\r\nEOD;\r\n  \r\n  $f2 = <<<EOD\r\nREX_VALUE[2]\r\nEOD;\r\n  \r\n  $f3 = <<<EOD\r\nREX_VALUE[3]\r\nEOD;\r\n\r\n  $f4 = <<<EOD\r\nREX_VALUE[4]\r\nEOD;\r\n\r\n  if ( $f3 == '''') $f3 = 0; \r\n  if ( $f4 == '''') $f4 = 0; \r\n\r\n  gbook_form_output($f1, $f2, $f3, $f4);\r\n} else {\r\n  if (!isset ($AddonInaktiv_Fehlerausgegeben)) $AddonInaktiv_Fehlerausgegeben = false;\r\n  if ($REX[''REDAXO'']) {\r\n    // Diese Information ist nur im Backend zu sehen\r\n    echo ''Addon <span style="font-style:italic;">''.$AKTUELLER_ADDON_NAME.''</span> ist nicht aktiv!'';\r\n  } elseif (!$AddonInaktiv_Fehlerausgegeben) {\r\n    // hier k�nnte z.B. ein Link auf einen Artikel rein, der auf eine nicht \r\n    // vorhandene Seite hinweist\r\n    // oder\r\n    // einfach nur einen allgemeinen Text ausgeben:\r\n    echo ''Diese Seite ist zur Zeit nicht verf�gbar.'';\r\n    $AddonInaktiv_Fehlerausgegeben = true;\r\n  }\r\n}\r\nunset ($AKTUELLER_ADDON_NAME);\r\n?>', '<?php\r\n// setze Variable mit Addonnamen \r\nif (!isset ($AKTUELLER_ADDON_NAME) or $AKTUELLER_ADDON_NAME == '''') { $AKTUELLER_ADDON_NAME = ''guestbook''; } else {\r\n  // Diese hier definierte Variable darf noch nirgends in Redxo verwendet worden sein.\r\n  echo ''Schwerere Fehler aufegtreten! Die Variable <span style="font-style:italic;">"''.$AKTUELLER_ADDON_NAME.''"</span> ist bereits belegt. Wende dich an Modulersteller.'';\r\n}\r\n// ist das Addon aktiv?\r\nif (OOAddon::isAvailable($AKTUELLER_ADDON_NAME)) {\r\n  require_once $REX[''INCLUDE_PATH''].''/addons/''.$AKTUELLER_ADDON_NAME.''/modules/module.form.inc.php'';\r\n  \r\n  $f1 = <<<EOD\r\nREX_VALUE[1]\r\nEOD;\r\n  \r\n  $f2 = <<<EOD\r\nREX_VALUE[2]\r\nEOD;\r\n  \r\n  $f3 = <<<EOD\r\nREX_VALUE[3]\r\nEOD;\r\n\r\n  $f4 = <<<EOD\r\nREX_VALUE[4]\r\nEOD;\r\n\r\n  if ( $f3 == '''') $f3 = 0; \r\n  if ( $f4 == '''') $f4 = 0; \r\n\r\n  gbook_form_input($f1, $f2, $f3, $f4);\r\n} else {\r\n  if ($REX[''REDAXO'']) {\r\n    // Diese Information ist nur im Backend zu sehen\r\n    echo ''Addon <span style="font-style:italic;">''.$AKTUELLER_ADDON_NAME.''</span> ist nicht aktiv!'';\r\n  } else {\r\n    // hier k�nnte z.B. ein Link auf einen Artikel rein, der auf eine nicht \r\n    // vorhandene Seite hinweist\r\n    // oder\r\n    // einfach nur einen allgemeinen Text ausgeben:\r\n    echo ''Diese Seite ist zur Zeit nicht verf�gbar.'';\r\n  }\r\n}\r\nunset ($AKTUELLER_ADDON_NAME);\r\n?>', '%USER%', '', %TIME%, '');

INSERT INTO `%TABLE_PREFIX%action` (`id`, `name`, `preview`, `presave`, `postsave`, `previewmode`, `presavemode`, `postsavemode`, `createuser`, `updateuser`, `createdate`, `updatedate`)
VALUES (NULL, 'Gaestebuch - Eintragsliste StatusPerDatei', '', '<?php\r\n// rufe die Funktion zum setzen des Defaultwertes f�r den Status auf\r\n\r\n$errmsg = '''';\r\nif ($REX_ACTION[''VALUE''][6] == 0 or $REX_ACTION[''VALUE''][6] == 1) {\r\n  require_once $REX[''INCLUDE_PATH''].''/addons/guestbook/functions/function_gbook_file.inc.php'';\r\n  // speichere Status in Datei\r\n  // im Fehlerfall gib eine Meldung zur�ck\r\n  if (!gbook_saveStatusInFile($REX_ACTION[''VALUE''][6])) {\r\n    $errmsg = $I18N_GBOOK->msg("saveStatusInDatei_Fehler");\r\n  }\r\n} else {\r\n  $errmsg = $I18N_GBOOK->msg("saveStatusInDatei_FalscherStatus");\r\n}\r\n\r\nif (isset ($errmsg) and $errmsg != '''') {\r\n  echo rex_warning($errmsg);\r\n}\r\n?>', '', 0, 2, 0, '%USER%', '', %TIME%, '');
