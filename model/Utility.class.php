<?php

/**
 * Some static utility
 * @author Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * @version 0.2
 * 
 * (c) Maurizio Brioschi (maurizio.brioschi@ridesoft.org) 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */

namespace ridesoft\Boiler\model;

class Utility {

    /**
     * crea una password random
     * @return string 
     */
    public static function createRandomPassword() {
        $chars = "abcdefghijkmnopqrstuvwxyz023456789";
        srand((double) microtime() * 1000000);
        $i = 0;
        $pass = '';
        while ($i <= 2) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }
        return $pass;
    }

    public static function convertMonth($stringMonth) {

        if (strcasecmp($stringMonth, "jan") == 0)
            return "01";
        if (strcasecmp($stringMonth, "feb") == 0)
            return "02";
        if (strcasecmp($stringMonth, "mar") == 0)
            return "03";
        if (strcasecmp($stringMonth, "apr") == 0)
            return "04";
        if (strcasecmp($stringMonth, "may") == 0)
            return "05";
        if (strcasecmp($stringMonth, "jun") == 0)
            return "06";
        if (strcasecmp($stringMonth, "jul") == 0)
            return "07";
        if (strcasecmp($stringMonth, "aug") == 0)
            return "08";
        if (strcasecmp($stringMonth, "sep") == 0)
            return "09";
        if (strcasecmp($stringMonth, "oct") == 0)
            return "10";
        if (strcasecmp($stringMonth, "nov") == 0)
            return "11";
        if (strcasecmp($stringMonth, "dec") == 0)
            return "12";
    }

    public static function dateTimeToEnglishFormat($date) {
        $explotion = explode("/", $date);
        if (count($explotion) == 3) {
            return $explotion[2] . "-" . $explotion[1] . "-" . $explotion[0];
        }
        $explotion = explode("-", $date);
        if (count($explotion) == 3) {
            return $explotion[2] . "-" . $explotion[1] . "-" . $explotion[0];
        }
        return "";
    }

    public static function dateTimeToItalianFormat($date) {
        $explotion = explode("/", $date);
        if (count($explotion) == 3) {
            return $explotion[2] . "-" . $explotion[1] . "-" . $explotion[0];
        }
        $explotion = explode("-", $date);
        if (count($explotion) == 3) {
            return $explotion[2] . "-" . $explotion[1] . "-" . $explotion[0];
        }
        return "";
    }

    public static function dateToItalianFormat($date) {
        $explotion = explode(" ", $date);
        $explotionDate = explode("-", $explotion[0]);
        if (count($explotionDate) == 3) {
            // return $explotionDate[2]."-".$explotionDate[1]."-".$explotionDate[0]." ".$explotion[1];
            return $explotionDate[2] . "/" . $explotionDate[1] . "/" . $explotionDate[0];
        }

        return "";
    }

    /**
     * Converte la data del datapicker perchè entri  nel db
     * @param string $txtDateFrom
     * @return string 
     */
    public static function convertDataForDB($txtDateFrom) {
        return substr($txtDateFrom, 6, 4) . "-" . substr($txtDateFrom, 3, 2) . "-" . substr($txtDateFrom, 0, 2);
    }

    public static function getStato($field) {
        if (strcmp($field, "1") == 0)
            return "Attivo";
        else
            return "Non attivo";
    }

    public static function cleanField($field) {
        $actual_length = strlen($field);
        $stripped_length = strlen(strip_tags($field));
        if ($actual_length != $stripped_length) {
            return str_replace("'", "''", $field);
        } else {
            return addslashes($field);
        }
    }

    /**
     * Converte i caratteru speciali di $value in html $value e richiama l'eventuale include se presente tra [[ ]] se $parti_speciali è a true
     * @param string $value
     * @param bool $parti_speciali
     * @return string
     */
    public static function codeToHTML($value, $parti_speciali = false) {
        if ($parti_speciali) {
            $special_parts = explode("[[", $value);
            foreach ($special_parts as $part) {
                if (strstr($part, "]]")) {
                    $thepart = strstr($part, "]]", true);
                    ob_start();
                    include "./views/include/" . $thepart . ".php";
                    $content = ob_get_contents();
                    ob_end_clean();
                    $value = str_replace("[[" . $thepart . "]]", $content, $value);
                }
            }
        }

        $value = str_replace("©", "&copy;", $value);
        $value = str_replace("é", "&eacute;", $value);
        $value = str_replace("É", "&Eacute;", $value);
        $value = str_replace("è", "&egrave;", $value);
        $value = str_replace("È", "&Egrave;", $value);
        $value = str_replace("€", "&euro;", $value);
        $value = str_replace("í", "&iacute;", $value);
        $value = str_replace("Í", "&Iacute;", $value);
        $value = str_replace("Ì", "&Igrave;", $value);
        $value = str_replace("ì", "&igrave;", $value);
        $value = str_replace("ò", "&ograve;", $value);
        $value = str_replace("Ò", "&Ograve;", $value);
        $value = str_replace("Ù", "&Ugrave;", $value);
        $value = str_replace("ù", "&ugrave;", $value);
        $value = str_replace("à", "&agrave;", $value);
        $value = str_replace("À", "&Agrave;", $value);
        $value = str_replace("ß", "&szlig;", $value);
        $value = str_replace("ä", "&auml;", $value);
        $value = str_replace("ö", "&ouml;", $value);
        $value = str_replace("ü", "&uuml;", $value);
        $value = str_replace("Ü", "&Uuml;", $value);
        $value = str_replace("Ä", "&Auml;", $value);
        $value = str_replace("Ö", "&Ouml;", $value);



        return $value;
    }

    public static function replaceTextBoxBr($string) {
        return str_replace("<br />", "", $string);
    }

    public static function cleanForLabelGeneration($string) {
        return str_replace("\"", "\\\"", $string);
    }

    public static function cleanTextAreaQuotes($string) {
        $string = str_replace("'", "\'", $string);
        $string = str_replace('"', 'c\\"', $string);
        return $string;
    }

    /**
     *   Converte la data dal formato del datapicker al quello per un campo datetime di mysql
     * @param String $date
     * @return type 
     */
    public static function convertDateForDB($date) {
        return substr($date, 6, 4) . "-" . substr($date, 0, 2) . "-" . substr($date, 3, 2);
    }

    /**
     * ritorna una stringa pulita da passare ad un javascript
     * @param string $string
     * @param bool $html
     * @return type 
     */
    public static function cleanStringJS($string, $html = false) {
        $string = str_replace("<br />", '', $string);
        $string = str_replace("\\\"", '&quot;', $string);
        $string = str_replace("\n", '', $string);
        $string = str_replace("\r", '', $string);
        $string = str_replace("'", "\'", $string);
        return $string;
    }

    /**
     * ritorna una string pulita per i metatag
     * @param string $string
     * @param bool $html
     * @return string 
     */
    public static function cleanForMetatag($string, $html = false) {
        $string = stripslashes($string);
        $string = str_replace("<br />", ' ', $string);
        $string = str_replace("\"", ' ', $string);
        $string = str_replace("\n", ' ', $string);
        $string = str_replace("\r", '', $string);

        return $string;
    }

    /**
     * ritorna l'url corrente
     * @return string 
     */
    public static function curPageURL() {
        $pageURL = 'http';
        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        $pageURL = preg_replace("/\?lang\=[\d]+/i", "", $pageURL);
        return $pageURL;
    }

    /**
     * ritorna il tipo di dispositivo del client
     * @return string (iphone,ipad,blackbarry,android,desktop) 
     */
    public static function getDeviceType() {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $iphone = strpos($user_agent, "iPhone");
        $ipad = strpos($user_agent, "iPad");
        $blackberry = strpos($user_agent, "iBlackBerry");
        $android = strpos($user_agent, "Android");

        if ($iphone || $ipad || $blackberry || $android) {
            $browser = "mobile_";
            if ($iphone)
                return $browser . "iphone";
            if ($ipad)
                return $browser . "ipad";
            if ($blackberry)
                return $browser . "blackberry";
            if ($android)
                return $browser . "android";
        }else {
            return "desktop";
        }
    }

    /**
     * ottiene il sistema operativo del client
     * @return string (windows,linux o altro sistema operativo)
     */
    public static function getServerOS() {
        $sys = strtoupper(PHP_OS);
        if (substr($sys, 0, 3) == "WIN") {
            return "windows";
        } else if ($sys == "LINUX") {
            return "linux";
        }else
            return $sys;
    }

    public static function makeFriendlyUrl($string) {
        //$string = str_replace("&", "-and-", $string);

        return strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'), array('', '-', ''), Utility::removeAccent($string)));




        /* $string = html_entity_decode($string, ENT_QUOTES, 'UTF-8');

          //$string = fHTML::decode(fUTF8::ascii($string));
          $string = strtolower(trim($string));
          $string = str_replace("'", '', $string);
          $string = preg_replace('#[^a-z0-9\-]+#', '-', $string);
          $string = preg_replace('#_{2,}#', '-', $string);
          $string = preg_replace('#_-_#', '-', $string);
          return preg_replace('#(^_+|_+$)#D', '', $string); */
    }

    public static function removeAccent($string) {
        $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', 'iacute', 'igrave', 'aacute', 'agrave', 'eacute', 'egrave', 'oacute', 'ograve', 'uacute', 'ugrave', 'Iacute', 'Igrave', 'Aacute', 'Agrave', 'Eacute', 'Egrave', 'Oacute', 'Ograve', 'Uacute', 'Ugrave');
        $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', 'i', 'i', 'a', 'a', 'e', 'e', 'o', 'o', 'u', 'u', 'i', 'i', 'a', 'a', 'e', 'e', 'o', 'o', 'u', 'u');

        return str_replace($a, $b, $string);
    }

    function date_mysqltoscreen($stringa, $formato_visione = "%d/%m/%Y") {
        date_default_timezone_set('UTC');
        if ($stringa == "0000-00-00")
            return "";
        if ($stringa != null) {
            $output = strftime($formato_visione, strtotime($stringa));
            return $output;
        }
        else
            return "";
    }
    
    function quicksort( $array ) {
        if( count( $array ) < 2 ) {
            return $array;
        }
        $left = $right = array( );
        reset( $array );
        $pivot_key  = key( $array );
        $pivot  = array_shift( $array );
        foreach( $array as $k => $v ) {
            if( $v < $pivot )
                $left[$k] = $v;
            else
                $right[$k] = $v;
        }
        return array_merge(quicksort($left), array($pivot_key => $pivot), quicksort($right));
    }

}

?>