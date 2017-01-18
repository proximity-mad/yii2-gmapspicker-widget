<?php

namespace proximitymad\yii2mapspickerwidget;


use yii\web\AssetBundle;

class MapPickerAsset extends AssetBundle
{
    public $sourcePath = '@app/components/widgets/Maps/assets/';

    public $js = [
        'js/map-picker.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public function loadMapsLib($apiKey)
    {
        array_unshift($this->js, '//maps.googleapis.com/maps/api/js?key=' . $apiKey);
        return $this;
    }
}