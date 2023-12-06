<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title')</title>
    <meta name="keywords" content="@yield('meta_keywords','Backlink Market')">
    <meta name="description" content="@yield('meta_description','Backlink Market')">
    <link rel="canonical" href="{{url()->current()}}" />
    <link href="{{ asset('css/app.css')}}" rel="preload stylesheet" as="style">
    <link href="{{ asset('css/bootstrap.min.css')}}" rel="preload stylesheet" as="style">
    <link href="{{ asset('css/style.css')}}" rel="preload stylesheet" as="style">
    <link href="{{ asset('css/plugin/owl.carousel.min.css')}}" rel="preload stylesheet" as="style">
    <link href="{{ asset('css/customstyle.css')}}" rel="preload stylesheet" as="style">
    @yield('robots')
    @yield('custom_css')
    @stack('vendorcss')
</head>

<body>
    @stack("navtype")
    {{-- Stack for type of navigation need it here --}}

    @section('pagecontent')
    @show
    {{-- Footer --}}
    <x-frontend.layout.footer /> {{-- Footer component--}}
    {{-- Footer --}}
    <script src="{{ asset('js/jquery-3.6.0.min.js')}}" defer></script>
    <script src="{{ asset('js/bootstrap.min.js')}}" defer></script>
    <script src="{{ asset('js/plugin/owl.carousel.min.js')}}" defer></script>
    <script src="{{ asset('js/main.js')}}" defer></script>

    @stack('customjs')

    <!-- Global site tag (gtag.js) - Google Analytics -->
    @if (env('GA_MEASUREMENT_ID')!='')
    <script type="text/plain" data-cookiecategory="analytics" async src="https://www.googletagmanager.com/gtag/js?id={{env('GA_MEASUREMENT_ID')}}"></script>
    <script type="text/plain" data-cookiecategory="analytics">
    console.log('"ads" category accepted');
        window.dataLayer = window.dataLayer || [];
        function gtag(){window.dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '{{env('GA_MEASUREMENT_ID')}}');
    </script>
    @endif
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/orestbida/cookieconsent@v2.8.0/dist/cookieconsent.css">
    <script src='https://cdn.jsdelivr.net/gh/orestbida/cookieconsent@v2.8.0/dist/cookieconsent.js' id='cookieconsent-js'></script>
    <script>
      window.addEventListener('load', function () {
        var cookieconsent = initCookieConsent();

        cookieconsent.run({
            autorun: true,
            current_lang : 'en',
            autoclear_cookies : true,                   // default: false
            cookie_name: 'cc_cookie',             // default: 'cc_cookie'
            cookie_expiration : 365,                    // default: 182
            page_scripts: true,
            mode: 'opt-in',
            hide_from_bots: true,
            // ...
            gui_options: {
                consent_modal: {
                    layout: 'cloud',               // box/cloud/bar
                    position: 'bottom center',     // bottom/middle/top + left/right/center
                    transition: 'slide',           // zoom/slide
                    swap_buttons: false            // enable to invert buttons
                },
                settings_modal: {
                    layout: 'box',                 // box/bar
                    // position: 'left',           // left/right
                    transition: 'slide'            // zoom/slide
                }
            },
            languages: {
                'en': {
                    consent_modal: {
                        title: 'We uses cookies! ',
                        description: 'This website uses essential cookies to ensure its proper operation and tracking cookies to understand how you interact with it. The latter will be set only after consent. <button type="button" data-cc="c-settings" class="cc-link">Let me choose</button>',
                        primary_btn: {
                            text: 'Accept all',
                            role: 'accept_all'              // 'accept_selected' or 'accept_all'
                        },
                        secondary_btn: {
                            text: 'Reject all',
                            role: 'accept_necessary'        // 'settings' or 'accept_necessary'
                        }
                    },
                    settings_modal: {
                        title: 'Cookie preferences',
                        save_settings_btn: 'Save settings',
                        accept_all_btn: 'Accept all',
                        reject_all_btn: 'Reject all',
                        close_btn_label: 'Close',
                        cookie_table_headers: [
                            {col1: 'Name'},
                            {col2: 'Domain'},
                            {col3: 'Expiration'},
                            {col4: 'Description'}
                        ],
                        blocks: [
                            {
                                title: 'Cookie usage 📢',
                                description: 'This website uses cookies to ensure the basic functionalities and to enhance your online experience. You can choose for each category to opt-in/out whenever you want. For more details relative to cookies and other sensitive data, please read the full <a href="{{route('privacy')}}" class="cc-link">privacy policy</a>.'
                            }, {
                                title: 'Strictly necessary cookies',
                                description: 'These cookies are essential for the proper functioning of this website. Without these cookies, the website would not work properly',
                                toggle: {
                                    value: 'necessary',
                                    enabled: true,
                                    readonly: true          // cookie categories with readonly=true are all treated as "necessary cookies"
                                },
                                cookie_table: [
                                    {
                                        col1: 'laravel_session',
                                        col2: 'backlink-market.com',
                                        col3: '1 year',
                                        col4: 'Used to record unique visitor views of the consent banner.'
                                    },
                                    {
                                        col1: 'XSRF-TOKEN',
                                        col2: 'backlink-market.com',
                                        col3: '1 hour',
                                        col4: 'This cookie is written to help with site security in preventing Cross-Site Request Forgery attacks.'
                                    }
                                    ,
                                    {
                                        col1: '^remember_web',
                                        col2: 'backlink-market.com',
                                        col3: '5 years',
                                        col4: 'This cookie indicates whether the user has checked the "Remember me" box.',
                                        is_regex: true

                                    }
                                    ,
                                    {
                                        col1: 'cc_cookie',
                                        col2: 'backlink-market.com',
                                        col3: '6 months',
                                        col4: 'This cookie is used internally for remembering the cookie consent.'
                                    },
                                ]
                            }, {
                                title: 'Performance and Analytics cookies',
                                description: 'These cookies allow the website to remember the choices you have made in the past',
                                toggle: {
                                    value: 'analytics',     // there are no default categories => you specify them
                                    enabled: false,
                                    readonly: false
                                },
                                cookie_table: [
                                    {
                                        col1: '^_ga',
                                        col2: 'google.com',
                                        col3: '2 years',
                                        col4: 'Google Analytics cookie used to collect information about how visitors use our website. We use the information to compile reports and to help us improve the website. The cookies collect information in a way that does not directly identify anyone, including the number of visitors to the website and blog, where visitors have come to the website from and the pages they visited.',
                                        is_regex: true
                                    },
                                    {
                                        col1: '_gid',
                                        col2: 'google.com',
                                        col3: '1 day',
                                        col4: 'Google Analytics cookie, used to distinguish users',
                                    }
                                ]
                            }, {
                                title: 'More information',
                                description: 'For any queries in relation to the policy on cookies and your choices, please use the <a class="cc-link" href="{{route('contact')}}">contact form</a>.',
                            }
                        ]
                    }
                }
            }
            //...
          });
        });
      </script>
</body>
</html>
