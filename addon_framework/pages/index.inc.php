<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: index.inc.php,v 1.1 2006/06/13 20:31:09 koala_s Exp $
 */

// *************************************** INCLUDES

$Basedir = dirname(__FILE__);

// Classes
require_once $Basedir.'/../classes/form/class.rex_form.inc.php';
require_once $Basedir.'/../classes/list/class.rex_list.inc.php';

// *************************************** MAIN

require $REX['INCLUDE_PATH']."/layout/top.php";

//error_reporting(E_ALL);

$subpages = array (
  //   array( '', 'Einfache Liste'),
    //   array( 'formats', 'Formatierungen'),
    //   array( 'options', 'Optionen'),
       array( '', 'Toolbars'),
  array ('structure', 'Struktur Nachbildung'), array ('validate', 'Validierung'),);

rex_title('Redaxo Framework Komponenten', $subpages);

if ( !isset($subpage)) $subpage = '';
if ( !isset($func)) $func = '';
if ( !isset($view)) $view = '';

switch ($subpage)
{
  case 'formats' :
    $file = $Basedir.'/formats.inc.php';
    break;
  case 'options' :
    $file = $Basedir.'/options.inc.php';
    break;
  case 'toolbars' :
    $file = $Basedir.'/toolbars.inc.php';
    break;
  case 'structure' :
    $file = $Basedir.'/structure.inc.php';
    break;
  case 'validate' :
    $file = $Basedir.'/validate.inc.php';
    break;
  default :
    $file = $Basedir.'/simple.inc.php';
}

if ($view == 'source')
{
  echo '<div style="border: solid 1px red; padding: 10px; margin: 10px; font-size:11px; width: 730px;"><code>'.highlight_file($file, true).'</code></div>';
  // Link zur Preview
  echo '<a href="?page='.$page.'&amp;subpage='.$subpage.'"><b>Vorschau anzeigen</b></a><br/>';
}
else
{
?>
<h3>
Diese Seite ist nur eine Demo der rexList-/rexForm-Klasse.<br/> 
Alle Zugriffe auf die rex_article Tabelle werden ausschliesslich lesend vorgenommen.<br/>
Deshalb ist z.B. ein on/offline schalten von Kategorien, bzw. editieren/löschen nicht Möglich!<br/>
Zum Anzeigen des Sourcecodes der aktuellen Seite unten auf "<b>Sourcecode anzeigen</b>" klicken.
</h3>
<?php

  require $file;
  // Link zum Source
  echo '<a href="?page='.$page.'&amp;subpage='.$subpage.'&amp;view=source"><b>Sourcecode anzeigen</b></a><br/>';
}

//error_reporting(E_ALL^E_NOTICE);

require $REX['INCLUDE_PATH']."/layout/bottom.php";
?>