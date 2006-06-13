<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: function_rex_common.inc.php,v 1.1 2006/06/13 20:31:08 koala_s Exp $
 */

/**
 * Gibt eine Fehlermeldung aus
 * 
 * @param string Meldungstext
 * @param string Datei
 * @param string Zeilennummer
 * @param integer Fehlermeldungstype
 * @access public
 */
function rex_error($message, $file, $line, $type = E_USER_ERROR)
{
  trigger_error(sprintf('%s <br/>in <b>%s</b> on line <b>%s</b><br/>', $message, $file, $line), $type);
}

/**
 * Gibt eine Addonspezifischen Fehlermeldung aus
 * 
 * @param string Name des Addons
 * @param string Meldungstext
 * @param string Datei
 * @param string Zeilennummer
 * @param integer Fehlermeldungstype
 * @access public
 */
function rex_addon_error($addon, $file, $line, $message, $type = E_USER_ERROR)
{
  rex_error('rexAddon['.$addon.']: '.$message, $file, $line, $type);
}

/**
 * Gibt eine Fehlermeldung entsprechend der Datentyp validierung aus
 * 
 * @param mixed zu prüfende Variable
 * @param string Datentype der Variable (ermitteln durch gettype)
 * @param string erwarteter Datentype
 * @param string Datei
 * @param string Zeilennummer
 * @access protected
 */
function _rex_type_error($var, $type, $expected, $file, $line)
{
  switch ($expected)
  {
    case 'class' :
      rex_error('Unexpected class for Object "'.$var.'"!', $file, $line);
    case 'subclass' :
      rex_error('Class "'.$var.'" is not a valid subclass!', $file, $line);
      // filesystem-types
    case 'method' :
      rex_error('Method "'.$var.'" not exists!', $file, $line);
    case 'dir' :
      rex_error('Folder "'.$var.'" not found!', $file, $line);
    case 'file' :
      rex_error('File "'.$var.'" not found!', $file, $line);
    case 'resource' :
      rex_error('Var "'.$var.'" is not a valid resource!', $file, $line);
    case 'upload' :
      rex_error('File "'.$var.'" is no valid uploaded file!', $file, $line);
    case 'readable' :
      rex_error('Destination "'.$var.'" not readable!', $file, $line);
    case 'writable' :
      rex_error('Destination "'.$var.'" not writable!', $file, $line);
    case 'callable' :
      if (is_array($var))
        $var = implode('::', $var);
      rex_error('Function or Class "'.$var.'" not callable!', $file, $line);

    default :
      rex_error('Unexpected type "'.$type.'" for "$'.$var.'"! Expecting type "'.$expected.'"', $file, $line);
  }
}

/**
 * Prüft die übergebene Variable auf einen bestimmten Datentyp
 * und bricht bei einem Fehler mit einer Meldung das Script ab.
 * 
 * <code>
 * // Prüfung der Variable $url auf den type String
 * rex_valid_type($url, 'string', __FILE__, __LINE__);
 * </code>
 * 
 * <code>
 * // Prüfung der Variable $param auf String ODER Array
 * rex_valid_type($param, array ('string', 'array'), __FILE__, __LINE__);
 * </code>
 * 
 * <code>
 * // Prüfung von $file, ob die Datei existiert UND ob die Datei beschreibbar ist
 * rex_valid_type($file, array(array('file', 'readable')), __FILE__, __LINE__);
 * </code>
 * 
 * @param mixed zu überprüfende Variable
 * @param mixed Kriterium das geprüft werden soll
 * @param string Datei
 * @param string Zeilennummer
 * @access public
 */
function rex_valid_type($var, $expected, $file, $line)
{
  if (!_rex_valid_type($var, $expected, $file, $line))
  {
    _rex_type_error($var, gettype($var), $expected, $file, $line);
  }
}

/**
 * Prüft die übergebene Variable auf einen bestimmten Datentyp.
 * Diese Funktion verknüpft die übergebenen Kriterien mit logischen UND oder ODER.
 * 
 * @param mixed zu überprüfende Variable
 * @param mixed Kriterium das geprüft werden soll
 * @param string Datei
 * @param string Zeilennummer
 * @return boolean true wenn die Variable $var allen Kriterien des Types $type entspricht, sonst false
 * @access protected
 */
function _rex_valid_type($var, $type, $file, $line)
{
  if (is_array($type))
  {
    foreach ($type as $_type)
    {
      if (is_array($_type))
      {
        foreach ($_type as $__type)
        {
          // AND Opperator
          // if one of the checks is NOT correct, return false
          if (!_rex_check_vartype($var, $__type, $file, $line))
          {
            return false;
          }
        }
      }
      else
      {
        // OR Opperator
        // if one of the checks is correct, return true
        if (_rex_check_vartype($var, $_type, $file, $line))
        {
          return true;
        }
      }
    }

    return false;
  }
  elseif (is_string($type))
  {
    if (_rex_check_vartype($var, $type, $file, $line))
    {
      return true;
    }
  }
  else
  {
    rex_type_error('type', gettype($type), 'array|string', __FILE__, __LINE__);
  }

  return false;
}

/**
 * Prüft die übergebene Variable auf einen bestimmten Datentyp
 * 
 * @param mixed zu überprüfende Variable
 * @param mixed Kriterium das geprüft werden soll
 * @param string Datei
 * @param string Zeilennummer
 * @return bool true wenn die Variable $var dem Type $type entspricht, sonst false 
 * @access protected
 */
function _rex_check_vartype($var, $type, $file, $line)
{
  switch ($type)
  {
    // simple-vartypes
    case 'boolean' :
      return is_bool($var);
    case 'integer' :
      return is_int($var);
    case 'double' :
      return is_double($var);
    case 'float' :
      return is_float($var);
    case 'scalar' :
      return is_scalar($var);
    case 'numeric' :
      return is_numeric($var);
    case 'string' :
      return is_string($var);
    case 'array' :
      return is_array($var);
      // object-types
    case 'NULL' :
    case 'null' :
      return is_null($var);
    case 'object' :
      return is_object($var);
    case 'class' :
      rex_valid_type($var, 'array', $file, $line);
      rex_valid_type($var[0], 'object', $file, $line);
      rex_valid_type($var[1], 'string', $file, $line);
      return is_a($var[0], $var[1]);
    case 'subclass' :
      rex_valid_type($var, 'array', $file, $line);
      rex_valid_type($var[0], 'object', $file, $line);
      rex_valid_type($var[1], 'string', $file, $line);
      return is_subclass_of($var[0], $var[1]);
      // filesystem-types
    case 'method' :
      rex_valid_type($var, 'array', $file, $line);
      rex_valid_type($var[0], 'object', $file, $line);
      rex_valid_type($var[1], 'string', $file, $line);
      return method_exists($var[0], $var[1]);
    case 'file' :
      return is_file($var);
    case 'dir' :
      return is_dir($var);
    case 'resource' :
      return is_resource($var);
    case 'upload' :
      return is_uploaded_file($var);
      // attributechecks
    case 'readable' :
      return is_readable($var);
    case 'writable' :
      return is_writable($var);
    case 'callable' :
      rex_valid_type($var, array (
        'string',
        'array'
      ), $file, $line);
      return is_callable($var);
    default :
      return false;
  }
}

/**
 * Bindet eine CSS Datei via Extension Point in den Quelltext ein
 * 
 * @param string Quelltext des Artikels
 * @param string|array Datei(en) die eingebunden werden sollen 
 * @access private 
 */
function rex_a22_insertCss($content, $files)
{
  global $REX;

  $styles = '';

  if (!is_array($files))
  {
    $files = array (
      $files
    );
  }
  
  foreach ($files as $file)
  {
    if($file != '' && !isset($REX['ADDON']['css']['addon_framework'][$file]))
    {
      // CSS-Datei merken, damit jedes nur einmal eingebunden wird
      $REX['ADDON']['css']['addon_framework'][$file] = true;
      
      $url = 'index.php?rex_css='. $file;
      $styles .= '  <link rel="stylesheet" type="text/css" href="'. $url .'" />'. "\n";
    }
  }
  
  if($styles != '')
  {
    return str_replace('</head>', $styles.'</head>', $content);
  }
  else
  {
    return $content;
  }
}

/**
 * Gibt die Standard-Parameter zurück, die man benötigt um die aktuelle Seite
 * wieder aufzurufen
 * 
 * @return array Array von Parametern
 * @access protected 
 */
function rex_a22_getDefaultGlobalParams()
{
  global $REX, $page, $subpage, $func, $next;

  $params = array ();
  
  if (!$REX['REDAXO'])
  {
    global $article_id, $clang;

    $params['article_id'] = $article_id;
    $params['clang'] = $clang;
  }
  $params['page'] = $page;
  $params['subpage'] = $subpage;
  $params['next'] = $next;

  return $params;
}

/**
 * Gibt den aktuellen Tabindex zurück
 * Der Tabindex ist eine stetig fortlaufende Zahl,
 * welche die Priorität der Tabulatorsprünge des Browsers regelt.
 *  
 * @return integer aktueller Tabindex
 */
function rex_a22_getTabindex()
{
    global $REX;
    
    if(empty($REX['tabindex']))
    {
      $REX['tabindex'] = 0;
    }
    
    return $REX['tabindex'];
}

/**
 * Gibt den nächsten freien Tabindex zurück.
 * Der Tabindex ist eine stetig fortlaufende Zahl,
 * welche die Priorität der Tabulatorsprünge des Browsers regelt. 
 *  
 * @return integer nächster freier Tabindex
 */
function rex_a22_nextTabindex()
{
    global $REX;

    if(empty($REX['tabindex']))
    {
      $REX['tabindex'] = 0;
    }

    return ++$REX['tabindex'];
}
?>