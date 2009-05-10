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

// File encoding
declare( ENCODING = 'UTF-8' );

// Internal namespace
namespace Woops\Vcard;

/**
 * vCard creation class
 * 
 * This class is used to create a single vCard, as specified in RFC 2426.
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Vcard
 */
class Generator extends \Woops\Core\Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    /**
     * Whether the static variables are set or not
     */
    private static $_hasStatic = false;
    
    /**
     * The string utilities
     */
    protected static $_str     = NULL;
    
    /**
     * The vCard version
     */
    protected $_version        = '3.0';
    
    /**
     * The vCard character set - If not set, default is ASCII
     */
    protected $_defaultCharset = false;
    
    /**
     * The vCard language - If not set, default is en-US
     */
    protected $_language       = false;
    
    /**
     * The vCard default type for values
     */
    protected $_defaultType    = 'HOME';
    
    /**
     * The vCard properties storage array
     */
    protected $_vCard               = array(
        'ADR'         => array(),   // Delivery address
        'BDAY'        => array(),   // Birthdate
        'CATEGORIES'  => array(),   // Category information
        'CLASS'       => array(),   // Access classification
        'EMAIL'       => array(),   // Electronic mail
        'FN'          => array(),   // Formatted name 
        'GEO'         => array(),   // Geographical position
        'KEY'         => array(),   // Public key
        'LABEL'       => array(),   // Delivery label
        'LOGO'        => array(),   // Logo
        'MAILER'      => array(),   // Mailer
        'N'           => array(),   // Name
        'NICKNAME'    => array(),   // Nickname
        'NOTE'        => array(),   // Comment
        'ORG'         => array(),   // Organisation name and organisational unit
        'PHOTO'       => array(),   // Photograph
        'PRODID'      => array(),   // Creator product identifier
        'REV'         => array(),   // Last revision
        'ROLE'        => array(),   // Business category
        'SORT-STRING' => array(),   // Sorting string
        'SOUND'       => array(),   // Sound
        'TEL'         => array(),   // Telephone number
        'TITLE'       => array(),   // Title
        'TZ'          => array(),   // Time zone
        'UID'         => array(),   // Unique identifier
        'URL'         => array()    // Uniform resource locator
    );
    
    ############################################################################
    # vCard example:
    # 
    # BEGIN:VCARD
    # VERSION:3.0
    # N:test;test;;;
    # FN:test test
    # NICKNAME:test
    # ORG:test;test
    # TITLE:test
    # EMAIL;type=INTERNET;type=WORK;type=pref:test
    # EMAIL;type=INTERNET;type=HOME:test
    # TEL;type=HOME;type=pref:test
    # TEL;type=WORK:test
    # TEL;type=CELL:test
    # TEL;type=MAIN:test
    # TEL;type=HOME;type=FAX:test
    # TEL;type=WORK;type=FAX:test
    # TEL;type=PAGER:test
    # item1.ADR;type=HOME;type=pref:;;test;test;test;test;test
    # item1.X-ABADR:us
    # item2.ADR;type=WORK:;;test;test;test;test;test
    # item2.X-ABADR:us
    # item3.URL;type=pref:test
    # item3.X-ABLabel:_$!<HomePage>!$_
    # URL;type=HOME:test
    # X-AIM;type=HOME;type=pref:test
    # X-JABBER;type=WORK;type=pref:test
    # X-MSN;type=HOME;type=pref:test
    # X-YAHOO;type=HOME;type=pref:test
    # X-ICQ;type=WORK;type=pref:test
    # X-ABUID:D7B826C2-6EDA-4819-A8BE-2F486859E30B\:ABPerson
    # END:VCARD
    ############################################################################
    
    /**
     * Class constructor
     * 
     * @return  void
     */
    public function __construct()
    {
        // Checks if the static variables are set
        if( !self::$_hasStatic ) {
            
            // Sets the static variables
            self::_setStaticVars();
        }
    }
    
    /**
     * 
     */
    public function __toString()
    {
        return $this->createCard();
    }
    
    /**
     * Sets the needed static variables
     * 
     * @return  void
     */
    private static function _setStaticVars()
    {
        // Gets the instance of the string utilities
        self::$_str       = \Woops\String\Utils::getInstance();
        
        // Static variables are set
        self::$_hasStatic = true;
    }
    
    /**
     * 
     */
    protected function _escapeValue( $input )
    {
        // Checks if the value is an array
        if( is_array( $input ) ) {
            
            // Process each entry
            foreach( $input as $key => $value ) {
                
                // Escapes the vCard reserved characters (ASCII 44 & 59)
                $input[ $key ] = str_replace( ',', '\,', $value );
                $input[ $key ] = str_replace( ';', '\;', $value );
            }
            
        } else {
            
            // Escapes the vCard reserved characters (ASCII 44 & 59)
            $input = str_replace( ',', '\,', $input );
            $input = str_replace( ';', '\;', $input );
        }
        
        // Returns the escaped value
        return $input;
    }
    
    /**
     * 
     */
    public function createCard()
    {
        // Starts the vCard
        $vCard  = 'BEGIN:VCARD' . self::$_str->NL;
        $vCard .= 'VERSION:' . $this->_version . self::$_str->NL;
        
        // Process the vCard properties
        foreach( $this->_vCard as $property => $items ) {
            
            // Checks if the property contains data
            if( count( $items ) ) {
                
                // Process the property items
                foreach( $items as $item ) {
                    
                    // Adds the property name
                    $vCard .= $property;
                    
                    // Adds the property parameters
                    $vCard .= $this->_formatParams( $item[ 'params' ] ) . ':';
                    
                    // Adds the property value
                    $vCard .= $this->_formatValues( $item[ 'values' ] );
                    
                    // Adds a new line
                    $vCard .= self::$_str->NL;
                }
            }
        }
        
        // Ends the vCard
        $vCard .= 'END:VCARD';
        
        // Returns the full vCard
        return $vCard;
    }
    
    /**
     * 
     */
    protected function _formatParams( array $items = array() )
    {
        // Parameters storage
        $params = '';
        
        // Process each value
        foreach( $items as $key => $value ) {
            
            // Adds the parameter name
            $params .= ';' . $key . '=';
            
            // Adds the parameter value
            $params .= ( is_array( $value ) ) ? implode( ',', $value ) : $value;
            
        }
        
        // Returns the parameters
        return $params;
    }
    
    /**
     * 
     */
    protected function _formatValues( array $items = array() )
    {
        // Parameters storage
        $values = '';
        
        // Process each value
        foreach( $items as $key => $value ) {
            
            // Adds the value separator
            $values .= ( $key > 0 ) ? ';' : '';
            
            // Adds the value
            $values .= ( is_array( $value ) ) ? implode( ',', $value ) : $value;
            
        }
        
        // Returns the values
        return $values;
    }
    
    /**
     * 
     */
    public function addExtendedProperty( $name )
    {
        // Gets a valid name
        $name = $this->_extendedName( $name );
        
        // Checks if property already exists
        if ( !isset( $this->_vCard[ $name ] ) ) {
            
            // Adds the extended property
            $this->_vCard[ $name ] = array();
        }
    }
    
    /**
     * 
     */
    public function addExtendedValue()
    {}
    
    /**
     * 
     */
    protected function _extendedName( $name )
    {
        // Prefix the name if necessary
        $name = ( substr( $name, 0, 2 ) != 'X-' ) ? 'X-' . $name : $name;
        
        // Returns the extended name
        return $name;
    }
    
    /**
     * 
     */
    public function setDefaultType( $type )
    {
        // Sets the default type for the vCard objects
        $this->_defaultType = ( string )$type;
    }
    
    /**
     * 
     */
    public function setVersion( $version )
    {
        // Sets the vCard version
        $this->_version = ( string )$version;
    }
    
    /**
     * 
     */
    public function addAddress( $value )
    {}
    
    /**
     * 
     */
    public function addBirthday( $value )
    {}
    
    /**
     * 
     */
    public function addCategories( $value )
    {}
    
    /**
     * 
     */
    public function addClass( $value )
    {}
    
    /**
     * 
     */
    public function addEmail( $value )
    {}
    
    /**
     * 
     */
    public function addFormattedName( $name )
    {
        // Storage
        $store = array(
            'params' => array(),
            'values' => array(),
        );
        
        // Adds the value
        $store[ 'values' ][] = $this->_escapeValue( $name );
        
        // Stores the property
        $this->_vCard[ 'FN' ][] = $store;
    }
    
    /**
     * 
     */
    public function addGeo( $value )
    {}
    
    /**
     * 
     */
    public function addKey( $value )
    {}
    
    /**
     * 
     */
    public function addLabel( $value )
    {}
    
    /**
     * 
     */
    public function addLogo( $value )
    {}
    
    /**
     * 
     */
    public function addMailer( $value )
    {}
    
    /**
     * 
     */
    public function addName( $fN, $gN, $aN = '', $hP = '', $hS = '' )
    {
        // Storage
        $store = array(
            'params' => array(),
            'values' => array(),
        );
        
        // Adds the values
        $store[ 'values' ][] = $this->_escapeValue( $fN );
        $store[ 'values' ][] = $this->_escapeValue( $gN );
        $store[ 'values' ][] = $this->_escapeValue( $aN );
        $store[ 'values' ][] = $this->_escapeValue( $hP );
        $store[ 'values' ][] = $this->_escapeValue( $hS );
        
        // Stores the property
        $this->_vCard[ 'N' ][] = $store;
    }
    
    /**
     * 
     */
    public function addNickname( $nickname )
    {
        // Storage
        $store = array(
            'params' => array(),
            'values' => array(),
        );
        
        // Adds the values
        $store[ 'values' ][] = $this->_escapeValue( $nickname );
        
        // Stores the property
        $this->_vCard[ 'NICKNAME' ][] = $store;
    }
    
    /**
     * 
     */
    public function addNote( $value )
    {}
    
    /**
     * 
     */
    public function addOrganisation( $value )
    {}
    
    /**
     * 
     */
    public function addPhoto( $value )
    {}
    
    /**
     * 
     */
    public function addProdId( $value )
    {}
    
    /**
     * 
     */
    public function addRev( $value )
    {}
    
    /**
     * 
     */
    public function addRole( $value )
    {}
    
    /**
     * 
     */
    public function addSortingString( $value )
    {}
    
    /**
     * 
     */
    public function addSound( $value )
    {}
    
    /**
     * 
     */
    public function addTel( $value )
    {}
    
    /**
     * 
     */
    public function addTitle( $value )
    {}
    
    /**
     * 
     */
    public function addTimezone( $value )
    {}
    
    /**
     * 
     */
    public function addUid( $value )
    {}
    
    /**
     * 
     */
    public function addUrl( $value )
    {}
}
