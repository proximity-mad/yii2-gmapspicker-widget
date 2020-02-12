<?php

namespace proximitymad\yii2mapspickerwidget;


use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class MapPicker Widget
 */
class MapPicker extends Widget
{
    /**
     * @var bool|string Unique id for the map, optional
     */
    public $mapId = false;
    /**
     * @var bool|int Width for the map widget, optional
     */
    public $width = "200px";
    /**
     * @var int Height for the map widget, optional
     */
    public $height = "100px";
    /**
     * @var string Input field where to update matching latitude
     */
    public $latFieldClass = 'field-lat';
    /**
     * @var string Input field where to update matching longitude
     */
    public $lngFieldClass = 'field-lng';
    /**
     * @var string Selector for error message
     */
    public $errorClass = 'search-error';
    /**
     * @var string
     */
    public $errorMsg = 'No results founds';
    /**
     * @var bool
     */
    public $search = false;
    /**
     * @var array
     */
    public $searchField = [
        'inputClass'  => 'search-field',
        'buttonClass' => 'btn-search'
    ];
    public $mapOptions = [
        'zoom'              => 16,
        'streetViewControl' => false
    ];
    public $options = [];
    /**
     * @var bool Wether to load the Google Maps API file or not in case it is already included
     */
    public $loadMapApi = true;
    /**
     * @var bool|string Google Map API key, required if there is not an Google Maps API already loaded
     */
    public $apiKey = false;
    /**
     * @var bool Defines if the widget must load automatically
     */
    public $show = true;
    /**
     * @var null Defines where to load the JS @see yii\web\View
     */
    public $scriptsPosition = null;
    public function init()
    {
        $this->scriptsPosition = $this->scriptsPosition?:View::POS_READY;
        if ($this->loadMapApi === true && ($this->apiKey === false || $this->apiKey === '')) {
            throw new \Exception("Api key missing");
        }
        $asset = MapPickerAsset::register($this->view);
        if ($this->loadMapApi) {
            $asset->loadMapsLib($this->apiKey);
        }
        $this->mapId = $this->mapId ?: 'map_' . substr(md5(time() . rand(0, 999)), 0, 8);
        $styles = [];
        $width = strpos($this->width, "px")||strpos($this->width, "%")?$this->width:$this->width."px";
        $height = strpos($this->height, "px")||strpos($this->height, "%")?$this->height:$this->height."px";
        $styles[] = $this->width ? "width: {$width};" : '';
        $styles[] = $this->height ? "height: {$height};" : '';
        $style = implode(" ", $styles);
        $this->options = ArrayHelper::merge(['style' => $style], $this->options);
    }

    public function run()
    {
        $options = [
            'mapId'        => $this->mapId,
            'latField'     => $this->latFieldClass,
            'lngField'     => $this->lngFieldClass,
            'error'        => $this->errorClass,
            'mapOptions'   => $this->mapOptions,
            'searchField'  => $this->searchField,
            'errorMsg'     => $this->errorMsg

        ];
        $js = "var mapPicker_{$this->mapId} = new MapPicker(" . json_encode($options) . ")\n";
        $js .= "var mapInstances = []; mapInstances.push(mapPicker_{$this->mapId})\n";
        $js .= "mapPicker_{$this->mapId}.init()\n";
        if($this->show){
            $js .= "mapPicker_{$this->mapId}.init()\n";
            if ($this->search) {
                $js .= "mapPicker_{$this->mapId}.search('{$this->search}')\n";
            } else {
                throw new \Exception("MapPicker: 'search' parameter is required");
            }
        }
        $this->view->registerJs($js, $this->scriptsPosition);
        return Html::tag('div', 'Map', ArrayHelper::merge(['id' => $this->mapId], $this->options));
    }
}