<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# Copyright (C) 2009 Jean-David Gadina (macmade@eosgarden.com)                 #
# All rights reserved                                                          #
################################################################################

# $Id$

/**
 * Locale helper class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Locale
 */
final class Woops_Locale_Helper extends Woops_Core_Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The available locale types
     */
    const TYPE_ALL      = LC_ALL;
    const TYPE_COLLATE  = LC_COLLATE;
    const TYPE_CTYPE    = LC_CTYPE;
    const TYPE_MONETARY = LC_MONETARY;
    const TYPE_NUMERIC  = LC_NUMERIC;
    const TYPE_TIME     = LC_TIME;
    
    // Disabled for the moment as it seems this is not defined on some PHP setups
    #const TYPE_MESSAGES = LC_MESSAGES;
    
    /**
     * The unique instance of the class (singleton)
     */
    private static $_instance = NULL;
    
    /**
     * The available language tags, with their available variants
     */
    protected $_languages     = array(
        'aa'  => array(),   // Afar
        'ab'  => array(),   // Abkhazian
        'ace' => array(),   // Achinese
        'ach' => array(),   // Acoli
        'ada' => array(),   // Adangme
        'ady' => array(),   // Adyghe
        'ae'  => array(),   // Avestan
        'af'  => array(     // Afrikaans
            'ZA' => true
        ),
        'afa' => array(),   // Afro-Asiatic (Other)
        'afh' => array(),   // Afrihili
        'ain' => array(),   // Ainu
        'ak'  => array(),   // Akan
        'akk' => array(),   // Akkadian
        'ale' => array(),   // Aleut
        'alg' => array(),   // Algonquian languages
        'alt' => array(),   // Southern Altai
        'am'  => array(     // Amharic
            'ET' => true
        ),
        'an'  => array(),   // Aragonese
        'ang' => array(),   // English, Old (ca. 450-1100)
        'anp' => array(),   // Angika
        'apa' => array(),   // Apache languages
        'ar'  => array(     // Arabic
            'AE' => true,
            'BH' => true,
            'DZ' => true,
            'EG' => true,
            'IN' => true,
            'IQ' => true,
            'JO' => true,
            'KW' => true,
            'LB' => true,
            'LY' => true,
            'MA' => true,
            'OM' => true,
            'QA' => true,
            'SA' => true,
            'SD' => true,
            'SY' => true,
            'TN' => true,
            'YE' => true
        ),
        'arc' => array(),   // Aramaic
        'arn' => array(),   // Mapudungun
        'arp' => array(),   // Arapaho
        'art' => array(),   // Artificial (Other)
        'arw' => array(),   // Arawak
        'as'  => array(),   // Assamese
        'ast' => array(),   // Asturian
        'ath' => array(),   // Athapascan languages
        'aus' => array(),   // Australian languages
        'av'  => array(),   // Avaric
        'awa' => array(),   // Awadhi
        'ay'  => array(),   // Aymara
        'az'  => array(     // Azerbaijani
            'AZ' => true
        ),
        'ba'  => array(),   // Bashkir
        'bad' => array(),   // Banda languages
        'bai' => array(),   // Bamileke languages
        'bal' => array(),   // Baluchi
        'ban' => array(),   // Balinese
        'bas' => array(),   // Basa
        'bat' => array(),   // Baltic (Other)
        'be'  => array(     // Belarusian
            'BY' => true
        ),
        'bej' => array(),   // Beja
        'bem' => array(),   // Bemba
        'ber' => array(),   // Berber (Other)
        'bg'  => array(     // Bulgarian
            'BG' => true
        ),
        'bh'  => array(),   // Bihari
        'bho' => array(),   // Bhojpuri
        'bi'  => array(),   // Bislama
        'bik' => array(),   // Bikol
        'bin' => array(),   // Bini
        'bla' => array(),   // Siksika
        'bm'  => array(),   // Bambara
        'bn'  => array(     // Bengali
            'IN' => true
        ),
        'bnt' => array(),   // Bantu (Other)
        'bo'  => array(),   // Tibetan
        'br'  => array(),   // Breton
        'bra' => array(),   // Braj
        'bs'  => array(),   // Bosnian
        'btk' => array(),   // Batak languages
        'bua' => array(),   // Buriat
        'bug' => array(),   // Buginese
        'byn' => array(),   // Blin
        'ca'  => array(     // Catalan
            'ES' => true
        ),
        'cad' => array(),   // Caddo
        'cai' => array(),   // Central American Indian (Other)
        'car' => array(),   // Galibi Carib
        'cau' => array(),   // Caucasian (Other)
        'ce'  => array(),   // Chechen
        'ceb' => array(),   // Cebuano
        'cel' => array(),   // Celtic (Other)
        'ch'  => array(),   // Chamorro
        'chb' => array(),   // Chibcha
        'chg' => array(),   // Chagatai
        'chk' => array(),   // Chuukese
        'chm' => array(),   // Mari
        'chn' => array(),   // Chinook jargon
        'cho' => array(),   // Choctaw
        'chp' => array(),   // Chipewyan
        'chr' => array(),   // Cherokee
        'chy' => array(),   // Cheyenne
        'cmc' => array(),   // Chamic languages
        'co'  => array(),   // Corsican
        'cop' => array(),   // Coptic
        'cpe' => array(),   // Creoles and pidgins, English-based (Other)
        'cpf' => array(),   // Creoles and pidgins, French-based (Other)
        'cpp' => array(),   // Creoles and pidgins, Portuguese-based (Other)
        'cr'  => array(),   // Cree
        'crh' => array(),   // Crimean Tatar
        'crp' => array(),   // Creoles and pidgins (Other)
        'cs'  => array(     // Czech
            'CZ' => true
        ),
        'csb' => array(),   // Kashubian
        'cu'  => array(),   // Church Slavic
        'cus' => array(),   // Cushitic (Other)
        'cv'  => array(),   // Chuvash
        'cy'  => array(),   // Welsh
        'da'  => array(     // Danish
            'DK' => true
        ),
        'dak' => array(),   // Dakota
        'dar' => array(),   // Dargwa
        'day' => array(),   // Land Dayak languages
        'de'  => array(     // German
            'AT' => true,
            'BE' => true,
            'CH' => true,
            'DE' => true,
            'LI' => true,
            'LU' => true
        ),
        'del' => array(),   // Delaware
        'den' => array(),   // Slave (Athapascan)
        'dgr' => array(),   // Dogrib
        'din' => array(),   // Dinka
        'doi' => array(),   // Dogri
        'dra' => array(),   // Dravidian (Other)
        'dsb' => array(),   // Lower Sorbian
        'dua' => array(),   // Duala
        'dum' => array(),   // Dutch, Middle (ca. 1050-1350)
        'dv'  => array(),   // Divehi
        'dyu' => array(),   // Dyula
        'dz'  => array(),   // Dzongkha
        'ee'  => array(),   // Ewe
        'efi' => array(),   // Efik
        'egy' => array(),   // Egyptian (Ancient)
        'eka' => array(),   // Ekajuk
        'el'  => array(     // Greek, Modern (1453-)
            'GR' => true
        ),
        'elx' => array(),   // Elamite
        'en'  => array(     // English
            'AS'       => true,
            'AU'       => true,
            'BE'       => true,
            'BW'       => true,
            'BZ'       => true,
            'CA'       => true,
            'GB'       => true,
            'GU'       => true,
            'HK'       => true,
            'IE'       => true,
            'IN'       => true,
            'JM'       => true,
            'MH'       => true,
            'MP'       => true,
            'MT'       => true,
            'NZ'       => true,
            'PH'       => true,
            'SG'       => true,
            'TT'       => true,
            'UM'       => true,
            'US'       => true,
            'US_POSIX' => true,
            'VI'       => true,
            'ZA'       => true,
            'ZW'       => true
        ),
        'enm' => array(),   // English, Middle (1100-1500)
        'eo'  => array(),   // Esperanto
        'es'  => array(     // Spanish
            'AR' => true,
            'BO' => true,
            'CL' => true,
            'CO' => true,
            'CR' => true,
            'DO' => true,
            'EC' => true,
            'ES' => true,
            'GT' => true,
            'HN' => true,
            'MX' => true,
            'NI' => true,
            'PA' => true,
            'PE' => true,
            'PR' => true,
            'PY' => true,
            'SV' => true,
            'US' => true,
            'UY' => true,
            'VE' => true
        ),
        'et'  => array(     // Estonian
            'EE' => true
        ),
        'eu'  => array(     // Basque
            'ES' => true
        ),
        'ewo' => array(),   // Ewondo
        'fa'  => array(     // Persian
            'IR' => true
        ),
        'fan' => array(),   // Fang
        'fat' => array(),   // Fanti
        'ff'  => array(),   // Fulah
        'fi'  => array(     // Finnish
            'FI' => true
        ),
        'fil' => array(),   // Filipino
        'fiu' => array(),   // Finno-Ugrian (Other)
        'fj'  => array(),   // Fijian
        'fo'  => array(     // Faroese
            'FO' => true
        ),
        'fon' => array(),   // Fon
        'fr'  => array(     // French
            'BE' => true,
            'CA' => true,
            'CH' => true,
            'FR' => true,
            'LU' => true,
            'MC' => true
        ),
        'frm' => array(),   // French, Middle (ca. 1400-1600)
        'fro' => array(),   // French, Old (842-ca. 1400)
        'frr' => array(),   // Northern Frisian
        'frs' => array(),   // Eastern Frisian
        'fur' => array(),   // Friulian
        'fy'  => array(),   // Western Frisian
        'ga'  => array(     // Irish
            'IE' => true
        ),
        'gaa' => array(),   // Ga
        'gay' => array(),   // Gayo
        'gba' => array(),   // Gbaya
        'gd'  => array(),   // Gaelic
        'gem' => array(),   // Germanic (Other)
        'gez' => array(),   // Geez
        'gil' => array(),   // Gilbertese
        'gl'  => array(     // Galician
            'ES' => true
        ),
        'gmh' => array(),   // German, Middle High (ca. 1050-1500)
        'gn'  => array(),   // Guarani
        'goh' => array(),   // German, Old High (ca. 750-1050)
        'gon' => array(),   // Gondi
        'gor' => array(),   // Gorontalo
        'got' => array(),   // Gothic
        'grb' => array(),   // Grebo
        'grc' => array(),   // Greek, Ancient (to 1453)
        'gsw' => array(),   // Swiss German
        'gu'  => array(     // Gujarati
            'IN' => true
        ),
        'gv'  => array(     // Manx
            'GB' => true
        ),
        'gwi' => array(),   // Gwich'in
        'ha'  => array(),   // Hausa
        'hai' => array(),   // Haida
        'haw' => array(),   // Hawaiian
        'he'  => array(     // Hebrew
            'IL' => true
        ),
        'hi'  => array(     // Hindi
            'IN' => true
        ),
        'hil' => array(),   // Hiligaynon
        'him' => array(),   // Himachali
        'hit' => array(),   // Hittite
        'hmn' => array(),   // Hmong
        'ho'  => array(),   // Hiri Motu
        'hr'  => array(     // Croatian
            'HR' => true
        ),
        'hsb' => array(),   // Upper Sorbian
        'ht'  => array(),   // Haitian
        'hu'  => array(),   // Hungarian
        'hup' => array(),   // Hupa
        'hy'  => array(     // Armenian
            'HU'         => true,
            'AM'         => true,
            'AM_REVISED' => true
        ),
        'hz'  => array(),   // Herero
        'ia'  => array(),   // Interlingua (International Auxiliary Language Association)
        'iba' => array(),   // Iban
        'id'  => array(     // Indonesian
            'ID' => true
        ),
        'ie'  => array(),   // Interlingue
        'ig'  => array(),   // Igbo
        'ii'  => array(),   // Sichuan Yi
        'ijo' => array(),   // Ijo languages
        'ik'  => array(),   // Inupiaq
        'ilo' => array(),   // Iloko
        'inc' => array(),   // Indic (Other)
        'ine' => array(),   // Indo-European (Other)
        'inh' => array(),   // Ingush
        'io'  => array(),   // Ido
        'ira' => array(),   // Iranian (Other)
        'iro' => array(),   // Iroquoian languages
        'is'  => array(     // Icelandic
            'IS' => true
        ),
        'it'  => array(     // Italian
            'CH' => true,
            'IT' => true
        ),
        'iu'  => array(),   // Inuktitut
        'ja'  => array(     // Japanese
            'JP' => true
        ),
        'jbo' => array(),   // Lojban
        'jpr' => array(),   // Judeo-Persian
        'jrb' => array(),   // Judeo-Arabic
        'jv'  => array(),   // Javanese
        'ka'  => array(     // Georgian
            'GE' => true
        ),
        'kaa' => array(),   // Kara-Kalpak
        'kab' => array(),   // Kabyle
        'kac' => array(),   // Kachin
        'kam' => array(),   // Kamba
        'kar' => array(),   // Karen languages
        'kaw' => array(),   // Kawi
        'kbd' => array(),   // Kabardian
        'kg'  => array(),   // Kongo
        'kha' => array(),   // Khasi
        'khi' => array(),   // Khoisan (Other)
        'kho' => array(),   // Khotanese
        'ki'  => array(),   // Kikuyu
        'kj'  => array(),   // Kuanyama
        'kk'  => array(     // Kazakh
            'KZ' => true
        ),
        'kl'  => array(     // Kalaallisut
            'GL' => true
        ),
        'km'  => array(),   // Central Khmer
        'kmb' => array(),   // Kimbundu
        'kn'  => array(     // Kannada
            'IN' => true
        ),
        'ko'  => array(     // Korean
            'KR' => true
        ),
        'kok' => array(),   // Konkani
        'kos' => array(),   // Kosraean
        'kpe' => array(),   // Kpelle
        'kr'  => array(),   // Kanuri
        'krc' => array(),   // Karachay-Balkar
        'krl' => array(),   // Karelian
        'kro' => array(),   // Kru languages
        'kru' => array(),   // Kurukh
        'ks'  => array(),   // Kashmiri
        'ku'  => array(),   // Kurdish
        'kum' => array(),   // Kumyk
        'kut' => array(),   // Kutenai
        'kv'  => array(),   // Komi
        'kw'  => array(     // Cornish
            'GB' => true
        ),
        'ky'  => array(     // Kyrgyz
            'KG' => true
        ),
        'la'  => array(),   // Latin
        'lad' => array(),   // Ladino
        'lah' => array(),   // Lahnda
        'lam' => array(),   // Lamba
        'lb'  => array(),   // Luxembourgish
        'lez' => array(),   // Lezghian
        'lg'  => array(),   // Ganda
        'li'  => array(),   // Limburgan
        'ln'  => array(),   // Lingala
        'lo'  => array(),   // Lao
        'lol' => array(),   // Mongo
        'loz' => array(),   // Lozi
        'lt'  => array(     // Lithuanian
            'LT' => true
        ),
        'lu'  => array(),   // Luba-Katanga
        'lua' => array(),   // Luba-Lulua
        'lui' => array(),   // Luiseno
        'lun' => array(),   // Lunda
        'luo' => array(),   // Luo (Kenya and Tanzania)
        'lus' => array(),   // Lushai
        'lv'  => array(     // Latvian
            'LV' => true
        ),
        'mad' => array(),   // Madurese
        'mag' => array(),   // Magahi
        'mai' => array(),   // Maithili
        'mak' => array(),   // Makasar
        'man' => array(),   // Mandingo
        'map' => array(),   // Austronesian (Other)
        'mas' => array(),   // Masai
        'mdf' => array(),   // Moksha
        'mdr' => array(),   // Mandar
        'men' => array(),   // Mende
        'mg'  => array(),   // Malagasy
        'mga' => array(),   // Irish, Middle (900-1200)
        'mh'  => array(),   // Marshallese
        'mi'  => array(),   // Maori
        'mic' => array(),   // Mi'kmaq
        'min' => array(),   // Minangkabau
        'mis' => array(),   // Miscellaneous languages
        'mk'  => array(     // Macedonian
            'MK' => true
        ),
        'mkh' => array(),   // Mon-Khmer (Other)
        'ml'  => array(),   // Malayalam
        'mn'  => array(     // Mongolian
            'MN' => true
        ),
        'mnc' => array(),   // Manchu
        'mni' => array(),   // Manipuri
        'mno' => array(),   // Manobo languages
        'mo'  => array(),   // Moldavian
        'moh' => array(),   // Mohawk
        'mos' => array(),   // Mossi
        'mr'  => array(     // Marathi
            'IN' => true
        ),
        'ms'  => array(     // Malay
            'BN' => true,
            'MY' => true
        ),
        'mt'  => array(     // Maltese
            'MT' => true
        ),
        'mul' => array(),   // Multiple languages
        'mun' => array(),   // Munda languages
        'mus' => array(),   // Creek
        'mwl' => array(),   // Mirandese
        'mwr' => array(),   // Marwari
        'my'  => array(),   // Burmese
        'myn' => array(),   // Mayan languages
        'myv' => array(),   // Erzya
        'na'  => array(),   // Nauru
        'nah' => array(),   // Nahuatl languages
        'nai' => array(),   // North American Indian
        'nap' => array(),   // Neapolitan
        'nb'  => array(     // Norwegian Bokmål
            'NO' => true
        ),
        'nd'  => array(),   // Ndebele, North
        'nds' => array(),   // Low German
        'ne'  => array(),   // Nepali
        'new' => array(),   // Nepal Bhasa
        'ng'  => array(),   // Ndonga
        'nia' => array(),   // Nias
        'nic' => array(),   // Niger-Kordofanian (Other)
        'niu' => array(),   // Niuean
        'nl'  => array(     // Dutch
            'BE' => true,
            'NL' => true
        ),
        'nn'  => array(     // Norwegian Nynorsk
            'NO' => true
        ),
        'no'  => array(     // Norwegian
            'NO' => true,
            'NO_NY' => true
        ),
        'nog' => array(),   // Nogai
        'non' => array(),   // Norse, Old
        'nqo' => array(),   // N'Ko
        'nr'  => array(),   // Ndebele, South
        'nso' => array(),   // Northern Sotho
        'nub' => array(),   // Nubian languages
        'nv'  => array(),   // Navajo
        'nwc' => array(),   // Classical Newari
        'ny'  => array(),   // Chichewa
        'nym' => array(),   // Nyamwezi
        'nyn' => array(),   // Nyankole
        'nyo' => array(),   // Nyoro
        'nzi' => array(),   // Nzima
        'oc'  => array(),   // Occitan (post 1500)
        'oj'  => array(),   // Ojibwa
        'om'  => array(     // Oromo
            'ET' => true,
            'KE' => true
        ),
        'or'  => array(),   // Oriya
        'os'  => array(),   // Ossetian
        'osa' => array(),   // Osage
        'ota' => array(),   // Turkish, Ottoman (1500-1928)
        'oto' => array(),   // Otomian languages
        'pa'  => array(     // Panjabi
            'IN' => true
        ),
        'paa' => array(),   // Papuan (Other)
        'pag' => array(),   // Pangasinan
        'pal' => array(),   // Pahlavi
        'pam' => array(),   // Pampanga
        'pap' => array(),   // Papiamento
        'pau' => array(),   // Palauan
        'peo' => array(),   // Persian, Old (ca. 600-400 B.C.)
        'phi' => array(),   // Philippine (Other)
        'phn' => array(),   // Phoenician
        'pi'  => array(),   // Pali
        'pl'  => array(     // Polish
            'PL' => true
        ),
        'pon' => array(),   // Pohnpeian
        'pra' => array(),   // Prakrit languages
        'pro' => array(),   // Provençal, Old (to 1500)
        'ps'  => array(),   // Pushto
        'pt'  => array(     // Portuguese
            'BR' => true,
            'PT' => true
        ),
        'qu'  => array(),   // Quechua
        'raj' => array(),   // Rajasthani
        'rap' => array(),   // Rapanui
        'rar' => array(),   // Rarotongan
        'rm'  => array(),   // Romansh
        'rn'  => array(),   // Rundi
        'ro'  => array(     // Romanian
            'RO' => true
        ),
        'roa' => array(),   // Romance (Other)
        'rom' => array(),   // Romany
        'ru'  => array(     // Russian
            'RU' => true,
            'UA' => true
        ),
        'rup' => array(),   // Aromanian
        'rw'  => array(),   // Kinyarwanda
        'sa'  => array(     // Sanskrit
            'IN' => true
        ),
        'sad' => array(),   // Sandawe
        'sah' => array(),   // Yakut
        'sai' => array(),   // South American Indian (Other)
        'sal' => array(),   // Salishan languages
        'sam' => array(),   // Samaritan Aramaic
        'sas' => array(),   // Sasak
        'sat' => array(),   // Santali
        'sc'  => array(),   // Sardinian
        'scn' => array(),   // Sicilian
        'sco' => array(),   // Scots
        'sd'  => array(),   // Sindhi
        'se'  => array(),   // Northern Sami
        'sel' => array(),   // Selkup
        'sem' => array(),   // Semitic (Other)
        'sg'  => array(),   // Sango
        'sga' => array(),   // Irish, Old (to 900)
        'sgn' => array(),   // Sign Languages
        'shn' => array(),   // Shan
        'si'  => array(),   // Sinhala
        'sid' => array(),   // Sidamo
        'sio' => array(),   // Siouan languages
        'sit' => array(),   // Sino-Tibetan (Other)
        'sk'  => array(     // Slovak
            'SK' => true
        ),
        'sl'  => array(     // Slovenian
            'SI' => true
        ),
        'sla' => array(),   // Slavic (Other)
        'sm'  => array(),   // Samoan
        'sma' => array(),   // Southern Sami
        'smi' => array(),   // Sami languages (Other)
        'smj' => array(),   // Lule Sami
        'smn' => array(),   // Inari Sami
        'sms' => array(),   // Skolt Sami
        'sn'  => array(),   // Shona
        'snk' => array(),   // Soninke
        'so'  => array(     // Somali
            'DJ' => true,
            'ET' => true,
            'KE' => true,
            'SO' => true
        ),
        'sog' => array(),   // Sogdian
        'son' => array(),   // Songhai languages
        'sq'  => array(     // Albanian
            'AL' => true
        ),
        'sr'  => array(     // Serbian
            'YU' => true
        ),
        'srn' => array(),   // Sranan Tongo
        'srr' => array(),   // Serer
        'ss'  => array(),   // Swati
        'ssa' => array(),   // Nilo-Saharan (Other)
        'st'  => array(),   // Sotho, Southern
        'su'  => array(),   // Sundanese
        'suk' => array(),   // Sukuma
        'sus' => array(),   // Susu
        'sux' => array(),   // Sumerian
        'sv'  => array(     // Swedish
            'FI' => true,
            'SE' => true
        ),
        'sw'  => array(     // Swahili
            'KE' => true,
            'TZ' => true
        ),
        'syr' => array(),   // Syriac
        'ta'  => array(     // Tamil
            'IN' => true
        ),
        'tai' => array(),   // Tai (Other)
        'te'  => array(     // Telugu
            'IN' => true
        ),
        'tem' => array(),   // Timne
        'ter' => array(),   // Tereno
        'tet' => array(),   // Tetum
        'tg'  => array(),   // Tajik
        'th'  => array(     // Thai
            'TH' => true
        ),
        'ti'  => array(),   // Tigrinya
        'tig' => array(),   // Tigre
        'tiv' => array(),   // Tiv
        'tk'  => array(),   // Turkmen
        'tkl' => array(),   // Tokelau
        'tl'  => array(),   // Tagalog
        'tlh' => array(),   // Klingon
        'tli' => array(),   // Tlingit
        'tmh' => array(),   // Tamashek
        'tn'  => array(),   // Tswana
        'to'  => array(),   // Tonga (Tonga Islands)
        'tog' => array(),   // Tonga (Nyasa)
        'tpi' => array(),   // Tok Pisin
        'tr'  => array(     // Turkish
            'TR' => true
        ),
        'ts'  => array(),   // Tsonga
        'tsi' => array(),   // Tsimshian
        'tt'  => array(     // Tatar
            'RU' => true
        ),
        'tum' => array(),   // Tumbuka
        'tup' => array(),   // Tupi languages
        'tut' => array(),   // Altaic (Other)
        'tvl' => array(),   // Tuvalu
        'tw'  => array(),   // Twi
        'ty'  => array(),   // Tahitian
        'tyv' => array(),   // Tuvinian
        'udm' => array(),   // Udmurt
        'ug'  => array(),   // Uighur
        'uga' => array(),   // Ugaritic
        'uk'  => array(     // Ukrainian
            'UA' => true
        ),
        'umb' => array(),   // Umbundu
        'und' => array(),   // Undetermined
        'ur'  => array(     // Urdu
            'PK' => true
        ),
        'uz'  => array(     // Uzbek
            'UZ' => true
        ),
        'vai' => array(),   // Vai
        've'  => array(),   // Venda
        'vi'  => array(     // Vietnamese
            'VN' => true
        ),
        'vo'  => array(),   // Volapük
        'vot' => array(),   // Votic
        'wa'  => array(),   // Walloon
        'wak' => array(),   // Wakashan languages
        'wal' => array(),   // Walamo
        'war' => array(),   // Waray
        'was' => array(),   // Washo
        'wen' => array(),   // Sorbian languages
        'wo'  => array(),   // Wolof
        'xal' => array(),   // Kalmyk
        'xh'  => array(),   // Xhosa
        'yao' => array(),   // Yao
        'yap' => array(),   // Yapese
        'yi'  => array(),   // Yiddish
        'yo'  => array(),   // Yoruba
        'ypk' => array(),   // Yupik languages
        'za'  => array(),   // Zhuang
        'zap' => array(),   // Zapotec
        'zen' => array(),   // Zenaga
        'zh'  => array(     // Chinese
            'CN' => true,
            'HK' => true,
            'MO' => true,
            'SG' => true,
            'TW' => true
        ),
        'znd' => array(),   // Zande languages
        'zu'  => array(),   // Zulu
        'zun' => array(),   // Zuni
        'zxx' => array(),   // No linguistic content
        'zza' => array()    // Zaza
    );
    
    /**
     * Class constructor
     * 
     * The class constructor is private to avoid multiple instances of the
     * class (singleton).
     * 
     * @return  void
     */
    private function __construct()
    {}
    
    /**
     * Clones an instance of the class
     * 
     * A call to this method will produce an exception, as the class cannot
     * be cloned (singleton).
     * 
     * @return  void
     * @throws  Woops_Core_Singleton_Exception  Always, as the class cannot be cloned (singleton)
     */
    public function __clone()
    {
        throw new Woops_Core_Singleton_Exception(
            'Class ' . __CLASS__ . ' cannot be cloned',
            Woops_Core_Singleton_Exception::EXCEPTION_CLONE
        );
    }
    
    /**
     * Gets the unique class instance
     * 
     * This method is used to get the unique instance of the class
     * (singleton). If no instance is available, it will create it.
     * 
     * @return  Woops_Locale_Helper The unique instance of the class
     * @see     __construct
     */
    public static function getInstance()
    {
        // Checks if the unique instance already exists
        if( !is_object( self::$_instance ) ) {
            
            // Creates the unique instance
            self::$_instance = new self();
        }
        
        // Returns the unique instance
        return self::$_instance;
    }
    
    /**
     * Checks if a locale is valid
     * 
     * @param   string  The locale to validate
     * @return  boolean True if the locale is valid, otherwise false
     */
    public function isValid( $locale )
    {
        // Checks if we have a variant
        if( $sub = strpos( $locale, '_' ) ) {
            
            // Language code
            $lang    = substr( $locale, 0, $sub );
            
            // Variant
            $variant = substr( $locale, $sub + 1 );
            
            // Checks the variant
            return isset( $this->_languages[ $lang ][ $variant ] );
        }
        
        // Checks the language code
        return isset( $this->_languages[ $locale ] );
    }
    
    /**
     * Gets the available languages
     * 
     * @return  array   An array with the available languages
     */
    public function getLanguages()
    {
        return $this->_languages;
    }
}
