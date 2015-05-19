<?php
/**
 * default mapper args limit, offset
 * orderby and sort by
 *
 * @author Levon Naghashyan
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2014
 * @package dal.mappers
 * @version 6.0
 *
 */
namespace framework\dal\mappers {
	class StandartArgs {

		private $dto;

		private $orderBy = null;
		private $sortBy = null;
		private $limit = null;
		private $offset = null;
		private $userId = null;
		private $customField = null;

		function __construct($dto) {
			$this->dto = $dto;
		}

		public function setOrderBy($orderBy) {
			if ($this->dto->isExsistField($orderBy)) {
				$this->orderBy = $orderBy;
			}
		}

		public function getOrderBy() {
			return $this->orderBy;
		}

		public function setSortBy($sortBy) {
			$this->sortBy = $sortBy;
		}

		public function getSortBy() {
			return $this->sortBy;
		}

		public function setLimit($limit) {
			$this->limit = $limit;
		}

		public function getLimit() {
			return $this->limit;
		}

		public function setOffset($offset) {
			$this->offset = $offset;
		}

		public function getOffset() {
			return $this->offset;
		}

		public function setUserId($userId) {
			$this->userId = $userId;
		}

		public function getUserId() {
			return $this->userId;
		}

		public function setCustomField($customField) {
			$this->customField = "`".$this->dto->isExsistField($customField)."`.`".$customField."`";
		}

		public function getCustomField() {
			return $this->customField;
		}

	}

}
?>