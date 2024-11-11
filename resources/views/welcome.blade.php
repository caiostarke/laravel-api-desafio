<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite('resources/css/app.css')

    </head>

    <body class="font-sans antialiased dark:text-white/50">
        
        <div class="flex flex-col items-center min-h-screen pt-6 bg-gray-100 sm:justify-center sm:pt-0">
            <a href="{{ route('code.get') }}" class="px-4 py-2 text-white duration-150 ease-in bg-blue-600 rounded-md btn btn-primary hover:bg-sky-500 ">Grantee Access on Mercado Livre</a>
        </div>


    </body>
</html>
