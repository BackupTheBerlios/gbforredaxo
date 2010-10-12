<?php
// setze Variable mit Addonnamen 
if (!isset ($AKTUELLER_ADDON_NAME) or $AKTUELLER_ADDON_NAME == '') { $AKTUELLER_ADDON_NAME = 'guestbook'; } else {
  // Diese hier definierte Variable darf noch nirgends in Redaxo verwendet worden sein.
  echo 'Schwerere Fehler aufgetreten! Die Variable <span style="font-style:italic;">"'.$AKTUELLER_ADDON_NAME.'"</span> ist bereits belegt. Wende dich an Modulersteller.';
}
// ist das Addon aktiv?
if (OOAddon::isAvailable($AKTUELLER_ADDON_NAME)) {
  require_once $REX['INCLUDE_PATH'].'/addons/'.$AKTUELLER_ADDON_NAME.'/modules/module.form.inc.php';
  
  $f1 = <<<EOD
REX_VALUE[1]
EOD;
  
  $f2 = <<<EOD
REX_VALUE[2]
EOD;
  
  $f3 = <<<EOD
REX_VALUE[3]
EOD;

  $f4 = <<<EOD
REX_VALUE[4]
EOD;

  if ( $f3 == '') $f3 = 0; 
  if ( $f4 == '') $f4 = 0; 

  gbook_form_input($f1, $f2, $f3, $f4);
} else {
  if ($REX['REDAXO']) {
    // Diese Information ist nur im Backend zu sehen
    echo 'Addon <span style="font-style:italic;">'.$AKTUELLER_ADDON_NAME.'</span> ist nicht aktiv!';
  } else {
    // hier k�nnte z.B. ein Link auf einen Artikel rein, der auf eine nicht 
    // vorhandene Seite hinweist
    // oder
    // einfach nur einen allgemeinen Text ausgeben:
    echo 'Diese Seite ist zur Zeit nicht verf�gbar.';
  }
}
unset ($AKTUELLER_ADDON_NAME);
?>