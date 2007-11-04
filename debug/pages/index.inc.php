<?php
/**
 * Debug Addon 
 * @author sven[ät]koalshome[punkt]de Sven Eichler
 * @package redaxo4
 * @version $Id: index.inc.php,v 1.2 2007/11/04 21:45:22 koala_s Exp $
 */


// Parameter
$Basedir = dirname(__FILE__);


// Include Header and Navigation
include $REX['INCLUDE_PATH'].'/layout/top.php';


rex_title('Debug Addon');


?>

<h1>Anleitung</h1>
<h2>Features:</h2>
<p>Mit Hilfe des Debug Addon können Variablen-, Array- oder Objektinhalte in <br />
zum Teil formatierter Form ausgegeben werden.</p>
  
<br /><br />

<h2>Beispiele:</h2>
<p>
$foo = 'bar';<br />
DebugOut($foo);<br />
Ausgabe: <br />
<span style="font-weight:bold;">DEBUGOUT:</span> bar<br /><br />

$foo = array('bar','example');<br />
DebugOut($foo);<br />
Ausgabe: <br />
<span style="font-weight:bold;">DEBUGOUT:</span> 0 => bar<br />
1 => example<br /><br /><br />

<pre>/**
 * DebugOut gibt Variableninfos aus
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

</p>

<?php





// Include Footer 
include $REX['INCLUDE_PATH'].'/layout/bottom.php';
?>