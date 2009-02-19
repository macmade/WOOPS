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
 * Interface for the multi singleton classes
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.MultiSingleton
 */
interface Woops_Core_MultiSingleton_Interface
{
    /**
     * Gets a singleton instance
     * 
     * @param   string  The instance name
     * @return  object  The requested instance
     */
    public static function getInstance( $instanceName );
}
