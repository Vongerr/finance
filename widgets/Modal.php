<?php

namespace app\widgets;

/**
 * Class Modal
 * @package app\widgets
 */
class Modal extends \yii\bootstrap5\Modal
{
    public $size = self::SIZE_LARGE;

    protected function initOptions(): void
    {
        
        if (!$this->clientOptions) {
            
            $this->clientOptions = ['show' => false,];          
        }

        parent::initOptions();
        
        if ($this->options['tabindex'] == -1) {

            $this->options['tabindex'] = false;
        }
    }
}