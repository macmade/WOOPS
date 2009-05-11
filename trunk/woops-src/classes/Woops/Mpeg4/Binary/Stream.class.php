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
namespace Woops\Mpeg4\Binary;

/**
 * MPEG-4 binary stream
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mpeg4.Binary
 */
class Stream extends \Woops\Binary\Stream
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * Decodes a matrix field
     * 
     * A matrix field, used for instance in mvhd or tkhd, is 288 bits (9 * 32 bits).
     * All values are expressed as 16.16 big endian fixed point, except for u,
     * v and w which are 2.30 big endian fixed point.
     * 
     * SDL from ISO-14496-12:
     * 
     * template int( 32 )[ 9 ] matrix = { 0x00010000, 0, 0, 0, 0x00010000, 0, 0, 0, 0x40000000 };
     * 
     * @return  stdClass    The matrix object
     */
    public function matrix()
    {
        // Storage for the matrix
        $matrix    = new \stdClass();
        
        // Process the matrix field from the atom data
        $matrix->a = $this->bigEndianFixedPoint( 16, 16 );
        $matrix->b = $this->bigEndianFixedPoint( 16, 16 );
        $matrix->u = $this->bigEndianFixedPoint(  2, 30 );
        $matrix->c = $this->bigEndianFixedPoint( 16, 16 );
        $matrix->d = $this->bigEndianFixedPoint( 16, 16 );
        $matrix->v = $this->bigEndianFixedPoint(  2, 30 );
        $matrix->x = $this->bigEndianFixedPoint( 16, 16 );
        $matrix->y = $this->bigEndianFixedPoint( 16, 16 );
        $matrix->w = $this->bigEndianFixedPoint(  2, 30 );
        
        // Returns the matrix
        return $matrix;
    }
}
