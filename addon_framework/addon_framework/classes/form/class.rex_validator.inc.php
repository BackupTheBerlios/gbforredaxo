<?php

/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: class.rex_validator.inc.php,v 1.1 2006/06/13 19:13:40 koala_s Exp $
 */
 
if (!class_exists('Smarty'))
{
  // Create Smarty Env Dummy
  class Smarty
  {
  }

  $smartyDir = dirname(__FILE__);
  define('SMARTY_DIR', $smartyDir.DIRECTORY_SEPARATOR.'validate'.DIRECTORY_SEPARATOR);
  define('SMARTY_CORE_DIR', SMARTY_DIR.'internals'.DIRECTORY_SEPARATOR);
  define('SMARTY_PLUGINS_DIR', SMARTY_DIR.'plugins'.DIRECTORY_SEPARATOR);
}

class rexValidator extends Smarty
{
  var $plugins_dir = SMARTY_PLUGINS_DIR;
  var $_tpl_vars;

  /**
   * assigns values to template variables
   *
   * @param array|string $tpl_var the template variable name(s)
   * @param mixed $value the value to assign
   */
  function assign($tpl_var, $value = null)
  {
    if (is_array($tpl_var))
    {
      foreach ($tpl_var as $key => $val)
      {
        if ($key != '')
        {
          $this->_tpl_vars[$key] = $val;
        }
      }
    }
    else
    {
      if ($tpl_var != '')
        $this->_tpl_vars[$tpl_var] = $value;
    }
  }

  /**
   * appends values to template variables
   *
   * @param array|string $tpl_var the template variable name(s)
   * @param mixed $value the value to append
   */
  function append($tpl_var, $value = null, $merge = false)
  {
    if (is_array($tpl_var))
    {
      // $tpl_var is an array, ignore $value
      foreach ($tpl_var as $_key => $_val)
      {
        if ($_key != '')
        {
          if (!@ is_array($this->_tpl_vars[$_key]))
          {
            settype($this->_tpl_vars[$_key], 'array');
          }
          if ($merge && is_array($_val))
          {
            foreach ($_val as $_mkey => $_mval)
            {
              $this->_tpl_vars[$_key][$_mkey] = $_mval;
            }
          }
          else
          {
            $this->_tpl_vars[$_key][] = $_val;
          }
        }
      }
    }
    else
    {
      if ($tpl_var != '' && isset ($value))
      {
        if (!@ is_array($this->_tpl_vars[$tpl_var]))
        {
          settype($this->_tpl_vars[$tpl_var], 'array');
        }
        if ($merge && is_array($value))
        {
          foreach ($value as $_mkey => $_mval)
          {
            $this->_tpl_vars[$tpl_var][$_mkey] = $_mval;
          }
        }
        else
        {
          $this->_tpl_vars[$tpl_var][] = $value;
        }
      }
    }
  }

  /**
   * get filepath of requested plugin
   *
   * @param string $type
   * @param string $name
   * @return string|false
   */
  function _get_plugin_filepath($type, $name)
  {
    $_params = array ('type' => $type, 'name' => $name);
    require_once (SMARTY_CORE_DIR.'core.assemble_plugin_filepath.php');
    return smarty_core_assemble_plugin_filepath($_params, $this);
  }

  /**
   * Returns an array containing template variables
   *
   * @param string $name
   * @param string $type
   * @return array
   */
  function & get_template_vars($name = null)
  {
    if (!isset ($name))
    {
      return $this->_tpl_vars;
    }
    if (isset ($this->_tpl_vars[$name]))
    {
      return $this->_tpl_vars[$name];
    }
  }

  /**
   * trigger Smarty error
   *
   * @param string $error_msg
   * @param integer $error_type
   */
  function trigger_error($error_msg, $error_type = E_USER_WARNING)
  {
    trigger_error("Smarty error: $error_msg", $error_type);
  }
}
?>