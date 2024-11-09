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
        <div class="flex items-center justify-center min-h-screen pt-4 bg-gray-100">
            <div class="w-full max-w-lg p-8 bg-white rounded-lg shadow-lg">
                <h2 class="mb-6 text-2xl font-semibold text-gray-800">Cadastrar Produto</h2>
        
                <form action="#" method="POST">
                    @csrf
        
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nome do Produto</label>
                        <input type="text" id="name" name="name" class="block w-full px-3 py-2 mt-1 text-gray-700 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>
        
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Descrição</label>
                        <textarea id="description" name="description" class="block w-full px-3 py-2 mt-1 text-gray-700 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" rows="4" required></textarea>
                    </div>
        
                    <div class="mb-4">
                        <label for="price" class="block text-sm font-medium text-gray-700">Preço</label>
                        <input type="number" id="price" name="price" class="block w-full px-3 py-2 mt-1 text-gray-700 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>
        
                    <div class="mb-4 text-gray-700">
                        <label for="quantity" class="block text-sm font-medium text-gray-700">Quantidade em Estoque</label>
                        <input type="number" id="quantity" name="quantity" class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>
        
                    <div class="mb-4 text-gray-700">
                        <label for="category" class="block text-sm font-medium text-gray-700">Categoria</label>
                        <select id="category" name="category" class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="1">Categoria 1</option>
                            <option value="2">Categoria 2</option>
                            <option value="3">Categoria 3</option>
                        </select>
                    </div>

                    <div class="relative">
                        <label title="Clique para carregar um arquivo" for="button2" class="flex items-center gap-4 px-6 py-4 cursor-pointer before:border-gray-400/60 hover:before:border-gray-300 group before:bg-gray-100 before:absolute before:inset-0 before:rounded-3xl before:border before:border-dashed before:transition-transform before:duration-300 ">
                            <div class="relative">
                                <span class="relative block text-base font-semibold text-blue-900 group-hover:text-blue-500">
                                    Carregue um arquivo
                                </span>
                                <span class="mt-0.5 block text-sm text-gray-500">Max 2 MB</span>
                            </div>
                        </label>
                        <input hidden="" type="file" id="button2">
                    </div>
            
                    <div class="flex mt-12">
                        <button type="submit" class="px-6 py-3 text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Cadastrar</button>
                    </div>
                </form>
            </div>

        </div>
        

    </body>
</html>
