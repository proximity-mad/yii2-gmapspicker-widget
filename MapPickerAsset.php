<?php

namespace proximitymad\yii2mapspickerwidget;


use yii\web\AssetBundle;

class MapPickerAsset extends AssetBundle
{
    public $js = [
        'js/map-picker.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
    public function init()
    {
        $this->sourcePath = __DIR__ . '/assets';
        parent::init();
    }

    public function loadMapsLib($apiKey)
    {
        array_unshift($this->js, '//maps.googleapis.com/maps/api/js?key=' . $apiKey);
        return $this;
    }
}