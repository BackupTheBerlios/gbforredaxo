<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: toolbar.maxElementsBar.inc.php,v 1.1 2006/06/13 19:13:42 koala_s Exp $
 */

/**
 * Leiste die ein Textfeld zum Ändern der Datensätze pro Seite anzeigt
 */
class maxElementsBar extends rexListToolbar
{
  var $stepping;
  
  function maxElementsBar()
  {
    $this->rexListToolbar();
    $this->stepping = empty ($_REQUEST['stepping']) ? '' : $_REQUEST['stepping'];
  }
  function prepare()
  {
    parent::prepare();
    if ( $this->stepping != '')
    {
      $this->rexlist->setStepping( $this->stepping);
    }
  }
  
  function show()
  {
    return '<label for="stepping">Anzahl:</label>
          <input type="text" id="stepping" name="stepping" value="'.$this->rexlist->getStepping().'" style="width: 22px" maxlength="2" title="Einträge pro Seite"/>
          <input type="submit" value="Anzeigen" title="Einstellung übernehmen"/>'."\n";
  }
}
?>