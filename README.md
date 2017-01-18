# yii2-mapspicker-widget
A widget for the Yii2 framework to render a Map with Google Maps and allow the user to pick a location on the map and get the coordinates.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist proximitymad/yii2-mapspicker-widget "1.0.*"
```

or add

```
"proximitymad/yii2-mapspicker-widget": "1.0.*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php 
echo proximitymad\yii2mapspickerwidget\MapPicker::widget([
    'apiKey'=>'gmaps-api-key' //required,
    'search'=>'Madrid, Spain' //required
]);
```

### Required parameters ###

- __apiKey__: The google maps api key, you can get it following the instructions [here](https://developers.google.com/maps/documentation/javascript/get-api-key) (only required when __loadMapApi__ is set to true).
- __search__: The search string to start the map with, for example *Madrid, Spain*. Can be coordinates as well ( *40.4525784,-3.6813066* ).

### Optional parameters ###

- __loadMapApi__: If set to false, it will not load the Google Maps JS API in case you are already including it *default: true*.
- __mapId__: The ID of the map, if it is left empty it will be autogenerated.
- __width__: The width of the map *default: 100*.
- __height__: The height of the map *default: 100*.
- __latFieldClass__: The DOM element class which contains the input field for latitude *default: field-lat*.
- __lngFieldClass__: The DOM element class which contains the input field for longitude *default: field-lng*.
- __errorClass__: The DOM element class which contains the error message in case no results are found *default: search-error*.
- __errorMsg__: The error message to display in case no results are found *default: No results found*.
- __searchField__: 
    - __inputClass__: The input field with the search string *default: search-field*.
    - __buttonClass__: The button to start the search *default: btn-search*.
- __mapOptions__:
    - __zoom__: the zoom to start the map with *default: 16*.
    - __streetViewControl__: Enables or disables street view control or no *default: false*

## Examples ##
```html
<input name='lat' class='my-lat-field'/>
<input name='lng' class='my-lng-field'/>
```
```php
echo \app\components\widgets\Maps\MapPicker::widget([
    'apiKey'=>"my-api-key",
    'width'=>'100%',
    'height'=>300,
    'search'=>"Barcelona, Spain",
    'errorMsg'=>"Ey, your search didn't retrieve any results",
    'latFieldClass'=>"my-lat-field"
    'lngFieldClass'=>"my-lng-field"
]);
```
### Example with search ###
```html
<input name='search' class='my-search-field'/>
<button class='my-button'>Search</button>
```
```php
echo \app\components\widgets\Maps\MapPicker::widget([
    'apiKey'=>"my-api-key",
    'width'=>'100%',
    'height'=>300,
    'search'=>"Barcelona, Spain",
    'errorMsg'=>"Ey, your search didn't retrieve any results",
    'searchField'=>[
        'inputClass'=>'my-search-field',
        'buttonClass'=>'my-button'
    ]    
]);
```

## Events ##
After a search or after picking a place on the map an event is triggered with the search results, the event is __mappicker-searchresults__. The results contains all the results and the components (country, state, province and locality).
#### Example ####
```js
$(document).on('mappicker-searchresults', function(evt, data){
    console.log(data.results);
    console.log(data.components);
})
```
