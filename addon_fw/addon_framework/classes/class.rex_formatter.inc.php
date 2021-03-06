<?php


/**
 * Addon Framework Classes 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: class.rex_formatter.inc.php,v 1.2 2007/09/04 19:47:03 koala_s Exp $
 */

/**
 * Klasse zur Formatierung von Strings
 */
class rexFormatter
{
  /**
   * Formatiert den String <code>$value</code>
   * 
   * @param $value zu formatierender String
   * @param $format_type Formatierungstype
   * @param $format Format
   * 
   * Unterst�tzte Formatierugen:
   * 
   * - <Formatierungstype>
   *    + <Format>
   * 
   * - sprintf
   *    + siehe www.php.net/sprintf
   * - date 
   *    + siehe www.php.net/date
   * - strftime 
   *    + dateformat
   *    + datetime
   *    + siehe www.php.net/strftime
   * - number
   *    + siehe www.php.net/number_format
   *    + array( <Kommastelle>, <Dezimal Trennzeichen>, <Tausender Trennzeichen>)
   * - email
   *    + array( 'attr' => <Linkattribute>, 'params' => <Linkparameter>,
   * - url
   *    + array( 'attr' => <Linkattribute>, 'params' => <Linkparameter>,
   * - truncate
   *    + array( 'length' => <String-Laenge>, 'etc' => <ETC Zeichen>, 'break_words' => <true/false>,
   * - nl2br
   *    + siehe www.php.net/nl2br
   * - rexmedia
   *    + siehe www.php.net/nl2br
   */
  function format($value, $format_type, $format)
  {
    global $I18N, $REX;

    if($value === null)
    {
      return '';
    }

    // Stringformatierung mit sprintf()
    if ($format_type == 'sprintf')
    {
      $value = rexFormatter::_formatSprintf($value, $format);
    }
    // Datumsformatierung mit date()
    elseif ($format_type == 'date')
    {
      $value = rexFormatter::_formatDate($value, $format);
    }
    // Datumsformatierung mit strftime()
    elseif ($format_type == 'strftime')
    {
      $value = rexFormatter::_formatStrftime($value, $format);
    }
    // Zahlenformatierung mit number_format()
    elseif ($format_type == 'number')
    {
      $value = rexFormatter::_formatNumber($value, $format);
    }
    // Email-Mailto Linkformatierung
    elseif ($format_type == 'email')
    {
      $value = rexFormatter::_formatEmail($value, $format);
    }
    // URL-Formatierung
    elseif ($format_type == 'url')
    {
      $value = rexFormatter::_formatUrl($value, $format);
    }
    // String auf eine eine L�nge abschneiden
    elseif ($format_type == 'truncate')
    {
      $value = rexFormatter::_formatTruncate($value, $format);
    }
    // Newlines zu <br />
    elseif ($format_type == 'nl2br')
    {
      $value = rexFormatter::_formatNl2br($value, $format);
    }
    // REDAXO Medienpool files darstellen
    elseif ($format_type == 'rexmedia' && $value != '')
    {
      $value = rexFormatter::_formatRexMedia($value, $format);
    }

    return $value;
  }

  function _formatSprintf($value, $format)
  {
    rex_valid_type($format, 'string', __FILE__, __LINE__);

    if ($format == '')
    {
      $format = '%s';
    }
    return sprintf($format, $value);
  }

  function _formatDate($value, $format)
  {
    rex_valid_type($format, 'string', __FILE__, __LINE__);

    if ($format == '')
    {
      $format = 'd.m.Y';
    }

    return date($format, $value);
  }

  function _formatStrftime($value, $format)
  {
    global $I18N;

    rex_valid_type($format, 'string', __FILE__, __LINE__);

    if (empty ($value))
    {
      return '';
    }

    if ($format == '' || $format == 'dateformat')
    {
      // Default REX-Dateformat 
      $format = $I18N->msg('dateformat');
    }
    elseif ($format == 'datetime')
    {
      // Default REX-Datetimeformat 
      $format = $I18N->msg('datetimeformat');
    }
    return strftime($format, $value);
  }

  function _formatNumber($value, $format)
  {
    if (!is_array($format))
    {
      $format = array ();
    }

    // Kommastellen
    if (empty ($format[0]))
    {
      $format[0] = 2;
    }
    // Dezimal Trennzeichen
    if (empty ($format[1]))
    {
      $format[1] = ',';
    }
    // Tausender Trennzeichen
    if (empty ($format[2]))
    {
      $format[2] = ' ';
    }
    return number_format($value, $format[0], $format[1], $format[2]);
  }

  function _formatEmail($value, $format)
  {
    if (!is_array($format))
    {
      $format = array ();
    }

    // Linkattribute
    if (empty ($format['attr']))
    {
      $format['attr'] = '';
    }
    // Linkparameter (z.b. subject=Hallo Sir)
    if (empty ($format['params']))
    {
      $format['params'] = '';
    }
    else
    {
      if (!startsWith($format['params'], '?'))
      {
        $format['params'] = '?'.$format['params'];
      }
    }
    // Url formatierung
    return '<a href="mailto:'.$value.$format['params'].'"'.$format['attr'].'>'.$value.'</a>';
  }

  function _formatUrl($value, $format)
  {
    if (empty ($value))
    {
      return '';
    }

    if (!is_array($format))
    {
      $format = array ();
    }

    // Linkattribute
    if (empty ($format['attr']))
    {
      $format['attr'] = '';
    }
    // Linkparameter (z.b. subject=Hallo Sir)
    if (empty ($format['params']))
    {
      $format['params'] = '';
    }
    else
    {
      if (!startsWith($format['params'], '?'))
      {
        $format['params'] = '?'.$format['params'];
      }
    }
    // Protokoll
    if (!preg_match('@(http|https|ftp|ftps|telnet|redaxo)://@', $value))
    {
      $value = 'http://'.$value;
    }

    return '<a href="'.$value.$format['params'].'"'.$format['attr'].'>'.$value.'</a>';
  }

  function _formatTruncate($value, $format)
  {
    if (!is_array($format))
    {
      $format = array ();
    }

    // String-laenge
    if (empty ($format['length']))
    {
      $format['length'] = 80;
    }
    // ETC
    if (empty ($format['etc']))
    {
      $format['etc'] = '...';
    }
    // Break-Words?
    if (empty ($format['break_words']))
    {
      $format['break_words'] = false;
    }

    return truncate($value, $format['length'], $format['etc'], $format['break_words']);
  }
  
  function _formatNl2br($value, $format)
  {
    return nl2br($value);
  }

  function _formatRexMedia($value, $format)
  {
    if (!is_array($format))
    {
      $format = array ();
    }

    $params = $format['params'];

    // Resize aktivieren, falls nicht anders �bergeben
    if (empty ($params['resize']))
    {
      $params['resize'] = true;
    }

    $media = OOMedia :: getMediaByName($value);
    // Bilder als Thumbnail
    if ($media->isImage())
    {
      $value = $media->toImage($params);
    }
    // Sonstige mit Mime-Icons
    else
    {
      $value = $media->toIcon();
    }
    
    return $value;
  }
}
?>