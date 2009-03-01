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
 * Time relatded utilities
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Time
 */
final class Woops_Time_Utils implements Woops_Core_Singleton_Interface
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The unique instance of the class (singleton)
     */
    private static $_instance = NULL;
    
    /**
     * The available timezones
     */
    protected $_timezones = array(
        'Africa/Abidjan'                   => true,
        'Africa/Accra'                     => true,
        'Africa/Addis_Ababa'               => true,
        'Africa/Algiers'                   => true,
        'Africa/Asmara'                    => true,
        'Africa/Asmera'                    => true,
        'Africa/Bamako'                    => true,
        'Africa/Bangui'                    => true,
        'Africa/Banjul'                    => true,
        'Africa/Bissau'                    => true,
        'Africa/Blantyre'                  => true,
        'Africa/Brazzaville'               => true,
        'Africa/Bujumbura'                 => true,
        'Africa/Cairo'                     => true,
        'Africa/Casablanca'                => true,
        'Africa/Ceuta'                     => true,
        'Africa/Conakry'                   => true,
        'Africa/Dakar'                     => true,
        'Africa/Dar_es_Salaam'             => true,
        'Africa/Djibouti'                  => true,
        'Africa/Douala'                    => true,
        'Africa/El_Aaiun'                  => true,
        'Africa/Freetown'                  => true,
        'Africa/Gaborone'                  => true,
        'Africa/Harare'                    => true,
        'Africa/Johannesburg'              => true,
        'Africa/Kampala'                   => true,
        'Africa/Khartoum'                  => true,
        'Africa/Kigali'                    => true,
        'Africa/Kinshasa'                  => true,
        'Africa/Lagos'                     => true,
        'Africa/Libreville'                => true,
        'Africa/Lome'                      => true,
        'Africa/Luanda'                    => true,
        'Africa/Lubumbashi'                => true,
        'Africa/Lusaka'                    => true,
        'Africa/Malabo'                    => true,
        'Africa/Maputo'                    => true,
        'Africa/Maseru'                    => true,
        'Africa/Mbabane'                   => true,
        'Africa/Mogadishu'                 => true,
        'Africa/Monrovia'                  => true,
        'Africa/Nairobi'                   => true,
        'Africa/Ndjamena'                  => true,
        'Africa/Niamey'                    => true,
        'Africa/Nouakchott'                => true,
        'Africa/Ouagadougou'               => true,
        'Africa/Porto-Novo'                => true,
        'Africa/Sao_Tome'                  => true,
        'Africa/Timbuktu'                  => true,
        'Africa/Tripoli'                   => true,
        'Africa/Tunis'                     => true,
        'Africa/Windhoek'                  => true,
        'America/Adak'                     => true,
        'America/Anchorage'                => true,
        'America/Anguilla'                 => true,
        'America/Antigua'                  => true,
        'America/Araguaina'                => true,
        'America/Argentina/Buenos_Aires'   => true,
        'America/Argentina/Catamarca'      => true,
        'America/Argentina/ComodRivadavia' => true,
        'America/Argentina/Cordoba'        => true,
        'America/Argentina/Jujuy'          => true,
        'America/Argentina/La_Rioja'       => true,
        'America/Argentina/Mendoza'        => true,
        'America/Argentina/Rio_Gallegos'   => true,
        'America/Argentina/Salta'          => true,
        'America/Argentina/San_Juan'       => true,
        'America/Argentina/San_Luis'       => true,
        'America/Argentina/Tucuman'        => true,
        'America/Argentina/Ushuaia'        => true,
        'America/Aruba'                    => true,
        'America/Asuncion'                 => true,
        'America/Atikokan'                 => true,
        'America/Atka'                     => true,
        'America/Bahia'                    => true,
        'America/Barbados'                 => true,
        'America/Belem'                    => true,
        'America/Belize'                   => true,
        'America/Blanc-Sablon'             => true,
        'America/Boa_Vista'                => true,
        'America/Bogota'                   => true,
        'America/Boise'                    => true,
        'America/Buenos_Aires'             => true,
        'America/Cambridge_Bay'            => true,
        'America/Campo_Grande'             => true,
        'America/Cancun'                   => true,
        'America/Caracas'                  => true,
        'America/Catamarca'                => true,
        'America/Cayenne'                  => true,
        'America/Cayman'                   => true,
        'America/Chicago'                  => true,
        'America/Chihuahua'                => true,
        'America/Coral_Harbour'            => true,
        'America/Cordoba'                  => true,
        'America/Costa_Rica'               => true,
        'America/Cuiaba'                   => true,
        'America/Curacao'                  => true,
        'America/Danmarkshavn'             => true,
        'America/Dawson'                   => true,
        'America/Dawson_Creek'             => true,
        'America/Denver'                   => true,
        'America/Detroit'                  => true,
        'America/Dominica'                 => true,
        'America/Edmonton'                 => true,
        'America/Eirunepe'                 => true,
        'America/El_Salvador'              => true,
        'America/Ensenada'                 => true,
        'America/Fort_Wayne'               => true,
        'America/Fortaleza'                => true,
        'America/Glace_Bay'                => true,
        'America/Godthab'                  => true,
        'America/Goose_Bay'                => true,
        'America/Grand_Turk'               => true,
        'America/Grenada'                  => true,
        'America/Guadeloupe'               => true,
        'America/Guatemala'                => true,
        'America/Guayaquil'                => true,
        'America/Guyana'                   => true,
        'America/Halifax'                  => true,
        'America/Havana'                   => true,
        'America/Hermosillo'               => true,
        'America/Indiana/Indianapolis'     => true,
        'America/Indiana/Knox'             => true,
        'America/Indiana/Marengo'          => true,
        'America/Indiana/Petersburg'       => true,
        'America/Indiana/Tell_City'        => true,
        'America/Indiana/Vevay'            => true,
        'America/Indiana/Vincennes'        => true,
        'America/Indiana/Winamac'          => true,
        'America/Indianapolis'             => true,
        'America/Inuvik'                   => true,
        'America/Iqaluit'                  => true,
        'America/Jamaica'                  => true,
        'America/Jujuy'                    => true,
        'America/Juneau'                   => true,
        'America/Kentucky/Louisville'      => true,
        'America/Kentucky/Monticello'      => true,
        'America/Knox_IN'                  => true,
        'America/La_Paz'                   => true,
        'America/Lima'                     => true,
        'America/Los_Angeles'              => true,
        'America/Louisville'               => true,
        'America/Maceio'                   => true,
        'America/Managua'                  => true,
        'America/Manaus'                   => true,
        'America/Marigot'                  => true,
        'America/Martinique'               => true,
        'America/Mazatlan'                 => true,
        'America/Mendoza'                  => true,
        'America/Menominee'                => true,
        'America/Merida'                   => true,
        'America/Mexico_City'              => true,
        'America/Miquelon'                 => true,
        'America/Moncton'                  => true,
        'America/Monterrey'                => true,
        'America/Montevideo'               => true,
        'America/Montreal'                 => true,
        'America/Montserrat'               => true,
        'America/Nassau'                   => true,
        'America/New_York'                 => true,
        'America/Nipigon'                  => true,
        'America/Nome'                     => true,
        'America/Noronha'                  => true,
        'America/North_Dakota/Center'      => true,
        'America/North_Dakota/New_Salem'   => true,
        'America/Panama'                   => true,
        'America/Pangnirtung'              => true,
        'America/Paramaribo'               => true,
        'America/Phoenix'                  => true,
        'America/Port_of_Spain'            => true,
        'America/Port-au-Prince'           => true,
        'America/Porto_Acre'               => true,
        'America/Porto_Velho'              => true,
        'America/Puerto_Rico'              => true,
        'America/Rainy_River'              => true,
        'America/Rankin_Inlet'             => true,
        'America/Recife'                   => true,
        'America/Regina'                   => true,
        'America/Resolute'                 => true,
        'America/Rio_Branco'               => true,
        'America/Rosario'                  => true,
        'America/Santarem'                 => true,
        'America/Santiago'                 => true,
        'America/Santo_Domingo'            => true,
        'America/Sao_Paulo'                => true,
        'America/Scoresbysund'             => true,
        'America/Shiprock'                 => true,
        'America/St_Barthelemy'            => true,
        'America/St_Johns'                 => true,
        'America/St_Kitts'                 => true,
        'America/St_Lucia'                 => true,
        'America/St_Thomas'                => true,
        'America/St_Vincent'               => true,
        'America/Swift_Current'            => true,
        'America/Tegucigalpa'              => true,
        'America/Thule'                    => true,
        'America/Thunder_Bay'              => true,
        'America/Tijuana'                  => true,
        'America/Toronto'                  => true,
        'America/Tortola'                  => true,
        'America/Vancouver'                => true,
        'America/Virgin'                   => true,
        'America/Whitehorse'               => true,
        'America/Winnipeg'                 => true,
        'America/Yakutat'                  => true,
        'America/Yellowknife'              => true,
        'Antarctica/Casey'                 => true,
        'Antarctica/Davis'                 => true,
        'Antarctica/DumontDUrville'        => true,
        'Antarctica/Mawson'                => true,
        'Antarctica/McMurdo'               => true,
        'Antarctica/Palmer'                => true,
        'Antarctica/Rothera'               => true,
        'Antarctica/South_Pole'            => true,
        'Antarctica/Syowa'                 => true,
        'Antarctica/Vostok'                => true,
        'Arctic/Longyearbyen'              => true,
        'Asia/Aden'                        => true,
        'Asia/Almaty'                      => true,
        'Asia/Amman'                       => true,
        'Asia/Anadyr'                      => true,
        'Asia/Aqtau'                       => true,
        'Asia/Aqtobe'                      => true,
        'Asia/Ashgabat'                    => true,
        'Asia/Ashkhabad'                   => true,
        'Asia/Baghdad'                     => true,
        'Asia/Bahrain'                     => true,
        'Asia/Baku'                        => true,
        'Asia/Bangkok'                     => true,
        'Asia/Beirut'                      => true,
        'Asia/Bishkek'                     => true,
        'Asia/Brunei'                      => true,
        'Asia/Calcutta'                    => true,
        'Asia/Choibalsan'                  => true,
        'Asia/Chongqing'                   => true,
        'Asia/Chungking'                   => true,
        'Asia/Colombo'                     => true,
        'Asia/Dacca'                       => true,
        'Asia/Damascus'                    => true,
        'Asia/Dhaka'                       => true,
        'Asia/Dili'                        => true,
        'Asia/Dubai'                       => true,
        'Asia/Dushanbe'                    => true,
        'Asia/Gaza'                        => true,
        'Asia/Harbin'                      => true,
        'Asia/Ho_Chi_Minh'                 => true,
        'Asia/Hong_Kong'                   => true,
        'Asia/Hovd'                        => true,
        'Asia/Irkutsk'                     => true,
        'Asia/Istanbul'                    => true,
        'Asia/Jakarta'                     => true,
        'Asia/Jayapura'                    => true,
        'Asia/Jerusalem'                   => true,
        'Asia/Kabul'                       => true,
        'Asia/Kamchatka'                   => true,
        'Asia/Karachi'                     => true,
        'Asia/Kashgar'                     => true,
        'Asia/Kathmandu'                   => true,
        'Asia/Katmandu'                    => true,
        'Asia/Kolkata'                     => true,
        'Asia/Krasnoyarsk'                 => true,
        'Asia/Kuala_Lumpur'                => true,
        'Asia/Kuching'                     => true,
        'Asia/Kuwait'                      => true,
        'Asia/Macao'                       => true,
        'Asia/Macau'                       => true,
        'Asia/Magadan'                     => true,
        'Asia/Makassar'                    => true,
        'Asia/Manila'                      => true,
        'Asia/Muscat'                      => true,
        'Asia/Nicosia'                     => true,
        'Asia/Novosibirsk'                 => true,
        'Asia/Omsk'                        => true,
        'Asia/Oral'                        => true,
        'Asia/Phnom_Penh'                  => true,
        'Asia/Pontianak'                   => true,
        'Asia/Pyongyang'                   => true,
        'Asia/Qatar'                       => true,
        'Asia/Qyzylorda'                   => true,
        'Asia/Rangoon'                     => true,
        'Asia/Riyadh'                      => true,
        'Asia/Saigon'                      => true,
        'Asia/Sakhalin'                    => true,
        'Asia/Samarkand'                   => true,
        'Asia/Seoul'                       => true,
        'Asia/Shanghai'                    => true,
        'Asia/Singapore'                   => true,
        'Asia/Taipei'                      => true,
        'Asia/Tashkent'                    => true,
        'Asia/Tbilisi'                     => true,
        'Asia/Tehran'                      => true,
        'Asia/Tel_Aviv'                    => true,
        'Asia/Thimbu'                      => true,
        'Asia/Thimphu'                     => true,
        'Asia/Tokyo'                       => true,
        'Asia/Ujung_Pandang'               => true,
        'Asia/Ulaanbaatar'                 => true,
        'Asia/Ulan_Bator'                  => true,
        'Asia/Urumqi'                      => true,
        'Asia/Vientiane'                   => true,
        'Asia/Vladivostok'                 => true,
        'Asia/Yakutsk'                     => true,
        'Asia/Yekaterinburg'               => true,
        'Asia/Yerevan'                     => true,
        'Atlantic/Azores'                  => true,
        'Atlantic/Bermuda'                 => true,
        'Atlantic/Canary'                  => true,
        'Atlantic/Cape_Verde'              => true,
        'Atlantic/Faeroe'                  => true,
        'Atlantic/Faroe'                   => true,
        'Atlantic/Jan_Mayen'               => true,
        'Atlantic/Madeira'                 => true,
        'Atlantic/Reykjavik'               => true,
        'Atlantic/South_Georgia'           => true,
        'Atlantic/St_Helena'               => true,
        'Atlantic/Stanley'                 => true,
        'Australia/ACT'                    => true,
        'Australia/Adelaide'               => true,
        'Australia/Brisbane'               => true,
        'Australia/Broken_Hill'            => true,
        'Australia/Canberra'               => true,
        'Australia/Currie'                 => true,
        'Australia/Darwin'                 => true,
        'Australia/Eucla'                  => true,
        'Australia/Hobart'                 => true,
        'Australia/LHI'                    => true,
        'Australia/Lindeman'               => true,
        'Australia/Lord_Howe'              => true,
        'Australia/Melbourne'              => true,
        'Australia/North'                  => true,
        'Australia/NSW'                    => true,
        'Australia/Perth'                  => true,
        'Australia/Queensland'             => true,
        'Australia/South'                  => true,
        'Australia/Sydney'                 => true,
        'Australia/Tasmania'               => true,
        'Australia/Victoria'               => true,
        'Australia/West'                   => true,
        'Australia/Yancowinna'             => true,
        'Europe/Amsterdam'                 => true,
        'Europe/Andorra'                   => true,
        'Europe/Athens'                    => true,
        'Europe/Belfast'                   => true,
        'Europe/Belgrade'                  => true,
        'Europe/Berlin'                    => true,
        'Europe/Bratislava'                => true,
        'Europe/Brussels'                  => true,
        'Europe/Bucharest'                 => true,
        'Europe/Budapest'                  => true,
        'Europe/Chisinau'                  => true,
        'Europe/Copenhagen'                => true,
        'Europe/Dublin'                    => true,
        'Europe/Gibraltar'                 => true,
        'Europe/Guernsey'                  => true,
        'Europe/Helsinki'                  => true,
        'Europe/Isle_of_Man'               => true,
        'Europe/Istanbul'                  => true,
        'Europe/Jersey'                    => true,
        'Europe/Kaliningrad'               => true,
        'Europe/Kiev'                      => true,
        'Europe/Lisbon'                    => true,
        'Europe/Ljubljana'                 => true,
        'Europe/London'                    => true,
        'Europe/Luxembourg'                => true,
        'Europe/Madrid'                    => true,
        'Europe/Malta'                     => true,
        'Europe/Mariehamn'                 => true,
        'Europe/Minsk'                     => true,
        'Europe/Monaco'                    => true,
        'Europe/Moscow'                    => true,
        'Europe/Nicosia'                   => true,
        'Europe/Oslo'                      => true,
        'Europe/Paris'                     => true,
        'Europe/Podgorica'                 => true,
        'Europe/Prague'                    => true,
        'Europe/Riga'                      => true,
        'Europe/Rome'                      => true,
        'Europe/Samara'                    => true,
        'Europe/San_Marino'                => true,
        'Europe/Sarajevo'                  => true,
        'Europe/Simferopol'                => true,
        'Europe/Skopje'                    => true,
        'Europe/Sofia'                     => true,
        'Europe/Stockholm'                 => true,
        'Europe/Tallinn'                   => true,
        'Europe/Tirane'                    => true,
        'Europe/Tiraspol'                  => true,
        'Europe/Uzhgorod'                  => true,
        'Europe/Vaduz'                     => true,
        'Europe/Vatican'                   => true,
        'Europe/Vienna'                    => true,
        'Europe/Vilnius'                   => true,
        'Europe/Volgograd'                 => true,
        'Europe/Warsaw'                    => true,
        'Europe/Zagreb'                    => true,
        'Europe/Zaporozhye'                => true,
        'Europe/Zurich'                    => true,
        'Indian/Antananarivo'              => true,
        'Indian/Chagos'                    => true,
        'Indian/Christmas'                 => true,
        'Indian/Cocos'                     => true,
        'Indian/Comoro'                    => true,
        'Indian/Kerguelen'                 => true,
        'Indian/Mahe'                      => true,
        'Indian/Maldives'                  => true,
        'Indian/Mauritius'                 => true,
        'Indian/Mayotte'                   => true,
        'Indian/Reunion'                   => true,
        'Pacific/Apia'                     => true,
        'Pacific/Auckland'                 => true,
        'Pacific/Chatham'                  => true,
        'Pacific/Easter'                   => true,
        'Pacific/Efate'                    => true,
        'Pacific/Enderbury'                => true,
        'Pacific/Fakaofo'                  => true,
        'Pacific/Fiji'                     => true,
        'Pacific/Funafuti'                 => true,
        'Pacific/Galapagos'                => true,
        'Pacific/Gambier'                  => true,
        'Pacific/Guadalcanal'              => true,
        'Pacific/Guam'                     => true,
        'Pacific/Honolulu'                 => true,
        'Pacific/Johnston'                 => true,
        'Pacific/Kiritimati'               => true,
        'Pacific/Kosrae'                   => true,
        'Pacific/Kwajalein'                => true,
        'Pacific/Majuro'                   => true,
        'Pacific/Marquesas'                => true,
        'Pacific/Midway'                   => true,
        'Pacific/Nauru'                    => true,
        'Pacific/Niue'                     => true,
        'Pacific/Norfolk'                  => true,
        'Pacific/Noumea'                   => true,
        'Pacific/Pago_Pago'                => true,
        'Pacific/Palau'                    => true,
        'Pacific/Pitcairn'                 => true,
        'Pacific/Ponape'                   => true,
        'Pacific/Port_Moresby'             => true,
        'Pacific/Rarotonga'                => true,
        'Pacific/Saipan'                   => true,
        'Pacific/Samoa'                    => true,
        'Pacific/Tahiti'                   => true,
        'Pacific/Tarawa'                   => true,
        'Pacific/Tongatapu'                => true,
        'Pacific/Truk'                     => true,
        'Pacific/Wake'                     => true,
        'Pacific/Wallis'                   => true,
        'Pacific/Yap'                      => true
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
     * @return  Woops_Time_Utils    The unique instance of the class
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
     * Returns an age.
     * 
     * This method returns an age, calculated from a timestamp. By default,
     * the method takes the current time as reference, but another timestamp
     * can be specified. The method also returns by default the age in days,
     * but it can also returns it in seconds, minutes or hours.
     * 
     * @param   int     The base timestamp
     * @param   int     The time from which to calculate the age (timestamp). Will use current time if none supplied
     * @param   string  The type of age to return (s = seconds, i = minutes, h = hours, or d = days). Default is days.
     * @return  int     An age, as a numeric value
     */
    public function calcAge( $tstamp, $curTime = false, $ageType = false )
    {
        // Process age types
        switch( $ageType ) {
            
            // Seconds
            case 's':
                $division = 1;
                break;
            
            // Minutes
            case 'i':
                $division = 60;
                break;
            
            // Hours
            case 'h':
                $division = 3600;
                break;
            
            // Default - Days
            default:
                $division = 86400;
                break;
        }
        
        // Gets the current time, if none specified
        if( !$currentTime ) {
            
            $currentTime = time();
        }
        
        // Gets the differences between the two timestamps
        $diff = $currentTime - $tstamp;
        
        // Returns the age
        return ceil( $diff / $division );
    }
    
    /**
     * Converts a week number to a timestamp.
     * 
     * This method returns a timestamp for a given year (XXXX), week number, and
     * day number (0 is sunday, 6 is saturday).
     * 
     * Thanx to Nicolas Miroz for the informations about date computing.
     * 
     * @param   int The day number
     * @param   int The week number
     * @param   int The year
     * @return  int A timestamp
     */
    public function weekToDate( $day, $week, $year )
    {
        // First january of the year
        $firstDay = mktime( 0, 0, 0, 1, 1, $year );
        
        // Gets the day for the first january
        $firstDayNum = date( 'w', $firstDay );
        
        // Computes the first monday of the year and the week number for that day
        switch( $firstDayNum ) {
        
            // Sunday
            case 0:
                
                // Monday is 02.01 | Week is 1
                $monday = mktime( 0, 0, 0, 01, 02, $year );
                $weekNum = 1;
                break;
            
            // Monday
            case 1:
                
                // Monday is 01.01 | Week is 1
                $monday = mktime( 0, 0, 0, 01, 01, $year );
                $weekNum = 1;
                break;
            
            // Tuesday
            case 2:
                
                // Monday is 07.01 | Week is 2
                $monday = mktime( 0, 0, 0, 01, 07, $year );
                $weekNum = 2;
                break;
            
            // Wednesday
            case 3:
                
                // Monday is 06.01 | Week is 2
                $monday = mktime( 0, 0, 0, 01, 06, $year );
                $weekNum = 2;
                break;
            
            // Thursday
            case 4:
                
                // Monday is 05.01 | Week is 2
                $monday = mktime( 0, 0, 0, 01, 05, $year );
                $weekNum = 2;
                break;
            
            // Friday
            case 5:
                
                // Monday is 04.01 | Week is 1
                $monday = mktime( 0, 0, 0, 01, 04, $year );
                $weekNum = 1;
                break;
            
            // Saturday
            case 6:
                
                // Monday is 03.01 | Week is 1
                $monday = mktime( 0, 0, 0, 01, 03, $year );
                $weekNum = 1;
                break;
        }
        
        // Computes the difference in days from the monday to the requested day
        $dayDiff = ( $day == 0 ) ? 6 : ( $day - 1 );
        
        // Number of day to the requested date
        $numDay = ( ( $week - ( $weekNum - 1 ) - 1 ) * 7 ) + $dayDiff + date( 'd', $monday );
        
        // Creates and returns the timestamp for the requested date
        return mktime( 0, 0, 0, 01, $numDay, $year );
    }
    
    /**
     * Gets the available timezones
     * 
     * @return  array   An array with the available timezones, as keys
     */
    public function getTimezones()
    {
        return $this->_timezones;
    }
    
    /**
     * Checks if a timezone is valid
     * 
     * @param   string  The timezone to check
     * @return  boolean True if the timezone is valid, otherwise false
     */
    public function isValidTimezone( $timezone )
    {
        return isset( $this->_timezones[ $timezone ] );
    }
}
