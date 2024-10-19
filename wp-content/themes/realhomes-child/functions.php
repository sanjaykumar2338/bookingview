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
    try {
        // Check if a post with this ListingKey already exists
        $existing_post_id = check_for_existing_property($property_data['ListingKey']);

        // Prepare the post data for inserting/updating the property
        $post_data = array(
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

        if ($existing_post_id) {
            // Update the post if it already exists
            $post_data['ID'] = $existing_post_id;
            $post_id = wp_update_post($post_data);
        } else {
            // Insert a new post if it doesn't exist
            $post_id = wp_insert_post($post_data);
        }

        if ($post_id) {
            // Add/update ListingKey in post meta
            update_post_meta($post_id, 'REAL_HOMES_listing_key', $property_data['ListingKey']);

            // Now, insert/update the custom fields into postmeta
            add_property_meta_data($post_id, $property_data);
            handle_property_subtype($post_id, $property_data);
            update_features($post_id, $property_data);
            update_addition_details($post_id, $property_data);
            insert_property_images($post_id, $property_data['Media']);
            handle_property_city($post_id, $property_data);
            handle_property_status($post_id, $property_data);
        }
    } catch (Exception $e) {
        // Define the path to the log folder
        $log_folder = ABSPATH . 'wp-content/uploads/property_logs/';

        // Check if the folder exists; if not, create it
        if (!file_exists($log_folder)) {
            mkdir($log_folder, 0755, true);
        }

        // Create a daily log file
        $log_file = $log_folder . 'property_log_' . date('Y-m-d') . '.log';

        // Log the error in the debug.log file and the custom daily log file
        error_log("Error inserting/updating property with ListingKey: {$property_data['ListingKey']}. Error: " . $e->getMessage());

        // Log the error in the custom daily log file
        file_put_contents($log_file, date('Y-m-d H:i:s') . " - Error with ListingKey {$property_data['ListingKey']}: " . $e->getMessage() . "\n", FILE_APPEND);
    }
}

function handle_property_status($post_id, $property_data) {
    global $wpdb;

    try {
        if (isset($property_data['StandardStatus'])) {
            $status_name = $property_data['StandardStatus'];

            // Check if the status already exists in the terms table
            $term = $wpdb->get_row($wpdb->prepare("
                SELECT term_id FROM {$wpdb->prefix}terms WHERE name = %s
            ", $status_name));

            if (!$term) {
                // The status doesn't exist, so insert it
                $insert_result = $wpdb->insert("{$wpdb->prefix}terms", [
                    'name' => $status_name,
                    'slug' => sanitize_title($status_name),
                    'term_group' => 0
                ]);

                if ($insert_result === false) {
                    throw new Exception("Failed to insert property status into terms table: " . $wpdb->last_error);
                }

                // Get the inserted term_id
                $term_id = $wpdb->insert_id;

                // Insert into the term_taxonomy table for 'property-status'
                $wpdb->insert("{$wpdb->prefix}term_taxonomy", [
                    'term_id' => $term_id,
                    'taxonomy' => 'property-status',
                    'description' => '',
                    'parent' => 0,
                    'count' => 0
                ]);

                if ($wpdb->insert_id === false) {
                    throw new Exception("Failed to insert property status into term_taxonomy: " . $wpdb->last_error);
                }

                $term_taxonomy_id = $wpdb->insert_id;
            } else {
                // If the term exists, get its term_id
                $term_id = $term->term_id;

                // Get the term_taxonomy_id for the 'property-status' taxonomy
                $term_taxonomy = $wpdb->get_row($wpdb->prepare("
                    SELECT term_taxonomy_id FROM {$wpdb->prefix}term_taxonomy 
                    WHERE term_id = %d 
                    AND taxonomy = %s 
                    LIMIT 1", $term_id, 'property-status'
                ));

                if (!$term_taxonomy) {
                    // If term_taxonomy doesn't exist, insert it
                    $wpdb->insert("{$wpdb->prefix}term_taxonomy", [
                        'term_id' => $term_id,
                        'taxonomy' => 'property-status',
                        'description' => '',
                        'parent' => 0,
                        'count' => 0
                    ]);

                    if ($wpdb->insert_id === false) {
                        throw new Exception("Failed to insert property status into term_taxonomy: " . $wpdb->last_error);
                    }

                    $term_taxonomy_id = $wpdb->insert_id;
                } else {
                    $term_taxonomy_id = $term_taxonomy->term_taxonomy_id;
                }
            }

            // Now, save the term relationship with term_relationships
            $existing_relationship = $wpdb->get_row($wpdb->prepare("
                SELECT * FROM {$wpdb->prefix}term_relationships WHERE object_id = %d AND term_taxonomy_id = %d
            ", $post_id, $term_taxonomy_id));

            // Insert into term_relationships if the relationship does not exist
            if (!$existing_relationship) {
                $wpdb->insert("{$wpdb->prefix}term_relationships", [
                    'object_id' => $post_id,
                    'term_taxonomy_id' => $term_taxonomy_id,
                    'term_order' => 0
                ]);

                if ($wpdb->insert_id === false) {
                    throw new Exception("Failed to insert into term_relationships for property status: " . $wpdb->last_error);
                }
            }

        }
    } catch (Exception $e) {
        // Log the exception message for debugging
        error_log("Error in handle_property_status for post_id {$post_id}: " . $e->getMessage());

        // Optional: Write to a custom log file for more detailed tracking
        $log_folder = ABSPATH . 'wp-content/uploads/property_logs/';

        // Ensure the folder exists
        if (!file_exists($log_folder)) {
            mkdir($log_folder, 0755, true);
        }

        // Log into a daily file
        $log_file = $log_folder . 'property_status_log_' . date('Y-m-d') . '.log';
        file_put_contents($log_file, date('Y-m-d H:i:s') . " - Error with post_id {$post_id}: " . $e->getMessage() . "\n", FILE_APPEND);
    }
}

// Helper function to check if a property with the given ListingKey exists
function check_for_existing_property($listing_key) {
    global $wpdb;

    try {
        // Query to find a post ID with the given ListingKey in post meta
        $post_id = $wpdb->get_var($wpdb->prepare("
            SELECT post_id FROM {$wpdb->postmeta}
            WHERE meta_key = 'REAL_HOMES_listing_key'
            AND meta_value = %s
            LIMIT 1
        ", $listing_key));

        // Return the post ID if found, or null if not found
        return $post_id;
        
    } catch (Exception $e) {
        // Log the error message in WordPress default debug log
        error_log('Error checking for existing property: ' . $e->getMessage());

        // Optionally, log it in a custom file for further tracking
        $log_folder = ABSPATH . 'wp-content/uploads/property_logs/';
        
        // Ensure the folder exists
        if (!file_exists($log_folder)) {
            mkdir($log_folder, 0755, true);
        }

        // Create a daily log file
        $log_file = $log_folder . 'property_check_log_' . date('Y-m-d') . '.log';
        file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Error checking for property with ListingKey: ' . $listing_key . ' - ' . $e->getMessage() . "\n", FILE_APPEND);

        // Return null to continue the process without breaking
        return null;
    }
}

function add_property_meta_data($post_id, $property_data) {
    try {
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

        // Property Year Built
        if (isset($property_data['YearBuilt'])) {
            update_post_meta($post_id, 'REAL_HOMES_property_year_built', $property_data['YearBuilt']);
        }

        // Property Garage/Parking Total
        if (isset($property_data['ParkingTotal'])) {
            update_post_meta($post_id, 'REAL_HOMES_property_garage', $property_data['ParkingTotal']);
        }

        // Property Lot Size Dimensions
        if (isset($property_data['LotSizeDimensions'])) {
            update_post_meta($post_id, 'REAL_HOMES_property_lot_size', $property_data['LotSizeDimensions']);
        }

        // Property Lot Size Postfix (e.g., acres, square feet)
        if (isset($property_data['LotSizeUnits'])) {
            update_post_meta($post_id, 'REAL_HOMES_property_lot_size_postfix', $property_data['LotSizeUnits']);
        }

    } catch (Exception $e) {
        // Log the error in the default WordPress debug log
        error_log('Error in add_property_meta_data for post_id ' . $post_id . ': ' . $e->getMessage());

        // Optionally log the error in a custom log file
        $log_folder = ABSPATH . 'wp-content/uploads/property_logs/';
        
        // Ensure the folder exists
        if (!file_exists($log_folder)) {
            mkdir($log_folder, 0755, true);
        }

        // Create a daily log file
        $log_file = $log_folder . 'property_meta_log_' . date('Y-m-d') . '.log';
        file_put_contents($log_file, date('Y-m-d H:i:s') . " - Error adding meta for post_id {$post_id}: " . $e->getMessage() . "\n", FILE_APPEND);
    }
}

function update_addition_details($post_id, $property_data) {
    try {
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

        // Save additional details as post meta
        update_post_meta($post_id, 'REAL_HOMES_additional_details_list', $additional_details);

    } catch (Exception $e) {
        // Log the error in the default WordPress debug log
        error_log('Error in update_addition_details for post_id ' . $post_id . ': ' . $e->getMessage());

        // Optionally log the error in a custom log file
        $log_folder = ABSPATH . 'wp-content/uploads/property_logs/';
        
        // Ensure the folder exists
        if (!file_exists($log_folder)) {
            mkdir($log_folder, 0755, true);
        }

        // Create a daily log file
        $log_file = $log_folder . 'property_additional_details_log_' . date('Y-m-d') . '.log';
        file_put_contents($log_file, date('Y-m-d H:i:s') . " - Error adding additional details for post_id {$post_id}: " . $e->getMessage() . "\n", FILE_APPEND);
    }
}

function update_features($post_id, $property_data) {
    global $wpdb;

    $features = [
        'LotFeatures' => 'property-feature',
        'CommunityFeatures' => 'community-feature',
        'Appliances' => 'appliance-feature',
        'OtherEquipment' => 'equipment-feature',
        'SecurityFeatures' => 'security-feature',
        'AssociationFeeIncludes' => 'association-feature',
        'BuildingFeatures' => 'building-feature',
        'ArchitecturalStyle' => 'architectural-style',
        'Heating' => 'heating-feature',
        'Basement' => 'basement-feature',
        'ExteriorFeatures' => 'exterior-feature',
        'FoundationDetails' => 'foundation-feature',
        'ParkingFeatures' => 'parking-feature',
        'StructureType' => 'structure-feature',
        'WaterSource' => 'water-source-feature',
        'Utilities' => 'utilities-feature'
    ];

    foreach ($features as $key => $taxonomy) {
        if (isset($property_data[$key]) && is_array($property_data[$key])) {
            foreach ($property_data[$key] as $feature) {
                try {
                    $feature_slug = sanitize_title($feature);
                    $term_taxonomy_id = check_and_insert_term($feature, $feature_slug, $taxonomy);

                    // Check if the relationship already exists before inserting
                    $relationship = $wpdb->get_row($wpdb->prepare("
                        SELECT * FROM `{$wpdb->prefix}term_relationships` 
                        WHERE `object_id` = %d 
                        AND `term_taxonomy_id` = %d
                        LIMIT 1", $post_id, $term_taxonomy_id
                    ));

                    if (!$relationship) {
                        $wpdb->insert(
                            "{$wpdb->prefix}term_relationships",
                            array(
                                'object_id' => $post_id,
                                'term_taxonomy_id' => $term_taxonomy_id
                            ),
                            array('%d', '%d')
                        );
                    }

                } catch (Exception $e) {
                    error_log("Error updating feature: {$feature} for post_id {$post_id}. Error: " . $e->getMessage());

                    // Optional: Write to a custom log file for more detailed tracking
                    $log_folder = ABSPATH . 'wp-content/uploads/property_logs/';
                    
                    // Ensure the folder exists
                    if (!file_exists($log_folder)) {
                        mkdir($log_folder, 0755, true);
                    }

                    // Create a daily log file
                    $log_file = $log_folder . 'property_feature_log_' . date('Y-m-d') . '.log';
                    file_put_contents($log_file, date('Y-m-d H:i:s') . " - Error updating feature {$feature} for post_id {$post_id}: " . $e->getMessage() . "\n", FILE_APPEND);
                }
            }
        }
    }
}

function check_and_insert_term($term_name, $term_slug, $taxonomy = 'property-feature') {
    global $wpdb;

    try {
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
                'term_group' => 0
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

    } catch (Exception $e) {
        // Log the error
        error_log("Error in check_and_insert_term for term: {$term_name}. Error: " . $e->getMessage());

        // Optional: Write to a custom log file
        $log_folder = ABSPATH . 'wp-content/uploads/property_logs/';
        if (!file_exists($log_folder)) {
            mkdir($log_folder, 0755, true);
        }
        $log_file = $log_folder . 'property_term_log_' . date('Y-m-d') . '.log';
        file_put_contents($log_file, date('Y-m-d H:i:s') . " - Error checking/inserting term {$term_name}: " . $e->getMessage() . "\n", FILE_APPEND);

        // Return null or false on failure
        return null;
    }
}

function insert_property_images($post_id, $media_array) {
    try {
        if (!empty($media_array) && is_array($media_array)) {
            // First, delete all old images associated with the post from the media library and meta table
            $existing_images = get_post_meta($post_id, 'REAL_HOMES_property_images', true);

            if (!empty($existing_images) && is_array($existing_images)) {
                foreach ($existing_images as $image_id) {
                    // Delete the image attachment from the media library
                    wp_delete_attachment($image_id, true);
                }
            }

            // Reset the REAL_HOMES_property_images meta key
            delete_post_meta($post_id, 'REAL_HOMES_property_images');

            // Ensure media functions are available
            if (!function_exists('media_sideload_image')) {
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                require_once(ABSPATH . 'wp-admin/includes/media.php');
                require_once(ABSPATH . 'wp-admin/includes/image.php');
            }

            // Loop through each media item in the array and insert new images
            foreach ($media_array as $index => $media_item) {
                if (isset($media_item['MediaURL']) && !empty($media_item['MediaURL'])) {
                    $image_url = $media_item['MediaURL'];

                    // Download and attach the image to the post
                    $image_id = media_sideload_image($image_url, $post_id, null, 'id');

                    // If successfully downloaded, insert it into the post meta
                    if (!is_wp_error($image_id)) {
                        // Insert image ID into the REAL_HOMES_property_images meta key
                        add_post_meta($post_id, 'REAL_HOMES_property_images', $image_id);

                        // Set the first image as the featured image
                        if ($index === 0) {
                            set_post_thumbnail($post_id, $image_id);
                        }
                    } else {
                        // Log the error if the image download failed
                        throw new Exception("Failed to sideload image from {$image_url}. Error: " . $image_id->get_error_message());
                    }
                }
            }
        }
    } catch (Exception $e) {
        // Log the exception message for debugging
        error_log("Error inserting property images for post_id {$post_id}: " . $e->getMessage());

        // Optional: Write to a custom log file for more detailed tracking
        $log_folder = ABSPATH . 'wp-content/uploads/property_logs/';
        
        // Ensure the folder exists
        if (!file_exists($log_folder)) {
            mkdir($log_folder, 0755, true);
        }

        // Create a daily log file
        $log_file = $log_folder . 'property_image_log_' . date('Y-m-d') . '.log';
        file_put_contents($log_file, date('Y-m-d H:i:s') . " - Error inserting images for post_id {$post_id}: " . $e->getMessage() . "\n", FILE_APPEND);
    }
}

function handle_property_city($post_id, $property_data) {
    global $wpdb;
    
    try {
        if (isset($property_data['City'])) {
            $city_name = $property_data['City'];

            // Check if the City already exists in the terms table
            $term = $wpdb->get_row($wpdb->prepare("
                SELECT term_id FROM {$wpdb->prefix}terms WHERE name = %s
            ", $city_name));

            if (!$term) {
                // The city doesn't exist, so insert it
                $insert_result = $wpdb->insert("{$wpdb->prefix}terms", [
                    'name' => $city_name,
                    'slug' => sanitize_title($city_name),
                    'term_group' => 0
                ]);

                if ($insert_result === false) {
                    throw new Exception("Failed to insert city into terms table: " . $wpdb->last_error);
                }

                // Get the inserted term_id
                $term_id = $wpdb->insert_id;

                // Insert into the term_taxonomy table for 'property-city'
                $wpdb->insert("{$wpdb->prefix}term_taxonomy", [
                    'term_id' => $term_id,
                    'taxonomy' => 'property-city',
                    'description' => '',
                    'parent' => 0,
                    'count' => 0
                ]);

                $term_taxonomy_id = $wpdb->insert_id;

            } else {
                // If the term exists, get its term_id
                $term_id = $term->term_id;

                // Get the term_taxonomy_id for the 'property-city' taxonomy
                $term_taxonomy = $wpdb->get_row($wpdb->prepare("
                    SELECT term_taxonomy_id FROM {$wpdb->prefix}term_taxonomy 
                    WHERE term_id = %d 
                    AND taxonomy = %s 
                    LIMIT 1", $term_id, 'property-city'
                ));

                if (!$term_taxonomy) {
                    $wpdb->insert("{$wpdb->prefix}term_taxonomy", [
                        'term_id' => $term_id,
                        'taxonomy' => 'property-city',
                        'description' => '',
                        'parent' => 0,
                        'count' => 0
                    ]);

                    $term_taxonomy_id = $wpdb->insert_id;
                } else {
                    $term_taxonomy_id = $term_taxonomy->term_taxonomy_id;
                }
            }

            // Save the term relationship with term_relationships (with dynamic table prefix)
            $existing_relationship = $wpdb->get_row($wpdb->prepare("
                SELECT * FROM {$wpdb->prefix}term_relationships WHERE object_id = %d AND term_taxonomy_id = %d
            ", $post_id, $term_taxonomy_id));

            if (!$existing_relationship) {
                $wpdb->insert("{$wpdb->prefix}term_relationships", [
                    'object_id' => $post_id,
                    'term_taxonomy_id' => $term_taxonomy_id,
                    'term_order' => 0
                ]);

                if ($wpdb->insert_id === false) {
                    throw new Exception("Failed to insert into term_relationships: " . $wpdb->last_error);
                }
            }
        }
    } catch (Exception $e) {
        // Log the error
        error_log("Error in handle_property_city for post_id {$post_id}: " . $e->getMessage());
    }
}

function handle_property_subtype($post_id, $property_data) {
    global $wpdb;

    try {
        if (isset($property_data['PropertySubType'])) {
            $property_subtype = $property_data['PropertySubType'];

            // Check if the PropertySubType already exists in the bv_terms table using dynamic table prefix
            $term = $wpdb->get_row($wpdb->prepare("
                SELECT term_id FROM {$wpdb->prefix}terms WHERE name = %s
            ", $property_subtype));

            if (!$term) {
                $insert_result = $wpdb->insert("{$wpdb->prefix}terms", [
                    'name' => $property_subtype,
                    'slug' => sanitize_title($property_subtype),
                    'term_group' => 0
                ]);

                if ($insert_result === false) {
                    throw new Exception("Failed to insert property subtype into {$wpdb->prefix}terms table: " . $wpdb->last_error);
                }

                // Get the inserted term_id
                $term_id = $wpdb->insert_id;
            } else {
                // If the term exists, get the term_id
                $term_id = $term->term_id;
            }

            // Save the term relationship with bv_term_relationships using dynamic table prefix
            $existing_relationship = $wpdb->get_row($wpdb->prepare("
                SELECT * FROM {$wpdb->prefix}term_relationships WHERE object_id = %d AND term_taxonomy_id = %d
            ", $post_id, $term_id));

            if (!$existing_relationship) {
                $wpdb->insert("{$wpdb->prefix}term_relationships", [
                    'object_id' => $post_id,
                    'term_taxonomy_id' => $term_id,
                    'term_order' => 0
                ]);

                if ($wpdb->insert_id === false) {
                    throw new Exception("Failed to insert into {$wpdb->prefix}term_relationships: " . $wpdb->last_error);
                }
            }
        }
    } catch (Exception $e) {
        // Log the error
        error_log("Error in handle_property_subtype for post_id {$post_id}: " . $e->getMessage());
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

// Set unlimited execution time for long-running sync processes
ignore_user_abort(true);
set_time_limit(0);

// Register the API route
add_action('rest_api_init', function () {
    register_rest_route('property/v1', '/insert/', array(
        'methods' => 'get',
        'callback' => 'insert_property_api',
        'permission_callback' => '__return_true',
    ));
});

function insert_property_api(WP_REST_Request $request) {
    global $wpdb;

    try {
        // Fetch the limit for each batch
        $limit = 10; // Set a limit for each batch

        // Fetch total count once
        $api_url_count = 'https://ddfapi.realtor.ca/odata/v1/Property?$top=1&$count=true&$filter=City%20eq%20%27Edmonton%27';
        $response_count = get_property_data(get_access_token(), $api_url_count);

        if (!$response_count) {
            throw new Exception('Failed to fetch property count from API.');
        }

        $total_records = isset($response_count['@odata.count']) ? intval($response_count['@odata.count']) : 0;

        // Step 1: Reset seen status for all properties (set seen to 0)
        reset_property_seen_status();

        // Get last skip value from the database
        $table_name = $wpdb->prefix . 'property_import_status';
        $last_status = $wpdb->get_row("SELECT last_skip_value FROM $table_name ORDER BY id DESC LIMIT 1");
        $last_skip_value = $last_status ? $last_status->last_skip_value : 0;

        // If the last skip value exceeds or equals the total records, stop the process
        if ($last_skip_value >= $total_records) {
            // Step 4: After processing, clean up unseen properties
            delete_unseen_properties();

            return rest_ensure_response(array(
                'status' => 'completed',
                'message' => 'All properties have been inserted and cleanup completed.',
                'total_records' => $total_records,
                'last_processed' => $last_skip_value
            ));
        }

        // Step 2: Start fetching property data from the last skip value
        $api_url = 'https://ddfapi.realtor.ca/odata/v1/Property?$top=' . $limit . '&$skip=' . $last_skip_value . '&$count=true&$filter=City%20eq%20%27Edmonton%27';
        $response_data = get_property_data(get_access_token(), $api_url);

        if (!$response_data || !isset($response_data['value'])) {
            throw new Exception('Failed to fetch property data from API.');
        }

        $total_processed = 0;

        foreach ($response_data['value'] as $property_data) {
            insert_property_data($property_data);
            mark_property_as_seen($property_data['ListingKey']); // Step 3: Mark the property as seen
            $total_processed++;
        }

        // Update the last skip value in the database
        $new_skip_value = $last_skip_value + $total_processed;
        $wpdb->insert($table_name, array(
            'last_skip_value' => $new_skip_value,
        ));

        // Return success message
        return rest_ensure_response(array(
            'status' => 'success',
            'message' => 'Properties inserted successfully.',
            'processed' => $total_processed,
            'next_skip_value' => $new_skip_value
        ));
    } catch (Exception $e) {
        error_log('Error inserting properties: ' . $e->getMessage());

        return rest_ensure_response(array(
            'status' => 'error',
            'message' => 'An error occurred: ' . $e->getMessage()
        ));
    }
}

// Mark a property as seen during the current sync
function mark_property_as_seen($listing_key) {
    global $wpdb;
    $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}postmeta pm
		INNER JOIN {$wpdb->prefix}posts p ON pm.post_id = p.ID
		SET p.seen = 1
		WHERE pm.meta_key = 'REAL_HOMES_listing_key' AND pm.meta_value = %s", $listing_key));
}

// Reset the seen status before each sync
function reset_property_seen_status() {
    global $wpdb;
    $wpdb->query("UPDATE {$wpdb->prefix}posts SET seen = 0 WHERE post_type = 'property'");
}

// After the sync, delete all properties that are not marked as seen
function delete_unseen_properties() {
    global $wpdb;
    $wpdb->query("DELETE p, pm FROM {$wpdb->prefix}posts p
		LEFT JOIN {$wpdb->prefix}postmeta pm ON p.ID = pm.post_id
		WHERE p.post_type = 'property' AND p.seen = 0");
}

// Function to get the last processed skip value from the database
function get_last_skip_value() {
    global $wpdb;
    $skip_value = $wpdb->get_var("SELECT last_skip_value FROM {$wpdb->prefix}property_import_status LIMIT 1");
    return $skip_value ? intval($skip_value) : 0;
}

// Function to update the last processed skip value in the database
function update_last_skip_value($new_skip_value) {
    global $wpdb;
    $existing = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}property_import_status");

    if ($existing > 0) {
        $wpdb->update("{$wpdb->prefix}property_import_status", array(
            'last_skip_value' => $new_skip_value
        ), array('id' => 1));
    } else {
        $wpdb->insert("{$wpdb->prefix}property_import_status", array(
            'last_skip_value' => $new_skip_value
        ));
    }
}

// Function to get access token and refresh if necessary
function get_access_token($force_refresh = false) {
    try {
        $access_token = get_transient('crea_access_token');

        if ($force_refresh || !$access_token) {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://identity.crea.ca/connect/token',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'client_id=Ans5thODup8PzA8sPiJLvTyq&client_secret=K586spBextHUkimpCY1xzzU7&grant_type=client_credentials&scope=DDFApi_Read',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded',
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);

            $response_data = json_decode($response, true);

            if (isset($response_data['access_token'])) {
                set_transient('crea_access_token', $response_data['access_token'], 3600 - 300);
                return $response_data['access_token'];
            } else {
                throw new Exception('Failed to obtain access token.');
            }
        }

        return $access_token;
    } catch (Exception $e) {
        error_log('Error fetching access token: ' . $e->getMessage());
        return false;
    }
}

// Function to get property data
function get_property_data($access_token, $api_url) {
    try {
        if (!$access_token) {
            throw new Exception('Invalid access token.');
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $access_token,
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $data = json_decode($response, true);
        if (!$data) {
            throw new Exception('Failed to decode API response.');
        }

        return $data;
    } catch (Exception $e) {
        error_log('Error fetching property data: ' . $e->getMessage());
        return false;
    }
}

// Function to create the table if it doesn't exist
function create_property_import_status_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'property_import_status';

    if ($wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name) {
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE $table_name (
            id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            last_skip_value BIGINT(20) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

// Hook the function to run on theme activation
add_action('after_setup_theme', 'create_property_import_status_table');

// Function to add the 'seen' column to wp_posts if it doesn't exist
function add_seen_column_to_wp_posts() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'posts';

    // Check if the 'seen' column already exists in the wp_posts table
    $column_exists = $wpdb->get_results("SHOW COLUMNS FROM {$table_name} LIKE 'seen'");
    
    if (empty($column_exists)) {
        // SQL query to add the 'seen' column
        $sql = "ALTER TABLE {$table_name} ADD COLUMN seen TINYINT(1) DEFAULT 0";
        
        // Run the query to add the column
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $wpdb->query($sql);
    }
}

// Hook the function to run on theme activation
add_action('after_setup_theme', 'add_seen_column_to_wp_posts');
