<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# Copyright (C) 2009 Jean-David Gadina - www.xs-labs.com                       #
# All rights reserved                                                          #
################################################################################

# $Id: Parser.class.php 588 2009-03-07 11:52:36Z macmade $

/**
 * FLV file
 * 
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Flv
 */
class Woops_Flv_File extends Woops_Core_Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The FLV tag types
     */
    const TAG_AUDIO  = 0x08;
    const TAG_VIDEO  = 0x09;
    const TAG_SCRIPT = 0x12;
    
    /**
     * The FLV tag types with their corresponding PHP classname
     */
    protected static $_types = array(
        0x08 => 'Woops_Flv_Tag_Audio_Data',
        0x09 => 'Woops_Flv_Tag_Video_Data',
        0x12 => 'Woops_Flv_Tag_Script_Data'
    );
    
    /**
     * The FLV header
     */
    protected $_header       = NULL;
    
    /**
     * The FLV tags
     */
    protected $_tags         = array();
    
    /**
     * Class constructor
     * 
     * @return  void
     */
    public function __construct()
    {
        $this->_header = new Woops_Flv_Header();
    }
    
    /**
     * Gets the FLV header
     * 
     * @return  Woops_Flv_Header    The FLV header
     */
    public function getHeader()
    {
        return $this->_header;
    }
    
    /**
     * Creates a new tag
     * 
     * @param   int                         The tag type (one of the TAG_XXX constant)
     * @return  Woops_Flv_Tag               The tag object
     * @throws  Woops_Flv_File_Exception    If the tag type is invalid
     * @throws  Woops_Flv_File_Exception    If the tag type is audio, and if there is already an audio tag
     * @throws  Woops_Flv_File_Exception    If the tag type is video, and if there is already a video tag
     */
    public function newTag( $type )
    {
        // Ensures we have an integer
        $type = ( int )$type;
        
        // Checks for a valid tag type
        if( !isset( self::$_types[ $type ] ) ) {
            
            // Error - Invalid tag type
            throw new Woops_Flv_File_Exception(
                'Invalid FLV tag type (' . $type . ')',
                Woops_Flv_File_Exception::EXCEPTION_INVALID_TAG_TYPE
            );
        }
        
        // Gets the tag classname
        $tagClass      = self::$_types[ $type ];
        
        // Creates the tag
        $tag           = new $tagClass();
        
        // Stores the tag object
        $this->_tags[] = $tag;
        
        // Returns the tag object
        return $tag;
    }
}
