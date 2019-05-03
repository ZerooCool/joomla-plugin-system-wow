<?php

/**
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @copyright  (c) 2013 - 2016 Branko Wilhelm
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

class WoWAdapterWorldOfLogs extends WoWAdapterAbstract
{
    /**
     * @param integer $guildId
     *
     * @return mixed
     */
    public function getData($guildId)
    {
        $this->url = 'http://www.worldoflogs.com/feeds/guilds/' . $guildId . '/raids/';

        $result = $this->getRemote($this->url);

        if ($result->code != 200)
        {
            $msg = JText::sprintf('Server Error: %s url: %s', $result->body->reason, JHtml::_('link', $this->url, $result->code, array('target' => '_blank')));
            throw new RuntimeException($msg);
        }

        $result->body = json_decode($result->body);

        if (empty($result->body->rows) || !is_array($result->body->rows))
        {
            $msg = JText::_('no raids found');
            throw new RuntimeException($msg);
        }

        return $result;
    }
}