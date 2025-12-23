<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corndog Maxx</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="images/cmremove.ico" sizes="any">
    <link rel="icon" href="images/cmremove.svg" type="image/svg+xml">
    @livewireStyles
</head>

<body class="bg-gray-50 text-gray-900 flex items-center justify-center min-h-screen">
    {{ $slot }}
    @livewireScripts
</body>
</html>
