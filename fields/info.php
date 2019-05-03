<?php

/**
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @copyright  (c) 2013 - 2016 Branko Wilhelm
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('text');

class JFormFieldInfo extends JFormFieldText
{
    public $type = 'Info';

    protected function getInput()
    {
        $button = JHtml::_(
            'link',
            $this->getAttribute('info_url'),
            JText::_($this->getAttribute('info_label')),
            array('
                  target' => '_blank',
                  'class' => 'btn btn-info',
                  'title' => JText::_($this->getAttribute('info_title'))
            )
        );

        return parent::getInput() . '&nbsp;' . $button;
    }
}