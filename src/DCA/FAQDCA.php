<?php

namespace Hofff\Contao\LanguageRelations\FAQ\DCA;

/**
 * @author Oliver Hoff <oliver@hofff.com>
 */
class FAQDCA {

	/**
	 * @param string $table
	 * @return void
	 */
	public function hookLoadDataContainer($table) {
		if($table != 'tl_faq') {
			return;
		}

		$palettes = &$GLOBALS['TL_DCA']['tl_faq']['palettes'];
		foreach($palettes as $key => &$palette) {
			if($key != '__selector__') {
				$palette .= ';{hofff_language_relations_legend}';
				$palette .= ',hofff_language_relations';
			}
		}
		unset($palette, $palettes);
	}

}
