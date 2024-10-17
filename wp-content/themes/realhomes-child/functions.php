<?php
/**
 * RealHomes Child theme functions.
 *
 * @package realhomes-child
 */

if ( ! function_exists( 'inspiry_enqueue_child_styles' ) ) {
	/**
	 * Enqueue Styles in Child Theme.
	 */
	function inspiry_enqueue_child_styles() {
		if ( ! is_admin() ) {
			// dequeue and deregister parent default css.
			wp_dequeue_style( 'parent-default' );
			wp_deregister_style( 'parent-default' );

			// dequeue parent custom css.
			wp_dequeue_style( 'parent-custom' );

			// parent default css.
			wp_enqueue_style(
				'parent-default',
				get_template_directory_uri() . '/style.css',
				array(),
				'3.12.1'
			);

			// parent custom css.
			wp_enqueue_style( 'parent-custom' );

			// child default css.
			wp_enqueue_style(
				'child-default',
				get_stylesheet_uri(),
				array( 'parent-default' ),
				'1.4.2',
				'all'
			);

			// child custom css.
			wp_enqueue_style(
				'child-custom',
				get_stylesheet_directory_uri() . '/css/child-custom.css',
				array( 'child-default' ),
				'1.4.2',
				'all'
			);

			// child custom js.
			wp_enqueue_script(
				'child-custom',
				get_stylesheet_directory_uri() . '/js/child-custom.js',
				array( 'jquery' ),
				'1.4.2',
				true
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'inspiry_enqueue_child_styles', PHP_INT_MAX );


if ( ! function_exists( 'inspiry_load_translation_from_child' ) ) {
	/**
	 * Load translation files from child theme.
	 */
	function inspiry_load_translation_from_child() {
		load_child_theme_textdomain( 'framework', get_stylesheet_directory() . '/languages' );
	}

	add_action( 'after_setup_theme', 'inspiry_load_translation_from_child' );
}

//properties api to insert data
function insert_property_data($property_data) {
    // Prepare the post data for inserting the property
    $post_data = array(
        'ID' => 4947, // Use the existing post ID to avoid duplication
        'post_title' => $property_data['UnparsedAddress'], // Using the UnparsedAddress as the title
        'post_content' => $property_data['PublicRemarks'], // Description or remarks as the content
        'post_status' => 'publish', // You can set it to 'draft' if you don't want it published immediately
        'post_type' => 'property', // Custom post type for properties
        'post_author' => 1, // Assign the admin (ID = 1) as the author, change if needed
        'post_date' => date('Y-m-d H:i:s', strtotime($property_data['OriginalEntryTimestamp'])), // Use the original entry timestamp
        'post_date_gmt' => gmdate('Y-m-d H:i:s', strtotime($property_data['OriginalEntryTimestamp'])),
        'post_modified' => date('Y-m-d H:i:s', strtotime($property_data['ModificationTimestamp'])),
        'post_modified_gmt' => gmdate('Y-m-d H:i:s', strtotime($property_data['ModificationTimestamp'])),
    );

    // Update the post (instead of insert) to avoid duplication
    $post_id = wp_update_post($post_data);

    if ($post_id) {
        // Now, insert the custom fields into postmeta
		//add_property_meta_data($post_id, $property_data);
		//handle_property_subtype($post_id, $property_data);
		//update_features($post_id, $property_data);
		//update_addition_details($post_id, $property_data);
		insert_property_images($post_id, $property_data['Media']);
    }
}

function add_property_meta_data($post_id, $property_data) {
    // Assuming $post_id is the ID of the property post being created/updated
    // And $property_data is the data array you're working with (e.g., ListingKey, ListPrice, etc.)
    
    // Property ID (e.g., RH-POSTID-property)
    update_post_meta($post_id, 'REAL_HOMES_property_id', 'RH-' . $post_id . '-property');
    
    // Property Address (e.g., Merrick Way, Miami, FL 33134, USA)
    if (isset($property_data['UnparsedAddress'])) {
        update_post_meta($post_id, 'REAL_HOMES_property_address', $property_data['UnparsedAddress']);
    }
    
    // Property Price (e.g., 89000)
    if (isset($property_data['ListPrice'])) {
        update_post_meta($post_id, 'REAL_HOMES_property_price', $property_data['ListPrice']);
    }
    
    // Property Size (e.g., 1.2)
    if (isset($property_data['LotSizeArea'])) {
        update_post_meta($post_id, 'REAL_HOMES_property_size', $property_data['LotSizeArea']);
    }
    
    // Property Size Postfix (e.g., acres)
    if (isset($property_data['LotSizeUnits'])) {
        update_post_meta($post_id, 'REAL_HOMES_property_size_postfix', $property_data['LotSizeUnits']);
    }

	// Property Location (e.g., latitude, longitude, and zoom level)
    if (isset($property_data['Latitude']) && isset($property_data['Longitude'])) {
        // Assuming a default zoom level of 13
        $location_data = $property_data['Latitude'] . ',' . $property_data['Longitude'] . ',13';
        update_post_meta($post_id, 'REAL_HOMES_property_location', $location_data);
    }

	// Property Bedrooms (e.g., 3)
    if (isset($property_data['BedroomsTotal'])) {
        update_post_meta($post_id, 'REAL_HOMES_property_bedrooms', $property_data['BedroomsTotal']);
    }
    
    // Property Bathrooms (e.g., 2)
    if (isset($property_data['BathroomsTotalInteger'])) {
        update_post_meta($post_id, 'REAL_HOMES_property_bathrooms', $property_data['BathroomsTotalInteger']);
    }

	if (isset($property_data['YearBuilt'])) {
        update_post_meta($post_id, 'REAL_HOMES_property_year_built', $property_data['YearBuilt']);
    }

	if (isset($property_data['ParkingTotal'])) {
        update_post_meta($post_id, 'REAL_HOMES_property_garage', $property_data['ParkingTotal']);
    }

	if (isset($property_data['LotSizeDimensions'])) {
        update_post_meta($post_id, 'REAL_HOMES_property_lot_size', $property_data['LotSizeDimensions']);
    }

	if (isset($property_data['LotSizeUnits'])) {
        update_post_meta($post_id, 'REAL_HOMES_property_lot_size_postfix', $property_data['LotSizeUnits']);
    }
}

function update_addition_details($post_id, $property_data){
	$additional_details = array(); // Initialize the array for additional details

	// Lot Size Area
	if (isset($property_data['LotSizeArea']) && !empty($property_data['LotSizeArea'])) {
		$additional_details[] = array('LOT SIZE AREA', $property_data['LotSizeArea']);
	}

	// Lot Size Dimensions
	if (isset($property_data['LotSizeDimensions']) && !empty($property_data['LotSizeDimensions'])) {
		$additional_details[] = array('LOT SIZE DIMENSIONS', $property_data['LotSizeDimensions']);
	}

	// Lot Size Units
	if (isset($property_data['LotSizeUnits']) && !empty($property_data['LotSizeUnits'])) {
		$additional_details[] = array('LOT SIZE UNITS', $property_data['LotSizeUnits']);
	}

	// Road Surface Type
	if (isset($property_data['RoadSurfaceType']) && !empty($property_data['RoadSurfaceType'])) {
		$additional_details[] = array('ROAD SURFACE TYPE', implode(', ', $property_data['RoadSurfaceType']));
	}

	// Current Use
	if (isset($property_data['CurrentUse']) && !empty($property_data['CurrentUse'])) {
		$additional_details[] = array('CURRENT USE', implode(', ', $property_data['CurrentUse']));
	}

	// Possible Use
	if (isset($property_data['PossibleUse']) && !empty($property_data['PossibleUse'])) {
		$additional_details[] = array('POSSIBLE USE', implode(', ', $property_data['PossibleUse']));
	}

	// Waterfront Features
	if (isset($property_data['WaterfrontFeatures']) && !empty($property_data['WaterfrontFeatures'])) {
		$additional_details[] = array('WATERFRONT FEATURES', implode(', ', $property_data['WaterfrontFeatures']));
	}

	// Total Actual Rent
	if (isset($property_data['TotalActualRent']) && !empty($property_data['TotalActualRent'])) {
		$additional_details[] = array('TOTAL ACTUAL RENT', $property_data['TotalActualRent']);
	}

	// Existing Lease Type
	if (isset($property_data['ExistingLeaseType']) && !empty($property_data['ExistingLeaseType'])) {
		$additional_details[] = array('EXISTING LEASE TYPE', implode(', ', $property_data['ExistingLeaseType']));
	}

	// Association Fee
	if (isset($property_data['AssociationFee']) && !empty($property_data['AssociationFee'])) {
		$additional_details[] = array('ASSOCIATION FEE', $property_data['AssociationFee']);
	}

	// Association Fee Frequency
	if (isset($property_data['AssociationFeeFrequency']) && !empty($property_data['AssociationFeeFrequency'])) {
		$additional_details[] = array('ASSOCIATION FEE FREQUENCY', $property_data['AssociationFeeFrequency']);
	}

	// Original Entry Timestamp
	if (isset($property_data['OriginalEntryTimestamp']) && !empty($property_data['OriginalEntryTimestamp'])) {
		$additional_details[] = array('ORIGINAL ENTRY TIMESTAMP', $property_data['OriginalEntryTimestamp']);
	}

	// Modification Timestamp
	if (isset($property_data['ModificationTimestamp']) && !empty($property_data['ModificationTimestamp'])) {
		$additional_details[] = array('MODIFICATION TIMESTAMP', $property_data['ModificationTimestamp']);
	}

	// List Price
	if (isset($property_data['ListPrice']) && !empty($property_data['ListPrice'])) {
		$additional_details[] = array('LIST PRICE', $property_data['ListPrice']);
	}

	// Year Built
	if (isset($property_data['YearBuilt']) && !empty($property_data['YearBuilt'])) {
		$additional_details[] = array('YEAR BUILT', $property_data['YearBuilt']);
	}

	// Bathrooms Partial
	if (isset($property_data['BathroomsPartial']) && !empty($property_data['BathroomsPartial'])) {
		$additional_details[] = array('BATHROOMS PARTIAL', $property_data['BathroomsPartial']);
	}

	// Bathrooms Total
	if (isset($property_data['BathroomsTotalInteger']) && !empty($property_data['BathroomsTotalInteger'])) {
		$additional_details[] = array('BATHROOMS TOTAL', $property_data['BathroomsTotalInteger']);
	}

	// Bedrooms Total
	if (isset($property_data['BedroomsTotal']) && !empty($property_data['BedroomsTotal'])) {
		$additional_details[] = array('BEDROOMS TOTAL', $property_data['BedroomsTotal']);
	}

	// Living Area
	if (isset($property_data['LivingArea']) && !empty($property_data['LivingArea'])) {
		$additional_details[] = array('LIVING AREA', $property_data['LivingArea']);
	}

	// Living Area Units
	if (isset($property_data['LivingAreaUnits']) && !empty($property_data['LivingAreaUnits'])) {
		$additional_details[] = array('LIVING AREA UNITS', $property_data['LivingAreaUnits']);
	}

	// Parking Total
	if (isset($property_data['ParkingTotal']) && !empty($property_data['ParkingTotal'])) {
		$additional_details[] = array('PARKING TOTAL', $property_data['ParkingTotal']);
	}

	// Stories
	if (isset($property_data['Stories']) && !empty($property_data['Stories'])) {
		$additional_details[] = array('STORIES', $property_data['Stories']);
	}

	// Property Attached YN
	if (isset($property_data['PropertyAttachedYN']) && !empty($property_data['PropertyAttachedYN'])) {
		$additional_details[] = array('PROPERTY ATTACHED YN', $property_data['PropertyAttachedYN'] ? 'Yes' : 'No');
	}

	// Tax Annual Amount
	if (isset($property_data['TaxAnnualAmount']) && !empty($property_data['TaxAnnualAmount'])) {
		$additional_details[] = array('TAX ANNUAL AMOUNT', $property_data['TaxAnnualAmount']);
	}

	// Parcel Number
	if (isset($property_data['ParcelNumber']) && !empty($property_data['ParcelNumber'])) {
		$additional_details[] = array('PARCEL NUMBER', $property_data['ParcelNumber']);
	}

	update_post_meta($post_id, 'REAL_HOMES_additional_details_list', $additional_details);
}

function update_features($post_id, $property_data){
	// Add dynamic keys based on the provided data
	if (isset($property_data['LotFeatures']) && is_array($property_data['LotFeatures'])) {
		global $wpdb;

		foreach ($property_data['LotFeatures'] as $feature) {
			$feature_slug = sanitize_title($feature);
			$term_id = check_and_insert_term($feature, $feature_slug);

			// Check if the relationship already exists before inserting
			$relationship = $wpdb->get_row($wpdb->prepare("
				SELECT * FROM `{$wpdb->prefix}term_relationships` 
				WHERE `object_id` = %d 
				AND `term_taxonomy_id` = %d
				LIMIT 1", $post_id, $term_id
			));

			if (!$relationship) {
				$wpdb->insert(
					"{$wpdb->prefix}term_relationships",
					array(
						'object_id' => $post_id,
						'term_taxonomy_id' => $term_id
					),
					array(
						'%d',
						'%d'
					)
				);
			}
		}
	}

	if (isset($property_data['CommunityFeatures']) && is_array($property_data['CommunityFeatures'])) {
		global $wpdb;

		foreach ($property_data['CommunityFeatures'] as $feature) {
			$feature_slug = sanitize_title($feature);
			$term_id = check_and_insert_term($feature, $feature_slug);

			$relationship = $wpdb->get_row($wpdb->prepare("
				SELECT * FROM `{$wpdb->prefix}term_relationships` 
				WHERE `object_id` = %d 
				AND `term_taxonomy_id` = %d
				LIMIT 1", $post_id, $term_id
			));

			if (!$relationship) {
				$wpdb->insert(
					"{$wpdb->prefix}term_relationships",
					array(
						'object_id' => $post_id,
						'term_taxonomy_id' => $term_id
					),
					array(
						'%d',
						'%d'
					)
				);
			}
		}
	}

	if (isset($property_data['Appliances']) && is_array($property_data['Appliances'])) {
		global $wpdb;

		foreach ($property_data['Appliances'] as $feature) {
			$feature_slug = sanitize_title($feature);
			$term_id = check_and_insert_term($feature, $feature_slug);

			$relationship = $wpdb->get_row($wpdb->prepare("
				SELECT * FROM `{$wpdb->prefix}term_relationships` 
				WHERE `object_id` = %d 
				AND `term_taxonomy_id` = %d
				LIMIT 1", $post_id, $term_id
			));

			if (!$relationship) {
				$wpdb->insert(
					"{$wpdb->prefix}term_relationships",
					array(
						'object_id' => $post_id,
						'term_taxonomy_id' => $term_id
					),
					array(
						'%d',
						'%d'
					)
				);
			}
		}
	}

	if (isset($property_data['OtherEquipment']) && is_array($property_data['OtherEquipment'])) {
		global $wpdb;

		foreach ($property_data['OtherEquipment'] as $feature) {
			$feature_slug = sanitize_title($feature);
			$term_id = check_and_insert_term($feature, $feature_slug);

			$relationship = $wpdb->get_row($wpdb->prepare("
				SELECT * FROM `{$wpdb->prefix}term_relationships` 
				WHERE `object_id` = %d 
				AND `term_taxonomy_id` = %d
				LIMIT 1", $post_id, $term_id
			));

			if (!$relationship) {
				$wpdb->insert(
					"{$wpdb->prefix}term_relationships",
					array(
						'object_id' => $post_id,
						'term_taxonomy_id' => $term_id
					),
					array(
						'%d',
						'%d'
					)
				);
			}
		}
	}

	if (isset($property_data['SecurityFeatures']) && is_array($property_data['SecurityFeatures'])) {
		global $wpdb;

		foreach ($property_data['SecurityFeatures'] as $feature) {
			$feature_slug = sanitize_title($feature);
			$term_id = check_and_insert_term($feature, $feature_slug);

			$relationship = $wpdb->get_row($wpdb->prepare("
				SELECT * FROM `{$wpdb->prefix}term_relationships` 
				WHERE `object_id` = %d 
				AND `term_taxonomy_id` = %d
				LIMIT 1", $post_id, $term_id
			));

			if (!$relationship) {
				$wpdb->insert(
					"{$wpdb->prefix}term_relationships",
					array(
						'object_id' => $post_id,
						'term_taxonomy_id' => $term_id
					),
					array(
						'%d',
						'%d'
					)
				);
			}
		}
	}

	if (isset($property_data['AssociationFeeIncludes']) && is_array($property_data['AssociationFeeIncludes'])) {
		global $wpdb;

		foreach ($property_data['AssociationFeeIncludes'] as $feature) {
			$feature_slug = sanitize_title($feature);
			$term_id = check_and_insert_term($feature, $feature_slug);

			$relationship = $wpdb->get_row($wpdb->prepare("
				SELECT * FROM `{$wpdb->prefix}term_relationships` 
				WHERE `object_id` = %d 
				AND `term_taxonomy_id` = %d
				LIMIT 1", $post_id, $term_id
			));

			if (!$relationship) {
				$wpdb->insert(
					"{$wpdb->prefix}term_relationships",
					array(
						'object_id' => $post_id,
						'term_taxonomy_id' => $term_id
					),
					array(
						'%d',
						'%d'
					)
				);
			}
		}
	}

	if (isset($property_data['BuildingFeatures']) && is_array($property_data['BuildingFeatures'])) {
		global $wpdb;

		foreach ($property_data['BuildingFeatures'] as $feature) {
			$feature_slug = sanitize_title($feature);
			$term_id = check_and_insert_term($feature, $feature_slug);

			$relationship = $wpdb->get_row($wpdb->prepare("
				SELECT * FROM `{$wpdb->prefix}term_relationships` 
				WHERE `object_id` = %d 
				AND `term_taxonomy_id` = %d
				LIMIT 1", $post_id, $term_id
			));

			if (!$relationship) {
				$wpdb->insert(
					"{$wpdb->prefix}term_relationships",
					array(
						'object_id' => $post_id,
						'term_taxonomy_id' => $term_id
					),
					array(
						'%d',
						'%d'
					)
				);
			}
		}
	}

	if (isset($property_data['ArchitecturalStyle']) && is_array($property_data['ArchitecturalStyle'])) {
		global $wpdb;

		foreach ($property_data['ArchitecturalStyle'] as $feature) {
			$feature_slug = sanitize_title($feature);
			$term_id = check_and_insert_term($feature, $feature_slug);

			$relationship = $wpdb->get_row($wpdb->prepare("
				SELECT * FROM `{$wpdb->prefix}term_relationships` 
				WHERE `object_id` = %d 
				AND `term_taxonomy_id` = %d
				LIMIT 1", $post_id, $term_id
			));

			if (!$relationship) {
				$wpdb->insert(
					"{$wpdb->prefix}term_relationships",
					array(
						'object_id' => $post_id,
						'term_taxonomy_id' => $term_id
					),
					array(
						'%d',
						'%d'
					)
				);
			}
		}
	}

	if (isset($property_data['Heating']) && is_array($property_data['Heating'])) {
		global $wpdb;

		foreach ($property_data['Heating'] as $feature) {
			$feature_slug = sanitize_title($feature);
			$term_id = check_and_insert_term($feature, $feature_slug);

			$relationship = $wpdb->get_row($wpdb->prepare("
				SELECT * FROM `{$wpdb->prefix}term_relationships` 
				WHERE `object_id` = %d 
				AND `term_taxonomy_id` = %d
				LIMIT 1", $post_id, $term_id
			));

			if (!$relationship) {
				$wpdb->insert(
					"{$wpdb->prefix}term_relationships",
					array(
						'object_id' => $post_id,
						'term_taxonomy_id' => $term_id
					),
					array(
						'%d',
						'%d'
					)
				);
			}
		}
	}

	if (isset($property_data['Basement']) && is_array($property_data['Basement'])) {
		global $wpdb;

		foreach ($property_data['Basement'] as $feature) {
			$feature_slug = sanitize_title($feature);
			$term_id = check_and_insert_term($feature, $feature_slug);

			$relationship = $wpdb->get_row($wpdb->prepare("
				SELECT * FROM `{$wpdb->prefix}term_relationships` 
				WHERE `object_id` = %d 
				AND `term_taxonomy_id` = %d
				LIMIT 1", $post_id, $term_id
			));

			if (!$relationship) {
				$wpdb->insert(
					"{$wpdb->prefix}term_relationships",
					array(
						'object_id' => $post_id,
						'term_taxonomy_id' => $term_id
					),
					array(
						'%d',
						'%d'
					)
				);
			}
		}
	}

	if (isset($property_data['ExteriorFeatures']) && is_array($property_data['ExteriorFeatures'])) {
		global $wpdb;

		foreach ($property_data['ExteriorFeatures'] as $feature) {
			$feature_slug = sanitize_title($feature);
			$term_id = check_and_insert_term($feature, $feature_slug);

			$relationship = $wpdb->get_row($wpdb->prepare("
				SELECT * FROM `{$wpdb->prefix}term_relationships` 
				WHERE `object_id` = %d 
				AND `term_taxonomy_id` = %d
				LIMIT 1", $post_id, $term_id
			));

			if (!$relationship) {
				$wpdb->insert(
					"{$wpdb->prefix}term_relationships",
					array(
						'object_id' => $post_id,
						'term_taxonomy_id' => $term_id
					),
					array(
						'%d',
						'%d'
					)
				);
			}
		}
	}

	if (isset($property_data['FoundationDetails']) && is_array($property_data['FoundationDetails'])) {
		global $wpdb;

		foreach ($property_data['FoundationDetails'] as $feature) {
			$feature_slug = sanitize_title($feature);
			$term_id = check_and_insert_term($feature, $feature_slug);

			$relationship = $wpdb->get_row($wpdb->prepare("
				SELECT * FROM `{$wpdb->prefix}term_relationships` 
				WHERE `object_id` = %d 
				AND `term_taxonomy_id` = %d
				LIMIT 1", $post_id, $term_id
			));

			if (!$relationship) {
				$wpdb->insert(
					"{$wpdb->prefix}term_relationships",
					array(
						'object_id' => $post_id,
						'term_taxonomy_id' => $term_id
					),
					array(
						'%d',
						'%d'
					)
				);
			}
		}
	}

	if (isset($property_data['ParkingFeatures']) && is_array($property_data['ParkingFeatures'])) {
		global $wpdb;

		foreach ($property_data['ParkingFeatures'] as $feature) {
			$feature_slug = sanitize_title($feature);
			$term_id = check_and_insert_term($feature, $feature_slug);

			$relationship = $wpdb->get_row($wpdb->prepare("
				SELECT * FROM `{$wpdb->prefix}term_relationships` 
				WHERE `object_id` = %d 
				AND `term_taxonomy_id` = %d
				LIMIT 1", $post_id, $term_id
			));

			if (!$relationship) {
				$wpdb->insert(
					"{$wpdb->prefix}term_relationships",
					array(
						'object_id' => $post_id,
						'term_taxonomy_id' => $term_id
					),
					array(
						'%d',
						'%d'
					)
				);
			}
		}
	}

	if (isset($property_data['StructureType']) && is_array($property_data['StructureType'])) {
		global $wpdb;

		foreach ($property_data['StructureType'] as $feature) {
			$feature_slug = sanitize_title($feature);
			$term_id = check_and_insert_term($feature, $feature_slug);

			$relationship = $wpdb->get_row($wpdb->prepare("
				SELECT * FROM `{$wpdb->prefix}term_relationships` 
				WHERE `object_id` = %d 
				AND `term_taxonomy_id` = %d
				LIMIT 1", $post_id, $term_id
			));

			if (!$relationship) {
				$wpdb->insert(
					"{$wpdb->prefix}term_relationships",
					array(
						'object_id' => $post_id,
						'term_taxonomy_id' => $term_id
					),
					array(
						'%d',
						'%d'
					)
				);
			}
		}
	}

	if (isset($property_data['WaterSource']) && is_array($property_data['WaterSource'])) {
		global $wpdb;

		foreach ($property_data['WaterSource'] as $feature) {
			$feature_slug = sanitize_title($feature);
			$term_id = check_and_insert_term($feature, $feature_slug);

			$relationship = $wpdb->get_row($wpdb->prepare("
				SELECT * FROM `{$wpdb->prefix}term_relationships` 
				WHERE `object_id` = %d 
				AND `term_taxonomy_id` = %d
				LIMIT 1", $post_id, $term_id
			));

			if (!$relationship) {
				$wpdb->insert(
					"{$wpdb->prefix}term_relationships",
					array(
						'object_id' => $post_id,
						'term_taxonomy_id' => $term_id
					),
					array(
						'%d',
						'%d'
					)
				);
			}
		}
	}

	if (isset($property_data['Utilities']) && is_array($property_data['Utilities'])) {
		global $wpdb;

		foreach ($property_data['Utilities'] as $feature) {
			$feature_slug = sanitize_title($feature);
			$term_id = check_and_insert_term($feature, $feature_slug);

			$relationship = $wpdb->get_row($wpdb->prepare("
				SELECT * FROM `{$wpdb->prefix}term_relationships` 
				WHERE `object_id` = %d 
				AND `term_taxonomy_id` = %d
				LIMIT 1", $post_id, $term_id
			));

			if (!$relationship) {
				$wpdb->insert(
					"{$wpdb->prefix}term_relationships",
					array(
						'object_id' => $post_id,
						'term_taxonomy_id' => $term_id
					),
					array(
						'%d',
						'%d'
					)
				);
			}
		}
	}
}

function check_and_insert_term($term_name, $term_slug, $taxonomy = 'property-feature') {
    global $wpdb;

    // Check if the term already exists in the terms table
    $term = $wpdb->get_row($wpdb->prepare("
        SELECT * FROM `{$wpdb->prefix}terms` 
        WHERE `name` = %s 
        LIMIT 1", $term_name
    ));
    
    if (!$term) {
        // Term does not exist, insert it
        $wpdb->insert("{$wpdb->prefix}terms", array(
            'name' => $term_name,
            'slug' => $term_slug,
            'term_group' => 0 // Assuming no term group
        ));
        
        // Get the newly inserted term_id
        $term_id = $wpdb->insert_id;

        // Insert into term_taxonomy table
        $wpdb->insert("{$wpdb->prefix}term_taxonomy", array(
            'term_id' => $term_id,
            'taxonomy' => $taxonomy,
            'description' => '',
            'parent' => 0,
            'count' => 0
        ));
        
        $term_taxonomy_id = $wpdb->insert_id;
    } else {
        // Term already exists, get its term_id and term_taxonomy_id
        $term_id = $term->term_id;
        $term_taxonomy = $wpdb->get_row($wpdb->prepare("
            SELECT * FROM `{$wpdb->prefix}term_taxonomy` 
            WHERE `term_id` = %d 
            AND `taxonomy` = %s 
            LIMIT 1", $term_id, $taxonomy
        ));
        
        $term_taxonomy_id = $term_taxonomy->term_taxonomy_id;
    }

    return $term_taxonomy_id; // Return term_taxonomy_id to be used in the relationship
}

function insert_property_images( $post_id, $media_array ) {
    if ( ! empty( $media_array ) && is_array( $media_array ) ) {
        // First, delete all old images associated with the post from the media library and meta table
        $existing_images = get_post_meta( $post_id, 'REAL_HOMES_property_images', true );

        if ( ! empty( $existing_images ) && is_array( $existing_images ) ) {
            foreach ( $existing_images as $image_id ) {
                // Delete the image attachment from media library
                wp_delete_attachment( $image_id, true );
            }
        }

        // Reset the REAL_HOMES_property_images meta key
        delete_post_meta( $post_id, 'REAL_HOMES_property_images' );
		

        // Ensure media functions are available
        if ( ! function_exists( 'media_sideload_image' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            require_once( ABSPATH . 'wp-admin/includes/media.php' );
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
        }

		//echo "<pre>"; print_r($media_array); die;
        // Loop through each media item in the array and insert new images
        foreach ( $media_array as $index => $media_item ) {
            if ( isset( $media_item['MediaURL'] ) && ! empty( $media_item['MediaURL'] ) ) {
                $image_url = $media_item['MediaURL'];

                // Download and attach the image to the post
                $image_id = media_sideload_image( $image_url, $post_id, null, 'id' );

                // If successfully downloaded, insert it into the post meta
                if ( ! is_wp_error( $image_id ) ) {
                    // Insert image ID into the REAL_HOMES_property_images meta key
                    add_post_meta( $post_id, 'REAL_HOMES_property_images', $image_id );

                    // Set the first image as the featured image
                    if ( $index === 0 ) {
                        set_post_thumbnail( $post_id, $image_id );
                    }
                }
            }
        }
    }
}

function handle_property_subtype($post_id, $property_data) {
    global $wpdb;
    
    // Make sure PropertySubType exists in $property_data
    if (isset($property_data['PropertySubType'])) {
        $property_subtype = $property_data['PropertySubType'];

        // Check if the PropertySubType already exists in the bv_terms table
        $term = $wpdb->get_row($wpdb->prepare("
            SELECT term_id FROM bv_terms WHERE name = %s
        ", $property_subtype));

        // If the term does not exist, insert it
        if (!$term) {
            $wpdb->insert('bv_terms', [
                'name' => $property_subtype,
                'slug' => sanitize_title($property_subtype),
                'term_group' => 0
            ]);
            // Get the inserted term_id
            $term_id = $wpdb->insert_id;
        } else {
            // If the term exists, get the term_id
            $term_id = $term->term_id;
        }

        // Now, save the term relationship with bv_term_relationships
        $existing_relationship = $wpdb->get_row($wpdb->prepare("
            SELECT * FROM bv_term_relationships WHERE object_id = %d AND term_taxonomy_id = %d
        ", $post_id, $term_id));

        // Insert into bv_term_relationships if the relationship does not exist
        if (!$existing_relationship) {
            $wpdb->insert('bv_term_relationships', [
                'object_id' => $post_id,
                'term_taxonomy_id' => $term_id,
                'term_order' => 0
            ]);
        }
    }
}

// Example usage
$property_data = [
    // Data extracted from the JSON array
	"ListingKey" => "26576486",
    "ListOfficeKey" => "67132",
    "AvailabilityDate" => null,
    "PropertySubType" => "Retail",
    "DocumentsAvailable" => [],
    "LeaseAmount" => null,
    "LeaseAmountFrequency" => null,
    "BusinessType" => [
        "Retail and Wholesale"
    ],
    "WaterBodyName" => null,
    "View" => [],
    "NumberOfBuildings" => null,
    "NumberOfUnitsTotal" => null,
    "LotFeatures" => [],
    "LotSizeArea" => 14810.4000,
    "LotSizeDimensions" => "14810.4",
    "LotSizeUnits" => "square feet",
    "PoolFeatures" => [],
    "RoadSurfaceType" => [],
    "CurrentUse" => [],
    "PossibleUse" => [],
    "AnchorsCoTenants" => null,
    "WaterfrontFeatures" => [],
    "CommunityFeatures" => [],
    "Appliances" => [],
    "OtherEquipment" => [],
    "SecurityFeatures" => [
        "Smoke Detectors"
    ],
    "TotalActualRent" => null,
    "ExistingLeaseType" => [],
    "AssociationFee" => null,
    "AssociationFeeFrequency" => null,
    "AssociationName" => "",
    "AssociationFeeIncludes" => [],
    "OriginalEntryTimestamp" => "2024-03-04T12:36:04.34Z",
    "ModificationTimestamp" => "2024-10-01T20:55:23.5Z",
    "ListingId" => "E4375400",
    "InternetEntireListingDisplayYN" => true,
    "StandardStatus" => "Active",
    "StatusChangeTimestamp" => "2024-10-01T20:46:26.61Z",
    "PublicRemarks" => "Building and Land for Sale. In the Quarters, gorgeous building and iconic location off Jasper Ave, on 4 lots. Commercial mix use building with loading, attached garage, parking lot of 25 parking stalls. Main floor grocery retail, basement for distribution. Top floor residential use with 2 huge family apartment units (One with 1 huge master bedroom + huge den, master bedroom walk-in closet, 1- 4pc ensuite and 1- 4pc bath. Second one with 4 BD, 2- 4pc baths). Both suites have balconies with gas lines for barbecues. Original owners. Custom built in 1991. Perfectly located next to several seniors apartment buildings, newly finished LRT, new high-rises and more residential developments - great foot traffics and transportation. Zoned DC1, and City is going through zoning bylaw renewal, easily change to RA8. Main floor business and its equipment are not included. Tenancy rights are respected. (id:59938)",
    "ListPrice" => 2480000,
    "Inclusions" => null,
    "CoListOfficeKey" => null,
    "CoListAgentKey" => null,
    "ListAgentKey" => "1479015",
    "InternetAddressDisplayYN" => true,
    "ListingURL" => "www.realtor.ca/real-estate/26576486/9516-102-av-nw-edmonton-boyle-street",
    "OriginatingSystemName" => "REALTORS® Association of Edmonton",
    "PhotosCount" => 15,
    "PhotosChangeTimestamp" => "2024-07-19T18:25:52.54Z",
    "CommonInterest" => null,
    "ListAOR" => "Edmonton",
    "UnparsedAddress" => "9516 102 AV NW",
    "PostalCode" => "T5H0E3",
    "SubdivisionName" => null,
    "StateOrProvince" => "Alberta",
    "StreetDirPrefix" => null,
    "StreetDirSuffix" => null,
    "StreetName" => null,
    "StreetNumber" => null,
    "StreetSuffix" => null,
    "UnitNumber" => "",
    "Country" => "Canada",
    "City" => "Edmonton",
    "Directions" => null,
    "Latitude" => 53.5452585142191,
    "Longitude" => -113.47991455582,
    "CityRegion" => "Boyle Street",
    "MapCoordinateVerifiedYN" => true,
    "ParkingTotal" => 25,
    "YearBuilt" => 1959,
    "BathroomsPartial" => null,
    "BathroomsTotalInteger" => 2,
    "BedroomsTotal" => 3,
    "BuildingAreaTotal" => null,
    "BuildingAreaUnits" => null,
    "BuildingFeatures" => [
        "Ceiling - 10ft"
    ],
    "AboveGradeFinishedArea" => null,
    "AboveGradeFinishedAreaUnits" => null,
    "AboveGradeFinishedAreaSource" => null,
    "AboveGradeFinishedAreaMinimum" => null,
    "AboveGradeFinishedAreaMaximum" => null,
    "BelowGradeFinishedArea" => null,
    "BelowGradeFinishedAreaUnits" => null,
    "BelowGradeFinishedAreaSource" => null,
    "BelowGradeFinishedAreaMinimum" => null,
    "BelowGradeFinishedAreaMaximum" => null,
    "LivingArea" => 13000.0000,
    "LivingAreaUnits" => "square feet",
    "LivingAreaSource" => null,
    "LivingAreaMinimum" => null,
    "LivingAreaMaximum" => null,
    "FireplacesTotal" => null,
    "FireplaceYN" => false,
    "FireplaceFeatures" => [],
    "ArchitecturalStyle" => [],
    "Heating" => [
		"Forced air"
	],
    "FoundationDetails" => [],
    "Basement" => [],
    "ExteriorFeatures" => [],
    "Flooring" => [],
    "ParkingFeatures" => [],
    "Cooling" => [],
    "PropertyCondition" => [],
    "Roof" => [],
    "ConstructionMaterials" => [],
    "Stories" => null,
    "PropertyAttachedYN" => null,
    "AccessibilityFeatures" => [],
    "BedroomsAboveGrade" => null,
    "BedroomsBelowGrade" => null,
    "Zoning" => null,
    "ZoningDescription" => null,
    "TaxAnnualAmount" => null,
    "TaxBlock" => null,
    "TaxLot" => null,
    "TaxYear" => null,
    "StructureType" => [],
    "ParcelNumber" => "4202057",
    "Utilities" => [],
    "IrrigationSource" => [],
    "WaterSource" => [],
    "Sewer" => [],
    "Electric" => [],
	"Media" => [
		[
			"MediaKey" => "5739798090",
			"MediaURL" => "https://ddfcdn.realtor.ca/listing/TS638382411481400000/reb84/highres/1/SJ150771_1.jpg"
		],
		[
			"MediaKey" => "5739798108",
			"MediaURL" => "https://ddfcdn.realtor.ca/listing/TS638382411481200000/reb84/highres/1/SJ150771_2.jpg"
		],
		[
			"MediaKey" => "5739798153",
			"MediaURL" => "https://ddfcdn.realtor.ca/listing/TS638382411478400000/reb84/highres/1/SJ150771_3.jpg"
		]
	]
];

//insert_property_data($property_data);

