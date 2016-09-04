<?php

namespace Hofff\Contao\LanguageRelations\FAQ\Util;

use Contao\Config;
use Contao\FaqCategoryModel;
use Contao\FaqModel;
use Contao\Input;
use Contao\ModuleFaqList;
use Contao\PageModel;
use Hofff\Contao\LanguageRelations\Util\QueryUtil;

/**
 * @author Oliver Hoff <oliver@hofff.com>
 */
class ContaoFAQUtil extends ModuleFaqList {

	/**
	 */
	public function __construct() {
	}

	/**
	 * @param integer|null $jumpTo
	 * @return integer|null
	 */
	public static function findCurrentQuestion($jumpTo = null) {
		if(isset($_GET['items'])) {
			$idOrAlias = Input::get('items', false, true);
		} elseif(isset($_GET['auto_item']) && Config::get('useAutoItem')) {
			$idOrAlias = Input::get('auto_item', false, true);
		} else {
			return null;
		}

		$sql = <<<SQL
SELECT
	faq.id			AS faq_id,
	category.jumpTo	AS category_jump_to
FROM
	tl_faq
	AS faq
JOIN
	tl_faq_category
	AS category
	ON category.id = faq.pid
WHERE
	faq.id = ? OR faq.alias = ?
SQL;
		$result = QueryUtil::query(
			$sql,
			null,
			[ $idOrAlias, $idOrAlias ]
		);

		if(!$result->numRows) {
			return null;
		}

		if($jumpTo === null || $jumpTo == $result->category_jump_to) {
			return $result->faq_id;
		}

		return null;
	}

	/**
	 * @param FaqModel $faq
	 * @return string
	 */
	public static function getQuestionURL(FaqModel $faq) {
		static $instance;
		$instance || $instance = new self;
		return $instance->generateFaqLink($faq);
	}

	/**
	 * @param array $ids
	 * @return void
	 */
	public static function prefetchFAQModels(array $ids) {
		$categories = [];
		foreach(FaqModel::findMultipleByIds(array_values($ids)) as $faq) {
			$categories[] = $faq->pid;
		}

		$pages = [];
		foreach(FaqCategoryModel::findMultipleByIds($categories) as $category) {
			$category->jumpTo && $pages[] = $category->jumpTo;
		}

		PageModel::findMultipleByIds($pages);
	}

	/**
	 * @see \Contao\Module::compile()
	 */
	protected function compile() {
	}

}
