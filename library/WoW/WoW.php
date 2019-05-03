<?php

/**
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @copyright  (c) 2013 - 2016 Branko Wilhelm
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

class WoW
{
    /**
     * @var WoW
     */
    protected static $instance = null;

    /**
     * @var Registry
     */
    public $params = null;

    public static function getInstance(Registry $params = null)
    {
        // $instance set, but $params given -> return new instance
        if (self::$instance && $params != null)
        {
            return new self($params);
        }

        // create and get first $instance
        if (!self::$instance && $params)
        {
            self::$instance = new self($params);
        }

        // no $instance and $params set, cant work!
        if (!self::$instance && !$params)
        {
            throw new InvalidArgumentException(__CLASS__ . ' must be instanced with params!');
        }

        // return $instance
        return self::$instance;
    }

    /**
     * @param Registry $params
     */
    private function __construct(Registry $params)
    {
        JLoader::discover('WoWAdapter', __DIR__ . '/adapters/');
        JLoader::register('WoWModuleAbstract', __DIR__ . '/module/abstract.php');
        JFactory::getLanguage()->load('lib_wow');
        $this->params = $params;
    }

    /**
     * @param string $adapter
     *
     * @return WoWAdapterAbstract
     */
    public function getAdapter($adapter)
    {
        if (!$this->checkConfig())
        {
            throw new InvalidArgumentException(JText::_('LIB_WOW_CONFIGURATION_MISSING'));
        }

        $class = 'WoWAdapter' . $adapter;

        if (class_exists($class))
        {
            return new $class($this->params);
        }

        throw new InvalidArgumentException($class . ' adapter not found!');
    }

    /**
     * @return bool
     */
    public function checkConfig()
    {
        if (
            !$this->params->get('apikey') ||
            !$this->params->get('guild') ||
            !$this->params->get('realm') ||
            !$this->params->get('region') ||
            !$this->params->get('locale')
        )
        {
            return false;
        }

        return true;
    }

    public function getBattleNetUrl()
    {
        $uri = new JUri;
        $uri->setScheme($this->params->get('scheme', 'http'));
        $uri->setHost($this->params->get('region') . '.battle.net');
        $uri->setPath('/wow/' . $this->params->get('locale') . '/guild/' . rawurlencode($this->params->get('realm')) . '/' . rawurlencode($this->params->get('guild')) . '/');

        return $uri->toString();
    }
}
