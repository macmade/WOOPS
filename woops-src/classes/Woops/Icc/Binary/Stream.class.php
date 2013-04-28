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

# $Id: Stream.class.php 637 2009-03-09 09:05:52Z macmade $

/**
 * ICC binary stream
 * 
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Icc.Binary
 */
class Woops_Icc_Binary_Stream extends Woops_Binary_Stream
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * 
     */
    public function dateTime( $asTs = true )
    {
        $year    = $this->bigEndianUnsignedShort();
        $month   = $this->bigEndianUnsignedShort();
        $day     = $this->bigEndianUnsignedShort();
        $hours   = $this->bigEndianUnsignedShort();
        $minutes = $this->bigEndianUnsignedShort();
        $seconds = $this->bigEndianUnsignedShort();
        
        $ts      = mktime(  $hours, $minutes, $seconds, $month, $day, $year );
        
        return ( $asTs ) ? $ts : date( 'r', $ts ) ;
    }
    
    /**
     * 
     */
    public function s15Fixed16Number()
    {
        return $this->bigEndianFixedPoint( 16, 16 );
    }
    
    /**
     * 
     */
    public function xyzNumber( $size )
    {
        return array(
            'x' => $this->s15Fixed16Number(),
            'y' => $this->s15Fixed16Number(),
            'z' => $this->s15Fixed16Number()
        );
    }
}
