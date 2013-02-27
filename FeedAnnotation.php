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
			'AdminMenu.add' => 'addAdminMenu'
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
}
