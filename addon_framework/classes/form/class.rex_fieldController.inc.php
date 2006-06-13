<?php


/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: class.rex_fieldController.inc.php,v 1.1 2006/06/13 20:31:06 koala_s Exp $
 */

define('CONTROLLER_INSERT_MODE', 1);
define('CONTROLLER_UPDATE_MODE', 2);

class rexFieldController extends rexFieldContainer
{
  // Tabellen Name
  var $tableName;
  // Where Bedingungen
  var $whereParams;
  // Wenn mehrere Sections auf die gleiche Tabelle zeigen, diese als einen Datensatz behandeln  
  var $join_equal_sections;

  // Aktueller Datensatz
  var $dataset;
  // Aktueller Modus (Insert/Update)
  var $mode;
  // Validierungsmeldungen
  var $errors;

  // Referenz zu Rexform 
  var $rexform;

  /**
   * Klassenkonstruktor
   * @param string Name der Tabelle, auf den dieser Abschnitt gemappt werden soll
   * @param array Array von Where Parametern, die genau einen Datensatz der Tabelle beschreiben 
   */
  function rexfieldController($tableName, $whereParams)
  {
    rex_valid_type($tableName, 'string', __FILE__, __LINE__);

    $this->tableName = $tableName;
    $this->setWhere($whereParams);

    $this->mode = null;
    $this->dataset = null;
    $this->errors = null;

    // Parentkonstruktor aufrufen
    $this->rexFieldContainer();
  }

  function setWhere($whereParams)
  {
    rex_valid_type($whereParams, 'array', __FILE__, __LINE__);

    $this->whereParams = $whereParams;
  }

  function getWhere()
  {
    return $this->whereParams;
  }

  function getTableName()
  {
    return $this->tableName;
  }

  function & getForm()
  {
    return $this->rexform;
  }

  function _prepareValue($value)
  {
    if (substr($value, 0, 11) == '[[:mysql:]]')
    {
      return substr($value, 11);
    }
    return '"'.$value.'"';
  }

  function _getWhereString()
  {
    $where = '';
    $whereParams = $this->getWhere();

    foreach ($whereParams as $col => $val)
    {
      $where .= $col.'='.$this->_prepareValue($val).' AND ';
    }

    return $where.'1=1';
  }

  function _getMode()
  {
    if ($this->mode === null)
    {
      // Wenn der Select 0 Zeilen liefert => Insert
      // Wenn der Select 1 Zeile liefert => Update
      // Wenn der Select > 1 Zeilen liefert => Where Clause passt nicht
      $form = & $this->getForm();
      $sql = & $form->sql;
      $where = $this->_getWhereString();
      $qry = 'SELECT * FROM '.$this->getTableName().' WHERE '.$where.' LIMIT 2';
      $sql->setQuery($qry);

      switch ($sql->getRows())
      {
        case 0 :
          {
            $this->mode = CONTROLLER_INSERT_MODE;
            $this->dataset = array ();
            break;
          }
        case 1 :
          {
            $this->mode = CONTROLLER_UPDATE_MODE;
            $result = $sql->get_array();
            $this->dataset = $result[0];
            break;
          }
        default :
          {
            rexForm :: triggerError('Given WHERE-parameters affect more than one row!');
            return;
          }
      }
    }
    return $this->mode;
  }

  function _getDataSet()
  {
    if ($this->dataset === null)
    {
      // Der Datensatz wird in _getMode() bestimmt
      $this->_getMode();
    }

    return $this->dataset;
  }

  function isValid($section)
  {
    return is_object($section) && is_a($section, 'rexfieldcontroller');
  }

  function addField(& $field)
  {
    parent :: addField($field, $this);
  }

  function getErrors($revalidate = false)
  {
    if ($this->errors === null || $revalidate)
    {
      $form = & $this->getForm();
      $validator = & $form->getValidator();

      $var_identifier = 'validation_errors_'.$form->getName().'_'.$this->getTableName().'::'.$this->getLabel();
      $errors = $validator->get_template_vars($var_identifier);

      $this->errors = $errors === null ? array () : $errors;
    }

    return $this->errors;
  }

  function numErrors()
  {
    return count($this->getErrors());
  }

  function registerValidators()
  {
    $fields = & $this->getFields();
    for ($i = 0; $i < count($fields); $i++)
    {
      $fields[$i]->registerValidators();
    }
  }

  function activateValidators()
  {
    $fields = & $this->getFields();
    for ($i = 0; $i < count($fields); $i++)
    {
      $fields[$i]->activateValidators();
    }
  }

  function delete()
  {
    $form = & $this->getForm();
    $sql = & $form->sql;
    $where = $this->_getWhereString();

    $qry = '';
    $qry .= 'DELETE FROM '.$this->getTableName();
    $qry .= ' WHERE '.$where.' LIMIT 1';

    $sql->setQuery($qry);

    return $sql->getError();
  }

  function save()
  {
    $form = & $this->getForm();
    $sql = & $form->sql;
    $mode = $this->_getMode();
    $qry = '';

    switch ($mode)
    {
      case CONTROLLER_INSERT_MODE :
        {
          $qry = 'INSERT INTO ';
          break;
        }
      case CONTROLLER_UPDATE_MODE :
        {
          $qry = 'UPDATE ';
          break;
        }
      default :
        {
          rexForm :: triggerError('Unexpected value "'.$mode.'"for $mode !');
          return;
        }
    }

    $qry .= $this->getTableName().' SET';

    // Set values
    $first = true;
    $fields = & $this->getFields();
    for ($i = 0; $i < $this->numFields(); $i++)
    {
      $field_value = $fields[$i]->getInsertValue();
      // NULL Werte nicht speichern
      if ($field_value === null)
      {
        continue;
      }

      if ($first)
      {
        $first = false;
      }
      else
      {
        $qry .= ',';
      }
      $qry .= ' '.$fields[$i]->getName().'='.$this->_prepareValue($field_value);
    }

    if ($mode == CONTROLLER_UPDATE_MODE)
    {
      $where = $this->_getWhereString();
      $qry .= ' WHERE '.$where.' LIMIT 1';
    }

    $sql->setQuery($qry);

    return $sql->getError();
  }

  function toString()
  {
    return 'rexFieldController: tableName: "'.$this->getTableName().'", label: "'.$this->getLabel().'", felder: "'.$this->numFields().'"';
  }
}
?>