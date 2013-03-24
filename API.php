<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 * @category Piwik_Plugins
 * @package Piwik_API
 */

/**
 * FeedAnnotation API is used to request the configured Feed Annotation URLs.
 *
 * @package Piwik_FeedAnnotation
 */
class Piwik_FeedAnnotation_API {

	protected static $instance;

	/**
	 * Gets or creates the FeedAnnotation API singleton.
	 */
	static public function getInstance()
	{
		if (self::$instance == null)
		{
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Returns all configured feeds for all idSites.
	 * User needs to have admin access to a site.
	 *
	 * @param array $idSites
	 * @return array
	 */
	public function getFeeds($idSites = array()) {
		if (count($idSites)) {
			Piwik::checkUserHasViewAccess($idSites);
		} else {
			Piwik::checkUserHasSomeViewAccess();
			$idSites = Piwik_SitesManager_API::getInstance()->getSitesIdWithAtLeastViewAccess();
		}

		$query = sprintf("SELECT * FROM %s WHERE idsite IN (%s)",
			Piwik_Common::prefixTable("feedannotation"),
			implode(",", $idSites)
		);

		$db = Zend_Registry::get('db');
		$feeds = $db->fetchAll($query);

		return $feeds;
	}

    /**
     * Fetches a single feed by id.
     *
     * @param int $idFeed
     * @throws Piwik_FeedAnnotation_InvalidFeedException
     */
    public function getFeed($idFeed)
    {
        $query = sprintf("SELECT * FROM %s WHERE idfeed = %d",
            Piwik_Common::prefixTable("feedannotation"), $idFeed
        );

        $db = Zend_Registry::get('db');
        $feed = $db->fetchRow($query);

        if($feed)
        {
            Piwik::isUserHasViewAccess(array($feed['idsite']));
            return $feed;
        } else {
            throw new Piwik_FeedAnnotation_InvalidFeedException(sprintf("Feed ID not valid: %d", $idFeed));
        }
    }

    /**
     * Adds a new feed to idSite.
     *
     * @param $idSite
     * @param $url
     * @throws Piwik_FeedAnnotation_InvalidFeedException
     */
    public function addFeed($idSite, $url) {
		Piwik::checkUserHasAdminAccess(array($idSite));

		if($this->isValidFeedUrl($url))
        {
			$query = sprintf("INSERT INTO %s (idsite, feed_url) VALUES (?, ?)",
				Piwik_Common::prefixTable("feedannotation")
			);
			$db = Zend_Registry::get('db');
			$db->query($query, array($idSite, $url));
		} else {
			throw new Piwik_FeedAnnotation_InvalidFeedException(sprintf("Feed URL not valid: %s", $url));
		}
	}

	/**
	 * Checks whether the URL returns a parsable feed (RSS/Atom)
	 *
	 * @param $url
	 * @return bool
	 */
	public function isValidFeedUrl($url) {
		try {
			Zend_Feed::import($url);
			return true;
		} catch (Zend_Feed_Exception $e) {
			return false;
		}
	}
}

/**
 * Custom exception that is thrown when an invalid feed URL is specified.
 */
class Piwik_FeedAnnotation_InvalidFeedException extends Exception {

}