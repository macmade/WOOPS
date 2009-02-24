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
 * MPEG-4 ILOC atom
 * 
 * SDL from ISO-14496-12:
 * 
 * <code>
 * aligned( 8 ) class ItemLocationBox extends FullBox( 'iloc', version = 0, 0 )
 * {
 *      unsigned int( 4 ) offset_size;
 *      unsigned int( 4 ) length_size;
 *      unsigned int( 4 ) base_offset_size;
 *      unsigned int( 4 ) reserved;
 *      unsigned int( 16 ) item_count;
 *      
 *      for( i = 0; i < item_count; i++ ) {
 *          
 *          unsigned int( 16 ) item_ID;
 *          unsigned int( 16 ) data_reference_index;
 *          unsigned int( base_offset_size * 8 ) base_offset;
 *          unsigned int( 16 )  extent_count;
 *          
 *          for ( j = 0; j < extent_count; j++ ) {
 *              
 *              unsigned int( offset_size * 8 ) extent_offset;
 *              unsigned int( length_size *8 ) extent_length;
 *          }
 *      }
 * }
 * </code>
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.File.Mpeg4.Atom
 */
final class Woops_File_Mpeg4_Atom_Iloc extends Woops_File_Mpeg4_FullBox
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The atom type
     */
    protected $_type = 'iloc';
    
    /**
     * Process the atom flags
     * 
     * @params  string  The atom raw flags
     * @return  object  The processed atom flags
     */
    protected function _processFlags( $rawFlags )
    {
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
        $data                   = parent::getProcessedData();
        
        // Offset related data
        $offset                 = self::$_binUtils->bigEndianUnsignedShort( $this->_data, 4 );
        
        // Process the atom data
        $data->offset_size      = $offset & 0xF000; // Mask is 1111 0000 0000 0000
        $data->length_size      = $offset & 0x0F00; // Mask is 0000 1111 0000 0000
        $data->base_offset_size = $offset & 0x00F0; // Mask is 0000 0000 1111 0000
        $data->item_count       = self::$_binUtils->bigEndianUnsignedShort( $this->_data, 6 );
        
        // Storage for items
        $data->items            = array();
        
        // Data offset for the items
        $itemOffset             = 8;
        
        // Process each item
        for( $i = 0; $i < $data->item_count; $i++ ) {
            
            // Storage for the current item
            $item = new stdClass();
            
            // Process the current item data
            $item->item_ID              = self::$_binUtils->bigEndianUnsignedShort( $this->_data, $itemOffset );
            $item->data_reference_index = self::$_binUtils->bigEndianUnsignedShort( $this->_data, $itemOffset + 2 );
            
            // Stores the current item
            $data->items[]              = $item;
        }
        
        // Returns the processed data
        return $data;
    }
}
