<?php
namespace Piwik\Plugins\FeedAnnotation;
use Piwik\Common;
use Piwik\Controller\Admin as AdminController;
use Piwik\Piwik;
use Piwik\Plugins\SitesManager\API as SitesManagerAPI;

/**
 *
 * @package Piwik_FeedAnnotation
 */
class Controller extends AdminController
{
	public function index()
	{
		Piwik::checkUserHasSomeAdminAccess();
		$view = new \Piwik\View('@FeedAnnotation/index');
		$this->setBasicVariablesView($view);

		$idSite = Common::getRequestVar('idSite', false, 'int');
		$idSitesAvailable = SitesManagerAPI::getInstance()->getSitesWithAdminAccess();

		$view->feeds = API::getInstance()->getFeeds(array($idSite));
		$view->idSiteSelected = $idSite;
		$view->idSitesAvailable = $idSitesAvailable;
		$view->siteName = \Piwik\Site::getNameFor($idSite);
		$view->menu = Piwik_GetAdminMenu();
		echo $view->render();
	}

    public function createFeed()
    {
        $idSite = Common::getRequestVar('idSiteSelected', false, 'int');
        Piwik::isUserHasAdminAccess($idSite);

        $feedURL = Common::getRequestVar('feedUrl');
        try
        {
            API::getInstance()->addFeed($idSite, $feedURL);
        } catch (InvalidFeedException $ex) {
            // ToDo
        }
        $this->redirectToIndex('FeedAnnotation', 'index');
    }

    public function processFeed()
    {
        $idSite = Common::getRequestVar('idSiteSelected', false, 'int');
        Piwik::isUserHasAdminAccess($idSite);

        $idfeed = Common::getRequestVar('idfeed', false, 'int');
        try
        {
            $feed = API::getInstance()->getFeed($idfeed);
            $processor = new FeedProcessor($feed);
            $processor->processFeed();
        } catch (InvalidFeedException $ex) {
            // ToDo
        }
        $this->redirectToIndex('FeedAnnotation', 'index');
    }
}
