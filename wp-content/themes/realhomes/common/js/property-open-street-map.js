/**
 * Javascript to handle open street map for property single page.
 */
( function ( $ ) {
    "use strict";

    const mapContainer = document.getElementById( "property_map" );

    if ( typeof propertyMapData === "undefined" && mapContainer === null ) {
        return;
    }

    if ( ! propertyMapData.lat && ! propertyMapData.lng ) {
        return;
    }

    var tileLayer = L.tileLayer( 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution : '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    } );

    // Basic map
    var mapCenter = L.latLng( propertyMapData.lat, propertyMapData.lng );
    var mapZoom   = 16;

    // zoom
    if ( propertyMapData.zoom > 0 ) {
        mapZoom = parseInt( propertyMapData.zoom );
    }

    var mapOptions = {
        center : mapCenter,
        zoom   : mapZoom
    };

    var propertyMap = L.map( 'property_map', mapOptions );
    propertyMap.scrollWheelZoom.disable();
    propertyMap.addLayer( tileLayer );

    // Marker
    var markerOptions = {
        riseOnHover : true
    };

    if ( propertyMapData.title ) {
        markerOptions.title = propertyMapData.title;
    }

    if ( propertyMapData.marker_type === 'circle' ) {
        var propertyMarker = new L.Circle( mapCenter, 120, {
            fillColor   : propertyMapData.marker_color,
            color       : propertyMapData.marker_color,
            weight      : 2,
            fillOpacity : 0.6,
            opacity     : 0.6
        } );

        propertyMap.addLayer( propertyMarker );

    } else {
        // Marker icon
        if ( propertyMapData.icon ) {
            var iconOptions = {
                iconUrl     : propertyMapData.icon,
                iconSize    : [42, 57],
                iconAnchor  : [20, 57],
                popupAnchor : [1, -57]
            };

            if ( propertyMapData.retinaIcon ) {
                iconOptions.iconRetinaUrl = propertyMapData.retinaIcon;
            }

            markerOptions.icon = L.icon( iconOptions );
        }

        var propertyMarker = L.marker( mapCenter, markerOptions ).addTo( propertyMap );
    }

    // Marker popup
    var popupContent        = "",
        popupContentWrapper = document.createElement( "div" );

    popupContentWrapper.className = 'osm-popup-content';

    if ( propertyMapData.thumb ) {
        popupContent += '<img class="osm-popup-thumb" src="' + propertyMapData.thumb + '" alt="' + propertyMapData.title + '"/>';
    }

    if ( rhMapsData.isUltra ) {
        popupContent += '<div class="osm-detail-wrapper">';

        if ( propertyMapData.title ) {
            popupContent += '<h5 class="osm-popup-title">' + propertyMapData.title + '</h5>';
        }

        popupContent += '<div>';

        if ( propertyMapData.propertyType ) {
            popupContent += '<span class="type">' + propertyMapData.propertyType + '</span>';
        }

        if ( propertyMapData.price ) {
            popupContent += '<p><span class="osm-popup-price">' + propertyMapData.price + '</span></p>';
        }

        popupContent += '</div>';
        popupContent += '</div>';

    } else {
        if ( propertyMapData.title ) {
            popupContent += '<h5 class="osm-popup-title">' + propertyMapData.title + '</h5>';
        }

        if ( propertyMapData.price ) {
            popupContent += '<p><span class="osm-popup-price">' + propertyMapData.price + '</span></p>';
        }
    }

    popupContentWrapper.innerHTML = popupContent;
    propertyMarker.bindPopup( popupContentWrapper );

} )( jQuery );