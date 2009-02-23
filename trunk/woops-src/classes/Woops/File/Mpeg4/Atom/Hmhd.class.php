<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# (c) 2009 Jean-David Gadina (macmade@eosgarden.com)                           #
# All rights reserved                                                          #
################################################################################

# $Id$

/**
 * MPEG-4 HMHD atom
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.File.Mpeg4.Atom
 */
final class Woops_File_Mpeg4_Atom_Hmhd extends Woops_File_Mpeg4_FullBox
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The atom type
     */
    protected $_type = 'hmhd';
    
    protected function _processFlags( $flags )
    {
        return new stdClass();
    }
    
    public function getProcessedData()
    {
        $data             = parent::getProcessedData();
        $data->maxPDUsize = self::$_binUtils->bigEndianUnsignedShort( $this->_data, 4 );
        $data->avgPDUsize = self::$_binUtils->bigEndianUnsignedShort( $this->_data, 6 );
        $data->maxbitrate = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 8 );
        $data->avgbitrate = self::$_binUtils->bigEndianUnsignedLong( $this->_data, 12 );
        
        return $data;
    }
}
