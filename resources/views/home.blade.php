<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        {{-- desployed --}}
        {{-- <script type="module" src="build/assets/app-0b799021.js"></script>
        <link rel="stylesheet" href="build/assets/app-e69bd1db.css">
        <link rel="stylesheet" href="build/assets/app-5a65b97b.css"> --}}
        {{-- dev --}}
        @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    </head>
    <body>
        <div class="bg-danger" id="app">
            hola
            <mainapp></mainapp>
            algo
        </div>
    </body>
</html>
