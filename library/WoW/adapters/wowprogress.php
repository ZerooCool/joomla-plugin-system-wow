<?php

/**
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @copyright  (c) 2013 - 2016 Branko Wilhelm
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

class WoWAdapterWoWProgress extends WoWAdapterAbstract
{
    /**
     * @return mixed
     */
    public function getData()
    {
        $this->url = 'http://www.wowprogress.com/guild/' . $this->params->get('region') . '/' . JApplicationHelper::stringURLSafe($this->params->get('realm')) . '/' . $this->params->get('guild') . '/json_rank';

        $result = $this->getRemote($this->url);

        $result->body = json_decode($result->body);

        if (empty($result->body) || $result->code != 200)
        {
            $msg = JText::sprintf('invalid response: %s', JHtml::_('link', $this->url, $result->code, array('target' => '_blank')));
            throw new RuntimeException($msg);
        }

        return $result;
    }
}