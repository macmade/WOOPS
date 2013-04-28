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

Woops_Core_Aop_Advisor::addAdvice(
    Woops_Core_Aop_Advisor::ADVICE_TYPE_BEFORE_CALL,
    array( 'Woops_Mod_AopTest_Interceptor', 'interceptBefore' ),
    'Woops_Mod_HelloWorld_SayHello',
    'getBlockContent'
);

Woops_Core_Aop_Advisor::addAdvice(
    Woops_Core_Aop_Advisor::ADVICE_TYPE_AFTER_CALL,
    array( 'Woops_Mod_AopTest_Interceptor', 'interceptAfter' ),
    'Woops_Mod_HelloWorld_SayHello',
    'getBlockContent'
);
