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

# $Id$

/**
 * Class that contains WOOPS information constants
 * 
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Core
 */
abstract class Woops_Core_Informations
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE       = '5.2.0';
    
    /**
     * The WOOPS version
     */
    const WOOPS_VERSION        = '0.0.0';
    
    /**
     * The WOOPS version suffix (like rc, beta, aplha, dev, etc)
     */
    const WOOPS_VERSION_SUFFIX = 'dev';
}
