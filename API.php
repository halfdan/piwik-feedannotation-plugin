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
		$feeds = Piwik_FetchAll($query);

		return $feeds;
	}
}