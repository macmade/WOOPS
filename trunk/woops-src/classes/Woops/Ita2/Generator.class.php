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
namespace Woops\Ita2;

/**
 * ITA-2 (International Telegraph Alphabet) generator class
 * 
 * Around 1930, the CCITT introduced the International Telegraphy Alphabet No. 2
 * (ITA2) code as an international standard, which was based on the Western
 * Union code with some minor changes. The US standardized on a version of ITA2
 * called the American Teletypewriter code (USTTY) which was the basis for 5-bit
 * teletype codes until the debut of 7-bit ASCII in 1963.
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Ita2
 */
class Generator extends \Woops\Core\Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    /**
     * 
     */
    protected static $_chars = array(
        
        // Control characters
        0   => array( 0x00, 0x00 ), // NULL
        10  => array( 0x02, 0x00 ), // LF
        13  => array( 0x08, 0x00 ), // CR
        32  => array( 0x04, 0x00 ), // Space
        
        // Letters
        97  => array( 0x03, 0x1F ), // a
        98  => array( 0x19, 0x1F ), // b
        99  => array( 0x0E, 0x1F ), // c
        100 => array( 0x09, 0x1F ), // d
        101 => array( 0x01, 0x1F ), // e
        102 => array( 0x0D, 0x1F ), // f
        103 => array( 0x1A, 0x1F ), // g
        104 => array( 0x14, 0x1F ), // h
        105 => array( 0x06, 0x1F ), // i
        106 => array( 0x0B, 0x1F ), // j
        107 => array( 0x0F, 0x1F ), // k
        108 => array( 0x12, 0x1F ), // l
        109 => array( 0x1C, 0x1F ), // m
        110 => array( 0x0C, 0x1F ), // n
        111 => array( 0x18, 0x1F ), // o
        112 => array( 0x16, 0x1F ), // p
        113 => array( 0x17, 0x1F ), // q
        114 => array( 0x0A, 0x1F ), // r
        115 => array( 0x05, 0x1F ), // s
        116 => array( 0x10, 0x1F ), // t
        117 => array( 0x07, 0x1F ), // u
        118 => array( 0x1E, 0x1F ), // v
        119 => array( 0x13, 0x1F ), // w
        120 => array( 0x1D, 0x1F ), // x
        121 => array( 0x15, 0x1F ), // y
        122 => array( 0x11, 0x1F ), // z
        
        // Figures
        5   => array( 0x09, 0x1B ), // ENQ
        7   => array( 0x0B, 0x1B ), // BELL
        33  => array( 0x0D, 0x1B ), // !
        38  => array( 0x1A, 0x1B ), // &
        39  => array( 0x05, 0x1B ), // '
        40  => array( 0x0F, 0x1B ), // (
        41  => array( 0x12, 0x1B ), // )
        43  => array( 0x11, 0x1B ), // +
        44  => array( 0x0C, 0x1B ), // ,
        45  => array( 0x03, 0x1B ), // -
        46  => array( 0x1C, 0x1B ), // .
        47  => array( 0x1D, 0x1B ), // /
        48  => array( 0x16, 0x1B ), // 0
        49  => array( 0x17, 0x1B ), // 1
        50  => array( 0x13, 0x1B ), // 2
        51  => array( 0x01, 0x1B ), // 3
        52  => array( 0x0A, 0x1B ), // 4
        53  => array( 0x10, 0x1B ), // 5
        54  => array( 0x15, 0x1B ), // 6
        55  => array( 0x07, 0x1B ), // 7
        56  => array( 0x06, 0x1B ), // 8
        57  => array( 0x18, 0x1B ), // 9
        58  => array( 0x0E, 0x1B ), // :
        59  => array( 0x1E, 0x1B ), // ;
        63  => array( 0x19, 0x1B ), // ?
        163 => array( 0x14, 0x1B )  // £
    );
    
    /**
     * 
     */
    protected $_charValues   = array();
    
    /**
     * 
     */
    protected $_str          = '';
    
    /**
     * 
     */
    protected $_dotSize      = 8;
    
    /**
     * 
     */
    protected $_smallDotSize = 4;
    
    /**
     * 
     */
    protected $_dotMargin    = 4;
    
    /**
     * 
     */
    protected $_background   = 0xFFFFFF;
    
    /**
     * 
     */
    protected $_foreground   = 0x333333;
    
    /**
     * 
     */
    protected $_border       = 0x333333;
    
    /**
     * 
     */
    public function __construct( $str )
    {
        if( !function_exists( 'imagecreatetruecolor' ) ) {
            
            throw new Generator\Exception(
                'GD is not available',
                Generator\Exception::EXCEPTION_NO_GD
            );
        }
        
        $str    = strtolower( $str );
        $strLen = strlen( $str );
        $mode   = 0x00;
        
        for( $i = 0; $i < $strLen; $i++ ) {
            
            $char = ord( $str[ $i ] );
            
            if( !isset( self::$_chars[ $char ] ) ) {
                
                continue;
            }
            
            if( self::$_chars[ $char ][ 1 ] !== 0x00 && $mode !== self::$_chars[ $char ][ 1 ] ) {
                
                $this->_charValues[] = self::$_chars[ $char ][ 1 ];
                $mode                = self::$_chars[ $char ][ 1 ];
            }
            
            $this->_charValues[] = self::$_chars[ $char ][ 0 ];
            $this->_str          = $str[ $i ];
        }
    }
    
    /**
     * 
     */
    public function setDotSize( $size )
    {
        $this->_dotSize = ( int )$size;
    }
    
    /**
     * 
     */
    public function setSmallDotSize( $size )
    {
        $this->_smallDotSize = ( int )$size;
    }
    
    /**
     * 
     */
    public function setDotMargin( $size )
    {
        $this->_dotMargin = ( int )$size;
    }
    
    /**
     * 
     */
    public function setBackgroundColor( $color )
    {
        $this->_background = ( int )$color;
    }
    
    /**
     * 
     */
    public function setForegroundColor( $color )
    {
        $this->_foreground = ( int )$color;
    }
    
    /**
     * 
     */
    public function setBorderColor( $color )
    {
        $this->_border = ( int )$color;
    }
    
    /**
     * 
     */
    public function getImage()
    {
        $width  = count( $this->_charValues ) * ( $this->_dotSize + $this->_dotMargin ) + ( $this->_dotMargin );
        $height = ( ( $this->_dotSize + $this->_dotMargin ) * 6 ) + $this->_dotSize;
        $im     = imagecreatetruecolor( $width, $height ); 
        
        imageantialias( $im, true );
        
        $bg     = imagecolorallocate( $im, ( $this->_background & 0xFF0000 ) >> 16, ( $this->_background & 0x00FF00 ) >> 8, $this->_background & 0x0000FF); 
        $fg     = imagecolorallocate( $im, ( $this->_foreground & 0xFF0000 ) >> 16, ( $this->_foreground & 0x00FF00 ) >> 8, $this->_foreground & 0x0000FF); 
        $border = imagecolorallocate( $im, ( $this->_border     & 0xFF0000 ) >> 16, ( $this->_border     & 0x00FF00 ) >> 8, $this->_border     & 0x0000FF); 
        
        imagefilltoborder( $im, 0, 0, $bg, $bg );
        imagerectangle( $im, 0, 0, $width - 1, $height - 1, $border );
        
        foreach( $this->_charValues as $key => $value ) {
            
            imagefilledellipse(
                $im,
                ( ( $this->_dotSize + $this->_dotMargin )  * $key ) + $this->_dotSize,
                ( ( $this->_dotSize + $this->_dotMargin )  * 2 ) + $this->_dotSize,
                $this->_smallDotSize,
                $this->_smallDotSize,
                $fg
            );
            
            if( 0x01 & $value ) {
                
                imagefilledellipse(
                    $im,
                    ( ( $this->_dotSize + $this->_dotMargin )  * $key ) + $this->_dotSize,
                    $this->_dotSize,
                    $this->_dotSize,
                    $this->_dotSize,
                    $fg
                );
            }
            
            if( 0x02 & $value ) {
                
                imagefilledellipse(
                    $im,
                    ( ( $this->_dotSize + $this->_dotMargin )  * $key ) + $this->_dotSize,
                    ( ( $this->_dotSize + $this->_dotMargin )  * 1 ) + $this->_dotSize,
                    $this->_dotSize,
                    $this->_dotSize,
                    $fg
                );
            }
            
            if( 0x04 & $value ) {
                
                imagefilledellipse(
                    $im,
                    ( ( $this->_dotSize + $this->_dotMargin )  * $key ) + $this->_dotSize,
                    ( ( $this->_dotSize + $this->_dotMargin )  * 3 ) + $this->_dotSize,
                    $this->_dotSize,
                    $this->_dotSize,
                    $fg
                );
            }
            
            if( 0x08 & $value ) {
                
                imagefilledellipse(
                    $im,
                    ( ( $this->_dotSize + $this->_dotMargin )  * $key ) + $this->_dotSize,
                    ( ( $this->_dotSize + $this->_dotMargin )  * 4 ) + $this->_dotSize,
                    $this->_dotSize,
                    $this->_dotSize,
                    $fg
                );
            }
            
            if( 0x10 & $value ) {
                
                imagefilledellipse(
                    $im,
                    ( ( $this->_dotSize + $this->_dotMargin )  * $key ) + $this->_dotSize,
                    ( ( $this->_dotSize + $this->_dotMargin )  * 5 ) + $this->_dotSize,
                    $this->_dotSize,
                    $this->_dotSize,
                    $fg
                );
            }
        }
        
        return $im;
    }
}
