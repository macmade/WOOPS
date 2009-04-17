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

# $Id: Parser.class.php 588 2009-03-07 11:52:36Z macmade $

/**
 * SWF rectangle record
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Swf.Record
 */
class Woops_Swf_Record_Rectangle
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The X minimum position for the rectangle, in twips
     */
    protected $_xMin = 0;
    
    /**
     * The X mmaximum position for the rectangle, in twips
     */
    protected $_xMax = 0;
    
    /**
     * The Y minimum position for the rectangle, in twips
     */
    protected $_yMin = 0;
    
    /**
     * The Y maximum position for the rectangle, in twips
     */
    protected $_yMax = 0;
    
    /**
     * Class constructor
     * 
     * @param   int     The X minimum position for the rectangle, in twips
     * @param   int     The X maximum position for the rectangle, in twips
     * @param   int     The Y minimum position for the rectangle, in twips
     * @param   int     The Y maximum position for the rectangle, in twips
     * @return  void
     */
    public function __construct( $xMin = 0, $xMax = 0, $yMin = 0, $yMax = 0 )
    {
        $this->_xMin = ( int )$xMin;
        $this->_xMax = ( int )$xMax;
        $this->_yMin = ( int )$yMin;
        $this->_yMax = ( int )$yMax;
    }
    
    /**
     * Process the raw data from a binary stream
     * 
     * @return  void
     */
    public function processData( Woops_Swf_Binary_Stream $stream )
    {
        $data   = $stream->unsignedChar();
        
        $nBits  = ( $data & 0xF8 ) >> 3;
        
        $length = ceil( ( $nBits / 8 ) * 4 );
        
        $fields = str_pad( decbin( $data & 0x07 ), 3, '0', STR_PAD_LEFT);
        
        for( $i = 0; $i < $length; $i++ ) {
            
            $fields .= str_pad( decbin( $stream->unsignedChar() ), 8, '0', STR_PAD_LEFT);
        }
        
        $this->_xMin = bindec( substr( $fields, 0,          $nBits ) );
        $this->_xMax = bindec( substr( $fields, $nBits,     $nBits ) );
        $this->_yMin = bindec( substr( $fields, $nBits * 2, $nBits ) );
        $this->_yMax = bindec( substr( $fields, $nBits * 3, $nBits ) );
    }
    
    /**
     * Gets the X minimum position for the rectangle, in twips
     * 
     * @return  int     The X minimum position for the rectangle, in twips
     */
    public function getXMin()
    {
        return $this->_xMin;
    }
    
    /**
     * Sets the X minimum position for the rectangle, in twips
     * 
     * @param   int     The X minimum position for the rectangle, in twips
     * @return  void
     */
    public function setXMin( $value )
    {
        $this->_xMin = ( int )$value;
    }
    
    /**
     * Gets the X maximum position for the rectangle, in twips
     * 
     * @return  int     The X maximum position for the rectangle, in twips
     */
    public function getXMax()
    {
        return $this->_xMax;
    }
    
    /**
     * Sets the X maximum position for the rectangle, in twips
     * 
     * @param   int     The X maximum position for the rectangle, in twips
     * @return  void
     */
    public function setXMax( $value )
    {
        $this->_xMax = ( int )$value;
    }
    
    /**
     * Gets the Y minimum position for the rectangle, in twips
     * 
     * @return  int     The Y minimum position for the rectangle, in twips
     */
    public function getYMin()
    {
        return $this->_yMin;
    }
    
    /**
     * Sets the Y minimum position for the rectangle, in twips
     * 
     * @param   int     The Y minimum position for the rectangle, in twips
     * @return  void
     */
    public function setYMin( $value )
    {
        $this->_yMin = ( int )$value;
    }
    
    /**
     * Gets the Y maximum position for the rectangle, in twips
     * 
     * @return  int     The Y maximum position for the rectangle, in twips
     */
    public function getYMax()
    {
        return $this->_yMax;
    }
    
    /**
     * Sets the Y maximum position for the rectangle, in twips
     * 
     * @param   int     The Y maximum position for the rectangle, in twips
     * @return  void
     */
    public function setYMax( $value )
    {
        $this->_yMax = ( int )$value;
    }
}
