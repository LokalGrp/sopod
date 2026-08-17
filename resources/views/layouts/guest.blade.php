<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SOPOD')</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- The guest layout previously loaded Tailwind but not the design
         system, so the login screen used raw Tailwind defaults and looked
         like a different product. Including it here gives login the same
         tokens, palette, typography, inputs and buttons as the rest of
         NOMSUITE. Must come immediately after the CDN so tailwind.config
         is set before styles are generated. --}}
    @include('partials.theme')
</head>
<body class="bg-gray-900">
    @yield('content')
</body>
</html>