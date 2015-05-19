<?php
/**
 * main site load for all ngs site's loads
 * this class provide methods
 *
 * @author Levon Naghashyan
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2009-2014
 * @package loads
 * @version 6.0
 *
 */
namespace loads {
	use security\RequestGroups;
	/**
	 * General parent load for all ngs loads
	 */
	abstract class NgsLoad extends \framework\AbstractLoad {

		private $customer = false;
		private $customerId = false;

		/**
		 * Initializes translations array for selected language.
		 *
		 * @return
		 */
		public function __construct() {
		}

		//! A constructor.
		public function initialize() {
			parent::initialize();
		}

		public function initPaging($page, $itemsCount, $limit, $pagesShowed) {
			if ($limit < 1) {
				return false;
			}
			$pageCount = ceil($itemsCount / $limit);
			$centredPage = ceil($pagesShowed / 2);
			$pStart = 0;
			if (($page - $centredPage) > 0) {
				$pStart = $page - $centredPage;
			}
			if (($page + $centredPage) >= $pageCount) {
				$pEnd = $pageCount;
				if (($pStart - ($page + $centredPage - $pageCount)) > 0) {
					$pStart = $pStart - ($page + $centredPage - $pageCount) + 1;
				}
			} else {
				$pEnd = $pStart + $pagesShowed;
				if ($pEnd > $pageCount) {
					$pEnd = $pageCount;
				}
			}

			$this->addParam("pageCount", $pageCount);
			$this->addParam("page", $page);
			$this->addParam("pStart", $pStart);
			$this->addParam("pEnd", $pEnd);
			$this->addParam("limit", $limit);
			$this->addParam("itemsCount", $itemsCount);

			return true;
		}
		
	}

}
