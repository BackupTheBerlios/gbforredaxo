<?php


/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: class.rex_form.inc.php,v 1.1 2006/06/13 20:31:05 koala_s Exp $
 */

// Form Komponenten einbinden
$FormBasedir = dirname(__FILE__);

require_once $FormBasedir.'/class.rex_fieldContainer.inc.php';
require_once $FormBasedir.'/class.rex_fieldController.inc.php';
require_once $FormBasedir.'/class.rex_formSection.inc.php';
require_once $FormBasedir.'/class.rex_formField.inc.php';

require_once $FormBasedir.'/../../functions/function_rex_form.inc.php';

require_once $FormBasedir.'/../class.rex_formatter.inc.php';

// CSS einbinden
rex_register_extension('OUTPUT_FILTER', 'rex_a22_insertRexformCss');

define('FORM_INFO_MSG', 0);
define('FORM_WARNING_MSG', 1);
define('FORM_ERROR_MSG', 2);

/**
 * RexForm Klasse
 */

class rexForm extends rexFieldContainer
{
  // Name des Formulars
  var $name;
  // Status-Meldungen
  var $messages;
  // Url, auf die nach druck auf "Uebernehmen" umgeleitet wird
  var $apply_url;

  // Zuletzt hinzugefügte Section
  var $section;
  // Alle Sections
  var $sections;

  // Validierungsmeldungen
  var $errors;
  // Validatoren
  var $validator;

  // Datenbankverbindung
  var $sql;

  // Debug-Flag
  var $debug;

  // Modus des Formulars true/false
  var $edit_mode;

  function rexForm($name, $apply_url = '')
  {
    $this->name = $name;
    $this->messages = array ();
    $this->apply_url = '';
    $this->edit_mode = true;

    $this->errors = null;
    $this->validator = new rexValidator();

    $this->sql = new sql();
    $this->debug = & $this->sql->debugsql;
  }

  function setApplyUrl($apply_url)
  {
    if ($apply_url != '')
    {
      $this->apply_url = $apply_url;
    }
  }

  /**
   * Versetzt das Formular in den Editier-Modus.
   * Im Editier Modus, ist ein "ÜBERNEHMEN" und "SPEICHERN" Button vorhanden.
   * Diese sind sonst nicht eingeblendet.
   */
  function setEditMode($edit_mode)
  {
    rex_valid_type($edit_mode, 'boolean', __FILE__, __LINE__);
    $this->edit_mode = $edit_mode;
  }

  function isEditMode()
  {
    return $this->edit_mode;
  }

  function isValid($form)
  {
    return is_object($form) && is_a($form, 'rexform');
  }

  function & getValidator()
  {
    return $this->validator;
  }

  function getMessages()
  {
    return $this->messages;
  }

  function setMessage($message, $message_type)
  {
    $this->messages[] = array (
      $message,
      $message_type
    );
  }

  function getName()
  {
    return $this->name;
  }

  function addField(& $field)
  {
    parent::addField($field, $this->getSection());
  }

  function addSection(& $section)
  {
    if (!rexFormSection :: isValid($section))
    {
      rexForm::triggerError('Unexpected type "'.gettype($section).'" for $section! Expecting type string or rexFormSection-Object!');
    }

    $section->rexform = & $this;
    $this->section = & $section;
    $this->sections[] = & $section;
  }
  
  function & getSection()
  {
    return $this->section;
  }

  function & getSections()
  {
    return $this->sections;
  }

  function numSections()
  {
    return count($this->getSections());
  }

  function triggerError($message, $message_type = E_USER_ERROR)
  {
    trigger_error('rexForm: '.$message, $message_type);
  }

  function registerValidators()
  {
    // register our validators
    $sections = & $this->getSections();
    for ($i = 0; $i < count($sections); $i++)
    {
      $sections[$i]->registerValidators();
    }
  }

  function activateValidators()
  {
    $sections = & $this->getSections();
    for ($i = 0; $i < count($sections); $i++)
    {
      $sections[$i]->activateValidators();
    }
  }

  function delete()
  {
    $sections = & $this->getSections();
    for ($i = 0; $i < count($sections); $i++)
    {
      $sections[$i]->delete();
    }
    // trigger extensions point
    rex_register_extension_point('REX_FORM_'.strtoupper($this->getName()).'_DELETE', '', array (
      'form' => $this
    ));
  }

  function save()
  {
    $sections = & $this->getSections();
    for ($i = 0; $i < count($sections); $i++)
    {
      $sections[$i]->save();
    }
    // trigger extensions point
    rex_register_extension_point('REX_FORM_'.strtoupper($this->getName()).'_SAVE', '', array (
      'form' => $this
    ));
  }

  function _get($addDefaultFields = true)
  {
    $this->activateValidators();

    if ($addDefaultFields)
    {
      $section = & $this->getSection();
      $section->addField(new rexSaveField());
    }

    $def_params = rex_a22_getDefaultGlobalParams();
    if (is_array($def_params))
    {
      foreach ($def_params as $name => $value)
      {
        $field = & new hiddenField($name);
        $field->setValue($value);
        $this->addField($field);
      }
    }

    $s = '';
    $s .= '<!-- rexForm start -->'. "\n";
    $s .= '<div class="a22-rexform">'. "\n";
    $s .= '  <form action="index.php" method="post">'."\n";
    $s .= '    <div class="a22-rexform-hidden">'."\n";

    // Show Messages
    $s .= $this->formatMessages();

    // Show Hidden fields
    $fields = & $this->getFields();
    $numFields = $this->numFields();

    for ($t = 0; $t < $numFields; $t++)
    {
      if (is_a($fields[$t], 'hiddenfield'))
      {
        $s .= '      '.$fields[$t]->get()."\n";
      }
    }

    $s .= '    </div>'."\n";
    
    // Show Sections
    $sections = & $this->getSections();
    for ($i = 0; $i < $this->numSections(); $i++)
    {
      $section = & $sections[$i];
      $s .= $section->get();
    }

    $s .= '  </form>'."\n";
    $s .= '</div>'."\n";
    $s .= '<!-- rexForm end -->'. "\n";

    return $s;
  }

  function get($addDefaultFields = true)
  {
    if (isset ($_POST['rexform_cancel_button']))
    {
      // Abbrechen Button wurde gedrückt
      $this->redirect();
    }
    elseif (isset ($_POST['rexform_delete_button']))
    {
      // Löschen Button wurde gedrückt
      $this->delete();
      $this->redirect();
    }

    // Nur auf buttons reagieren, die von rex_form sind
    if (empty ($_POST) || !empty ($_POST) && empty ($_POST['rexform_save_button']) && empty ($_POST['rexform_update_button']) && empty ($_POST['rexform_delete_button']) && empty ($_POST['rexform_cancel_button']))
    {
      // new form, we (re)set the session data
      rexValidateEngine :: connect($this->getValidator());
      rexValidateEngine :: register_form($this->getName(), true);

      // register our validators
      $this->registerValidators();

      return $this->_get($addDefaultFields);
    }
    else
    {
      // validate after a POST
      rexValidateEngine :: connect($this->getValidator());

      if (rexValidateEngine :: is_valid($_POST, $this->getName()))
      {
        $this->setMessage('Daten <span class="emphasize">erfolgreich</span> gespeichert!', FORM_INFO_MSG);

        // save values
        $this->save();

        if (isset ($_POST['rexform_save_button']))
        {
          // Speichern Button wurde gedrückt
          $this->redirect();
        }
        else
        {
          // Übernehmen Button wurde gedrückt
          // Formular wieder anzeigen
          return $this->_get($addDefaultFields);
        }
      }
      else
      {
        $this->setMessage('Daten wurden <span class="emphasize">nicht</span> gespeichert!', FORM_ERROR_MSG);

        // error, redraw the form
        return $this->_get($addDefaultFields);
      }
    }
  }

  function show($addDefaultFields = true)
  {
    echo $this->get($addDefaultFields);
  }

  function formatMessages()
  {
    $s = '';
    $messages = & $this->getMessages();
    foreach ($messages as $message)
    {
      $text = $message[0];
      $type = $message[1];

      $class = '';
      switch ($type)
      {
        case 0 :
          $class = 'info';
          break;
        case 1 :
          $class = 'warning';
          break;
        case 2 :
          $class = 'error';
          break;
      }

      $s .= '    <div class="statusmessage"><p class="'. $class .'">'. $text .'</p></div>'."\n";
    }

    return $s;
  }

  function redirect($params = '')
  {
    $url = $this->apply_url;
    $hasParams = strpos($url, '?') !== false;
    if ($params != '')
    {
      if ($hasParams)
      {
        $url .= '&'.$params;
      }
      else
      {
        $url .= '?'.$params;
        $hasParams = true;
      }
    }

    $hasParams = strpos($url, '?') !== false;
    $def_params = rex_a22_getDefaultGlobalParams();
    if (is_array($def_params))
    {
      foreach ($def_params as $name => $value)
      {
        if (strpos($url, '&'.$name.'=') === false && 
            strpos($url, '?'.$name.'=') === false)
        {
          if ($hasParams)
          {
              $url .= '&'.$name.'='.$value;
          }
          else
          {
              $url .= '?'.$name.'='.$value;
              $hasParams = true;
          }
        }
      }
    }

    if ($this->debug)
    {
      exit ('<hr />Redirect to:'.$url);
    }

    header('Location: '.$url);
    exit ();
  }

  /**
   * Durchsucht das Formular nach einem Feld
   * @param string Name des Feldes, wonach gesucht werden soll
   * @return object|null Bei erfolgreicher Suche wird ein rexFormField-Objekt zurückgegeben, sonst null 
   * @access public 
   */
  function searchField($name)
  {
    $result = parent :: searchField($name);
    if ($result !== null)
    {
      return $result;
    }

    $sections = & $this->getSections();
    for ($i = 0; $this->numSections(); $i++)
    {
      $section = & $sections[$i];
      $result = $section->searchField($name);
      if ($result !== null)
      {
        return $result;
      }
    }
    return null;
  }

  function toString()
  {
    return 'rexForm: name: "'.$this->getName().'", edit_mode: "'. ($this->isEditMode() ? 'true' : 'false').'", sections: "'.$this->numSections().'"';
  }
}
?>