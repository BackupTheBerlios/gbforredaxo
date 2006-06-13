<?php


/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: class.rex_formField.inc.php,v 1.1 2006/06/13 19:13:40 koala_s Exp $
 */

class rexFormField
{
  var $id;
  var $name;
  var $value;
  var $attributes;
  var $needFullColumn;
  var $activateSave;

  var $validators;

  var $rexsection;

  function rexFormField($name, $label, $attributes = array (), $id = '')
  {
    $this->name = $name;
    $this->label = $label;
    $this->attributes = $attributes;
    $this->id = $id;

    $this->validators = array ();
    $this->transformators = array ();
    $this->needFullColumn(false);
    $this->activateSave(true);
  }

  function getName()
  {
    return $this->name;
  }

  function getLabel()
  {
    return $this->label;
  }

  function setValue($value)
  {
    $this->value = $value;
  }

  function stripslashes($value)
  {
    if (is_array($value))
    {
      for ($i = 0; $i < count($value); $i++)
      {
        $value[$i] = stripslashes($value[$i]);
      }
    }
    else
    {
      $value = stripslashes($value);
    }

    return $value;
  }

  /**
   * Schalter, um die Speicherfunktion dieses Feldes zu aktivieren/deaktvieren.
   * In der Grundeinstellung zeigt das readOnlyField die Werte nur an, 
   * speichert diese beim save() aber nicht in die DB.
   * 
   * @param $activate_save boolean true/false Speicherfunktion aktiviert/deaktiviert
   */
  function activateSave($activateSave)
  {
    rex_valid_type($activateSave, array( 'boolean'), __FILE__, __LINE__);
    
    $this->activateSave = $activateSave;
  } 
  
  function _getInsertValue()
  {
    // Werte auf den Insert vorbereiten
    // Aktuell nur den Wert zurückgeben, da die Werte via magic_quotes escaped werden!
    $value = $this->getValue();
    return $value;
  }

  function getInsertValue()
  {
    if ($this->activateSave === true)
    {
      return $this->_getInsertValue();
    }
    
    // null zurückgeben, damit der Wert nicht im SQL auftaucht
    return null;
  }

  function getValue()
  {
    $name = $this->getName();
    if (isset ($_POST[$name]))
    {
      // Da beim Redraw des Formulars via mquotes slashes hinzugefügt wurden, diese entfernen!
      return $this->stripslashes($_POST[$name]);
    }

    if ($this->value != '')
    {
      return $this->value;
    }

    $section = & $this->getSection();
    $dataset = & $section->_getDataSet();

    if (isset ($dataset[$name]))
    {
      return $dataset[$name];
    }
    else
    {
      return '';
    }
  }

  function & getSection()
  {
    return $this->rexsection;
  }

  function getId()
  {
    $id = $this->id;

    if ($id == '')
    {
      $section = & $this->getSection();
      $form = & $section->getForm();
      $section_label = $section->parseLabel($section->getLabel());

      $id = strtolower($form->getName().'_'.$section_label.'_'.$this->getName());
    }

    return $id;
  }

  function addAttribute($tag_name, $tag_value, $overwrite = true)
  {
    if ($overwrite === false && array_key_exists($tag_name, $this->attributes))
    {
      return;
    }
    $this->attributes[$tag_name] = $tag_value;
  }

  function & _getAttributes()
  {
    return $this->attributes;
  }

  function getAttributes()
  {
    $s = '';
    $attributes = $this->_getAttributes();

    $section =& $this->getSection();
    $form =& $section->getForm();
    
    $isInvalid = false;
    if(!empty($_POST))
    {
      // falls das Feld nicht gültig ist, CSS Klasse "invalid" zuweisen
      $failed_fields = rexValidateEngine::_failed_fields($_POST, $form->getName());
      if(is_array($failed_fields) && in_array($this->getName(), $failed_fields))
      {
        $isInvalid = true;
        if(empty($attributes['class'])) 
        {
          $attributes['class'] = 'invalid';
        }
        else
        {
          $attributes['class'] = $attributes['class'] .' invalid';
        }
      }
    }
    
    // Falls das Feld valide ist, Pflichtfelder markieren
    if(!$isInvalid && $this->hasValidator())
    {
      if(empty($attributes['class'])) 
      {
        $attributes['class'] = 'required';
      }
      else
      {
        $attributes['class'] = $attributes['class'] .' required';
      }
    }
    
    // Attribute zu String umwandeln
    if (is_array($attributes))
    {
      foreach ($attributes as $attr_name => $attr_value)
      {
        $s .= ' '.$attr_name.'="'.$attr_value.'"';
      }
    }

    return $s;
  }

  function isValid($field)
  {
    return is_object($field) && is_a($field, 'rexformfield');
  }
  
  function needFullColumn($needFullColumn = null)
  {
    if($needFullColumn !== null)
    {
      $this->needFullColumn = $needFullColumn;
    }
    return $this->needFullColumn;
  }

  function registerValidators()
  {
    $section = & $this->getSection();
    $form = & $section->getForm();
    $validators = & $this->getValidators();

    for ($i = 0; $i < count($validators); $i++)
    {
      $validator = & $validators[$i];
      
      if(($pos = strpos($validator['criteria'],':')) === false)
      {
        // Validierung ohne Parameter 
        rexValidateEngine :: register_validator($this->_getValidatorId($validator['criteria']), $this->getName(), $validator['criteria'], $validator['empty'], $validator['halt'], $validator['transform'], $form->getName());
      }
      else
      {
        // validierung mit Parametern
        rexValidateEngine :: register_validator($this->_getValidatorId($validator['criteria']), $this->getName().substr($validator['criteria'],$pos), substr($validator['criteria'],0,$pos), $validator['empty'], $validator['halt'], $validator['transform'], $form->getName());
      }
    }
  }

  function activateValidators()
  {
    $section = & $this->getSection();
    $form = & $section->getForm();
    $formValidator = & $form->getValidator();

    $validators = & $this->getValidators();
    for ($i = 0; $i < count($validators); $i++)
    {
      $validator = & $validators[$i];
//      var_dump('validation_errors_'.$form->getName().'_'.$section->getTableName().'::'.$section->getLabel());
      $params = array (
        'id' => $this->_getValidatorId($validator['criteria']), 
        'message' => $validator['message'],
        'form' => $form->getName(),
        'append' => 'validation_errors_'.$form->getName().'_'.$section->getTableName().'::'.$section->getLabel(),
        'halt' => $validator['halt']
      );
      // validierung starten
      smarty_function_validate($params, $formValidator);
    }
  }

  function _getValidatorId($criteria)
  {
    $section = & $this->getSection();
    return $section->getLabel().'->'.$this->getName().'['.$criteria.']';
  }

  function getValidators()
  {
    return $this->validators;
  }

  /**
   * Fügt einen Validator hinzu
   * 
   * @param $empty
   * "empty" determines if the field is allowed to be empty or not. If
   * allowed, the validation will be skipped when the field is empty.
   * Note this is ignored with the "notEmpty" criteria.
   * 
   * @param $halt
   * If the validator fails, "halt" determines if any remaining validators for
   * this form will be processed. If "halt" is yes, validation will stop at this point.
   * 
   * @param $transform
   * "transform" is used to apply a transformation to a form value prior to
   * validation. For instance, you may want to trim off extra whitespace from
   * the form value before validating. 
   */
  function addValidator($criteria, $message, $empty = false, $halt = false, $transform = null)
  {
    $this->validators[] = array (
      'criteria' => $criteria,
      'message' => $message,
      'empty' => $empty,
      'halt' => $halt,
      'transform' => $transform
    );
  }
  
  function hasValidator()
  {
    return count($this->getValidators()) > 0;
  }

  function get()
  {
    return '';
  }

  function show()
  {
    echo $this->get();
  }

  function toString()
  {
    $section_str = 'null';
    $section = & $this->getSection();
    if ($section !== null)
    {
      $section_str = $section->toString();
    }
    return 'rexFormField: type: "'.get_class($this).'", name: "'.$this->getName().'", label: "'.$this->getLabel().'", section: "{'.$section_str.'}"';
  }
}

// Field Klassen einbinden
$FieldBasedir = dirname(__FILE__);

// 3rd Party Klassen
require_once $FieldBasedir.'/validate/SmartyValidate.class.php';
require_once $FieldBasedir.'/validate/internals/core.assemble_plugin_filepath.php';
require_once $FieldBasedir.'/validate/plugins/function.validate.php';

// Validierung
require_once $FieldBasedir.'/class.rex_validator.inc.php';
require_once $FieldBasedir.'/validate/rex_ValidateEngine.inc.class.php';

// Field-Basis-Klassen
require_once $FormBasedir.'/class.rex_formMultiValueField.inc.php';

// Allgemeine Field-Klassen
require_once $FieldBasedir.'/fields/field.textField.inc.php';
require_once $FieldBasedir.'/fields/field.textAreaField.inc.php';
require_once $FieldBasedir.'/fields/field.selectField.inc.php';
require_once $FieldBasedir.'/fields/field.buttonField.inc.php';
require_once $FieldBasedir.'/fields/field.saveField.inc.php';
require_once $FieldBasedir.'/fields/field.hiddenField.inc.php';
require_once $FieldBasedir.'/fields/field.readOnlyField.inc.php';
require_once $FieldBasedir.'/fields/field.readOnlyTextField.inc.php';
require_once $FieldBasedir.'/fields/field.foreignField.inc.php';
require_once $FieldBasedir.'/fields/field.popupButtonField.inc.php';
require_once $FieldBasedir.'/fields/field.checkboxField.inc.php';
require_once $FieldBasedir.'/fields/field.radioField.inc.php';
require_once $FieldBasedir.'/fields/field.fieldsetField.inc.php';

// Redaxo Field-Klassen
require_once $FieldBasedir.'/fields/rex/field.rexSaveField.inc.php';
require_once $FieldBasedir.'/fields/rex/field.rexWYSIWYGField.inc.php';
require_once $FieldBasedir.'/fields/rex/field.rexLinkButtonField.inc.php';
require_once $FieldBasedir.'/fields/rex/field.rexMediaButtonField.inc.php';
?>