<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://github.com/halfdan/piwik-feedannotation-plugin
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 * @category Piwik_Plugins
 * @package Piwik_FeedAnnotation
 */

/**
 *
 * @package Piwik_FeedAnnotation
 */
class Piwik_FeedAnnotation extends Piwik_Plugin
{
	/**
	 * Return information about this plugin.
	 *
	 * @see Piwik_Plugin
	 *
	 * @return array
	 */
	public function getInformation()
	{
		return array(
			'description' => Piwik_Translate('FeedAnnotation_PluginDescription'),
			'author' => 'Fabian Becker <halfdan@xnorfz.de>',
			'author_homepage' => 'http://geekproject.eu/',
			'license' => 'GPL v3 or later',
			'license_homepage' => 'http://www.gnu.org/licenses/gpl.html',
			'version' => '0.1',
			'translationAvailable' => true,
		);
	}

	/**
	 * Create required FeedAnnotation database table
	 *
	 * @throws Exception
	 */
	public function install() {
		$tableFeedAnnotation = "CREATE TABLE " . Piwik_Common::prefixTable("feedannotation") . " (
			idfeed INT NOT NULL AUTO_INCREMENT,
			idsite INT(11) NOT NULL,
			feed_url VARCHAR(200) NOT NULL,
			last_processed DATETIME,
			PRIMARY KEY (idfeed)
		) DEFAULT CHARSET=utf8;";

		try {
			Piwik_Exec($tableFeedAnnotation);
		} catch (Exception $e) {
			// mysql code error 1050:table already exists
			// see bug #153 http://dev.piwik.org/trac/ticket/153
			if (!Zend_Registry::get('db')->isErrNo($e, '1050')) {
				throw $e;
			}
		}
	}

	/**
	 * Return the registered hooks
	 *
	 * @return array
	 */
	public function getListHooksRegistered()
	{
		return array(
			'AdminMenu.add' => 'addAdminMenu',
			'TaskScheduler.getScheduledTasks' => 'getScheduledTasks',
            'AssetManager.getJsFiles' => 'getJsFiles'
		);
	}

	/**
	 * Add new "Feed Annotations" admin menu.
	 */
	public function addAdminMenu() {
		Piwik_AddAdminSubMenu('General_Settings', 'FeedAnnotation_MenuGeneralSettings',
			array('module' => 'FeedAnnotation', 'action' => 'index'),
			Piwik::isUserHasSomeAdminAccess(),
			$order = 10);
	}

    /**
     * Add feedannotation.js
     * for the AssetManager.
     *
     * @param $notification Event_Notification
     */
    public function getJsFiles($notification)
    {
        $jsFiles = &$notification->getNotificationObject();
        $jsFiles[] = "plugins/FeedAnnotation/templates/feedannotation.js";
    }

	/**
	 * Gets all scheduled tasks executed by this plugin.
	 *
	 * @param Piwik_Event_Notification $notification  notification object
	 */
	public function getScheduledTasks($notification)
	{
		$tasks = &$notification->getNotificationObject();

		$updateFeedAnnotationsTask = new Piwik_ScheduledTask(
			$this,
			'updateFeedAnnotations',
			null,
			new Piwik_ScheduledTime_Daily()
		);
		$tasks[] = $updateFeedAnnotationsTask;
	}

	/**
	 * Fetches configured feeds and creates/updates Annotations.
	 */
	public function updateFeedAnnotations() {
		$feeds = Piwik_FeedAnnotation_API::getInstance()->getFeeds();

		foreach($feeds as $feed) {
            $processor = new Piwik_FeedAnnotation_FeedProcessor($feed);
			try {
				$processor->processFeed();
			} catch (Zend_Feed_Exception $ex) {
				Zend_Registry::get('logger_exception')->logEvent( $ex );
			}
		}
	}
}
