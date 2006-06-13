<?php


/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: field.buttonField.inc.php,v 1.1 2006/06/13 20:31:06 koala_s Exp $
 */

class buttonField extends rexFormField
{
  var $buttons;

  function buttonField($attributes = array ())
  {
    $this->rexFormField('', '', $attributes);
  }

  function addButton($name, $label, $status = true)
  {
    rex_valid_type($name, 'string', __FILE__, __LINE__);
    rex_valid_type($label, 'string', __FILE__, __LINE__);
    rex_valid_type($status, 'boolean', __FILE__, __LINE__);

    $this->buttons[$name] = array (
      'name' => $name,
      'label' => $label,
      'status' => $status
    );
  }

  function getButton($name)
  {
    return $this->buttons[$name];
  }

  function getButtons()
  {
    return $this->buttons;
  }

  function removeButtons()
  {
    $this->buttons = array ();
  }

  function setButtonStatus($name, $status)
  {
    rex_valid_type($name, 'string', __FILE__, __LINE__);
    rex_valid_type($status, 'boolean', __FILE__, __LINE__);
    $this->buttons[$name]['status'] = $status;
  }

  function setButtonLabel($name, $label)
  {
    rex_valid_type($name, 'string', __FILE__, __LINE__);
    rex_valid_type($label, 'string', __FILE__, __LINE__);
    $this->buttons[$name]['label'] = $label;
  }

  function formatButton($name, $attributes = '', $prefix = '', $suffix = '')
  {
    $button = $this->getButton($name);

    if (!$button['status'])
    {
      return '';
    }

    return sprintf('%s<input type="submit" name="%s" value="%s" tabindex="%s"%s />%s', $prefix, $name, $button['label'], rex_a22_nextTabindex(), $attributes, $suffix);
  }

  function getInsertValue()
  {
    // null zurückgeben, damit der Wert nicht im SQL auftaucht
    return null;
  }

  function get()
  {
    $s = '';

    $buttons = $this->getButtons();

    foreach ($buttons as $button)
    {
      $s .= $this->formatButton($button['name']);
    }

    return $s;
  }
}
?>