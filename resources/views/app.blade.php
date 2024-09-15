<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" href="/wmmns-logo.ico" type="image/x-icon" />
        <meta name="title" content="{{ config('app.name', 'Laravel') }}">
        <meta name="description" content="The Waste Management with Mobile Notification System for CENRO is a mobile platform designed to enhance waste collection efficiency in Santiago City. It provides real-time updates, improves communication, and promotes resident participation in garbage disposal, leading to better sanitation and a cleaner environment in local barangays." />
        <meta name="keywords" content="Waste Management, Mobile Notification System, CENRO, Santiago City, Garbage Collection, Barangay Waste Disposal, Real-Time Updates, Sanitation, Hygiene" />

        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="{{ config('app.name', 'Laravel') }}">
        <meta property="og:description"
        content="The Waste Management with Mobile Notification System for CENRO is a mobile platform designed to enhance waste collection efficiency in Santiago City. It provides real-time updates, improves communication, and promotes resident participation in garbage disposal, leading to better sanitation and a cleaner environment in local barangays.">
        <meta property="og:image" content="{{ url('/wmmns-logo.jpg') }}">
        <meta property="og:image:alt" content="Wmmns platform preview">
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.ts', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased dark:bg-slate-900 min-h-screen">
        @inertia
    </body>
</html>
