<?php
// rufe die Funktion zum setzen des Defaultwertes f�r den Status auf
global $I18N_A63;

$errmsg = '';
if ($REX_ACTION['VALUE'][6] == 0 or $REX_ACTION['VALUE'][6] == 1) {
  require_once $REX['INCLUDE_PATH'].'/addons/guestbook/functions/function_gbook_file.inc.php';
  // speichere Status in Datei
  // im Fehlerfall gib eine Meldung zur�ck
  if (!gbook_saveStatusInFile($REX_ACTION['VALUE'][6])) {
    $errmsg = $I18N_GBOOK->msg("saveStatusInDatei_Fehler");
  }
} else {
  $errmsg = $I18N_GBOOK->msg("saveStatusInDatei_FalscherStatus");
}

if (isset ($errmsg) and $errmsg != '') {
  echo '<table cellpadding="5" cellspacing="1" width="770"><tr><td class="warning">'.$errmsg.'</td></tr></table><br />';
}
?>