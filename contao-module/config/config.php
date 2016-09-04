<?php

$GLOBALS['BE_MOD']['content']['faq']['stylesheet'][]
	= 'system/modules/hofff_language_relations/assets/css/style.css';

$GLOBALS['TL_HOOKS']['loadDataContainer']['hofff_language_relations_faq']
	= [ 'Hofff\\Contao\\LanguageRelations\\FAQ\\DCA\\FAQDCA', 'hookLoadDataContainer' ];
$GLOBALS['TL_HOOKS']['sqlCompileCommands']['hofff_language_relations_faq']
	= [ 'Hofff\\Contao\\LanguageRelations\\FAQ\\Database\\Installer', 'hookSQLCompileCommands' ];
$GLOBALS['TL_HOOKS']['hofff_language_relations_language_switcher']['hofff_language_relations_faq']
	= [ 'Hofff\\Contao\\LanguageRelations\\FAQ\\LanguageRelationsFAQ', 'hookLanguageSwitcher' ];
