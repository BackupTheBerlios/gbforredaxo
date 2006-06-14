/**
 * Guestbook Addon 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: _readme.txt,v 1.2 2006/06/14 22:34:07 koala_s Exp $
 */

<a href="?page=addon&amp;spage=help&amp;addonname=guestbook&amp;mode=changelog">Changelog</a>

<strong>Beschreibung:</strong>

Einfaches Gästebuch mit Eingabe-Formular und Redaxo-Seitiger Administration.

<em>Das Gästebuch ist seit version RC4 nur noch mit redaxo 3.0 kompatibel!</em>


<strong>Download:</strong>

<a href="http://www.redaxo.de/18-0-addons.html">REDAXO Addon-Sammlung</a>


<strong>Installation:</strong>

- Unter "redaxo/include/addons" einen Ordner "guestbook" anlegen
  <strong>Wichtig: ( Der Name des Ordners muss "guestbook" lauten!)</strong>

- Alle Dateien des Archivs nach "redaxo/include/addons/guestbook" entpacken

- Im Redaxo AddOn Manager das Plugin installieren

- Im Redaxo AddOn Manager das Plugin aktivieren

- Dem Benutzer das recht "guestbook[]" verleihen

- Die Module "Gästebuch - Formular" und "Gästebuch - Eintragsliste" in die ensprechenden Artikel einfügen
  
- CSS auf die eingene Seite anpassen

- fertig ;)


<strong>Update von einer früheren Version:</strong>

- Alle Gästebuch Module löschen (Dabei gehen die Gästebuch-Einträge nicht verloren!)

- Weiter: siehe <strong>Installation</strong>


<strong>Actions:</strong>
<em>Gästebuch - Eintragsliste StatusPerDatei</em>
<p>PRE/POST: POST
STATUS: EDIT</p><p><?php
// rufe die Funktion zum setzen des Defaultwertes für den Status auf

$errmsg = '';
if ($REX_ACTION['VALUE'][6] == 0 or $REX_ACTION['VALUE'][6] == 1) {
  require_once $REX['INCLUDE_PATH'].'/addons/guestbook/functions/function_gbook_file.php';
  // speichere Status in Datei
  // im Fehlerfall gib eine Meldung zurück
  if (!gbook_saveStatusInFile($REX_ACTION['VALUE'][6])) {
    $errmsg = $I18N_GBOOK->msg("saveStatusInDatei_Fehler");
  }
} else {
  $errmsg = $I18N_GBOOK->msg("saveStatusInDatei_FalscherStatus");
}

if (isset ($errmsg) and $errmsg != '') {
  echo '<table cellpadding="5" cellspacing="1" width="770"><tr><td class="warning">'.$errmsg.'</td></tr></table><br />';
}
?></p>

<em>Gästebuch - Eintragsliste StatusPerDB</em>
<p>PRE/POST: POST
STATUS: EDIT</p><p><?php
// rufe die Funktion zum setzen des Defaultwertes für den Status auf

$errmsg = '';
if ($REX_ACTION['VALUE'][6] == 0 or $REX_ACTION['VALUE'][6] == 1) {
  require_once $REX['INCLUDE_PATH'].'/addons/guestbook/modules/module.list.inc.php';
  // speichere Status in DB
  // im Fehlerfall gib eine Meldung zurück
  if (!gbook_saveStatusInDB($REX_ACTION['VALUE'][6])) {
    $errmsg = $I18N_GBOOK->msg("saveStatusInDB_SQLFehler");
  }
} else {
  $errmsg = $I18N_GBOOK->msg("saveStatusInDB_FalscherStatus");
}

if (isset ($errmsg) and $errmsg != '') {
  echo '<table cellpadding="5" cellspacing="1" width="770"><tr><td class="warning">'.$errmsg.'</td></tr></table><br />';
}
?></p>



<strong>Credits:</strong>

- andre.5tz vom REDAXO-Forum

- <a href="http://www.blumbeet.de">Thomas Blum (tbaddade) vom REDAXO-Team</a>

- PEN vom REDAXO-Forum

    Vielen dank an alle die Bugs gemeldet oder Verbesserungsvorschläge gegeben haben.