<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: config.inc.php,v 1.1 2006/06/13 20:31:05 koala_s Exp $
 */

$mypage = 'addon_framework'; // only for this file

$REX['ADDON']['page'][$mypage] = $mypage;
$REX['ADDON']['rxid'][$mypage] = '22';
$REX['ADDON']['name'][$mypage] = 'Addon Framework';
$REX['ADDON']['perm'][$mypage] = 'addon_framework[]';

$REX['PERM'][] = 'addon_framework[]';

$Basedir = dirname(__FILE__);

// CSS Datei einbinden
if(isset($_GET['rex_css']))
{
  $file = $_GET['rex_css'];
  $fileurl = '';
  $errors = array();
  
  // Pfad validieren
  if(strpos($file, '/') !== false)
  {
    $file_parts = explode('/', $file);
    
    foreach($file_parts as $part)
    {
      if(preg_match('/^[0-9A-Z_\.]*$/i', $part))
      {
        $fileurl .= $part. '/';
      }
    }
  }
  else
  {
    $errors[] = 'Datei "'. $file .'" verweist auf keinen Ordner!';
    $errors[] = 'Beispielpfad: "addon_framework/css/rexform.css"';
  }
    
  // letzte "/" abschneiden
  $fileurl = substr($fileurl, 0, strlen($fileurl) - 1);
  
  $css_file = $Basedir .'/../../addons/'. $fileurl;
  if(empty($errors) && is_readable($css_file))
  {
    header('Content-Type: text/css');
    readfile($css_file);
  }
  else
  {
    $errors[] = 'Datei "'. $css_file .'" wurde nicht gefunden!';
  }
  exit(implode("<br />\n", $errors));
}

// Allgemeine libs includen
require_once $Basedir.'/functions/function_rex_common.inc.php';

// Im Backendlibs includen
if ($REX['REDAXO'])
{
  require_once $Basedir.'/functions/function_rex_string.inc.php';
  require_once $Basedir.'/functions/function_rex_installation.inc.php';
  require_once $Basedir.'/functions/function_rex_compat.inc.php';
}
?>