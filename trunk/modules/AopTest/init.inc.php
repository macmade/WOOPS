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

Woops_Core_Aop_Advisor::addAdvice(
    Woops_Core_Aop_Advisor::ADVICE_TYPE_BEFORE_CALL,
    array( 'Woops_Mod_AopTest_Interceptor', 'intercept' ),
    'Woops_Mod_HelloWorld_SayHello',
    'getBlockContent'
);
