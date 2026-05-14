<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="light">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @hasSection('meta')
        @yield('meta')
    @endif

    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,400;0,700;1,400&family=Manrope:wght@300;400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "secondary-fixed-dim": "#c8c6c6",
                        "surface-container": "#f0eee8",
                        "secondary-container": "#e4e2e1",
                        "on-error-container": "#93000a",
                        "error": "#ba1a1a",
                        "outline": "#7f7667",
                        "on-primary": "#ffffff",
                        "background": "#fbf9f3",
                        "tertiary-fixed-dim": "#d4c4b0",
                        "secondary": "#5f5e5e",
                        "on-secondary-fixed-variant": "#474747",
                        "on-tertiary-fixed-variant": "#504536",
                        "primary-container": "#c5a059",
                        "inverse-on-surface": "#f2f1eb",
                        "secondary-fixed": "#e4e2e1",
                        "tertiary-fixed": "#f0e0cb",
                        "tertiary-container": "#b1a390",
                        "inverse-surface": "#30312d",
                        "outline-variant": "#d1c5b4",
                        "on-background": "#1b1c19",
                        "on-error": "#ffffff",
                        "on-tertiary": "#ffffff",
                        "surface": "#fbf9f3",
                        "surface-bright": "#fbf9f3",
                        "surface-container-lowest": "#ffffff",
                        "primary-fixed-dim": "#e9c176",
                        "on-secondary": "#ffffff",
                        "surface-tint": "#775a19",
                        "tertiary": "#685d4c",
                        "on-surface-variant": "#4e4639",
                        "primary": "#775a19",
                        "surface-container-high": "#eae8e2",
                        "on-primary-fixed-variant": "#5d4201",
                        "surface-dim": "#dbdad4",
                        "on-primary-container": "#4e3700",
                        "on-surface": "#1b1c19",
                        "on-tertiary-container": "#433a2b",
                        "on-primary-fixed": "#261900",
                        "surface-variant": "#e4e2dd",
                        "on-tertiary-fixed": "#221a0d",
                        "surface-container-low": "#f5f3ee",
                        "on-secondary-container": "#656464",
                        "primary-fixed": "#ffdea5",
                        "inverse-primary": "#e9c176",
                        "surface-container-highest": "#e4e2dd",
                        "on-secondary-fixed": "#1b1c1c",
                        "error-container": "#ffdad6"
                    },
                    "borderRadius": {
                        "DEFAULT": "1rem",
                        "lg": "2rem",
                        "xl": "3rem",
                        "full": "9999px"
                    },
                    "fontFamily": {
                        "headline": ["Noto Serif"],
                        "body": ["Manrope"],
                        "label": ["Manrope"]
                    }
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24;
        }
        body {
            background-color: #fdfaf5;
            color: #1b1c19;
            font-family: 'Manrope', sans-serif;
        }
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#fdfaf5]">
    @yield('content')
    @stack('scripts')
</body>
</html>
