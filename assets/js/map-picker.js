var MapPicker = function (config) {
    this.map = false;
    this.marker = false;
    this.geocoder = false;
    this.config = config;
    var $body = $('body');
    var $error = $('.'+config.error);
    var $latField = $('.'+config.latField);
    var $lngField = $('.'+config.lngField);
    var $searchInput = $('.'+config.searchField.inputClass);
    var $searchButton = $('.'+config.searchField.buttonClass);
    var errorMsg = '';
    var that = this;
    this.init = function () {
        this.updateFields = config.updateFields
        this.map = new google.maps.Map(document.getElementById(this.config.mapId));
        this.map.setOptions(this.config.mapOptions);
        this.marker = new google.maps.Marker({
            map: this.map
        });
        this.geocoder = new google.maps.Geocoder();
        google.maps.event.addListener(this.map, 'click', function (event) {
            that.gmapsSearch(event.latLng.lat() + "," + event.latLng.lng()).then(function (data) {
                var location = data.results[0].geometry.location;
                that.placeMarker(location, false)
            })
        });
    }
    this.placeMarker = function (location, center) {
        that.marker.setPosition(location);
        if (center === undefined || center === true) that.map.setCenter(location);
        $latField.val(location.lat());
        $lngField.val(location.lng());
    }
    this.updateField = function (field, value) {
        if (field.length > 0) {
            field.val(value);
        }
    }
    this.search = function (address) {
        $error.html("");
        if(!this.map) this.init();
        this.gmapsSearch(address).then(function (data) {
            var location = data.results[0].geometry.location;
            that.placeMarker(location)
            that.map.setCenter(location);
        }, function (err) {
            $error.html(that.errorMsg);
        })
    }
    this.gmapsSearch = function (address) {
        var deferred = $.Deferred();
        this.geocoder.geocode({'address': address}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                var components = that.getComponents(results[0]);
                var searchResults ={'results': results, 'components': components}
                deferred.resolve(searchResults)
                $(document).trigger('mappicker-searchresults', searchResults);
            } else {
                deferred.reject(status)
            }
        });
        return deferred.promise();

    }
    this.getComponents = function(location) {
        var components = {};
        for (var i in location.address_components) {
            var component = location.address_components[i];
            var types = component.types;
            for (var typeId in types) {
                if (types[typeId] == 'country') {
                    components.country = component.long_name;
                }
                if (types[typeId] == 'administrative_area_level_1') {
                    components.state = component.long_name;
                }
                if (types[typeId] == 'administrative_area_level_2') {
                    components.province = component.long_name;
                }
                if (types[typeId] == 'locality') {
                    components.locality = component.long_name;
                }
            }
        }
        return components;
    }
    /**
     * Fix for enabling MapPicker in Bootstrap modal
     */
    this.mapsModalFix = function(){
        google.maps.event.trigger(this.map, 'resize');
        this.map.setZoom(this.map.getZoom());
    }
    $body.on('click', '.'+this.config.searchField.buttonClass, function(ev){
        var address = $searchInput.val();
        that.search(address);
        ev.preventDefault();
        return false;
    });

}
