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

#
# Dumping data for table `CONTRIBUTORS`
#
INSERT INTO `{$PREFIX}CONTRIBUTORS` (`id_contributors`, `deleted`, `mtime`, `ctime`, `username`, `password`, `admin`, `email`, `fullname`, `lang`, `lastlogin`, `lastip`, `session`) VALUES
(1, 0, 1230764400, 1230764400, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 1, 'woops-admin@example.com', 'WOOPS administrator', '{$DEFAULT_LANGUAGE}', 0, '', '');

#
# Dumping data for table `PAGEHEADERS`
#
INSERT INTO `{$PREFIX}PAGEHEADERS` (`id_pageheaders`, `id_pageinfos`, `id_templates`, `deleted`, `ctime`, `mtime`, `lang`, `title`, `menutitle`, `keywords`, `description`) VALUES
(1, 1, 1, 0, 1230764400, 1230764400, '{$DEFAULT_LANGUAGE}', 'WOOPS home page', '', '', ''),
(2, 2, 2, 0, 1230764400, 1230764400, '{$DEFAULT_LANGUAGE}', 'WOOPS administration page', '', '', '');

#
# Dumping data for table `PAGEINFOS`
#
INSERT INTO `{$PREFIX}PAGEINFOS` (`id_pageinfos`, `id_parent`, `deleted`, `ctime`, `mtime`, `home`) VALUES
(1, 0, 0, 1230764400, 1230764400, 1),
(2, 1, 0, 1230764400, 1230764400, 0);

#
# Dumping data for table `TEMPLATES`
#
INSERT INTO `{$PREFIX}TEMPLATES` (`id_templates`, `id_parent`, `deleted`, `ctime`, `mtime`, `title`, `file`, `engine`, `engine_options`) VALUES
(1, 0, 0, 1230764400, 1230764400, 'WOOPS default template', 'woops-mod://Cms/resources/templates/woops-default.html', 'Woops_Mod_Cms_Page_Engine', 'O:8:"stdClass":2:{s:6:"tagMap";a:0:{}s:8:"keepHead";a:1:{i:0;a:2:{i:0;s:4:"link";i:1;i:1;}}}'),
(2, 0, 0, 1230764400, 1230764400, 'WOOPS administration template', 'woops-mod://Admin/resources/templates/woops-admin.html', 'Woops_Mod_Cms_Page_Engine', 'O:8:"stdClass":2:{s:6:"tagMap";a:0:{}s:8:"keepHead";a:1:{i:0;a:2:{i:0;s:4:"link";i:1;i:1;}}}');
