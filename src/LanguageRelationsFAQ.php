<?php

namespace Hofff\Contao\LanguageRelations\FAQ;

use Contao\FaqCategoryModel;
use Contao\FaqModel;
use Contao\PageModel;
use Hofff\Contao\LanguageRelations\FAQ\Util\ContaoFAQUtil;
use Hofff\Contao\LanguageRelations\Module\ModuleLanguageSwitcher;
use Hofff\Contao\LanguageRelations\Relations;
use Hofff\Contao\LanguageRelations\Util\ContaoUtil;

/**
 * @author Oliver Hoff <oliver@hofff.com>
 */
class LanguageRelationsFAQ {

	/**
	 * @var Relations
	 */
	private static $relations;

	/**
	 * @return Relations
	 * @deprecated
	 */
	public static function getRelationsInstance() {
		isset(self::$relations) || self::$relations = new Relations(
			'tl_hofff_language_relations_faq',
			'hofff_language_relations_faq_item',
			'hofff_language_relations_faq_relation'
		);
		return self::$relations;
	}

	/**
	 * @param array $items
	 * @param ModuleLanguageSwitcher $module
	 * @return array
	 */
	public function hookLanguageSwitcher(array $items, ModuleLanguageSwitcher $module) {
		$currentPage = $GLOBALS['objPage'];

		$currentQuestion = ContaoFAQUtil::findCurrentQuestion($currentPage->id);
		if(!$currentQuestion) {
			return $items;
		}

		$relatedQuestions = self::getRelationsInstance()->getRelations($currentQuestion);
		$relatedQuestions[$currentPage->hofff_root_page_id] = $currentQuestion;

		ContaoFAQUtil::prefetchFAQModels($relatedQuestions);

		foreach($items as $rootPageID => &$item) {
			if(!isset($relatedQuestions[$rootPageID])) {
				continue;
			}

			$faq = FaqModel::findByPk($relatedQuestions[$rootPageID]);
			if(!ContaoUtil::isPublished($faq)) {
				continue;
			}

			$archive = FaqCategoryModel::findByPk($faq->pid);
			if(!$archive->jumpTo) {
				continue;
			}

			$page = PageModel::findByPk($archive->jumpTo);
			if(!ContaoUtil::isPublished($page)) {
				continue;
			}

			$item['href']		= ContaoFAQUtil::getQuestionURL($faq);
			$item['pageTitle']	= strip_tags($faq->headline);
		}
		unset($item);

		return $items;
	}

}
