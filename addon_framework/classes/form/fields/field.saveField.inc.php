<?php


/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: field.saveField.inc.php,v 1.1 2006/06/13 20:31:06 koala_s Exp $
 */

class saveField extends buttonField
{
  var $saveButtonName;

  function saveField($attributes = array ())
  {
    $this->saveButtonName = 'rexform_save_button';
    $this->addButton($this->saveButtonName, 'Speichern');

    $this->buttonField($attributes);
  }
  
  function setSaveButtonStatus($status)
  {
    $this->setButtonStatus($this->saveButtonName, $status);
  }
}
?>