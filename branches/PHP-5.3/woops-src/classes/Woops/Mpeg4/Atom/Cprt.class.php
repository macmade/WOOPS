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
 * @package     Woops.Mpeg4.Atom
 */
final class Cprt extends \Woops\Mpeg4\FullBox
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
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
        $data           = parent::getProcessedData();
        
        // Process the atom data
        $data->language = $this->_stream->bigEndianIso639Code();
        
        // Tries the get the byte order mark
        $noticeBom      = $this->_stream->bigEndianUnsignedShort();
        
        // Checks for the byte order mark
        if( ( $noticeBom & 0xFEFF ) === $noticeBom ) {
            
            // UTF-16 string
            $data->notice = substr( $this->_stream->getRemainingData(), 0, -1 );
            
        } else {
            
            // UTF-8 string
            $this->_stream->seek( -2, \Woops\Mpeg4\Binary\Stream::SEEK_CUR );
            $data->notice = substr( $this->_stream->getRemainingData(), 0, -1 );
        }
        
        // Return the processed data
        return $data;
    }
}
