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
 * MPEG-4 FTYP atom
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mpeg4.Atom
 */
final class Woops_Mpeg4_Atom_Ftyp extends Woops_Mpeg4_DataAtom
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The atom type
     */
    protected $_type = 'ftyp';
    
    public function getProcessedData()
    {
        $data                    = new stdClass();
        $data->major_brand       = substr( $this->_data, 0, 4 );
        $data->minor_version     = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 4 );
        $data->compatible_brands = array();
        
        if( $this->_dataLength > 8 ) {
            
            for( $i = 8; $i < $this->_dataLength; $i += 4 ) {
                
                $data->compatible_brands[] = substr( $this->_data, $i, 4 );
            }
        }
        
        return $data;
    }
}
