<?php

/**
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @copyright  (c) 2013 - 2016 Branko Wilhelm
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

abstract class WoWAdapterAbstract
{
    /**
     * @var Registry
     */
    protected $params;

    /**
     * @var string
     */
    protected $url;

    /**
     * @param Registry $params
     */
    public function __construct(Registry $params)
    {
        $this->params = $params;
    }


    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this, $name))
        {
            return call_user_func_array(array($this, $name), $arguments);
        }

        throw new InvalidArgumentException('method "' . $name . '" not found in ' . get_class($this));
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @param bool $persistent
     *
     * @return mixed JHttpResponse|string
     */
    protected function getRemote($url, $persistent = false)
    {
        $cache = JFactory::getCache('wow', 'output');
        $cache->setCaching(1);
        $cache->setLifeTime($this->params->get('cache_timeout', 30) * ($persistent ? 172800 : 60) + rand(0, 60)); // randomize cache time a little bit for each url

        $key = md5($url);

        if (!$result = $cache->get($key))
        {
            try
            {
                $http = JHttpFactory::getHttp();
                $http->setOption('userAgent', 'Joomla/' . JVERSION . '; WoW Library/@REVISION@; php/' . phpversion());
                $result = $http->get($url, null, $this->params->get('socket_timeout', 10));
            } catch (Exception $e)
            {
                return $e->getMessage();
            }

            $cache->store($result, $key);
        }

        return $result;
    }
}