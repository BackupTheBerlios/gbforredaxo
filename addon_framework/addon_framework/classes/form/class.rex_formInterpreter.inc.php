<?php


/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: class.rex_formInterpreter.inc.php,v 1.1 2006/06/13 19:13:40 koala_s Exp $
 */

class rexFormStringInterpreter
{
  function rexFormStringInterpreter()
  {
  }

  function interpret($string)
  {
    $dataSet = explode("\n", $string);
    
    if(count($dataSet)> 0)
    {
      foreach($dataSet as $dataRow)
      {
      }
    }
  }

  function _interpretString($fieldName, $fieldLabel, $fieldId, $fieldType, $fieldAttributes, $fieldValidators, $fieldExtras)
  {
    rex_valid_type($fieldName, 'string', __FILE__, __LINE__);
    rex_valid_type($fieldLabel, 'string', __FILE__, __LINE__);
    rex_valid_type($fieldId, 'string', __FILE__, __LINE__);
    rex_valid_type($fieldType, 'string', __FILE__, __LINE__);
    rex_valid_type($fieldAttributes, 'string', __FILE__, __LINE__);
    rex_valid_type($fieldValidators, 'string', __FILE__, __LINE__);
    rex_valid_type($fieldExtras, 'string', __FILE__, __LINE__);
    
    $fieldAttributes = explode("\n", $fieldAttributes);
    $fieldValidator = explode("\n", $fieldValidator);
    $fieldExtras = explode("\n", $fieldExtras);
    
    return rexFormInterpreter :: _interpret($fieldName, $fieldLabel, $fieldId, $fieldType, $fieldAttributes, $fieldValidators, $fieldExtras);
  }

  function _interpret($fieldName, $fieldLabel, $fieldId, $fieldType, $fieldAttributes, $fieldValidators, $fieldExtras)
  {
    rex_valid_type($fieldName, 'string', __FILE__, __LINE__);
    rex_valid_type($fieldLabel, 'string', __FILE__, __LINE__);
    rex_valid_type($fieldId, 'string', __FILE__, __LINE__);
    rex_valid_type($fieldType, 'string', __FILE__, __LINE__);
    rex_valid_type($fieldAttributes, 'array', __FILE__, __LINE__);
    rex_valid_type($fieldValidators, 'array', __FILE__, __LINE__);
    rex_valid_type($fieldExtras, 'array', __FILE__, __LINE__);

    // Feld attribute
    if (count($fieldAttributes) > 0)
    {
      $attributes = $fieldAttributes; 
      $fieldAttributes = array ();

      foreach ($attributes as $attr)
      {
        $parts = explode("=", $attr);
        $fieldAttributes[$parts[0]] = $parts[1];
      }
    }
    // Feld anlegen
    $field = new $fieldType ($fieldName, $fieldLabel, $fieldAttributes);

    // Valdiator hinzufügen
    if (count($fieldValidators) > 0)
    {
      foreach ($fieldValidators as $criteria)
      {
        $criteria = trim($criteria);
        if ($criteria == '')
        {
          continue;
        }
        $validator_parts = explode('|', $criteria);
        $field->addValidator($validator_parts[0], $validator_parts[1]);
      }
    }

    // Feldspezifische Extras
    if (count($fieldExtras) > 0)
    {
      switch ($fieldType)
      {
        case "selectField" :
          {
            foreach ($fieldExtras as $extra)
            {
              // wenn | drinne ist, dann nach value u. name aufspalten
              if (strpos($extra, '|') !== false)
              {
                $extra_parts = explode('|', $extra);
                $name = $extra_parts[0];
                $value = $extra_parts[1];
              }
              else
              {
                $name = $extra;
                $value = $extra;
              }
              $field->AddOption($name, $value);
            }
          }
          break;
      }
    }

    return $field;
  }
}
?>