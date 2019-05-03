<?php

/**
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @copyright  (c) 2013 - 2016 Branko Wilhelm
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

class WoWAdapterMMOChampion extends WoWAdapterAbstract
{
    /**
     * @return mixed
     */
    public function getData($language)
    {
        $this->url = 'http://blue.mmo-champion.com/rss/?language=' . $language;

        $result = $this->getRemote($this->url);

        if ($result->code != 200)
        {
            $msg = JText::sprintf('Server Error: %s url: %s', $result->body->reason, JHtml::_('link', $this->url, $result->code, array('target' => '_blank')));
            throw new RuntimeException($msg);
        }

        $result->body = simplexml_load_string($result->body);

        if (!($result->body instanceof SimpleXMLElement))
        {
            $msg = JText::_('ERROR');
            throw new RuntimeException($msg);
        }

        return $result;
    }
}