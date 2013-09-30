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
namespace Piwik\Plugins\FeedAnnotation;
use Piwik\Common;
use Piwik\Piwik;
use Piwik\Plugins\Annotations\API as AnnotationsAPI;

/**
 *
 * @package Piwik_FeedAnnotation
 */
class FeedProcessor
{
    private $feed;

    public function __construct(array $feed)
    {
        $this->feed = $feed;
    }

    /**
     * Fetches a feed by its URL and adds new feed items as annotations.
     * New items are determined by last_processed date and time.
     */
    public function processFeed() {
        $lastProcessed = $this->feed['last_processed'];
        $idSite = $this->feed['idsite'];
        $idFeed = $this->feed['idfeed'];
        $url = $this->feed['feed_url'];

        $feedData = \Zend_Feed::import($url);

        foreach($feedData as $feedEntry) {
            $date = null;
            if(!empty($feedEntry->published)) {
                // Atom Feed (RFC 3339 Date)
                $date = new \DateTime($feedEntry->published);
            } else if(!empty($feedEntry->pubDate)) {
                // RSS 2.0 Feed (RFC 822 Date)
                $date = new \DateTime($feedEntry->pubDate);
            } else {
                Piwik::log(sprintf("Feed %s contains invalid entries. Skipping entry.", $url));
                continue;
            }

            $title = (string)$feedEntry->title;

            // If feed was never processed or entry is newer than $lastProcessed
            if(!$lastProcessed || $date > new \DateTime($lastProcessed)) {
                AnnotationsAPI::getInstance()->add($idSite, $date->format('Y-m-d'), $title);
            }
        }
        // Update last_processed
        $sql = sprintf("UPDATE %s SET last_processed = now() WHERE idfeed = %d", Common::prefixTable("feedannotation"), $idFeed);
        \Piwik\Db::exec($sql);
    }
}
