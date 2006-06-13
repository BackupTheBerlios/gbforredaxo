<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: class.rex_formMultiValueField.inc.php,v 1.1 2006/06/13 19:13:40 koala_s Exp $
 */

class rexFormMultiValueField extends rexFormField
{
  var $values;
  var $value_separator;

  function rexFormMultiValueField($name, $label, $tags = array (), $id = '', $value_separator = '||')
  {
    $this->rexFormField($name, $label, $tags, $id);
    $this->setValueSeparator($value_separator);
    $this->values = array ();
  }

  /**
   * Setzt den Trenner für die Werte
   * @access public
   */
  function setValueSeparator($value_separator)
  {
    rex_valid_type($value_separator, 'string', __FILE__, __LINE__);
    
    $this->value_separator = $value_separator;
  }

  /**
   * Fügt dem Feld einen neuen Wert hinzu
   * @param $label Label des Wertes
   * @param $value Wert des Wertes
   * @access protected
   */
  function addValue($label, $value)
  {
    rex_valid_type($label, array('string', 'scalar'), __FILE__, __LINE__);
    rex_valid_type($value, array('string', 'scalar'), __FILE__, __LINE__);
    
    $this->values[] = array (
      $label,
      $value
    );
  }

  /**
   * Fügt dem Feld eine Array von Werten hinzu
   * @param $values Array von Werten
   * @access protected
   */
  function addValues($values)
  {
    rex_valid_type($values, 'array', __FILE__, __LINE__);

    $value = array_shift($values);
    $mode = '';
    if (isset ($value[0]) && isset ($value[1]))
    {
      $mode = 'Numeric';
    }
    elseif (isset ($value['label']) && isset ($value['value']))
    {
      $mode = 'Assoc';
    }
    elseif (is_scalar($value))
    {
      $mode = 'Scalar';
    }
    else
    {
      rexForm :: triggerError('Unexpected Array-Structure for Array $values. Expected Keys are "0" and "1" or "label" and "value"!');
    }

    if ($mode == 'Numeric')
    {
      // Add first Option
      $this->addValue($value[0], $value[1]);

      // Add remaing Options
      foreach ($values as $value)
      {
        $this->addValue($value[0], $value[1]);
      }
    }
    elseif ($mode == 'Assoc')
    {
      // Add first Option
      $this->addValue($value['label'], $value['value']);

      // Add remaing Options
      foreach ($values as $value)
      {
        $this->addValue($value['label'], $value['value']);
      }
    }
    elseif ($mode == 'Scalar')
    {
      // Add first Option
      $this->addValue($value, $value);
      
      // Add remaing Options
      foreach ($values as $value)
      {
        $this->addValue($value, $value);
      }
    }
  }

  /**
   * Fügt dem Feld neue Werte via SQL-Query hinzu.
   * Dieser Query muss ein 2 Spaltiges Resultset beschreiben.
   * 
   * @param $query SQL-Query
   * @access protected
   */
  function addSqlValues($query)
  {
    $sql = new sql();
    //      $sql->debugsql = true;

    $result = $sql->get_array($query, MYSQL_NUM);

    if (is_array($result) && count($result) >= 1)
    {
      $value = array_shift($result);

      if (count($value) > 2)
      {
        rexForm :: triggerError('Query "'.$query.'" affects more than 2 columns!');
      }

      if (count($value) == 2)
      {
        // Add first Option
        $this->addValue($value[0], $value[1]);
        foreach ($result as $value)
        {
          // Add remaing Options
          $this->addValue($value[0], $value[1]);
        }
      }
      elseif (count($value) == 1)
      {
        // Add first Option
        $this->addValue($value[0], $value[0]);
        foreach ($result as $value)
        {
          // Add remaing Options
          $this->addValue($value[0], $value[0]);
        }
      }
    }
  }

  /**
   * Entfernt einen Wert des Feld 
   * @param $value Wert des Wertes
   * @access protected
   */
  function delValue($value)
  {
    rex_valid_type($value, 'string', __FILE__, __LINE__);
    
    if ($this->hasValue($value))
    {
      unset ($this->values[$value]);
    }
  }

  /**
   * Prüft, ob ein Wert schon vorhanden ist 
   * @param $value Wert des Wertes
   * @access protected
   */
  function hasValue($value)
  {
    rex_valid_type($value, 'string', __FILE__, __LINE__);
    
    return array_key_exists($value, $this->getValues());
  }
  
  /**
   * Gibt alle Werte des Feldes zurück 
   * @access protected
   */
  function getValues()
  {
    return $this->values;
  }
  
  /*
   * Prepariert den InsertValue um das Array als String in die DB zu speichern 
   * @access protected
   */
  function getInsertValue()
  {
    $value = parent :: getInsertValue();
    if (is_array($value))
    {
      $value = implode($this->value_separator, $value);
    }
    return $value;
  }
  
  /*
   * Prepariert den Value um den String aus der DB als Array zurückzugeben 
   * @access protected
   */
  function getValue()
  {
    $value = parent :: getValue();
    if (!is_array($value))
    {
      $value = explode($this->value_separator, $value);
    }
    return $value;
  }
}
?>