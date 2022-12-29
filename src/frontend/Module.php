<?php

namespace luya\crawler\frontend;

/**
 * LUYA Crawler Frontend Module.
 *
 * The Crawler will create an index with all pages based on your defined `baseUrl`. You can run the crawler by using the command
 *
 * ```sh
 * ./vendor/bin/luya crawler/crawl
 * ```
 *
 * This will create an index where you can search inside (See helper methods in `luya\crawler\models\Index` to find by query methods).
 * You should run your crawler command by a cronjob to make sure your page will be crawled everynight and the users have a fresh index.
 *
 * @link https://github.com/FriendsOfPHP/Goutte
 * @link https://api.symfony.com/2.7/Symfony/Component/DomCrawler.html
 *
 * @author Basil Suter <basil@nadar.io>
 * @since 1.0.0
 */
final class Module extends \luya\base\Module
{
    const CRAWLER_USER_AGENT = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30'; // Chrome 12 in Mac OS X 10.6.8
    
    /**
     * @var boolean This module enables by default to lookup for view files in the apps/views folder.
     */
    public $useAppViewPath = true;

    /**
     * @var string The based Url where the crawler should start to lookup for pages, the crawler only allowes
     * links which matches the base url. It doenst matter if you have a trailing slash or not, the module is taking
     * care of this.
     *
     * So on a localhost your base url could look like this:
     *
     * ```php
     * 'baseUrl' => 'http://localhost/luya-kickstarter/public_html/',
     * ```
     *
     * If you are on a production/preproduction server the url in your config could look like this:
     *
     * ```php
     * 'baseUrl' => 'https://luya.io',
     * ```
     */
    public $baseUrl;
    
    /**
     * @var array An array with regular expression (including delimiters) which will be applied to found links so you can
     * filter several urls which should not be followed by the crawler.
     *
     * Examples:
     *
     * ```php
     * 'filterRegex' => [
     *     '#.html#i', // filter all links with `.html`
     *     '#/agenda#i', // filter all links which contain the word with leading slash agenda,
     *     '#date\=#i, // filter all links with the word date inside. for example when using an agenda which will generate infinite links with `?date=123456789`
     * ],
     * ```
     */
    public $filterRegex = [];
    
    /**
     * @var array E-Mail addresses array with recipients for the statistic command
     */
    public $statisticRecipients = [];
    
    /**
     * @var integer Number of pages
     */
    public $searchResultPageSize = 25;

    /**
     * @var array An array with classes implementing the {{CrawlIndexInterface}}. Example
     * ```php
     * 'indexer' => [
     *     'app/models/MyModel',
     * ],
     * ```
     * 
     * > Keep in mind, that when using URLs with indexer, the will also apply to the $filterRegex rules.
     * 
     * @since 2.0.0
     */
    public $indexer = [];
    
    /**
     * @inheritdoc
     */
    public $urlRules = [
        'crawler' => 'crawler/default/index',
    ];

    /**
     * {@inheritDoc}
     */
    public static function onLoad()
    {
        self::registerTranslation('crawler', static::staticBasePath() . '/messages', [
            'crawler' => 'crawler.php',
        ]);
    }

    /**
     * Translate Message
     *
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, array $params = [])
    {
        return parent::baseT('crawler', $message, $params);
    }
}
