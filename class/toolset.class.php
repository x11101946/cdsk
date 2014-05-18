<?php

class toolSet {

    function rrmDir($p_strDir) {
        foreach (glob($p_strDir . '/*') as $strFile) {
            if (is_dir($strFile))
                self::rrmDir($strFile);
            else
                unlink($strFile);
        }
        rmdir($p_strDir);
    }

    function utf8_latin_to_ascii($string, $case = 0) {
        $UTF8_LOWER_ACCENTS = null;
        $UTF8_UPPER_ACCENTS = null;
        if ($case <= 0) {

            if (is_null($UTF8_LOWER_ACCENTS)) {
                $UTF8_LOWER_ACCENTS = array(
                    'à' => 'a',
                    'ô' => 'o',
                    'ď' => 'd',
                    'ḟ' => 'f',
                    'ë' => 'e',
                    'š' => 's',
                    'ơ' => 'o',
                    'ß' => 'ss',
                    'ă' => 'a',
                    'ř' => 'r',
                    'ț' => 't',
                    'ň' => 'n',
                    'ā' => 'a',
                    'ķ' => 'k',
                    'ŝ' => 's',
                    'ỳ' => 'y',
                    'ņ' => 'n',
                    'ĺ' => 'l',
                    'ħ' => 'h',
                    'ṗ' => 'p',
                    'ó' => 'o',
                    'ú' => 'u',
                    'ě' => 'e',
                    'é' => 'e',
                    'ç' => 'c',
                    'ẁ' => 'w',
                    'ċ' => 'c',
                    'õ' => 'o',
                    'ṡ' => 's',
                    'ø' => 'o',
                    'ģ' => 'g',
                    'ŧ' => 't',
                    'ș' => 's',
                    'ė' => 'e',
                    'ĉ' => 'c',
                    'ś' => 's',
                    'î' => 'i',
                    'ű' => 'u',
                    'ć' => 'c',
                    'ę' => 'e',
                    'ŵ' => 'w',
                    'ṫ' => 't',
                    'ū' => 'u',
                    'č' => 'c',
                    'ö' => 'oe',
                    'è' => 'e',
                    'ŷ' => 'y',
                    'ą' => 'a',
                    'ł' => 'l',
                    'ų' => 'u',
                    'ů' => 'u',
                    'ş' => 's',
                    'ğ' => 'g',
                    'ļ' => 'l',
                    'ƒ' => 'f',
                    'ž' => 'z',
                    'ẃ' => 'w',
                    'ḃ' => 'b',
                    'å' => 'a',
                    'ì' => 'i',
                    'ï' => 'i',
                    'ḋ' => 'd',
                    'ť' => 't',
                    'ŗ' => 'r',
                    'ä' => 'ae',
                    'í' => 'i',
                    'ŕ' => 'r',
                    'ê' => 'e',
                    'ü' => 'ue',
                    'ò' => 'o',
                    'ē' => 'e',
                    'ñ' => 'n',
                    'ń' => 'n',
                    'ĥ' => 'h',
                    'ĝ' => 'g',
                    'đ' => 'd',
                    'ĵ' => 'j',
                    'ÿ' => 'y',
                    'ũ' => 'u',
                    'ŭ' => 'u',
                    'ư' => 'u',
                    'ţ' => 't',
                    'ý' => 'y',
                    'ő' => 'o',
                    'â' => 'a',
                    'ľ' => 'l',
                    'ẅ' => 'w',
                    'ż' => 'z',
                    'ī' => 'i',
                    'ã' => 'a',
                    'ġ' => 'g',
                    'ṁ' => 'm',
                    'ō' => 'o',
                    'ĩ' => 'i',
                    'ù' => 'u',
                    'į' => 'i',
                    'ź' => 'z',
                    'á' => 'a',
                    'û' => 'u',
                    'þ' => 'th',
                    'ð' => 'dh',
                    'æ' => 'ae',
                    'µ' => 'u',
                    'ĕ' => 'e',
                    'œ' => 'oe');
            }

            $string = str_replace(array_keys($UTF8_LOWER_ACCENTS), array_values($UTF8_LOWER_ACCENTS), $string);
        }
        return $string;
    }

    function transliterate($string) {

        $string = self::utf8_latin_to_ascii($string);
        $string = strtolower($string);

        return $string;
    }

    function stringURLSafe($string) {
        // remove any '-' from the string since they will be used as concatenaters
        $str = str_replace('-', ' ', $string);

        $str = self::transliterate($str);

        // Trim white spaces at beginning and end of alias and make lowercase
        $str = trim(strtolower($str));

        // Remove any duplicate whitespace, and ensure all characters are alphanumeric
        $str = preg_replace('/(\s|[^A-Za-z0-9\-])+/', '-', $str);

        // Trim dashes at beginning and end of alias
        $str = trim($str, '-');

        return $str;
    }

}