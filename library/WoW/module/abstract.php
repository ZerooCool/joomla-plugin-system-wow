<?php

/**
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @copyright  (c) 2013 - 2016 Branko Wilhelm
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

class WoWModuleAbstract
{
    /**
     * @var stdClass
     */
    protected $params = null;

    public function __construct(Registry $params)
    {
        $this->params = new stdClass;
        $this->params->module = $params;
        $this->params->global = WoW::getInstance()->params;
    }

    public static function getAjax()
    {
        $module = JModuleHelper::getModule('mod_' . JFactory::getApplication()->input->get('module'));

        if (empty($module))
        {
            return false;
        }

        JFactory::getLanguage()->load($module->module);

        $params = new Registry($module->params);
        $params->set('ajax', 0);

        ob_start();

        require(JPATH_ROOT . '/modules/' . $module->module . '/' . $module->module . '.php');

        return ob_get_clean();
    }

    public static function getData(Registry $params)
    {
        if ($params->get('ajax'))
        {
            return;
        }

        $instance = new static($params);

        return $instance->getInternalData();
    }

    protected function getInternalData()
    {
        return null;
    }

}