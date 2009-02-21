<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# (c) 2009 eosgarden - Jean-David Gadina (macmade@eosgarden.com)               #
# All rights reserved                                                          #
################################################################################

# $Id$

/**
 * MPEG-4 CPRT atom
 * 
 * SDL from ISO-14496-12:
 * 
 * <code>
 * aligned( 8 ) class CopyrightBox extends FullBox( 'cprt', version = 0, 0 )
 * {
 *      const bit( 1 ) pad = 0;
 *      unsigned int( 5 )[ 3 ] language;
 *      string notice;
 * }
 * </code>
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.File.Mpeg4.Atom
 */
final class Woops_File_Mpeg4_Atom_Cprt extends Woops_File_Mpeg4_FullBox
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The atom type
     */
    protected $_type = 'cprt';
    
    /**
     * Process the atom flags
     * 
     * @params  string  The atom raw flags
     * @return  object  The processed atom flags
     */
    protected function _processFlags( $rawFlags )
    {
        // Returns the atom flags
        return new stdClass();
    }
    
    /**
     * Process the atom data
     * 
     * @return  object  The processed atom data
     */
    public function getProcessedData()
    {
        // Gets the processed data from the parent (fullbox)
        $data           = parent::getProcessedData();
        
        // Process the atom data
        $data->language = self::$_binUtils->bigEndianIso639Code( $this->_data, 4 );
        
        // Tries the get the byte order mark
        $noticeBom      = self::$_binUtils->bigEndianUnsignedShort( $this->_data, 6 );
        
        // Checks for the byte order mark
        if( ( $noticeBom & 0xFEFF ) === $noticeBom ) {
            
            // UTF-16 string
            $data->notice = substr( $this->_data, 8, -1 );
            
        } else {
            
            // UTF-8 string
            $data->notice = substr( $this->_data, 6, -1 );
        }
        
        // Return the processed data
        return $data;
    }
}