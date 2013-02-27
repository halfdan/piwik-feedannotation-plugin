<?php
/**
 *
 * @package Piwik_FeedAnnotation
 */
class Piwik_FeedAnnotation_Controller extends Piwik_Controller_Admin
{
	function index()
	{
		Piwik::checkUserHasSomeAdminAccess();
		$view = Piwik_View::factory('index');
		$this->setBasicVariablesView($view);

		$idSite = Piwik_Common::getRequestVar('idSite', false, 'int');
		$idSitesAvailable = Piwik_SitesManager_API::getInstance()->getSitesWithAdminAccess();


		$view->idSiteSelected = $idSite;
		$view->idSitesAvailable = $idSitesAvailable;
		$view->menu = Piwik_GetAdminMenu();
		echo $view->render();
	}
}