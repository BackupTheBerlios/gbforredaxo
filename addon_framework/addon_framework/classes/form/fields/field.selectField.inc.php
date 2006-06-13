<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: field.selectField.inc.php,v 1.1 2006/06/13 19:13:41 koala_s Exp $
 */

class selectField extends rexFormMultiValueField
{
  var $multiple;

  function selectField($name, $label, $attributes = array (), $id = '')
  {
    $this->rexFormMultiValueField($name, $label, $attributes, $id);
    $this->multiple = false;
  }

  /**
   * Fügt eine Option hinzu
   * @param $label Label der Option
   * @param $value Wert der Option
   * @access public
   */
  function addOption($label, $value = '')
  {
    $this->addValue($label, $value);
  }

  /**
   * Fügt ein Array von Optionen hinzu
   * @param $options Array von Optionen
   * @access public
   */
  function addOptions($options)
  {
    $this->addValues($options);
  }

  /**
   * Fügt Optionen via SQL-Query hinzu
   * @param $query SQL-Query, der ein 2 spaltiges Resultset beschreibt
   * @access public
   */
  function addSqlOptions($query)
  {
    $this->addSqlValues($query);
  }

  /**
   * Gibt alle Optionen als Array zurück
   * @access public
   */
  function getOptions()
  {
    return $this->getValues();
  }

  /**
   * Aktiviert/Deaktiviert, dass mehrere Optionen zugleich gewählt werden können
   * @param $multiple true => aktivieren / false => deaktivieren
   */
  function setMultiple($multiple = true)
  {
    $this->multiple = $multiple;
  }

  /**
   * Gibt den HTML Content zurück
   */
  function get()
  {
    $options = '';
    $name = $this->getName();
    $value = $this->getValue();

    foreach ($this->getOptions() as $opt)
    {
      $selected = '';
      if (in_array($opt[1], $value))
      {
        $selected = ' selected="selected"';
      }
      $options .= sprintf('<option value="%s"%s>%s</option>', $opt[1], $selected, $opt[0]);
    }

    if ($this->multiple)
    {
      $name .= '[]';
      $this->addAttribute('multiple', 'multiple');
      $this->addAttribute('size', '5', false);
    }
    else
    {
      $this->addAttribute('size', '3', false);
    }

    return sprintf('<select name="%s" id="%s" tabindex="%s"%s>%s</select>', $name, $this->getId(), rex_a22_nextTabindex(), $this->getAttributes(), $options);
  }
}
?>