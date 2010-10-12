<?php
/**
 * Moduletest Addon 
 * @author redaxo[at]koalashome[dot]de Sven (Koala) Eichler
 * @package redaxo3
 * @version $Id: class.moduleinstall.inc.php,v 1.1 2010/10/12 19:38:08 koala_s Exp $
 */

class moduleinstall {
  
  /**
   * Name of AddOn
   * @var     string
   * @access  public
   */
  var $addonname = '';
  
  /**
   * Array mit den zu installierenden Modulendateinamen und Modulnamen 
   * 
   * $modul_array = array("file_name" => 'modul name')
   * 
   * @var    array
   * @access public 
   */
  var $modul_array = array();

  /**
   * Array mit den zu installierenden Actiondateinamen und Actionnamen
   * 
   * $action_array = array("file_name" => 
   *     array ('name' => 'action name', 
   *            'prepost'  => 1,
   *            'add'      => 0,
   *            'edit'     => 1,
   *            'delete'   => 0)
   *   );
   * prepost = 1 > Action is POST else PRE
   * 
   * @var    array
   * @access public 
   */
  var $action_array = array();
  
  
  /**
   * Typ der zu bearbeiten ist - Modul oder Action
   * 
   * usage: $mi->typ = 'modul';
   *
   * @var string  'Modul' oder 'Action' 
   * @access public
   */
  var $typ = '';
  
  
 /**
  * Determines how Template handles error conditions.
  * "yes"      = the error is reported, then execution is halted
  * "report"   = the error is reported, then execution continues by returning "false"
  * "no"       = errors are silently ignored, and execution resumes reporting "false"
  *
  * @var       string
  * @access    public
  * @see       halt
  */
  var $halt_on_error  = "yes";

 /**
  * The last error message is retained in this variable.
  *
  * @var       string
  * @access    public
  * @see       halt
  */
  var $last_error     = "";
  
  
  
  
  
  
  /**
   * init
   *
   * @param  string $addonname
   * @access public
   */
  function moduleinstall($addonname = '') {
    if (isset ($addonname) and $addonname > 0) {
      $this->addonname = $addonname;
    } else {
      $this->halt('Schwerer Fehler aufgetreten. Es wurde kein AddonName uebergeben! Abbruch.');
      return false;
    }
    
  }
  

  
  /**
   * Uebernimmt die Arrays fuer Module und Action und startet den Installationsprozess.
   *
   * @param   array   $array - Array mit Module-/Action-Angaben
   * @access  public
   * @see     $modul_array und $action_array
   * @return  bool
   */
  function install($array)
  {
    if (!is_array ($array) or count ($array) == 0) {
      $this->halt('Es muss ein Array übergeben werden. Abbruch.');
      return false;
    }
    
    switch (strtolower ($this->typ)) {
      case 'modul':
        $this->module_array($array);
        break;
    	
      case 'action':
        $this->action_array($array);
        break;
    	
      default:
        $this->halt('Konnte Typ (Modul oder Action) nicht finden. Siehe "var $typ". Abbruch.');
        return false;
        break;
    }
    
    return true;
  }
  
  
  
  /**
   * zerlege Modulearray 
   * 
   * Uebergeben werden muss ein Array mit dem Dateinamen und Namen des Modules 
   *
   * @access  private
   * @see     var $modul_array
   * @return  bool
   */
  function module_array($modul_array)
  {
    foreach ($modul_array as $modul_dateiname => $modul_name) {
      
      if ($this->_checkSourceFile($modul_dateiname))
      {
        // Wenn die Datei exitiert, dann prüfe Vorhandensein des Modules in 
        // der DB und entscheide ob Neuinstallation oder nur Update notwendig ist

        
      }
    } 
    
    return true;
  }
  
  
  /**
   * zerlege Actionarray 
   * 
   * Uebergeben werden muss ein Array mit dem Dateinamen und Namen des Modules 
   *
   * @access  private
   * @see     var $action_array
   * @return  bool
   */
  function action_array($array)
  {
    
    return true;
  }
  
  
  
  /**
   * Pruefe Dateien auf vorhandensein und Leserechte.
   * Fehlt eine Datei oder hat keine Leserechte gibt es ne Fehlermeldung.
   * 
   * TODO: Das kann bestimmt noch vereinfacht werden?! 
   *
   * @param   string $dateiname
   * @return unknown
   */
  function _checkSourceFile($dateiname)
  {
    global $REX;
    
    switch (strtolower ($this->typ)) {
      case 'modul':
        // Eingabe
        $pfad = $REX['INCLUDE_PATH'].'/addons/'.$this->addonname.'/install/'.$this->typ.'/'.$dateiname.'_eingabe.php';
        if (!file_exists ($pfad) or !is_readable ($pfad))
        {
          $this->halt('Entweder ist '.$pfad.' nicht vorhanden oder ich habe keine Leserechte.');
          return false;
        }
        // Ausgabe
        $pfad = $REX['INCLUDE_PATH'].'/addons/'.$this->addonname.'/install/'.$this->typ.'/'.$dateiname.'_ausgabe.php';
        if (!file_exists ($pfad) or !is_readable ($pfad))
        {
          $this->halt('Entweder ist '.$pfad.' nicht vorhanden oder ich habe keine Leserechte.');
          return false;
        }
        return true;
        break;
      
      case 'action':
        $pfad = $REX['INCLUDE_PATH'].'/addons/'.$this->addonname.'/install/'.$this->typ.'/'.$dateiname.'.php';
        if (!file_exists ($pfad) or
        !is_readable ($REX['INCLUDE_PATH'].'/addons/'.$this->addonname.'/install/'.$this->typ.'/'.$dateiname.'.php'))
        {
          $this->halt('Entweder ist '.$pfad.' nicht vorhanden oder ich habe keine Leserechte.');
          return false;
        }
        return true;
        break;
      default:
        $this->halt('Konnte Typ (Modul oder Action) nicht finden. Siehe "var $typ". Abbruch.');
        return false;
        break;
    }
    
    return true;
  }
  
  
  
  /**
   * Frage Datenbanktabelle "rex_module" ab.
   * Suche nach dem Modulnamen in der Tabelle.
   *
   * @param   string $dateiname
   * @return  bool
   */
  function _DBQuery($dateiname)
  {
    
    $sql = new sql();
    //$sql->debugsql = true;
    $data = $sql->get_array($qry);
    
    
    
    return true;
  }
  
  
  
  
  
  

  
  
  
  /**
   * This function is called whenever an error occurs and will handle the error
   * according to the policy defined in $this->halt_on_error. Additionally the
   * error message will be saved in $this->last_error.
   *
   * Returns: always returns false.
   *
   * usage: halt(string $msg)
   *
   * @param     $msg         a string containing an error message
   * @access    private
   * @return    void
   * @see       $halt_on_error
   */
  function halt($msg) {
    $this->last_error = $msg;

    if ($this->halt_on_error != "no") {
      $this->haltmsg($msg);
    }

    if ($this->halt_on_error == "yes") {
      die("<b>Halted.</b>");
    }

    return false;
  }


  /**
   * This function prints an error message.
   * It can be overridden by your subclass of Template. It will be called with an
   * error message to display.
   *
   * usage: haltmsg(string $msg)
   *
   * @param     $msg         a string containing the error message to display
   * @access    public
   * @return    void
   * @see       halt
   */
  function haltmsg($msg) {
    printf("<b>Template Error:</b> %s<br>\n", $msg);
  }
  
  
}
?>