<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: column.resultColumn.inc.php,v 1.1 2006/06/13 19:13:42 koala_s Exp $
 */

/**
 * Klasse für Spalten mit Werten aus dem SQL.
 */
class resultColumn extends rexListColumn
{
  // Name des Spalte im ResultSet
  var $name;

  // Formatierung des Spaltenwertes
  var $format;
  // Type der Formatierung des Spaltenwertes
  var $format_type;

  /**
   * Konstruktor
   * @param $name Name des Spalte im ResultSet
   * @param $label Spaltenüberschrift
   * @param $format Formatstring/Formatarray
   * @param $format_type Formatierungstyp
   * 
   * Mögliche Formatierungstypen sind '','sprintf','date','strftime','number'
   */
  function resultColumn($name, $label = '', $format_type = '', $format = '', $options = OPT_ALL)
  {
    if ($label == '')
    {
      $label = $name;
    }

    $this->name = $name;
    $this->format_type = $format_type;
    $this->format = $format;
    $this->rexListColumn($label, $options);
  }

  function getLabel()
  {
    $label = parent :: getLabel();

    // Spalten nach denen nicht sortiert werden darf
    if (!$this->hasOption(OPT_SORT))
    {
      return $label;
    }

    $format = '<img src="%s" alt="%s"/>';
    $next = empty ($_REQUEST['next']) ? '' : $_REQUEST['next'];

    $sort_asc = $this->link(sprintf($format, 'pics/file_up.gif', 'Aufsteigend nach &quot;'.$label.'&quot; sortieren'), array ('order_col' => $this->name, 'order_type' => 'asc', 'next' => $next));
    $sort_desc = $this->link(sprintf($format, 'pics/file_down.gif', 'Absteigend nach &quot;'.$label.'&quot; sortieren'), array ('order_col' => $this->name, 'order_type' => 'desc', 'next' => $next));

    return $label.$sort_asc.$sort_desc;
  }

  /**
   * Fügt der Spalte einen Wert hinzu, der von einer Spalte abhängig ist.
   * 
   * @param $cond_column Name der Spalte die geprüft werden soll [Default ist die eigene Column]
   * @param $cond_value Wert, auf den geprüft werden soll
   * @param $text Text der ausgegeben werden soll
   * @param $params Link-Parameter die auf $text als Link gesetzt werden sollen
   */
  function addCondition($cond_column = '', $cond_value, $text, $params = array ())
  {
    if (strlen($cond_column) == 0)
    {
      $cond_column = $this->name;
    }
    parent :: addCondition($cond_column, $cond_value, $text, $params);
  }

  function format($row)
  {
    global $I18N;

    $format = parent :: format($row);
    if (strlen($format) != 0)
    {
      return $format;
    }

    $value = $row[$this->name];

    if ($this->format_type == '')
    {
      if ($this->format == '')
      {
        $this->format = '%'.$this->name.'%';
      }
      // Alle Spaltenamen ersetzen durch deren Werte %id%, %name%, etc.
      $value = $this->parseString($this->format, $row);
    }
    else
    {
      $value = rexFormatter :: format($value, $this->format_type, $this->format);
    }

    return $this->link($value, $this->parseParams($row));
  }
}
?>