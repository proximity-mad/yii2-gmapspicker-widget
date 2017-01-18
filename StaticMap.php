<?php

namespace app\components\widgets\Maps;


use yii\base\Exception;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class StaticMap
 * Devuelve una imagen de mapa estatico
 *
 * Ejemplo:
 * ```php
 * \app\components\widgets\Maps\StaticMap::widget(['lat'=>'40.4647254', 'lng'=>'-3.6724659', 'width'=>'50','height'=>'50','apiKey'=>'xxx'); *
 *```
 * Ejemplo con opciones:
 * ```php
 * $options = [
 *  'width'=>50,
 *  'height'=>50,
 *  'color'=>'red',
 *  'label'=>'P',
 *  'size'=>'tiny',
 *  'markers'=>[
 *      [
 *          'color'=>'red',
 *          'label'=>'P',
 *          'lat'=>'40.5',
 *          'lng'=>'-4.67'
 *      ]
 *  ]
 * ];
 * ```
 */
class StaticMap extends Widget
{
    /**
     * @var bool|string La API KEY de Google para cargar el mapa, obligatorio
     */
    public $apiKey = false;
    /**
     * @var bool|int Latitud del marcador
     */
    public $lat = false;
    /**
     * @var bool|int Longitud del marcador
     */
    public $lng = false;
    /**
     * @var int Ancho de la imagen a generar
     */
    public $width = 100;
    /**
     * @var int Alto de la imagen a generar
     */
    public $height = 100;
    /**
     * @var int|bool Zoom del mapa
     */
    public $zoom = false;
    /**
     * @var string Color del marcador: black, brown, green, purple, yellow, bluye, gray, orange, red, white o 24bits (0xFFFFCC)
     */
    public $labelColor = 'green';
    /**
     * @var string Caracter del marcador (mayusculas)
     */
    public $label = 'P';
    /**
     * @var string tamaño del marcador: tiny, mid, small
     */
    public $size = 'mid';
    /**
     * @var array Marcadores adicionales.
     * Cada marcador debe tener obligatoriamente los atributos `lat` y `lng`, y opcionalmente `color` y `label`
     */
    public $markers = [];
    /**
     * @var string Url base que genera el mapa
     */
    public $apiUrl = 'http://maps.googleapis.com/maps/api/staticmap';

    public function init()
    {
        if (!$this->lat || !$this->lng || !$this->apiKey) {
            throw new Exception("El widget StaticMap requiere los parametros lat, lng y apiKey");
        }
    }

    public function run()
    {
        $url = $this->apiUrl;
        $urlParts = [];
        $urlParts[] = "markers=color:{$this->labelColor}%7Clabel:{$this->label}%7Csize:{$this->size}%7C{$this->lat},{$this->lng}";
        if ($this->zoom) {
            $urlParts[] = "zoom={$this->zoom}";
        }
        $urlParts[] = "size={$this->width}x{$this->height}";
        $urlParts[] = "key=" . $this->apiKey;
        foreach ($this->markers as $marker) {
            $color = ArrayHelper::getValue($marker, 'color', 'red');
            $label = ArrayHelper::getValue($marker, 'label', 'P');
            $lat = ArrayHelper::getValue($marker, 'lat');
            $lng = ArrayHelper::getValue($marker, 'lng');
            if ($lat && $lng) {
                $urlParts[] = "markers=color:{$color}%7Clabel:{$label}%7C{$lat},{$lng}";
            }
        }
        $url .= "?" . implode("&", $urlParts);
        return Html::img($url);
    }
}