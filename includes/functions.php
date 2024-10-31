<?php

defined( 'ABSPATH' ) || exit();

function podcast_box_get_settings( $field, $default = '', $key = 'podcast_box_general_settings' ) {
	$settings = get_option( $key );

	return ! empty( $settings[ $field ] ) ? $settings[ $field ] : $default;
}

function podcast_box_podcast_map() {
	$options = [
		'al' => [ 'count' => 2, 'label' => 'Albania' ],
		'ar' => [ 'count' => 3, 'label' => 'Argentina' ],
		'at' => [ 'count' => 4, 'label' => 'Austria' ],
		'au' => [ 'count' => 29, 'label' => 'Australia' ],
		'ba' => [ 'count' => 1, 'label' => 'Bosnia and Herzegovina' ],
		'bd' => [ 'count' => 45, 'label' => 'Bangladesh' ],
		'be' => [ 'count' => 2, 'label' => 'Belgium' ],
		'br' => [ 'count' => 45, 'label' => 'Brazil' ],
		'ca' => [ 'count' => 19, 'label' => 'Canada' ],
		'ch' => [ 'count' => 109, 'label' => 'Switzerland' ],
		'cl' => [ 'count' => 2, 'label' => 'Chile' ],
		'co' => [ 'count' => 2, 'label' => 'Colombia' ],
		'cr' => [ 'count' => 133, 'label' => 'Costa Rica' ],
		'de' => [ 'count' => 147, 'label' => 'Germany' ],
		'dk' => [ 'count' => 9, 'label' => 'Denmark' ],
		'do' => [ 'count' => 1, 'label' => 'Dominican Republic' ],
		'es' => [ 'count' => 47, 'label' => 'Spain' ],
		'et' => [ 'count' => 12, 'label' => 'Ethiopia' ],
		'fi' => [ 'count' => 8, 'label' => 'Finland' ],
		'fr' => [ 'count' => 18, 'label' => 'France' ],
		'gb' => [ 'count' => 64, 'label' => 'United Kingdom' ],
		'gh' => [ 'count' => 1, 'label' => 'Ghana' ],
		'gr' => [ 'count' => 1, 'label' => 'Greece' ],
		'gt' => [ 'count' => 1, 'label' => 'Guatemala' ],
		'hu' => [ 'count' => 6, 'label' => 'Hungary' ],
		'id' => [ 'count' => 5, 'label' => 'Indonesia' ],
		'ie' => [ 'count' => 5, 'label' => 'Ireland' ],
		'il' => [ 'count' => 4, 'label' => 'Israel' ],
		'in' => [ 'count' => 22, 'label' => 'India' ],
		'ir' => [ 'count' => 4, 'label' => 'Iran' ],
		'is' => [ 'count' => 1, 'label' => 'Iceland' ],
		'it' => [ 'count' => 34, 'label' => 'Italy' ],
		'jp' => [ 'count' => 15, 'label' => '日本 (Japan)' ],
		'ke' => [ 'count' => 1, 'label' => 'Kenya' ],
		'kh' => [ 'count' => 1, 'label' => 'Cambodia' ],
		'kr' => [ 'count' => 2, 'label' => '대한민국 (South Korea)' ],
		'lb' => [ 'count' => 8, 'label' => 'Lebanon' ],
		'lk' => [ 'count' => 1, 'label' => 'Sri Lanka' ],
		'lt' => [ 'count' => 4, 'label' => 'Lithuania' ],
		'lv' => [ 'count' => 2, 'label' => 'Latvia' ],
		'mx' => [ 'count' => 22, 'label' => 'Mexico' ],
		'my' => [ 'count' => 2, 'label' => 'Malaysia' ],
		'nl' => [ 'count' => 19, 'label' => 'Netherlands' ],
		'no' => [ 'count' => 28, 'label' => 'Norway' ],
		'nz' => [ 'count' => 2, 'label' => 'New Zealand' ],
		'pa' => [ 'count' => 1, 'label' => 'Panama' ],
		'pe' => [ 'count' => 2, 'label' => 'Peru' ],
		'ph' => [ 'count' => 1, 'label' => 'Philippines' ],
		'pk' => [ 'count' => 1, 'label' => 'Pakistan' ],
		'pl' => [ 'count' => 30, 'label' => 'Poland' ],
		'pr' => [ 'count' => 2, 'label' => 'Puerto Rico' ],
		'pt' => [ 'count' => 66, 'label' => 'Portugal' ],
		'ro' => [ 'count' => 7, 'label' => 'Romania' ],
		'rs' => [ 'count' => 3, 'label' => 'Serbia' ],
		'ru' => [ 'count' => 18, 'label' => 'Россия (Russia)' ],
		'sa' => [ 'count' => 1, 'label' => 'Saudi Arabia' ],
		'se' => [ 'count' => 33, 'label' => 'Sweden' ],
		'si' => [ 'count' => 5, 'label' => 'Slovenia' ],
		'sk' => [ 'count' => 23, 'label' => 'Slovakia' ],
		'th' => [ 'count' => 9, 'label' => 'Thailand' ],
		'tr' => [ 'count' => 6, 'label' => 'Turkey' ],
		'tw' => [ 'count' => 9, 'label' => 'Taiwan' ],
		'ua' => [ 'count' => 1, 'label' => 'Ukraine' ],
		'us' => [ 'count' => 4157, 'label' => 'United States' ],
		'uy' => [ 'count' => 1, 'label' => 'Uruguay' ],
		've' => [ 'count' => 3, 'label' => 'Venezuela' ],
		'vn' => [ 'count' => 9, 'label' => 'Vietnam' ],
		'za' => [ 'count' => 4, 'label' => 'South Africa' ],
	];

	return $options;

}

function podcast_box_get_country_list() {
	$countries = array(
		"AF" => array( "country" => "Afghanistan", "continent" => "Asia" ),
		"AL" => array( "country" => "Albania", "continent" => "Europe" ),
		"DZ" => array( "country" => "Algeria", "continent" => "Africa" ),
		"AS" => array( "country" => "American Samoa", "continent" => "Oceania" ),
		"AD" => array( "country" => "Andorra", "continent" => "Europe" ),
		"AO" => array( "country" => "Angola", "continent" => "Africa" ),
		"AI" => array( "country" => "Anguilla", "continent" => "North America" ),
		"AG" => array( "country" => "Antigua and Barbuda", "continent" => "North America" ),
		"AR" => array( "country" => "Argentina", "continent" => "South America" ),
		"AM" => array( "country" => "Armenia", "continent" => "Asia" ),
		"AW" => array( "country" => "Aruba", "continent" => "North America" ),
		"AU" => array( "country" => "Australia", "continent" => "Oceania" ),
		"AT" => array( "country" => "Austria", "continent" => "Europe" ),
		"AZ" => array( "country" => "Azerbaijan", "continent" => "Asia" ),
		"BS" => array( "country" => "Bahamas", "continent" => "North America" ),
		"BH" => array( "country" => "Bahrain", "continent" => "Asia" ),
		"BD" => array( "country" => "Bangladesh", "continent" => "Asia" ),
		"BB" => array( "country" => "Barbados", "continent" => "North America" ),
		"BY" => array( "country" => "Belarus", "continent" => "Europe" ),
		"BE" => array( "country" => "Belgium", "continent" => "Europe" ),
		"BZ" => array( "country" => "Belize", "continent" => "North America" ),
		"BJ" => array( "country" => "Benin", "continent" => "Africa" ),
		"BM" => array( "country" => "Bermuda", "continent" => "North America" ),
		"BT" => array( "country" => "Bhutan", "continent" => "Asia" ),
		"BO" => array( "country" => "Bolivia", "continent" => "South America" ),
		"BA" => array( "country" => "Bosnia and Herzegovina", "continent" => "Europe" ),
		"BW" => array( "country" => "Botswana", "continent" => "Africa" ),
		"BR" => array( "country" => "Brazil", "continent" => "South America" ),
		"BN" => array( "country" => "Brunei", "continent" => "Asia" ),
		"BG" => array( "country" => "Bulgaria", "continent" => "Europe" ),
		"BF" => array( "country" => "Burkina Faso", "continent" => "Africa" ),
		"BI" => array( "country" => "Burundi", "continent" => "Africa" ),
		"BQ" => array( "country" => "Bonaire", "continent" => "Europe" ),
		"KH" => array( "country" => "Cambodia", "continent" => "Asia" ),
		"CM" => array( "country" => "Cameroon", "continent" => "Africa" ),
		"CA" => array( "country" => "Canada", "continent" => "North America" ),
		"CV" => array( "country" => "Cape Verde", "continent" => "Africa" ),
		"KY" => array( "country" => "Cayman Islands", "continent" => "North America" ),
		"CF" => array( "country" => "Central African Republic", "continent" => "Africa" ),
		"TD" => array( "country" => "Chad", "continent" => "Africa" ),
		"CL" => array( "country" => "Chile", "continent" => "South America" ),
		"CN" => array( "country" => "China", "continent" => "Asia" ),
		"CO" => array( "country" => "Colombia", "continent" => "South America" ),
		"KM" => array( "country" => "Comoros", "continent" => "Africa" ),
		"CG" => array( "country" => "Congo", "continent" => "Africa" ),
		"CD" => array( "country" => "DR Congo", "continent" => "Africa" ),
		"CK" => array( "country" => "Cook Islands", "continent" => "Oceania" ),
		"CR" => array( "country" => "Costa Rica", "continent" => "North America" ),
		"CI" => array( "country" => "Ivory Coast", "continent" => "Africa" ),
		"HR" => array( "country" => "Croatia", "continent" => "Europe" ),
		"CU" => array( "country" => "Cuba", "continent" => "North America" ),
		"CY" => array( "country" => "Cyprus", "continent" => "Asia" ),
		"CZ" => array( "country" => "Czech Republic", "continent" => "Europe" ),
		"CW" => array( "country" => "Curacao", "continent" => "South America" ),
		"xk" => array( "country" => "Kosovo", "continent" => "Europe" ),
		"DK" => array( "country" => "Denmark", "continent" => "Europe" ),
		"DJ" => array( "country" => "Djibouti", "continent" => "Africa" ),
		"DM" => array( "country" => "Dominica", "continent" => "North America" ),
		"DO" => array( "country" => "Dominican Republic", "continent" => "North America" ),
		"EC" => array( "country" => "Ecuador", "continent" => "South America" ),
		"EG" => array( "country" => "Egypt", "continent" => "Africa" ),
		"SV" => array( "country" => "El Salvador", "continent" => "North America" ),
		"GQ" => array( "country" => "Equatorial Guinea", "continent" => "Africa" ),
		"ER" => array( "country" => "Eritrea", "continent" => "Africa" ),
		"EE" => array( "country" => "Estonia", "continent" => "Europe" ),
		"ET" => array( "country" => "Ethiopia", "continent" => "Africa" ),
		"FK" => array( "country" => "Falkland Islands", "continent" => "South America" ),
		"FO" => array( "country" => "Faroe Islands, Denmark", "continent" => "Europe" ),
		"FJ" => array( "country" => "Fiji", "continent" => "Oceania" ),
		"FI" => array( "country" => "Finland", "continent" => "Europe" ),
		"FR" => array( "country" => "France", "continent" => "Europe" ),
		"GF" => array( "country" => "French Guiana", "continent" => "South America" ),
		"PF" => array( "country" => "French Polynesia", "continent" => "Oceania" ),
		"GA" => array( "country" => "Gabon", "continent" => "Africa" ),
		"GM" => array( "country" => "Gambia", "continent" => "Africa" ),
		"GE" => array( "country" => "Georgia", "continent" => "Asia" ),
		"DE" => array( "country" => "Germany", "continent" => "Europe" ),
		"GH" => array( "country" => "Ghana", "continent" => "Africa" ),
		"GI" => array( "country" => "Gibraltar", "continent" => "Europe" ),
		"GR" => array( "country" => "Greece", "continent" => "Europe" ),
		"GL" => array( "country" => "Greenland", "continent" => "North America" ),
		"GD" => array( "country" => "Grenada", "continent" => "North America" ),
		"GP" => array( "country" => "Guadeloupe", "continent" => "North America" ),
		"GU" => array( "country" => "Guam", "continent" => "Oceania" ),
		"GT" => array( "country" => "Guatemala", "continent" => "North America" ),
		"GG" => array( "country" => "Guernsey", "continent" => "Europe" ),
		"GN" => array( "country" => "Guinea", "continent" => "Africa" ),
		"GW" => array( "country" => "Guinea-bissau", "continent" => "Africa" ),
		"GY" => array( "country" => "Guyana", "continent" => "South America" ),
		"HT" => array( "country" => "Haiti", "continent" => "North America" ),
		"VA" => array( "country" => "Vatican", "continent" => "Europe" ),
		"HN" => array( "country" => "Honduras", "continent" => "North America" ),
		"HK" => array( "country" => "Hong Kong", "continent" => "Asia" ),
		"HU" => array( "country" => "Hungary", "continent" => "Europe" ),
		"IS" => array( "country" => "Iceland", "continent" => "Europe" ),
		"IN" => array( "country" => "India", "continent" => "Asia" ),
		"ID" => array( "country" => "Indonesia", "continent" => "Asia" ),
		"IR" => array( "country" => "Iran", "continent" => "Asia" ),
		"IQ" => array( "country" => "Iraq", "continent" => "Asia" ),
		"IE" => array( "country" => "Ireland", "continent" => "Europe" ),
		"IM" => array( "country" => "Isle of Man", "continent" => "Europe" ),
		"IL" => array( "country" => "Israel", "continent" => "Asia" ),
		"IT" => array( "country" => "Italy", "continent" => "Europe" ),
		"JM" => array( "country" => "Jamaica", "continent" => "North America" ),
		"JP" => array( "country" => "日本 (Japan)", "continent" => "Asia" ),
		"JE" => array( "country" => "Jersey", "continent" => "Europe" ),
		"JO" => array( "country" => "Jordan", "continent" => "Asia" ),
		"KZ" => array( "country" => "Kazakhstan", "continent" => "Asia" ),
		"KE" => array( "country" => "Kenya", "continent" => "Africa" ),
		"KI" => array( "country" => "Kiribati", "continent" => "Oceania" ),
		"KR" => array( "country" => "대한민국 (South Korea)", "continent" => "Asia" ),
		"KW" => array( "country" => "Kuwait", "continent" => "Asia" ),
		"KG" => array( "country" => "Kyrgyzstan", "continent" => "Asia" ),
		"LA" => array( "country" => "Laos", "continent" => "Asia" ),
		"LV" => array( "country" => "Latvia", "continent" => "Europe" ),
		"LB" => array( "country" => "Lebanon", "continent" => "Asia" ),
		"LS" => array( "country" => "Lesotho", "continent" => "Africa" ),
		"LR" => array( "country" => "Liberia", "continent" => "Africa" ),
		"LY" => array( "country" => "Libya", "continent" => "Africa" ),
		"LI" => array( "country" => "Liechtenstein", "continent" => "Europe" ),
		"LT" => array( "country" => "Lithuania", "continent" => "Europe" ),
		"LU" => array( "country" => "Luxembourg", "continent" => "Europe" ),
		"MK" => array( "country" => "Macedonia", "continent" => "Europe" ),
		"MG" => array( "country" => "Madagascar", "continent" => "Africa" ),
		"MW" => array( "country" => "Malawi", "continent" => "Africa" ),
		"MY" => array( "country" => "Malaysia", "continent" => "Asia" ),
		"MV" => array( "country" => "Maldives", "continent" => "Asia" ),
		"ML" => array( "country" => "Mali", "continent" => "Africa" ),
		"MT" => array( "country" => "Malta", "continent" => "Europe" ),
		"MH" => array( "country" => "Marshall Islands", "continent" => "Oceania" ),
		"MQ" => array( "country" => "Martinique", "continent" => "North America" ),
		"MR" => array( "country" => "Mauritania", "continent" => "Africa" ),
		"MU" => array( "country" => "Mauritius", "continent" => "Africa" ),
		"YT" => array( "country" => "Mayotte", "continent" => "Africa" ),
		"MX" => array( "country" => "Mexico", "continent" => "North America" ),
		"FM" => array( "country" => "Micronesia", "continent" => "Oceania" ),
		"MD" => array( "country" => "Moldova", "continent" => "Europe" ),
		"MC" => array( "country" => "Monaco", "continent" => "Europe" ),
		"MN" => array( "country" => "Mongolia", "continent" => "Asia" ),
		"ME" => array( "country" => "Montenegro", "continent" => "Europe" ),
		"MS" => array( "country" => "Montserrat", "continent" => "North America" ),
		"MA" => array( "country" => "Morocco", "continent" => "Africa" ),
		"MZ" => array( "country" => "Mozambique", "continent" => "Africa" ),
		"MM" => array( "country" => "Myanmar", "continent" => "Asia" ),
		"MF" => array( "country" => "Saint Martin", "continent" => "North America" ),
		"SX" => array( "country" => "Sint Maarten", "continent" => "North America" ),
		"BL" => array( "country" => "Saint-Barthelemy", "continent" => "North America" ),
		"NA" => array( "country" => "Namibia", "continent" => "Africa" ),
		"NP" => array( "country" => "Nepal", "continent" => "Asia" ),
		"NL" => array( "country" => "Netherlands", "continent" => "Europe" ),
		"NC" => array( "country" => "New Caledonia", "continent" => "Oceania" ),
		"NZ" => array( "country" => "New Zealand", "continent" => "Oceania" ),
		"NI" => array( "country" => "Nicaragua", "continent" => "North America" ),
		"NE" => array( "country" => "Niger", "continent" => "Africa" ),
		"NG" => array( "country" => "Nigeria", "continent" => "Africa" ),
		"MP" => array( "country" => "Northern Mariana Islands", "continent" => "Oceania" ),
		"NO" => array( "country" => "Norway", "continent" => "Europe" ),
		"OM" => array( "country" => "Oman", "continent" => "Asia" ),
		"PK" => array( "country" => "Pakistan", "continent" => "Asia" ),
		"PW" => array( "country" => "Palau", "continent" => "Oceania" ),
		"PS" => array( "country" => "Palestine", "continent" => "Asia" ),
		"PA" => array( "country" => "Panama", "continent" => "North America" ),
		"PG" => array( "country" => "Papua New Guinea", "continent" => "Oceania" ),
		"PY" => array( "country" => "Paraguay", "continent" => "South America" ),
		"PE" => array( "country" => "Peru", "continent" => "South America" ),
		"PH" => array( "country" => "Philippines", "continent" => "Asia" ),
		"PL" => array( "country" => "Poland", "continent" => "Europe" ),
		"PT" => array( "country" => "Portugal", "continent" => "Europe" ),
		"PR" => array( "country" => "Puerto Rico", "continent" => "North America" ),
		"QA" => array( "country" => "Qatar", "continent" => "Asia" ),
		"RE" => array( "country" => "Reunion", "continent" => "Africa" ),
		"RO" => array( "country" => "Romania", "continent" => "Europe" ),
		"RU" => array( "country" => "Россия (Russia)", "continent" => "Europe" ),
		"RW" => array( "country" => "Rwanda", "continent" => "Africa" ),
		"KN" => array( "country" => "Saint Kitts and Nevis", "continent" => "North America" ),
		"LC" => array( "country" => "Saint Lucia", "continent" => "North America" ),
		"PM" => array( "country" => "Saint Pierre and Miquelon", "continent" => "North America" ),
		"VC" => array( "country" => "Saint Vincent and the Grenadines", "continent" => "North America" ),
		"WS" => array( "country" => "Samoa", "continent" => "Oceania" ),
		"SM" => array( "country" => "Republic of San Marino", "continent" => "Europe" ),
		"ST" => array( "country" => "Sao Tome and Principe", "continent" => "Africa" ),
		"SA" => array( "country" => "Saudi Arabia", "continent" => "Asia" ),
		"SN" => array( "country" => "Senegal", "continent" => "Africa" ),
		"RS" => array( "country" => "Serbia", "continent" => "Europe" ),
		"SC" => array( "country" => "Seychelles", "continent" => "Africa" ),
		"SS" => array( "country" => "South Sudan", "continent" => "Africa" ),
		"SL" => array( "country" => "Sierra Leone", "continent" => "Africa" ),
		"SG" => array( "country" => "Singapore", "continent" => "Asia" ),
		"SK" => array( "country" => "Slovakia", "continent" => "Europe" ),
		"SI" => array( "country" => "Slovenia", "continent" => "Europe" ),
		"SB" => array( "country" => "Solomon Islands", "continent" => "Oceania" ),
		"SO" => array( "country" => "Somalia", "continent" => "Africa" ),
		"ZA" => array( "country" => "South Africa", "continent" => "Africa" ),
		"ES" => array( "country" => "Spain", "continent" => "Europe" ),
		"LK" => array( "country" => "Sri Lanka", "continent" => "Asia" ),
		"SD" => array( "country" => "Sudan", "continent" => "Africa" ),
		"SR" => array( "country" => "Suriname", "continent" => "South America" ),
		"SZ" => array( "country" => "Swaziland", "continent" => "Africa" ),
		"SE" => array( "country" => "Sweden", "continent" => "Europe" ),
		"CH" => array( "country" => "Switzerland", "continent" => "Europe" ),
		"SY" => array( "country" => "Syria", "continent" => "Asia" ),
		"TW" => array( "country" => "Taiwan", "continent" => "Asia" ),
		"TJ" => array( "country" => "Tajikistan", "continent" => "Asia" ),
		"TZ" => array( "country" => "Tanzania", "continent" => "Africa" ),
		"TH" => array( "country" => "Thailand", "continent" => "Asia" ),
		"TL" => array( "country" => "Timor-leste", "continent" => "Asia" ),
		"TG" => array( "country" => "Togo", "continent" => "Africa" ),
		"TK" => array( "country" => "Tokelau", "continent" => "Oceania" ),
		"TO" => array( "country" => "Tonga", "continent" => "Oceania" ),
		"TT" => array( "country" => "Trinidad and Tobago", "continent" => "North America" ),
		"TN" => array( "country" => "Tunisia", "continent" => "Africa" ),
		"TR" => array( "country" => "Turkey", "continent" => "Asia" ),
		"TM" => array( "country" => "Turkmenistan", "continent" => "Asia" ),
		"TC" => array( "country" => "Turks and Caicos Islands", "continent" => "North America" ),
		"TV" => array( "country" => "Tuvalu", "continent" => "Oceania" ),
		"UG" => array( "country" => "Uganda", "continent" => "Africa" ),
		"UA" => array( "country" => "Ukraine", "continent" => "Europe" ),
		"AE" => array( "country" => "United Arab Emirates", "continent" => "Asia" ),
		"GB" => array( "country" => "United Kingdom", "continent" => "Europe" ),
		"US" => array( "country" => "United States", "continent" => "North America" ),
		"UY" => array( "country" => "Uruguay", "continent" => "South America" ),
		"UZ" => array( "country" => "Uzbekistan", "continent" => "Asia" ),
		"VU" => array( "country" => "Vanuatu", "continent" => "Oceania" ),
		"VE" => array( "country" => "Venezuela", "continent" => "South America" ),
		"VN" => array( "country" => "Vietnam", "continent" => "Asia" ),
		"VG" => array( "country" => "Virgin Islands, British", "continent" => "North America" ),
		"VI" => array( "country" => "Virgin Islands (US)", "continent" => "North America" ),
		"WF" => array( "country" => "Wallis and Futuna", "continent" => "Oceania" ),
		"EH" => array( "country" => "Western Sahara", "continent" => "Africa" ),
		"YE" => array( "country" => "Yemen", "continent" => "Asia" ),
		"ZM" => array( "country" => "Zambia", "continent" => "Africa" ),
		"ZW" => array( "country" => "Zimbabwe", "continent" => "Africa" ),
	);

	return $countries;
}

function podcast_box_get_meta( $post_id, $key, $default = '' ) {
	$meta = get_post_meta( $post_id, $key, true );

	return ! empty( $meta ) ? $meta : $default;
}

function podcast_box_sanitize_data( $data ) {

	trim( (string) $data );
	$data = str_replace( [ "&nbsp;", '<![CDATA[', ']]>' ], [ "", '', '' ], $data );

	$data = trim( html_entity_decode( $data ) );

	return $data;
}

// Return an embed audio player depending on the podcast hosting provider
function podcast_box_embed_validator( $parsed_feed_host, $embed_url, $audio_url, $secondline_rss_feed_url, $guid ) {
	if ( strpos( $parsed_feed_host, 'transistor.fm' ) !== false ) {

		$fixed_share_url            = str_replace( '/s/', '/e/', $embed_url );
		$secondline_audio_shortcode = '<iframe src="' . esc_url( $fixed_share_url )
		                              . '" width="100%" height="180" frameborder="0" scrolling="no" seamless="true" style="width:100%; height:180px;"></iframe>';

	} elseif ( strpos( $parsed_feed_host, 'anchor.fm' ) !== false ) {

		$fixed_share_url            = str_replace( '/episodes/', '/embed/episodes/', $embed_url );
		$secondline_audio_shortcode = '<iframe src="' . esc_url( $fixed_share_url )
		                              . '" height="180px" width="100%" frameborder="0" scrolling="no" style="width:100%; height:180px;"></iframe>';

	} elseif ( strpos( $parsed_feed_host, 'simplecast.com' ) !== false ) {

		$simplecast_response = wp_remote_get( 'https://api.simplecast.com/oembed?url=' . rawurlencode( $embed_url ) );
		$simplecast_json     = json_decode( $simplecast_response['body'], true );
		$simplecast_html     = $simplecast_json['html'];
		preg_match( '/src="([^"]+)"/', $simplecast_html, $match );
		$fixed_share_url            = $match[1];
		$secondline_audio_shortcode = '<iframe src="' . esc_url( $fixed_share_url )
		                              . '" height="200px" width="100%" frameborder="no" scrolling="no" style="width:100%; height:200px;"></iframe>';

	} elseif ( strpos( $parsed_feed_host, 'whooshkaa.com' ) !== false ) {

		$whooshkaa_audio_id         = substr( $embed_url, strpos( $embed_url, "?id=" ) + 4 );
		$fixed_share_url            = 'https://webplayer.whooshkaa.com/player/episode/id/' . $whooshkaa_audio_id . '?theme=light';
		$secondline_audio_shortcode = '<iframe src="' . esc_url( $fixed_share_url )
		                              . '" width="100%" height="200" frameborder="0" scrolling="no" style="width: 100%; height: 200px"></iframe>';

	} elseif ( ( strpos( $secondline_rss_feed_url, 'omny.fm' ) !== false )
	           || ( strpos( $secondline_rss_feed_url, 'omnycontent.com' ) !== false ) ) {

		$secondline_audio_shortcode = '<iframe src="' . esc_url( $embed_url )
		                              . '" width="100%" height="180px" scrolling="no"  frameborder="0" style="width:100%; height:180px;"></iframe>';

	} elseif ( strpos( $parsed_feed_host, 'podbean.com' ) !== false ) {

		$secondline_audio_shortcode = wp_oembed_get( esc_url( $embed_url ) ); // oEmbed

	} elseif ( strpos( $secondline_rss_feed_url, 'megaphone.fm' ) !== false ) {
		$megaphone_audio_link       = explode( 'megaphone.fm/', $audio_url );
		$megaphone_audio_id         = explode( '.', $megaphone_audio_link[1] );
		$fixed_share_url            = 'https://playlist.megaphone.fm/?e=' . $megaphone_audio_id[0];
		$secondline_audio_shortcode = '<iframe src="' . esc_url( $fixed_share_url )
		                              . '" width="100%" height="210" scrolling="no"  frameborder="0" style="width: 100%; height: 210px"></iframe>';

	} elseif ( strpos( $secondline_rss_feed_url, 'captivate.fm' ) !== false ) {
		$fixed_share_url            = 'https://player.captivate.fm/episode/' . $guid;
		$secondline_audio_shortcode = '<iframe src="' . esc_url( $fixed_share_url )
		                              . '" width="100%" height="170" scrolling="no"  frameborder="0" style="width: 100%; height: 170px"></iframe>';

	} elseif ( strpos( $audio_url, 'buzzsprout.com' ) !== false ) {
		$buzzsprout_audio_url       = explode( '.mp3', $audio_url );
		$fixed_share_url            = $buzzsprout_audio_url[0] . '?iframe=true';
		$secondline_audio_shortcode = '<iframe src="' . esc_url( $fixed_share_url )
		                              . '" scrolling="no" width="100%" scrolling="no"  height="200" frameborder="0" style="width: 100%; height: 200px"></iframe>';

	} elseif ( strpos( $audio_url, 'pinecast.com' ) !== false ) {
		$pinecast_audio_url         = explode( '.mp3', $audio_url );
		$pinecast_episode_url       = str_replace( '/listen/', '/player/', $pinecast_audio_url[0] );
		$fixed_share_url            = $pinecast_episode_url . '?theme=flat';
		$secondline_audio_shortcode = '<iframe src="' . esc_url( $fixed_share_url )
		                              . '" scrolling="no" width="100%" scrolling="no"  height="200" frameborder="0" style="width: 100%; height: 200px"></iframe>';

	} elseif ( strpos( $secondline_rss_feed_url, 'feed.ausha.co' ) !== false ) {
		$ausha_audio_link = explode( 'audio.ausha.co/', $audio_url );
		$ausha_audio_id   = explode( '.mp3', $ausha_audio_link[1] );
		$podcastId        = $ausha_audio_id[0];
		$secondline_audio_shortcode
		                  = '<iframe frameborder="0" height="200px" scrolling="no"  width="100%" src="https://widget.ausha.co/index.html?podcastId='
		                    . $podcastId . '&amp;display=horizontal&amp;v=2"></iframe>';

	} elseif ( strpos( $secondline_rss_feed_url, 'spreaker.com' ) !== false ) {
		$fixed_share_url = explode( '/episode/', $guid );
		if ( isset( $fixed_share_url[1] ) ) {
			$secondline_audio_shortcode
				= '<iframe frameborder="0" height="200" scrolling="no" width="100%" src="https://widget.spreaker.com/player?episode_id='
				  . $fixed_share_url[1] . '"></iframe>';
		} else {
			$secondline_audio_shortcode = '[audio src="' . esc_url( $audio_url ) . '"][/audio]';
		}

	} elseif ( strpos( $secondline_rss_feed_url, 'fireside.fm' ) !== false ) {
		$secondline_audio_shortcode = $embed_url . '</iframe>';

	} elseif ( strpos( $secondline_rss_feed_url, 'audioboom.com' ) !== false ) {
		$fixed_share_url            = str_replace( '/posts/', '/boos/', $embed_url );
		$secondline_audio_shortcode = '<iframe frameborder="0" height="220" scrolling="no" width="100%" src="' . $fixed_share_url
		                              . '/embed/v4"></iframe>';

	} else {

		$secondline_audio_shortcode = '[audio src="' . esc_url( $audio_url ) . '"][/audio]';

	}

	return $secondline_audio_shortcode;
}

function podcast_box_embed_host_checker( $parsed_feed_host, $secondline_rss_feed_url ) {
	if ( ( preg_match( '/transistor.fm|anchor.fm|fireside.fm|simplecast.com|spreaker.com|whooshkaa.com|omny.fm|omnycontent.com|megaphone.fm|podbean.com/i',
			$parsed_feed_host ) )
	     || ( preg_match( '/megaphone.fm|captivate.fm|ausha.co|omny.fm|omnycontent.com|pinecast.com|audioboom.com|buzzsprout.com/i',
			$secondline_rss_feed_url ) ) ) {
		$secondline_host_checker = true;
	} else {
		$secondline_host_checker = false;
	}

	return $secondline_host_checker;
}

function podcast_box_redirect_to_edit( $post_id ) {
	wp_redirect( admin_url( "post.php?post=$post_id&action=edit" ) );
	exit();
}

function podcast_box_get_podcasts( $args = [], $return_query = false ) {
	global $podcast_box_args;

	$posts_per_page = podcast_box_get_settings( 'posts_per_page', 10, 'podcast_box_display_settings' );

	$podcast_box_args = wp_parse_args( $args, [
		'post_type'      => 'podcast',
		'posts_per_page' => $posts_per_page,
		'orderby'        => 'date',
		'order'          => 'DESC',
	] );

	if ( ! empty( $_REQUEST['keyword'] ) ) {
		$podcast_box_args['s'] = sanitize_text_field( $_REQUEST['keyword'] );
	}

	if ( ! empty( $_REQUEST['paginate'] ) ) {
		$podcast_box_args['paged'] = intval( $_REQUEST['paginate'] );
	}

	$query = new WP_Query( $podcast_box_args );

	if ( $return_query ) {
		return $query;
	}

	return $query->have_posts() ? $query->posts : false;
}

function podcast_box_get_episodes( $args = [], $return_query = false ) {

	$posts_per_page = podcast_box_get_settings( 'posts_per_page', 10, 'podcast_box_display_settings' );

	$args = wp_parse_args( $args, [
		'post_type'      => 'episode',
		'posts_per_page' => $posts_per_page,
		'orderby'        => 'date',
		'post_status'    => 'publish',
		'order'          => 'DESC',
	] );

	if ( ! empty( $_REQUEST['keyword'] ) ) {
		$args['s'] = sanitize_text_field( $_REQUEST['keyword'] );
	}

	if ( ! empty( $_REQUEST['paginate'] ) ) {
		$args['paged'] = intval( $_REQUEST['paginate']);
	}

	$query = new WP_Query( $args );

	if ( $return_query ) {
		return $query;
	}

	return $query->have_posts() ? $query->posts : false;
}

function podcast_box_get_country( $post_id ) {
	$terms = wp_get_post_terms( $post_id, 'podcast_country' );

	return $terms ? $terms[0] : false;

}

function podcast_box_get_episode_data( $post_id ) {
	$podcast_id = podcast_box_get_episode_podcast( $post_id );

	$data = [
		'episode_id'    => $post_id,
		'episode_title' => get_the_title( $post_id ),
		'episode_logo'  => podcast_box_get_meta( $post_id, 'logo', PODCAST_BOX_ASSETS . '/images/placeholder.svg' ),
		'episode_url'   => get_the_permalink( $post_id ),
		'media'         => podcast_box_get_meta( $post_id, 'file' ),
		'podcast_id'    => $podcast_id,
		'podcast_title' => get_the_title( $podcast_id ),
		'podcast_url'   => get_the_permalink( $podcast_id ),
		'podcast_logo'  => podcast_box_get_meta( $podcast_id, 'logo' ),
	];

	return json_encode( $data );
}

/**
 * podcast listing
 */
function podcast_box_listing_page_content() {

	$queried_object = get_queried_object();

	global $wp_query;

	ob_start();
	podcast_box()->get_template( 'listing' );
	$html = ob_get_clean();

	$post_title = '';

	if ( ! empty( $queried_object->name ) ) {
		if ( in_array( $queried_object->taxonomy, [ 'podcast_country', 'podcast_category' ] ) ) {
			$post_title = $queried_object->name . __( ' - Podcasts', 'podcast-box' );
			//todo - add breadcrumb
			//$post_title = wp_radio_breadcrumb() . $post_title;
		}
	}


	$post_name = ! empty( $queried_object->slug ) ? $queried_object->slug : '';

	$dummy_post_properties = array(
		'ID'                    => 0,
		'post_status'           => 'publish',
		'post_author'           => '',
		'post_parent'           => 0,
		'post_type'             => 'page',
		'post_date'             => '',
		'post_date_gmt'         => '',
		'post_modified'         => '',
		'post_modified_gmt'     => '',
		'post_content'          => $html,
		'post_title'            => apply_filters( 'the_title', $post_title ),
		'post_excerpt'          => '',
		'post_content_filtered' => '',
		'post_mime_type'        => '',
		'post_password'         => '',
		'post_name'             => $post_name,
		'guid'                  => '',
		'menu_order'            => 0,
		'pinged'                => '',
		'to_ping'               => '',
		'ping_status'           => '',
		'comment_status'        => 'closed',
		'comment_count'         => 0,
		'filter'                => 'raw',
	);

	// Set the $post global.
	$post = new WP_Post( (object) $dummy_post_properties );

	// Copy the new post global into the main $wp_query.
	$wp_query->post  = $post;
	$wp_query->posts = array( $post );

	// Prevent comments form from appearing.
	$wp_query->post_count = 1;
	$wp_query->is_page    = true;
	$wp_query->is_single  = true;
	if ( is_tax() ) {
		$wp_query->is_single = false;
		$wp_query->is_tax    = true;
	}
	$wp_query->max_num_pages = 0;

	// Prepare everything for rendering.
	setup_postdata( $post );
	remove_all_filters( 'the_content' );
	remove_all_filters( 'the_excerpt' );
}

function podcast_box_template_redirect() {
	if ( is_tax( 'podcast_category' ) || is_tax( 'podcast_country' ) ) {
		podcast_box_listing_page_content();
	}
}

add_action( 'template_redirect', 'podcast_box_template_redirect' );

function podcast_box_get_podcasts_by_country( $country = '' ) {

	if ( ! empty( $country ) ) {
		$country = explode( ',', $country );
		$country = array_map( 'sanitize_key', $country );
	} else {
		$country = podcast_box_get_visitor_country();
	}

	global $podcast_box_args;

	if ( $country ) {
		$podcast_box_args['tax_query'] = [
			'relation' => 'AND',
			[
				'taxonomy' => 'podcast_country',
				'field'    => 'slug',
				'terms'    => $country,
			]
		];
	}

	$query = podcast_box_get_podcasts( $podcast_box_args, true );

	if ( ! $query->have_posts() ) {
		$query = podcast_box_get_podcasts( [], true );
	}

	return $query;
}

function podcast_box_get_visitor_country() {
	global $wpdb;

	$ip = podcast_box_get_user_ip();

	$table = $wpdb->prefix . 'podcast_box_visitors';

	$sql      = $wpdb->prepare( "SELECT country_code FROM {$table} WHERE ip=%s LIMIT 1;", $ip );
	$saved_ip = $wpdb->get_var( $sql );


	if ( $saved_ip ) {
		return $saved_ip;
	}

	$json_feed_url = 'http://ip-api.com/json/' . $ip;
	$args          = [
		'timeout' => 120,
	];

	$response = wp_remote_get( $json_feed_url, $args );

	if ( is_wp_error( $response ) ) {
		return false;
	}

	$json_feed = json_decode( $response['body'] );

	if ( ! empty( $json_feed->countryCode ) ) {
		$country_code = strtolower( $json_feed->countryCode );

		$sql = "INSERT INTO 
                        {$table} (`ip`,`country_code`) 
                    VALUES 
                        ( %s, %s)
                    ON DUPLICATE KEY UPDATE
                        `country_code` = VALUES (country_code)  
                ";

		$wpdb->query( $wpdb->prepare( $sql, [ $ip, $country_code ] ) );

		return $country_code;
	}

	return false;

}

function podcast_box_get_user_ip() {
	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		//ip from share internet
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		//ip pass from proxy
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}

	return $ip;
}

function podcast_box_pagination( $paged, $total, $type = 'podcast', $podcast_id = false ) {

	global $podcast_box_args;
	$is_podcast = 'podcast' == $type;

	$current = max( 1, $paged );

	ob_start();
	if ( $total > 1 ) { ?>
        <nav id="post-navigation" class="navigation pagination" role="navigation" aria-label="Post Navigation">
            <div class="nav-links">
				<?php

				$translated = __( 'Page', 'podcast-box' ); // Supply translatable string

				echo paginate_links( array(
					'format'             => '?paginate=%#%',
					'current'            => $current,
					'total'              => $total,
					'before_page_number' => '<span class="screen-reader-text">' . $translated . ' </span>',
					'mid_size'           => 1,
					'prev_text'          => '<i class="dashicons dashicons-arrow-left-alt"></i>',
					'next_text'          => '<i class="dashicons dashicons-arrow-right-alt"></i>',
				) );

				?>
            </div>
        </nav>

        <div class="podcast-box-loader"></div>

        <div class="podcast-box-pagination-form">
            <label for="podcast-box-pagination-form-input"><?php _e( 'Go to page', 'podcast-box' ); ?></label>
            <input min="1" max="<?php echo esc_attr($total); ?>" type="number" class="podcast-box-pagination-form-input" id="podcast-box-pagination-form-input" value="<?php echo $current; ?>">
            <button type="button" class="podcast-box-pagination-form-submit">
                <span>Go</span>
                <i class="dashicons dashicons-arrow-right-alt2"></i>
            </button>
        </div>
	<?php }
	$html = ob_get_clean();

	if ( $is_podcast ) {
		printf( '<div class="podcast-box-pagination" data-args=\'%s\' data-type="podcast" >%s</div>', json_encode( $podcast_box_args ),
			$html );
	} else {
		printf( '<div class="podcast-box-pagination" data-type="episode" data-podcast_id="%s" >%s</div>', $podcast_id, $html );
	}

}

function podcast_box_get_country_flag( $country_code, $size = 16, $lazyload = false ) {

	if ( strlen( $country_code ) != 2 ) {
		return '';
	}

	$url = PODCAST_BOX_ASSETS . "/images/flags/${country_code}.svg";
	if($lazyload){
		return sprintf('<img class="podcast-box-lazy-load" data-src="%s" width="%s">', $url, $size);
	}

	return sprintf( '<img src="%s" width="%s">', $url, $size );
}

function podcast_box_get_episode_count($podcast_id){
	global $wpdb;
	$sql = $wpdb->prepare( "SELECT count(p.ID) FROM $wpdb->posts p LEFT JOIN $wpdb->postmeta m ON p.ID = m.post_id WHERE m.meta_key = %s AND m.meta_value = %s;", [ 'podcast', $podcast_id ] );

	return $wpdb->get_var( $sql );
}

function podcast_box_get_episode_ids( $podcast_id, $order = 'ASC', $offset = 0, $limit = 999 ) {
	global $wpdb;
	$sql
		= $wpdb->prepare( "SELECT SQL_CALC_FOUND_ROWS episode_id FROM {$wpdb->prefix}podcast_episode_relation WHERE podcast_id = %d ORDER BY episode_id {$order} LIMIT %d, %d;",
		[ $podcast_id, $offset, $limit ] );


	if ( 1 == $limit ) {
		return $wpdb->get_var( $sql );
	}

	return $wpdb->get_col( $sql );
}

function podcast_box_get_episode_podcast( $episode_id ) {
	global $wpdb;
	$sql = $wpdb->prepare( "SELECT podcast_id FROM {$wpdb->prefix}podcast_episode_relation WHERE episode_id = %d;", [ $episode_id ] );

	return $wpdb->get_var( $sql );
}

function podcast_box_delete_episode_relation( $episode_id ) {
	global $wpdb;

	$wpdb->delete( $wpdb->prefix . 'podcast_episode_relation', [ 'episode_id' => $episode_id ], [ '%d' ] );
}

function podcast_box_delete_podcast_relation( $podcast_id ) {
	global $wpdb;

	$wpdb->delete( $wpdb->prefix . 'podcast_episode_relation', [ 'podcast_id' => $podcast_id ], [ '%d' ] );
}

function podcast_box_insert_podcast_episode_relation( $podcast_id, $episode_id ) {

	global $wpdb;
	$table = $wpdb->prefix . 'podcast_episode_relation';

	$sql = "INSERT INTO {$table} (`podcast_id`,`episode_id`) 
                    VALUES  (%d, %d)
                    ON DUPLICATE KEY UPDATE 
                    `podcast_id` = VALUES(podcast_id) 
                ";

	$wpdb->query( $wpdb->prepare( $sql, [
		$podcast_id,
		$episode_id,
	] ) );

}

function podcast_box_localize_array() {
	$is_popup = ! empty( $_GET['podcast_player'] );

	return [
		'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
		'siteUrl'       => site_url(),
		'nonce'         => wp_create_nonce( 'podcast-box' ),
		'volume'        => podcast_box_get_settings( 'player_volume', 70, 'podcast_box_player_settings' ),
		'isPremium'     => true,
		'isPopupWindow' => $is_popup,
		'popup_width'   => podcast_box_get_settings( 'popup_player_width', 400, 'podcast_box_player_settings' ),
		'popup_height'  => podcast_box_get_settings( 'popup_player_height', 330, 'podcast_box_player_settings' ),
		'i18n'          => [
			'showPlayer'  => esc_html__( 'Show Radio Player', 'podcast-box' ),
			'pause'       => esc_html__( 'Pause', 'podcast-box' ),
			'sending'     => esc_html__( 'Sending...', 'podcast-box' ),
			'sendMessage' => esc_html__( 'Send Message', 'podcast-box' ),
		],
	];
}

function podcast_box_get_next_prev_data( $current_id, $next_prev = 'next' ) {

	global $post;
	$post = get_post( $current_id );

	if('next' == $next_prev && get_next_post()){
		$post_id = get_next_post()->ID;
    }elseif (get_previous_post()){
		$post_id = get_previous_post()->ID;
	}

	return ! empty( $post_id ) ? podcast_box_get_episode_data( $post_id ) : false;
}