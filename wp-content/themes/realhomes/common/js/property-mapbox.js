/**
 * Javascript to handle open street map for property single page.
 *
 * @since 3.21.0
 */
( function ( $ ) {
    "use strict";

    let propertyMapBoxContainer = document.getElementById( "property_map" );

    if ( typeof propertyMapData === "undefined" && propertyMapBoxContainer === null ) {
        return;
    }

    let mapboxAPI = propertyMapData.mapboxAPI;

    if ( mapboxAPI === null ) {
        return;
    }

    const mapCenter   = [propertyMapData.lng, propertyMapData.lat],
          mapboxStyle = propertyMapData.mapboxStyle;

    mapboxgl.accessToken = mapboxAPI;
    const propertyMap    = new mapboxgl.Map( {
        attributionControl : false,
        container          : propertyMapBoxContainer,
        style              : mapboxStyle,
        center             : mapCenter,
        zoom               : 12
    } ).addControl( new mapboxgl.AttributionControl( {} ) );

    // Map marker.
    if ( 'pin' === propertyMapData.marker_type && propertyMapData.icon ) {
        // Create DOM element for the marker
        const marker_icon     = document.createElement( 'div' );
        marker_icon.className = 'mapbox-marker';

        const marker_icon_img = document.createElement( 'img' );
        marker_icon_img.src   = propertyMapData.icon;
        marker_icon_img.alt   = propertyMapData.title;
        marker_icon.append( marker_icon_img );

        const propertyMarker = new mapboxgl.Marker( marker_icon, { anchor : 'top' } ).setLngLat( mapCenter ).addTo( propertyMap );

        // Unbinding marker click event
        propertyMarker.unbind( 'click' );

    } else {

        propertyMap.on( 'load', function () {
            // Add a data source containing one point feature
            propertyMap.addSource( 'point', {
                'type' : 'geojson',
                'data' : {
                    'type'     : 'FeatureCollection',
                    'features' : [
                        {
                            'type'     : 'Feature',
                            'geometry' : {
                                'type'        : 'Point',
                                'coordinates' : mapCenter
                            }
                        }
                    ]
                }
            } );

            // Add a circle layer
            propertyMap.addLayer( {
                'id'     : 'circle',
                'type'   : 'circle',
                'source' : 'point',
                'paint'  : {
                    'circle-radius'         : 25,
                    'circle-color'          : propertyMapData.marker_color,
                    'circle-opacity'        : 0.6,
                    'circle-stroke-width'   : 2,
                    'circle-stroke-color'   : propertyMapData.marker_color,
                    'circle-stroke-opacity' : 1
                }
            } );
        } );
    }
} )( jQuery );