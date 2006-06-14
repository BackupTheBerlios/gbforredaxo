<?php
/**
 * Guestbook Addon 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @author Koala (diverse Erweiterungen)
 * @package redaxo3
 * @version $Id: function_gbook_file.php,v 1.1 2006/06/14 22:34:07 koala_s Exp $
 */

/**
 * Speichere Status in Textfile
 * 
 * @param int Status
 */
function gbook_saveStatusInFile($status = 1) {
  global $REX;
  
  // Pfad zur Statusdatei 
  $dir = $REX['INCLUDE_PATH'].'/addons/guestbook/config/';
  $filename = 'status.txt';
  
  if (isset ($status) and ($status == 0 or $status == 1)) {
    if (is_writable($dir) && $fp = fopen($dir.$filename, "w")) {
      fputs($fp, $status);
      fclose($fp);
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }
}

/**
 * Lese Status aus Textfile
 * 
 * @return int  Status
 */
function gbook_readStatusFromFile() {
  global $REX;
  
  // Pfad zur Statusdatei 
  $dir = $REX['INCLUDE_PATH'].'/addons/guestbook/config/';
  $filename = 'status.txt';
  
  if (is_readable($dir) && $fp = fopen($dir.$filename, "r")) {
    // lese nur ein Zeichen aus der Datei 
    $status = fgets($fp, 2);
    fclose($fp);
  }
  // wenn alles gut ging, gib den Status zurück 
  if (isset ($status) and ($status == 0 or $status == 1)) {
    return $status;
  } else {
    return false;
  }
}














?>