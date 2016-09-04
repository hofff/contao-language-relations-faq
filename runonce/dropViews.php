<?php

Database::getInstance()->query('DROP VIEW IF EXISTS hofff_language_relations_faq_tree');
Database::getInstance()->query('DROP VIEW IF EXISTS hofff_language_relations_faq_aggregate');
Database::getInstance()->query('DROP VIEW IF EXISTS hofff_language_relations_faq_relation');
Database::getInstance()->query('DROP VIEW IF EXISTS hofff_language_relations_faq_item');
