/**
 * Javascript to handle Google Map for contact pages.
 */
( function ( $ ) {
    "use strict";

    const mapContainer = document.getElementById( "map_canvas" );

    if ( typeof contactMapData === "undefined" && mapContainer === null ) {
        return;
    }

    if ( ! contactMapData.lat && ! contactMapData.lng ) {
        return;
    }

    const officeLocation = new google.maps.LatLng( parseFloat( contactMapData.lat ), parseFloat( contactMapData.lng ) );

    let styles,
        mapZoom = 14;

    if ( contactMapData.zoom ) {
        mapZoom = parseInt( contactMapData.zoom );
    }

    if ( rhMapsData.isUltra ) {
        styles = [{
            "featureType" : "landscape.natural",
            "stylers"     : [{ "color" : "#bcddff" }]
        }, {
            "featureType" : "road.highway",
            "elementType" : "geometry.fill",
            "stylers"     : [{ "color" : "#5fb3ff" }]
        }, {
            "featureType" : "road.arterial",
            "stylers"     : [{ "color" : "#ebf4ff" }]
        }, {
            "featureType" : "road.local",
            "elementType" : "geometry.fill",
            "stylers"     : [{ "color" : "#ebf4ff" }]
        }, {
            "featureType" : "road.local",
            "elementType" : "geometry.stroke",
            "stylers"     : [{ "visibility" : "on" }, { "color" : "#93c8ff" }]
        }, {
            "featureType" : "landscape.man_made",
            "elementType" : "geometry",
            "stylers"     : [{ "color" : "#c7e2ff" }]
        }, {
            "featureType" : "transit.station.airport",
            "elementType" : "geometry",
            "stylers"     : [{ "saturation" : 100 }, { "gamma" : 0.82 }, { "hue" : "#0088ff" }]
        }, {
            "elementType" : "labels.text.fill",
            "stylers"     : [{ "color" : "#1673cb" }]
        }, {
            "featureType" : "road.highway",
            "elementType" : "labels.icon",
            "stylers"     : [{ "saturation" : 58 }, { "hue" : "#006eff" }]
        }, {
            "featureType" : "poi",
            "elementType" : "geometry",
            "stylers"     : [{ "color" : "#4797e0" }]
        }, {
            "featureType" : "poi.park",
            "elementType" : "geometry",
            "stylers"     : [{ "color" : "#209ee1" }, { "lightness" : 49 }]
        }, {
            "featureType" : "transit.line",
            "elementType" : "geometry.fill",
            "stylers"     : [{ "color" : "#83befc" }]
        }, {
            "featureType" : "road.highway",
            "elementType" : "geometry.stroke",
            "stylers"     : [{ "color" : "#3ea3ff" }]
        }, {
            "featureType" : "administrative",
            "elementType" : "geometry.stroke",
            "stylers"     : [{ "saturation" : 86 }, { "hue" : "#0077ff" }, { "weight" : 0.8 }]
        }, {
            "elementType" : "labels.icon",
            "stylers"     : [{ "hue" : "#0066ff" }, { "weight" : 1.9 }]
        }, {
            "featureType" : "poi",
            "elementType" : "geometry.fill",
            "stylers"     : [{ "hue" : "#0077ff" }, { "saturation" : -7 }, { "lightness" : 24 }]
        }, {
            "featureType" : "water",
            "elementType" : "all",
            "stylers"     : [{
                "color" : contactMapData.mapWaterColor
            }, {
                "visibility" : "on"
            }]
        }];

    } else if ( rhMapsData.isModern ) {
        styles = [{
            "featureType" : "administrative",
            "elementType" : "labels.text",
            "stylers"     : [{
                "color" : "#000000"
            }]
        }, {
            "featureType" : "administrative",
            "elementType" : "labels.text.fill",
            "stylers"     : [{
                "color" : "#444444"
            }]
        }, {
            "featureType" : "administrative",
            "elementType" : "labels.text.stroke",
            "stylers"     : [{
                "visibility" : "off"
            }]
        }, {
            "featureType" : "administrative",
            "elementType" : "labels.icon",
            "stylers"     : [{
                "visibility" : "on"
            }, {
                "color" : "#380d0d"
            }]
        }, {
            "featureType" : "landscape",
            "elementType" : "all",
            "stylers"     : [{
                "color" : "#f2f2f2"
            }]
        }, {
            "featureType" : "poi",
            "elementType" : "all",
            "stylers"     : [{
                "visibility" : "off"
            }]
        }, {
            "featureType" : "road",
            "elementType" : "all",
            "stylers"     : [{
                "saturation" : -100
            }, {
                "lightness" : 45
            }]
        }, {
            "featureType" : "road",
            "elementType" : "geometry",
            "stylers"     : [{
                "visibility" : "on"
            }, {
                "color" : "#dedddb"
            }]
        }, {
            "featureType" : "road",
            "elementType" : "labels.text",
            "stylers"     : [{
                "color" : "#000000"
            }]
        }, {
            "featureType" : "road",
            "elementType" : "labels.text.fill",
            "stylers"     : [{
                "color" : "#1f1b1b"
            }]
        }, {
            "featureType" : "road",
            "elementType" : "labels.text.stroke",
            "stylers"     : [{
                "visibility" : "off"
            }]
        }, {
            "featureType" : "road",
            "elementType" : "labels.icon",
            "stylers"     : [{
                "visibility" : "on"
            }, {
                "hue" : "#ff0000"
            }]
        }, {
            "featureType" : "road.highway",
            "elementType" : "all",
            "stylers"     : [{
                "visibility" : "simplified"
            }]
        }, {
            "featureType" : "road.arterial",
            "elementType" : "labels.icon",
            "stylers"     : [{
                "visibility" : "off"
            }]
        }, {
            "featureType" : "transit",
            "elementType" : "all",
            "stylers"     : [{
                "visibility" : "off"
            }]
        }, {
            "featureType" : "water",
            "elementType" : "all",
            "stylers"     : [{
                "color" : contactMapData.mapWaterColor
            }, {
                "visibility" : "on"
            }]
        }];
    }

    const contactMapOptions = {
        center      : officeLocation,
        zoom        : mapZoom,
        scrollwheel : false,
        styles      : styles
    };

    // Map Styles
    if ( undefined !== contactMapData.styles ) {
        contactMapOptions.styles = JSON.parse( contactMapData.styles );
    }

    // Setting Google Map Type
    switch ( contactMapData.type ) {
        case 'satellite':
            contactMapOptions.mapTypeId = google.maps.MapTypeId.SATELLITE;
            break;
        case 'hybrid':
            contactMapOptions.mapTypeId = google.maps.MapTypeId.HYBRID;
            break;
        case 'terrain':
            contactMapOptions.mapTypeId = google.maps.MapTypeId.TERRAIN;
            break;
        default:
            contactMapOptions.mapTypeId = google.maps.MapTypeId.ROADMAP;
    }

    let iconURL = "";
    if ( contactMapData.iconURL ) {
        iconURL = contactMapData.iconURL;
    }

    const contactMap    = new google.maps.Map( mapContainer, contactMapOptions ),
          contactMarker = new google.maps.Marker( {
              position : officeLocation,
              map      : contactMap,
              icon     : iconURL
          } );

} )( jQuery );