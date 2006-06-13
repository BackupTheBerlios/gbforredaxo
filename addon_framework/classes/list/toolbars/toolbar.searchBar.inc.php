<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: toolbar.searchBar.inc.php,v 1.1 2006/06/13 20:31:08 koala_s Exp $
 */

/**
 * Suchleiste
 */
class searchBar extends rexListToolbar
{
  var $column;
  var $key;
  var $mode;

  function searchBar()
  {
    if (empty ($_REQUEST['search_cancel']))
    {
      // POST Vars auswerten
      $this->column = empty ($_REQUEST['search_column']) ? '' : $_REQUEST['search_column'];
      $this->key = empty ($_REQUEST['search_key']) ? '' : $_REQUEST['search_key'];
      $this->mode = empty ($_REQUEST['search_mode']) ? '' : 'exact';
    }
    else
    {
      // Vars resetten
      $this->column = '';
      $this->key = '';
      $this->mode = '';
    }
  }

  function show()
  {
    $search_column = $this->column;
    $search_key = $this->key;
    $search_mode_checked = $this->mode == 'exact' ? ' checked="checked"' : '';

    if (!empty ($search_key))
    {
      $this->addGlobalParams(array ('search_key' => $search_key, 'search_column' => $search_column));
    }

    $s = '';
    $s .= '<label for="search_key">Suche</label>'."\n";
    $s .= '          <input type="text" value="'.$search_key.'" id="search_key" title="Suchwort" name="search_key" />'."\n";
    $s .= '          <label for="search_column">in</label>'."\n";
    $s .= '          <select id="search_column" name="search_column" title="Suchspalte">'."\n";

    // Suchspalten anzeigen
    for ($i = 0; $i < $this->rexlist->numColumns(); $i ++)
    {
      $column = & $this->rexlist->columns[$i];

      if ($column->hasOption(OPT_SEARCH))
      {
        $selected = '';
        if ($search_column != '' && $search_column == $column->name || $search_column == '' && $this->rexlist->def_search_col == $column->name)
        {
          $selected = ' selected="selected"';
        }
        $s .= sprintf('            <option value="%s"%s>%s</option>'."\n", $column->name, $selected, $column->label);
      }
    }

    $s .= '          </select>'."\n";
    $s .= '          <input type="checkbox" value="exact" title="Exakter Suchmodus" name="search_mode"'.$search_mode_checked.' />'."\n";
    $s .= '          <input type="submit" value="Suchen" title="Suche starten" name="search_button" />'."\n";

    if ($search_key != '')
    {
      $s .= '       <input type="submit" value="Suche aufheben" name="search_cancel" />'."\n";
    }

    return $s;
  }

  function prepareQuery(& $listsql)
  {
    $search_column = $this->column;
    $search_key = $this->key;
    $search_mode = $this->mode;

    if ($search_column != '' && $search_key != '')
    {
      if ($search_mode == 'exact')
      {
        $listsql->addWhere($search_column.' = "'.$search_key.'"');
      }
      else
      {
        $listsql->addWhere($search_column.' LIKE "%'.$search_key.'%"');
      }
    }
  }
}
?>