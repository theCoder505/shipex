@extends('layouts.surface.app')

@section('title', 'Your Wholesaler Profile')

@section('style')
    <link rel="stylesheet" href="/assets/css/manufacturer_profile.css">
    <style>
        .profile-card {
            background: white;
            border: 1px solid #E4E4E4;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 16px;
        }

        .profile-field {
            margin-bottom: 20px;
        }

        .profile-field-label {
            font-size: 12px;
            color: #6B7280;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .profile-field-value {
            font-size: 16px;
            color: #121212;
            font-weight: 500;
        }

        .edit-profile-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #003FB4;
            font-weight: 600;
            padding: 8px 0px;
            transition: all 0.2s;
        }

        .tab-navigation {
            display: flex;
            gap: 8px;
            border-bottom: 2px solid #E4E4E4;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .tab-item {
            padding: 12px 24px;
            cursor: pointer;
            color: #6B7280;
            font-weight: 500;
            border-bottom: 3px solid transparent;
            transition: all 0.2s;
            text-decoration: none;
        }

        .tab-item:hover {
            color: #003FB4;
        }

        .tab-item.active {
            color: #003FB4;
            border-bottom-color: #003FB4;
        }

        .settings-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px 0;
            border-bottom: 1px solid #BCBCBC;
        }

        .settings-item:last-child {
            border-bottom: none;
        }

        .help-box {
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            padding: 24px;
        }
    </style>
@endsection

@section('content')
    <div class="hero_section my-4 px-4 lg:px-8 max-w-[1600px] mx-auto">
        <!-- Header -->
        <div class="text-[#46484D] text-xl lg:text-[40px] mb-2">Personal Space</div>
        <div class="text-[#46484D] mb-6">Wholesaler name</div>

        <!-- Tab Navigation -->
        <div class="tab-navigation bg-white px-4 lg:px-0">
            <button type="button" onclick="switchTab('Profile')"
                class="tab-item @if ($page_type == 'profile') active @endif" data-tab="Profile">
                Profile
            </button>
            <button type="button" onclick="switchTab('Settings')"
                class="tab-item @if ($page_type == 'settings') active @endif" data-tab="Settings">
                Settings
            </button>
            <a href="/wholesaler/chats" class="tab-item">My Chats</a>
        </div>

        <!-- Profile Tab -->
        <div class="step-content active">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 @if ($page_type != 'profile') hidden @endif"
                id="Profile">
                <div class="lg:col-span-2">
                    <!-- Profile Information Card -->
                    <div class="profile-card lg:max-w-[900px]">
                        <form action="/wholesaler/update-profile-image" method="post" enctype="multipart/form-data"
                            class="text-center">
                            @csrf

                            @php
                                $currentImage = $wholesaler->profile_picture
                                    ? asset($wholesaler->profile_picture)
                                    : '/assets/images/user_default.png';
                            @endphp

                            <div class="mb-6">
                                <label for="changeProfileImage" class="inline-flex flex-col items-center cursor-pointer">
                                    <img id="previewProfileImage" src="{{ $currentImage }}" alt="user_image"
                                        class="profile_image">
                                    <span class="mt-2 text-sm text-gray-500">Click image to select a new one</span>
                                </label>

                                <input type="file" accept="image/*" name="profile_image" id="changeProfileImage"
                                    class="hidden" onchange="showImage(this)">
                            </div>

                            <div>
                                <button id="saveProfileImageBtn" type="submit"
                                    class="inline-flex items-center justify-center gap-2 px-6 py-2 rounded-md text-white font-semibold transition-colors bg-gradient-to-r from-[#9000b4] to-[#de00e6] hover:from-[#540085] hover:to-[#cb00ba] disabled:opacity-50 disabled:cursor-not-allowed"
                                    disabled>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 12h14M12 5l7 7-7 7"></path>
                                    </svg>
                                    Save
                                </button>
                            </div>
                        </form>


                        <div class="flex justify-start gap-4 items-start mb-6">
                            <h2 class="text-2xl font-semibold text-[#121212]">Profile Information</h2>
                            <a href="/wholesaler/profile-setup" class="edit-profile-btn">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                    </path>
                                </svg>
                                Edit
                            </a>
                        </div>

                        <div class="gap-6">
                            <!-- Company Name -->
                            <div class="profile-field">
                                <div class="profile-field-label">Company name</div>
                                <div class="profile-field-value capitalize">{{ $wholesaler->company_name ?? '-' }}</div>
                            </div>

                            <!-- Business Type -->
                            <div class="profile-field">
                                <div class="profile-field-label">Business Type</div>
                                <div class="profile-field-value capitalize">{{ $wholesaler->business_type ?? '-' }}</div>
                            </div>

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

                                $rawCountry = $wholesaler->country ?? null;
                                $countryKey = is_string($rawCountry) ? strtolower(trim($rawCountry)) : null;
                                $countryName = $countryCodes[$countryKey] ?? ($rawCountry ?? '-');
                            @endphp

                            <div class="profile-field">
                                <div class="profile-field-label">Country</div>
                                <div class="profile-field-value capitalize">{{ $countryName }}</div>
                            </div>

                            <!-- Industry Focus -->
                            <div class="profile-field">
                                <div class="profile-field-label">Industry focus</div>
                                <div class="profile-field-value capitalize">{{ $wholesaler->industry_focus ?? '-' }}</div>
                            </div>

                            <!-- Preferred Product Categories -->
                            <div class="profile-field lg:col-span-2">
                                <div class="profile-field-label">Preferred product categories</div>
                                <div class="profile-field-value capitalize">
                                    @if ($wholesaler->category && is_array($wholesaler->category))
                                        {{ implode(', ', $wholesaler->category) }}
                                    @else
                                        {{ $wholesaler->category ?? '-' }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Tab -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 @if ($page_type != 'settings') hidden @endif"
                id="Settings">
                <div class="lg:col-span-2">
                    <div class="profile-card max-w-[900px]">
                        <!-- Email Setting -->
                        <div class="settings-item">
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Email</p>
                                <p class="text-lg text-[#121212] font-medium">{{ $wholesaler->email }}</p>
                            </div>
                            <button class="edit-profile-btn" onclick="editEmailModal()">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                    </path>
                                </svg>
                                Edit
                            </button>
                        </div>

                        <!-- Password Setting -->
                        <div class="settings-item">
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Password</p>
                                <p class="text-lg text-[#121212] font-medium">*****************</p>
                            </div>
                            <button class="edit-profile-btn" onclick="editPwdModal()">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                    </path>
                                </svg>
                                Edit
                            </button>
                        </div>


                        <div class="flex justify-between gap-4 py-6 border-b border-[#BCBCBC]">
                            <div class="left">
                                <p class="text-xs">Default language</p>
                                <p class="text-lg text-[#121212] capitalize">{{ $wholesaler->language }}</p>
                            </div>
                            <div class="right">
                                <button class="edit-btn" onclick="editLangModal(this)">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                        </path>
                                    </svg>
                                    Edit
                                </button>
                            </div>
                        </div>

                        <!-- Delete Account -->
                        <div class="settings-item">
                            <button
                                class="border rounded-lg flex items-center gap-4 border-[#D01007] text-[#D01007] hover:text-white hover:bg-[#D01007] bg-red-50 px-4 py-2 transition-all"
                                onclick="deleteAccountModal()">
                                <i class="fa fa-trash"></i>
                                <span>Delete account</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Email Modal -->
    <div id="editEmailAddress" class="modal-overlay">
        <div class="modal-content filter_content">
            <img src="/assets/images/cross.png" alt="Close" class="modal-close" onclick="closeeditEmailAddress()">
            <div class="filter_text text-center py-4 text-lg lg:text-[32px] border-b border-[#BCBCBC]">
                Editing your email address
            </div>

            <form action="/wholesaler/change-email-address" method="post">
                @csrf

                <div class="py-10 px-6">
                    <div class="text-xs mb-2">Email</div>
                    <input type="email" class="w-full lg:w-[400px] rounded px-4 py-2 border border-[#BCBCBC]"
                        name="email_addr" placeholder="hello@example.com" value="{{ $wholesaler->email }}" required>
                </div>

                <div class="links grid lg:flex justify-end items-center gap-4 lg:gap-8 border-t border-[#BCBCBC] p-4">
                    <button type="button" class="text-[#003FB4] text-center px-4 py-2 font-semibold"
                        onclick="closeeditEmailAddress()">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-8 py-3 bg-[#003FB4] text-white rounded-lg text-base font-medium hover:bg-[#002d85] transition-colors">
                        Confirm email change
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Password Modal -->
    <div id="EditPassChange" class="modal-overlay">
        <div class="modal-content filter_content">
            <img src="/assets/images/cross.png" alt="Close" class="modal-close" onclick="closeEditPassChange()">
            <div class="filter_text text-center py-4 text-lg lg:text-[32px] border-b border-[#BCBCBC]">
                Editing your account password
            </div>

            <form action="/wholesaler/change-account-password" method="post">
                @csrf

                <div class="py-10 px-6">
                    <div class="mb-4 relative max-w-[400px]">
                        <label for="current_password" class="text-sm text-gray-700 mb-2 block">Current Password</label>
                        <input type="password" id="current_password" name="current_password" placeholder="********"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-[#003FB4]"
                            required>
                        <span class="absolute right-3 top-[38px] cursor-pointer text-gray-500"
                            onclick="passwordToggle(this)">
                            <i class="fa fa-eye"></i>
                        </span>
                    </div>

                    <div class="mb-4 relative max-w-[400px]">
                        <label for="password" class="text-sm text-gray-700 mb-2 block">New Password</label>
                        <input type="password" id="password" name="password" placeholder="********"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-[#003FB4]"
                            required>
                        <span class="absolute right-3 top-[38px] cursor-pointer text-gray-500"
                            onclick="passwordToggle(this)">
                            <i class="fa fa-eye"></i>
                        </span>
                    </div>

                    <div class="mb-1 relative max-w-[400px]">
                        <label for="password_confirmation" class="text-sm text-gray-700 mb-2 block">Confirm New
                            Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            placeholder="********"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-[#003FB4]"
                            required>
                        <span class="absolute right-3 top-[38px] cursor-pointer text-gray-500"
                            onclick="passwordToggle(this)">
                            <i class="fa fa-eye"></i>
                        </span>
                    </div>
                </div>

                <div class="links grid lg:flex justify-end items-center gap-4 lg:gap-8 border-t border-[#BCBCBC] p-4">
                    <button type="button" class="text-[#003FB4] text-center px-4 py-2 font-semibold"
                        onclick="closeEditPassChange()">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-8 py-3 bg-[#003FB4] text-white rounded-lg text-base font-medium hover:bg-[#002d85] transition-colors">
                        Confirm new password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div id="deleteAccountModal" class="modal-overlay">
        <div class="modal-content create_modal">
            <img src="/assets/images/cross.png" alt="Close" class="modal-close" onclick="closeDeleteAccountModal()">
            <img src="/assets/images/log-out.png" alt="User" class="w-24 h-24 rounded-lg block mx-auto">
            <div class="popup_text text-xl lg:text-[40px] my-6 text-center">
                Are you sure you want to<br>delete your account?
            </div>

            <p class="mb-8 text-[#46484D] text-center">
                You won't be able to access your profile or information again once you delete your account. If you have
                questions, please reach out to <a href="mailto:support@shipex.com"
                    class="text-[#003FB4]">support@shipex.com</a>
            </p>

            <div class="links grid lg:flex justify-center items-center gap-4 lg:gap-8">
                <button type="button" class="text-[#003FB4] text-center px-4 py-2 font-semibold"
                    onclick="closeDeleteAccountModal()">Cancel</button>
                <form action="/wholesaler/delete-account" method="post">
                    @csrf
                    <button type="submit"
                        class="bg-[#FEE0DE] rounded-lg px-4 py-3 cursor-pointer text-[#D01007] hover:text-white hover:bg-[#D01007] transition-all">
                        Delete account
                    </button>
                </form>
            </div>
        </div>
    </div>





    <div id="EditLangChange" class="modal-overlay">
        <div class="modal-content filter_content">
            <img src="/assets/images/cross.png" alt="Close" class="modal-close" onclick="closeEditLangChange()">
            <div class="filter_text text-center py-4 text-lg lg:text-[32px] border-b border-[#BCBCBC]">
                Editing your default language
            </div>

            <form action="/wholesaler/change-language-selection" method="post">
                @csrf

                <div class="py-10 px-6 grid gap-6">
                    <div class="rounded-lg p-4 flex gap-2 bg-[#DEEFFF] lg:w-[400px]">
                        <i class="fa fa-info-circle text-[#0B45B9]"></i>
                        <p class="text-[#0B45B9]">
                            This information will allow AI to automatically translate conversations into your default
                            language. It won't change the language of the interface.
                        </p>
                    </div>

                    <div class="mb-1">
                        <label for="language" class="text-sm text-gray-700 mb-2 block">Language</label>
                        <select id="language" name="language"
                            class="lg:w-[400px] border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-[#003FB4]"
                            required>
                            <option value="korean">Korean</option>
                            <option value="english">English</option>
                            <option value="spanish">Spanish</option>
                            <option value="french">French</option>
                            <option value="german">German</option>
                            <option value="italian">Italian</option>
                            <option value="portuguese">Portuguese</option>
                            <option value="russian">Russian</option>
                            <option value="japanese">Japanese</option>
                            <option value="chinese">Chinese (Simplified)</option>
                            <option value="chinese-traditional">Chinese (Traditional)</option>
                            <option value="arabic">Arabic</option>
                            <option value="hindi">Hindi</option>
                            <option value="bengali">Bengali</option>
                            <option value="dutch">Dutch</option>
                            <option value="turkish">Turkish</option>
                            <option value="polish">Polish</option>
                            <option value="vietnamese">Vietnamese</option>
                            <option value="thai">Thai</option>
                            <option value="indonesian">Indonesian</option>
                        </select>
                    </div>
                </div>

                <div class="links grid lg:flex justify-end items-center gap-4 lg:gap-8 border-t border-[#BCBCBC] p-4">
                    <button type="button" class="text-[#003FB4] text-center px-4 py-2 font-semibold"
                        onclick="closeEditLangChange()">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-8 py-3 bg-[#003FB4] text-white rounded-lg text-base font-medium hover:bg-[#002d85] transition-colors">
                        Confirm language change
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function switchTab(tabName) {
            // Remove active class from all tabs
            document.querySelectorAll('.tab-item').forEach(tab => {
                tab.classList.remove('active');
            });

            // Add active class to clicked tab
            event.target.classList.add('active');

            // Hide all content sections
            document.getElementById('Profile').classList.add('hidden');
            document.getElementById('Settings').classList.add('hidden');

            // Show selected content
            document.getElementById(tabName).classList.remove('hidden');

            // Update URL without reload
            const url = new URL(window.location);
            url.searchParams.set('tab', tabName.toLowerCase());
            window.history.pushState({}, '', url);
        }

        function editEmailModal() {
            document.getElementById('editEmailAddress').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeeditEmailAddress() {
            document.getElementById('editEmailAddress').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        function editPwdModal() {
            document.getElementById('EditPassChange').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeEditPassChange() {
            document.getElementById('EditPassChange').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        function deleteAccountModal() {
            document.getElementById('deleteAccountModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteAccountModal() {
            document.getElementById('deleteAccountModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }


        function editLangModal() {
            document.getElementById('EditLangChange').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }


        function closeEditLangChange() {
            document.getElementById('EditLangChange').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        function passwordToggle(el) {
            const input = el.parentElement.querySelector('input');
            const icon = el.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function showImage(input) {
            if (!input || !input.files || !input.files[0]) return;
            const file = input.files[0];
            if (!file.type.startsWith('image/')) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('previewProfileImage');
                if (img) img.src = e.target.result;
            };
            reader.readAsDataURL(file);

            const saveBtn = document.getElementById('saveProfileImageBtn');
            if (saveBtn) saveBtn.disabled = false;
        }

        // Make clicking the preview open file picker (in case label behavior differs)
        document.getElementById('previewProfileImage')?.addEventListener('click', function() {
            document.getElementById('changeProfileImage')?.click();
        });
    </script>
@endsection
