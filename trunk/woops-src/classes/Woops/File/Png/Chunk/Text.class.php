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
 * PNG tEXt chunk (textual data)
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.File.Png.Chunk
 */
class Woops_File_Png_Chunk_Text extends Woops_File_Png_Chunk
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The chunk type
     */
    protected $_type = 'tEXt';
    
    /**
     * Process the chunk data
     * 
     * This method will process the chunk raw data and returns human readable
     * values, stored as properties of an stdClass object. Please take a look
     * at the PNG specification for this specific chunk to see which data will
     * be extracted.
     * 
     * @return  stdClass    The human readable chunk data
     */
    public function getProcessedData()
    {
        // Storage
        $data             = new stdClass();
        
        // Position of the null separator
        $null             = strpos( $this->_data, chr( 0 ) );
        
        // Gets the keyword
        $data->keyword    = substr( $this->_data, 0, $null );
        
        // Gets the text string
        $data->textString = substr( $this->_data, $null + 1 );
        
        // Returns the processed data
        return $data;
    }
}
