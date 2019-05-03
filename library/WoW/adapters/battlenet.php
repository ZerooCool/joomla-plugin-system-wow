<?php

/**
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @copyright  (c) 2013 - 2016 Branko Wilhelm
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

class WoWAdapterBattleNET extends WoWAdapterAbstract
{
    /**
     * @param string $target
     * @param bool $persistent
     *
     * @return mixed|null
     */
    public function getData($target, $persistent = false)
    {
        switch ($target)
        {
            case 'guild_news':
                $this->url = 'http://' . $this->params->get('region') . '.battle.net/wow/' . $this->params->get('locale') . '/guild/' . rawurlencode($this->params->get('realm')) . '/' . rawurlencode($this->params->get('guild')) . '/news';
                break;

            case 'top_weekly_contributors':
                $this->url = 'http://' . $this->params->get('region') . '.battle.net/wow/' . $this->params->get('locale') . '/guild/' . rawurlencode($this->params->get('realm')) . '/' . rawurlencode($this->params->get('guild')) . '/';
                break;

            default:
                return null;
                break;
        }

        $result = $this->getRemote($this->url, $persistent);

        if ($result->code != 200)
        {
            $msg = JText::sprintf('Server Error: %s', JHtml::_('link', $this->url, $result->code, array('target' => '_blank')));
            throw new RuntimeException($msg);
        }

        return $result;
    }
}