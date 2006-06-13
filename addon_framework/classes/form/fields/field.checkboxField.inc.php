<?php


/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: field.checkboxField.inc.php,v 1.1 2006/06/13 20:31:06 koala_s Exp $
 */

class checkboxField extends rexFormMultiValueField
{
  function checkboxField($name, $label, $attributes = array (), $id = '')
  {
    $this->rexFormMultiValueField($name, $label, $attributes, $id);
  }

  /**
   * Fügt eine Box hinzu
   * @param $label Label der Option
   * @param $value Wert der Option
   * @access public
   */
  function addBox($label, $value = '')
  {
    $this->addValue($label, $value);
  }

  /**
   * Fügt ein Array von Boxen hinzu
   * @param $options Array von Optionen
   * @access public
   */
  function addBoxes($boxes)
  {
    $this->addValues($boxes);
  }

  /**
   * Fügt Boxen via SQL-Query hinzu
   * @param $query SQL-Query, der ein 2 spaltiges Resultset beschreibt
   * @access public
   */
  function addSqlBoxes($query)
  {
    $this->addSqlValues($query);
  }

  /**
   * Gibt alle Boxen als Array zurück
   * @access public
   */
  function getBoxes()
  {
    return $this->getValues();
  }

  /**
   * Gibt den HTML Content zurück
   */
  function get()
  {
    $s = '';
    $name = $this->getName();
    $id = $this->getId();
    $value = $this->getValue();
    $attributes = $this->getAttributes();

    $i = 0;
    foreach ($this->getBoxes() as $box)
    {
      $boxid = $id . $i;
      $checked = '';
      if (in_array($box[1], $value))
      {
        $checked = ' checked="checked"';
      }
      $s .= sprintf('<input type="checkbox" name="%s[]" value="%s" id="%s" tabindex="%s"%s%s /><label for="%s">%s</label>', $name, $box[1], $boxid, rex_a22_nextTabindex(), $checked, $attributes, $boxid, $box[0]);
      $i++;
    }

    return $s;
  }
}
?>