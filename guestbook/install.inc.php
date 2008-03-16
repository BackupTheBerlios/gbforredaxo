<?php
/**
 * Guestbook Addon 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @author redaxo[at]koalashome[dot]de Sven (Koala) Eichler
 * @package redaxo4
 * @version $Id: install.inc.php,v 1.13 2008/03/16 20:13:33 koala_s Exp $
 */
 
$error = '';
//$Basedir = dirname(__FILE__);

/**
 * Setze SQL-Comparestatus.
 * Notwendig für SQL-Abfrage die nicht mit REDAXO > 4.0.1 funktionieren.
 */
$REX['a63_sql_compare'] = false;

/**
 * Definiere DB-Tabellennamen.
 */
if (!defined('TBL_GBOOK')) {
  define('TBL_GBOOK', $REX['TABLE_PREFIX'].'63_gbook');
}

$_REX['REDAXO-VERSION'] = $REX['VERSION'].'.'.$REX['SUBVERSION'].'.'.$REX['MINORVERSION'];

// mind. REDAXO-Version 4.0.0 muss vorhanden sein
if (version_compare($_REX['REDAXO-VERSION'], "4.0.0", "<"))
{
  $error = 'Required REDAXO <= 4.0.0';
}

// wenn REDAXO-Version = 4.0.0, dann spezielle SQL-Abfragen ignorieren
if (version_compare($_REX['REDAXO-VERSION'], "4.0.0", "=="))
{
  $REX['a63_sql_compare'] = true;
}

if (!isset ($REX['DIRPERM'])) {
  $REX['DIRPERM'] = octdec(777); // oktaler wert
}



/**
 * Hilfsfunktion zur Installation.
 *
 * @param int $step Installationsschritt
 * @return string
 */
function _a_63_install($step = 0) {
  global $REX;
  
  /**
   * init
   */
  $error = '';
  $tmpFolder = $REX['MEDIAFOLDER'].'/'. $REX['TEMP_PREFIX'] .'/';
  $mediaFolder = $REX['MEDIAFOLDER'].'/'. $REX['TEMP_PREFIX'] .'/guestbook_63/';
  $cssPathSource = '/addons/guestbook/css/';
  $cssFile = 'guestbook.css';
  

  static $_a63_checkTabelle = '';
  

  switch ($step) {
    case 1:
      /**
       * pruefe config/ auf Schreibrechte
       */
      $error = rex_is_writable(dirname(__FILE__).'/config');
    break;
    
    case 2:
      /**
       * pruefe config/status.txt auf Schreibrechte
       */
      $error = rex_is_writable(dirname(__FILE__).'/config/status.txt');
    break;
    
    case 3:
      /**
       * das addon_framework muss installiert und aktiviert sein
       */
      if (!OOAddon :: isAvailable('addon_framework'))
      {
        $error = 'Required addon "addon_framework" is either not installed or not activated!';
      }
    break;
    
    case 4:
      // erstelle tmp-Ordner, wenn nicht schon vorhanden
      if (!is_dir($tmpFolder) && !mkdir($tmpFolder))
      {
        $error = 'Unable to create folder "'. $tmpFolder .'"';
      }
      @chmod($tmpFolder, $REX['DIRPERM']);
    break;
    
    case 5:
      // erstelle AddOn-Ordner im tmp-Ordner
      if (!is_dir($mediaFolder) && !mkdir($mediaFolder))
      {
        $error = 'Unable to create folder "'. $mediaFolder .'"';
      }
      @chmod($mediaFolder, $REX['DIRPERM']);
    break;
    
    case 6:
      // kopiere CSS in Mediafolder 
      $cssSrc = $REX['INCLUDE_PATH'] . $cssPathSource . $cssFile;
      $cssDst = $mediaFolder . $cssFile;
      
      if (!file_exists($cssDst) && !copy($cssSrc, $cssDst))
      {
        $error = 'Unable to copy file to "'. $cssDst .'"';
      }
      // setze Rechte nach master.inc.php-Vorgabe
      @ chmod($cssDst, $REX['FILEPERM']);
      
    break;
    
    case 7:
      // Gibt es die GB-Tabelle schon?
      $_a63_checkTabelle = rex_a63_CheckTabelle();
      
      // SQL wird nur ausgefuehrt, wenn die Tabelle noch nicht in der DB existiert.
      if (!$_a63_checkTabelle)
      {
        $error = rex_install_dump(dirname(__FILE__).'/install_tabelle.sql', false);
      }
    break;
    
    case 8:
      // installiere die benoetigten Module und Aktionen
      // ToDo: eine Ueberpruefung ob die Module und Aktionen schon installiert ist waere notwendig
      // ToDo: aber dazu fehlen noch einige Vorraussetzungen in den Redaxotabellen selbst
      $error = rex_install_dump(dirname(__FILE__).'/install_moduleaction.sql', false);
    break;
    
    case 9:
      // DB-Daten installiert? Dann hiermit weiter.
      include(dirname(__FILE__).'/install.php');
      // rex_a63_installAction2Modul(Name des Modules, Name der Action)
      $error = rex_a63_installAction2Modul('Gaestebuch - Eintragsliste', 'Gaestebuch - Eintragsliste StatusPerDatei');
    break;
    
    default:
      $error = $I18N_A63('Fehler bei der Installation. Kein passender Installationsschritt gefunden.');
    break;
  }
  return $error;
}

/**
 * Anzahl an Installationsschritte
 * Siehe function _a_63_install()
 */
$step = 9;

/**
 * Arbeite alle Installationsschritte ab.
 * Im Fehlerfall > Abbruch
 */
for ($i = 1; $i <= $step; $i++) {
  $error = _a_63_install($i);
  if ($error != '' and strlen($error) > 3)
  {
    $REX['ADDON']['installmsg']['guestbook'] = $error;
    break;
  }
}




if ($error != '' and strlen($error) > 3)
{
  $REX['ADDON']['installmsg']['guestbook'] = $error;
} else {
  $REX['ADDON']['install']['guestbook'] = true;
}




/**
 * Pruefe ob Gaestebuchtabelle schon existiert.
 *
 * @return boolean
 */
function rex_a63_CheckTabelle()
{
  $qry = "SHOW TABLES LIKE '".TBL_GBOOK."'";
  $sql = new rex_sql();
  //$sql->debugsql = true;
  $sql->setQuery($qry);

  if ($sql->getRows() == 1)
  {
    return true;
  }
  return false;
}

?>