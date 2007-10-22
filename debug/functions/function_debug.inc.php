<?php
/**
 * Debug Addon 
 * @author sven[ät]koalshome[punkt]de Sven Eichler
 * @package redaxo3
 * @version $Id: function_debug.inc.php,v 1.1 2007/10/22 19:48:42 koala_s Exp $
 */

/**
 * Debug_Out gibt Variableninfos aus
 *
 * Aufrufbeispiel:
 * $a = array(1, 2, array("a", "b", "c"));
 * Debug_Out($a);
 * 
 * @param  mixed  auszugebene Variablendaten
 * @param  mixed  Anweisungen fuer die switch-Abfrage
 *                'sql' - speziell formatierte Ausgabe
 * @param  bool   0 (default): Ausgabe erfolgt per var_export()
 *                1: Ausgabe erfolgt per var_dump()
 */
function Debug_Out($input, $spezial = '', $dumpexport = 0) {
  //echo '<br />';
  $return = '';
  switch ($spezial) {
    case 'sql': 
      if ($spezial == 'sql') {
        $return .= "\n".nl2br($input)."<br /><br />\n";
      }
      break;
    default:  
  
    if (is_array($input) or is_object($input)) {
      ob_start();
      if ($dumpexport) { var_dump ($input); } else { var_export ($input); }
      $return .= "\n<pre>".ob_get_contents().'</pre><br />'."\n";
      ob_end_clean(); 
    }
    if (!is_array($input)) {
      $return .= $input.'<br />'."\n";
    }
    break;
  } // switch ($spezial)
  flush();
  
  // setze vor die Ausgabe einen deutlichen Hinweis auf die Debug-Ausgabe
  $return = '<span style="font-weight: bold;">DEBUGOUT: </span>'.$return."\n";
  // gib die Debuginfos aus
  echo $return;

  return true;
}


/**
* DebugOut
*
* @see  Debug_Out
*/
function DebugOut($input, $spezial = '', $dumpexport = 0) {
  return Debug_Out($input, $spezial, $dumpexport);
}



?>