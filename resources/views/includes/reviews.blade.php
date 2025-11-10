<div class="py-12 pt-32 lg:pt-18 md:py-12 border-b-2 border-gray-200 dark:border-gray-700" id="Reviews">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3 flex-wrap">
            <h3 class="text-xl text-[#46484D] dark:text-gray-100 font-medium">Reviews</h3>
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 md:w-6 md:h-6" viewBox="0 0 24 24" fill="#FDB022" stroke="#FDB022" stroke-width="2">
                    <path
                        d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                </svg>
                <span
                    class="text-xl md:text-2xl font-semibold text-gray-800 dark:text-gray-200">{{ number_format($reviews->avg('rating'), 1) }}</span>
                <span class="text-sm md:text-base text-gray-500 dark:text-gray-400">
                    ({{ $reviews->count() }} reviews)
                </span>
            </div>
        </div>
        @if (Auth::guard('wholesaler')->check())
            <button onclick="writeReview(this)"
                class="px-4 py-2 md:px-6 md:py-2.5 text-sm md:text-base border-2 border-[#003FB4] dark:border-blue-600 text-[#003FB4] dark:text-blue-400 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900 transition-colors font-medium self-start sm:self-auto">
                Write a review
            </button>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mt-6 md:mt-8">
        @forelse ($reviews as $review)
            <div class="bg-white dark:bg-gray-800 p-4 md:p-6 rounded-lg">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                    <div class="flex items-center gap-2">
                        <div class="flex gap-1">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $review->rating)
                                    <svg class="w-5 h-5 md:w-6 md:h-6" viewBox="0 0 24 24" fill="#FDB022"
                                        stroke="#FDB022" stroke-width="2">
                                        <path
                                            d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 md:w-6 md:h-6" viewBox="0 0 24 24" fill="none"
                                        stroke="#FDB022" stroke-width="2">
                                        <path
                                            d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                    </svg>
                                @endif
                            @endfor
                        </div>
                        <span
                            class="text-lg md:text-xl font-semibold text-gray-800 dark:text-gray-200">{{ $review->rating }}</span>
                    </div>
                    <div
                        class="flex flex-col sm:flex-row sm:items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                        <span>{{ $review->created_at->format('F j, Y') }}</span>
                        <span class="hidden sm:inline">•</span>
                        @forelse ($wholesalers as $wholesaler)
                            @if ($wholesaler->wholesaler_uid == $review->wholesaler_uid)
                                @php
                                    $countryCodes = [
                                        'af' => 'Afghanistan',
                                        'al' => 'Albania',
                                        'dz' => 'Algeria',
                                        'as' => 'American Samoa',
                                        'ad' => 'Andorra',
                                        'ao' => 'Angola',
                                        'ai' => 'Anguilla',
                                        'aq' => 'Antarctica',
                                        'ag' => 'Antigua and Barbuda',
                                        'ar' => 'Argentina',
                                        'am' => 'Armenia',
                                        'aw' => 'Aruba',
                                        'au' => 'Australia',
                                        'at' => 'Austria',
                                        'az' => 'Azerbaijan',
                                        'bs' => 'Bahamas',
                                        'bh' => 'Bahrain',
                                        'bd' => 'Bangladesh',
                                        'bb' => 'Barbados',
                                        'by' => 'Belarus',
                                        'be' => 'Belgium',
                                        'bz' => 'Belize',
                                        'bj' => 'Benin',
                                        'bm' => 'Bermuda',
                                        'bt' => 'Bhutan',
                                        'bo' => 'Bolivia',
                                        'bq' => 'Bonaire, Sint Eustatius and Saba',
                                        'ba' => 'Bosnia and Herzegovina',
                                        'bw' => 'Botswana',
                                        'bv' => 'Bouvet Island',
                                        'br' => 'Brazil',
                                        'io' => 'British Indian Ocean Territory',
                                        'bn' => 'Brunei Darussalam',
                                        'bg' => 'Bulgaria',
                                        'bf' => 'Burkina Faso',
                                        'bi' => 'Burundi',
                                        'cv' => 'Cabo Verde',
                                        'kh' => 'Cambodia',
                                        'cm' => 'Cameroon',
                                        'ca' => 'Canada',
                                        'ky' => 'Cayman Islands',
                                        'cf' => 'Central African Republic',
                                        'td' => 'Chad',
                                        'cl' => 'Chile',
                                        'cn' => 'China',
                                        'cx' => 'Christmas Island',
                                        'cc' => 'Cocos (Keeling) Islands',
                                        'co' => 'Colombia',
                                        'km' => 'Comoros',
                                        'cd' => 'Congo, Democratic Republic of the',
                                        'cg' => 'Congo, Republic of the',
                                        'ck' => 'Cook Islands',
                                        'cr' => 'Costa Rica',
                                        'ci' => 'Côte d\'Ivoire',
                                        'hr' => 'Croatia',
                                        'cu' => 'Cuba',
                                        'cw' => 'Curaçao',
                                        'cy' => 'Cyprus',
                                        'cz' => 'Czech Republic',
                                        'dk' => 'Denmark',
                                        'dj' => 'Djibouti',
                                        'dm' => 'Dominica',
                                        'do' => 'Dominican Republic',
                                        'ec' => 'Ecuador',
                                        'eg' => 'Egypt',
                                        'sv' => 'El Salvador',
                                        'gq' => 'Equatorial Guinea',
                                        'er' => 'Eritrea',
                                        'ee' => 'Estonia',
                                        'sz' => 'Eswatini',
                                        'et' => 'Ethiopia',
                                        'fk' => 'Falkland Islands (Malvinas)',
                                        'fo' => 'Faroe Islands',
                                        'fj' => 'Fiji',
                                        'fi' => 'Finland',
                                        'fr' => 'France',
                                        'gf' => 'French Guiana',
                                        'pf' => 'French Polynesia',
                                        'tf' => 'French Southern Territories',
                                        'ga' => 'Gabon',
                                        'gm' => 'Gambia',
                                        'ge' => 'Georgia',
                                        'de' => 'Germany',
                                        'gh' => 'Ghana',
                                        'gi' => 'Gibraltar',
                                        'gr' => 'Greece',
                                        'gl' => 'Greenland',
                                        'gd' => 'Grenada',
                                        'gp' => 'Guadeloupe',
                                        'gu' => 'Guam',
                                        'gt' => 'Guatemala',
                                        'gg' => 'Guernsey',
                                        'gn' => 'Guinea',
                                        'gw' => 'Guinea-Bissau',
                                        'gy' => 'Guyana',
                                        'ht' => 'Haiti',
                                        'hm' => 'Heard Island and McDonald Islands',
                                        'va' => 'Holy See (Vatican City State)',
                                        'hn' => 'Honduras',
                                        'hk' => 'Hong Kong',
                                        'hu' => 'Hungary',
                                        'is' => 'Iceland',
                                        'in' => 'India',
                                        'id' => 'Indonesia',
                                        'ir' => 'Iran, Islamic Republic of',
                                        'iq' => 'Iraq',
                                        'ie' => 'Ireland',
                                        'im' => 'Isle of Man',
                                        'il' => 'Israel',
                                        'it' => 'Italy',
                                        'jm' => 'Jamaica',
                                        'jp' => 'Japan',
                                        'je' => 'Jersey',
                                        'jo' => 'Jordan',
                                        'kz' => 'Kazakhstan',
                                        'ke' => 'Kenya',
                                        'ki' => 'Kiribati',
                                        'kp' => 'Korea, Democratic People\'s Republic of',
                                        'kr' => 'Korea, Republic of',
                                        'kw' => 'Kuwait',
                                        'kg' => 'Kyrgyzstan',
                                        'la' => 'Lao People\'s Democratic Republic',
                                        'lv' => 'Latvia',
                                        'lb' => 'Lebanon',
                                        'ls' => 'Lesotho',
                                        'lr' => 'Liberia',
                                        'ly' => 'Libya',
                                        'li' => 'Liechtenstein',
                                        'lt' => 'Lithuania',
                                        'lu' => 'Luxembourg',
                                        'mo' => 'Macao',
                                        'mg' => 'Madagascar',
                                        'mw' => 'Malawi',
                                        'my' => 'Malaysia',
                                        'mv' => 'Maldives',
                                        'ml' => 'Mali',
                                        'mt' => 'Malta',
                                        'mh' => 'Marshall Islands',
                                        'mq' => 'Martinique',
                                        'mr' => 'Mauritania',
                                        'mu' => 'Mauritius',
                                        'yt' => 'Mayotte',
                                        'mx' => 'Mexico',
                                        'fm' => 'Micronesia, Federated States of',
                                        'md' => 'Moldova, Republic of',
                                        'mc' => 'Monaco',
                                        'mn' => 'Mongolia',
                                        'me' => 'Montenegro',
                                        'ms' => 'Montserrat',
                                        'ma' => 'Morocco',
                                        'mz' => 'Mozambique',
                                        'mm' => 'Myanmar',
                                        'na' => 'Namibia',
                                        'nr' => 'Nauru',
                                        'np' => 'Nepal',
                                        'nl' => 'Netherlands',
                                        'nc' => 'New Caledonia',
                                        'nz' => 'New Zealand',
                                        'ni' => 'Nicaragua',
                                        'ne' => 'Niger',
                                        'ng' => 'Nigeria',
                                        'nu' => 'Niue',
                                        'nf' => 'Norfolk Island',
                                        'mk' => 'North Macedonia',
                                        'mp' => 'Northern Mariana Islands',
                                        'no' => 'Norway',
                                        'om' => 'Oman',
                                        'pk' => 'Pakistan',
                                        'pw' => 'Palau',
                                        'ps' => 'Palestine, State of',
                                        'pa' => 'Panama',
                                        'pg' => 'Papua New Guinea',
                                        'py' => 'Paraguay',
                                        'pe' => 'Peru',
                                        'ph' => 'Philippines',
                                        'pn' => 'Pitcairn',
                                        'pl' => 'Poland',
                                        'pt' => 'Portugal',
                                        'pr' => 'Puerto Rico',
                                        'qa' => 'Qatar',
                                        're' => 'Réunion',
                                        'ro' => 'Romania',
                                        'ru' => 'Russian Federation',
                                        'rw' => 'Rwanda',
                                        'bl' => 'Saint Barthélemy',
                                        'sh' => 'Saint Helena, Ascension and Tristan da Cunha',
                                        'kn' => 'Saint Kitts and Nevis',
                                        'lc' => 'Saint Lucia',
                                        'mf' => 'Saint Martin (French part)',
                                        'pm' => 'Saint Pierre and Miquelon',
                                        'vc' => 'Saint Vincent and the Grenadines',
                                        'ws' => 'Samoa',
                                        'sm' => 'San Marino',
                                        'st' => 'Sao Tome and Principe',
                                        'sa' => 'Saudi Arabia',
                                        'sn' => 'Senegal',
                                        'rs' => 'Serbia',
                                        'sc' => 'Seychelles',
                                        'sl' => 'Sierra Leone',
                                        'sg' => 'Singapore',
                                        'sx' => 'Sint Maarten (Dutch part)',
                                        'sk' => 'Slovakia',
                                        'si' => 'Slovenia',
                                        'sb' => 'Solomon Islands',
                                        'so' => 'Somalia',
                                        'za' => 'South Africa',
                                        'gs' => 'South Georgia and the South Sandwich Islands',
                                        'ss' => 'South Sudan',
                                        'es' => 'Spain',
                                        'lk' => 'Sri Lanka',
                                        'sd' => 'Sudan',
                                        'sr' => 'Suriname',
                                        'sj' => 'Svalbard and Jan Mayen',
                                        'se' => 'Sweden',
                                        'ch' => 'Switzerland',
                                        'sy' => 'Syrian Arab Republic',
                                        'tw' => 'Taiwan, Province of China',
                                        'tj' => 'Tajikistan',
                                        'tz' => 'Tanzania, United Republic of',
                                        'th' => 'Thailand',
                                        'tl' => 'Timor-Leste',
                                        'tg' => 'Togo',
                                        'tk' => 'Tokelau',
                                        'to' => 'Tonga',
                                        'tt' => 'Trinidad and Tobago',
                                        'tn' => 'Tunisia',
                                        'tr' => 'Turkey',
                                        'tm' => 'Turkmenistan',
                                        'tc' => 'Turks and Caicos Islands',
                                        'tv' => 'Tuvalu',
                                        'ug' => 'Uganda',
                                        'ua' => 'Ukraine',
                                        'ae' => 'United Arab Emirates',
                                        'gb' => 'United Kingdom',
                                        'us' => 'United States',
                                        'um' => 'United States Minor Outlying Islands',
                                        'uy' => 'Uruguay',
                                        'uz' => 'Uzbekistan',
                                        'vu' => 'Vanuatu',
                                        've' => 'Venezuela, Bolivarian Republic of',
                                        'vn' => 'Viet Nam',
                                        'vg' => 'Virgin Islands, British',
                                        'vi' => 'Virgin Islands, U.S.',
                                        'wf' => 'Wallis and Futuna',
                                        'eh' => 'Western Sahara',
                                        'ye' => 'Yemen',
                                        'zm' => 'Zambia',
                                        'zw' => 'Zimbabwe',
                                    ];

                                    $countryName = $countryCodes[$wholesaler->country] ?? $wholesaler->country;
                                @endphp
                                <span>{{ $wholesaler->company_name }}, {{ $countryName }}</span>
                            @endif
                        @empty
                        @endforelse
                    </div>
                </div>
                @if (isset($review->title))
                    <h4 class="text-base md:text-lg font-medium text-gray-800 dark:text-gray-200 mb-3">
                        {{ $review->title }}
                    </h4>
                @endif
                <p class="text-sm md:text-base text-gray-600 dark:text-gray-300 leading-relaxed">
                    {{ $review->review_text }}
                </p>
            </div>
        @empty
            <div class="col-span-2 p-8 rounded-lg bg-[#F6F6F6] dark:bg-gray-800 mx-4 lg:mx-auto empty_results">
                <img src="/assets/images/empty_review.png" alt="" class="w-32 rounded-lg block mx-auto">
                <h3 class="text-xl my-4 text-[40px] text-center dark:text-gray-100">
                    No reviews yet
                </h3>
                <p class="text-[16px] text-gray-500 dark:text-gray-400 mb-2 text-center">
                    Have you interacted with {{ $spec_manufacturer->company_name_en ?? '[company name]' }}?
                </p>
                @if (Auth::guard('wholesaler')->check())
                    <p class="text-[16px] text-[#003FB4] dark:text-blue-400 mb-2 text-center cursor-pointer"
                        onclick="writeReview(this)">
                        Write a review
                    </p>
                @endif
            </div>
        @endforelse
    </div>
</div>
