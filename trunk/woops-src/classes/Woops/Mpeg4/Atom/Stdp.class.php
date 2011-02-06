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
namespace Woops\Mpeg4\Atom;

/**
 * MPEG-4 STDP atom
 * 
 * SDL from ISO-14496-12:
 * 
 * <code>
 * aligned( 8 ) class DegradationPriorityBox extends FullBox( 'stdp', version = 0, 0 )
 * {
 *      int i;
 *      
 *      for( i=0; i < sample_count; i++ ) {
 *      
 *          unsigned int( 16 ) priority;
 *      }
 * }
 * </code>
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mpeg4.Atom
 */
final class Stdp extends \Woops\Mpeg4\FullBox
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The atom type
     */
    protected $_type = 'stdp';
    
    /**
     * Process the atom flags
     * 
     * @params  string  The atom raw flags
     * @return  object  The processed atom flags
     */
    protected function _processFlags( $rawFlags )
    {
        // Returns the atom flags
        return new \stdClass();
    }
    
    /**
     * Process the atom data
     * 
     * @return  object  The processed atom data
     */
    public function getProcessedData()
    {
        // Resets the stream pointer
        $this->_stream->rewind();
        
        // Gets the processed data from the parent (fullbox)
        $data = parent::getProcessedData();
            
        // Storage for the entries
        $data->priorities = array();
        
        // Checks for the STSZ atom
        if( !isset( $this->_parent->stsz ) )
        {
            // Return the processed data
            return $data;
        }
        
        // Gets data from STSZ
        $stsz = $this->_parent->stsz->getProcessedData();
        
        // Process each priority
        for( $i = 0; $i < $stsz->sample_count; $i++ )
        {
            // Stores the current priority
            $data->priorities[] = $this->_stream->bigEndianUnsignedShort();
        }
        
        // Return the processed data
        return $data;
    }
}
