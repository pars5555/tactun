<?php

/**
 * @author Levon Naghashyan
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace demo\loads\demo {

    use \demo\security\RequestGroups;

    class HomeLoad extends \demo\loads\NgsLoad {

        public function load() {

            $id = \demo\managers\DemoManager::getInstance()->insertDemoRecord("New Inserted");
            \demo\managers\DemoManager::getInstance()->updateDemoRecord($id, "Updated Row");
            $this->addParam("demoDto", \demo\managers\DemoManager::getInstance()->seletDemoRecord($id));
        }

        public function getTemplate() {
            return TEMPLATES_DIR . "/main/home.tpl";
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
