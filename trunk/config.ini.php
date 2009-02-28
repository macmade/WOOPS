; WOOPS configuration file <?php exit(); ?>

; ##############################################################################
; #                                                                            #
; #               WOOPS - Web Object Oriented Programming System               #
; #                                                                            #
; #                              COPYRIGHT NOTICE                              #
; #                                                                            #
; # Copyright (C) 2009 Jean-David Gadina (macmade@eosgarden.com)               #
; # All rights reserved                                                        #
; ##############################################################################

;  $Id$

; ##############################################################################
; # Database settings                                                          #
; ##############################################################################

[database]

; The default database engine to use
; 
; WOOPS comes by default with a PDO and an ADODB engine, each one in a specific
; module.
; Other database engine may be available, depending on the modules available in
; your WOOPS installation.
; 
; @type string
; @required
engine      = pdo

; The database driver to use
; 
; Note that the database driver setting depends directly of the database engine
; you are using.
; 
; @type string
; @required
driver      = mysql

; The database host
; 
; @type string
; @required
host        = localhost

; The database port
; 
; @type     int
; @required
port        = 3306

; The database username
; 
; @type string
; @required
user        = woops

; The database user password
; 
; @type string
; @required
password    = woops

; The name of the database to use
; 
; @type string
; @required
database    = woops

; The prefix to use for all WOOPS tables
; 
; @type string
tablePrefix = WOOPS_

; ##############################################################################
; # Date and time settings                                                     #
; ##############################################################################

[time]

; The default timezone
; 
; @type string
; @required
timezone = Europe/Zurich

; ##############################################################################
; # Language settings                                                          #
; ##############################################################################

[lang]

; The default language
; 
; @type string
; @required
defaultLanguage = en

; ##############################################################################
; # XHTML settings                                                             #
; ##############################################################################

[xhtml]

; Format and indent the generated XHTML code
; 
; @type boolean
format = On

; ##############################################################################
; # XML settings                                                               #
; ##############################################################################

[xml]

; Format and indent the generated XML code
; 
; @type boolean
format = On

; ##############################################################################
; # Class cache related settings                                               #
; ##############################################################################

[classCache]

; Allows WOOPS classes to be stored in the class cache directory.
; 
; @type boolean
enable   = On

; Optimizes the PHP source code before putting classes in the cache.
; 
; @type boolean
optimize = On

; ##############################################################################
; # AOP related settings                                                       #
; ##############################################################################

[aop]

; Allows the generation of AOP classes. Only turn off on production boxes!
; 
; Note that if this settings is on, the class cache option will automatically
; be enabled.
; 
; @type boolean
enable = On

; ##############################################################################
; # Error related settings                                                     #
; ##############################################################################

[error]

; The error reporting level
; 
; Determines the way errors are reported.
; If set to "development", the error
; message will be printed with a full backtrace.
; If set to "production", only the error message will be printed.
; If set to "none", no error message will be printed.
; 
; @type     select
; @option   development
; @option   production
; @option   none
; @required
report = development

; ##############################################################################
; # Modules related settings                                                     #
; ##############################################################################

[modules]

; The loaded (active) modules
loaded[] = Pdo
loaded[] = Install
loaded[] = Cms
loaded[] = Admin
loaded[] = ModManager
loaded[] = HelloWorld
loaded[] = AopTest
