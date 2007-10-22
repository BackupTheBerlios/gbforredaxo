<?php
/**
 * Debug Addon 
 * @author sven[ät]koalshome[punkt]de Sven Eichler
 * @package redaxo3
 * @version $Id: index.inc.php,v 1.1 2007/10/22 19:48:42 koala_s Exp $
 */


// Parameter
$Basedir = dirname(__FILE__);


// Include Header and Navigation
include $REX['INCLUDE_PATH'].'/layout/top.php';


rex_title('Debug Addon');


?>

<table border="0" cellpadding="5" cellspacing="1" width="770">
  <tbody>
  <tr>
    <th colspan="2" align="left">Anleitung</th>
  </tr>
  <tr>
    <td class="grey" valign="top" ><br />
      <b>Features:</b><br /><br />
      Mit Hilfe des Debug Addon können Variablen-, Array- oder Objektinhalte in <br />
      zum Teil formatierter Form ausgegeben werden. 
  
      <br /><br />
      
      <b>Beispiele:</b><br /><br />
      $foo = 'bar';<br />
      DebugOut($foo);<br />
      Ausgabe: <br />
      DEBUGOUT: bar<br /><br />
      
      $foo = array('bar','example');<br />
      DebugOut($foo);<br />
      Ausgabe: <br />
      DEBUGOUT: 0 => bar<br />
               1 => example<br /><br /><br />
      
<pre>/**
 * Debug_Out gibt Variableninfos aus
 *
 * Aufrufbeispiel:
 * $a = array(1, 2, array("a", "b", "c"));
 * DebugOut($a);
 * 
 * @param  mixed  auszugebene Variablendaten
 * @param  mixed  Anweisungen fuer die switch-Abfrage
 *                'sql' - speziell formatierte Ausgabe
 * @param  bool   0 (default): Ausgabe erfolgt per var_export()
 *                1: Ausgabe erfolgt per var_dump()
 */</pre>
      

      <b>Version:</b> RC 2 - 12.09.2006

    </td>
  </tr>
</tbody>
</table>

<?php





// Include Footer 
include $REX['INCLUDE_PATH'].'/layout/bottom.php';
?>