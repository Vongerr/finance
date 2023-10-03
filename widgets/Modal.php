<?php

namespace app\widgets;

use yii\helpers\Html;

/**
 * Class Modal
 * @package core\widgets
 */
class Modal extends \yii\bootstrap\Modal
{
    public $size = self::SIZE_LARGE;

    protected function initOptions()
    {
        if (!$this->header) {
            
            $this->header = Html::tag('h3', '', ['class' => 'modal-title']);
        }
        
        if (!$this->clientOptions) {
            
            $this->clientOptions = ['show' => false,];          
        }

        parent::initOptions();
        
        if ($this->options['tabindex'] == -1) {

            $this->options['tabindex'] = false;
        }
    }
}