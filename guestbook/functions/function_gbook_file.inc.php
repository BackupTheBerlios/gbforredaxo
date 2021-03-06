<?php
/**
 * Guestbook Addon 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @author redaxo[at]koalashome[dot]de Sven (Koala) Eichler
 * @author Koala (diverse Erweiterungen)
 * @package redaxo4
 * @version $Id: function_gbook_file.inc.php,v 1.2 2007/10/22 14:33:58 koala_s Exp $
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
  // wenn alles gut ging, gib den Status zur�ck 
  if (isset ($status) and ($status == 0 or $status == 1)) {
    return $status;
  } else {
    return false;
  }
}














?>