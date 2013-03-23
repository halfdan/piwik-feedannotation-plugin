<?php
/**
 *
 * @package Piwik_FeedAnnotation
 */
class Piwik_FeedAnnotation_Controller extends Piwik_Controller_Admin
{
	public function index()
	{
		Piwik::checkUserHasSomeAdminAccess();
		$view = Piwik_View::factory('index');
		$this->setBasicVariablesView($view);

		$idSite = Piwik_Common::getRequestVar('idSite', false, 'int');
		$idSitesAvailable = Piwik_SitesManager_API::getInstance()->getSitesWithAdminAccess();

		$view->feeds = Piwik_FeedAnnotation_API::getInstance()->getFeeds(array($idSite));
		$view->idSiteSelected = $idSite;
		$view->idSitesAvailable = $idSitesAvailable;
		$view->siteName = Piwik_Site::getNameFor($idSite);
		$view->menu = Piwik_GetAdminMenu();
		echo $view->render();
	}

    public function createFeed()
    {
        $idSite = Piwik_Common::getRequestVar('idSiteSelected', false, 'int');
        Piwik::isUserHasAdminAccess($idSite);

        $feedURL = Piwik_Common::getRequestVar('feedUrl');
        try
        {
            Piwik_FeedAnnotation_API::getInstance()->addFeed($idSite, $feedURL);
        } catch (Piwik_FeedAnnotation_InvalidFeedException $ex) {
            // ToDo
        }
        $this->redirectToIndex('FeedAnnotation', 'index');
    }
}
