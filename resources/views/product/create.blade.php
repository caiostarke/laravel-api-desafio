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


                @if(session('error'))
                    <div class="p-3 my-5 text-white bg-red-400 rounded-sm alert alert-danger">
                    <p>{{ session('error')['message'] }}</p>
                        <ul>
                            @foreach(session('error')['cause'] as $cause)
                                <li>{{ $cause['message'] }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                @if(session('success'))
                    <div class="p-3 my-5 text-white bg-green-400 rounded-sm alert alert-danger alert-success">
                        {{ session('success') }}
                        
                        @if(session('productID'))
                            <p>product ID: {{ session('productID') }}</p>
                        @endif

                        @if(session('productStatus'))
                            <p>product Status: {{ session('productStatus') }}</p>
                        @endif
                    </div>
                @endif


        
                <form action="{{route('product.store')}}" method="POST" enctype="multipart/form-data">
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
                        <select onchange="fetchAttributes(this.value)" id="category" name="category" class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @foreach($categories as $category)
                                <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                            @endforeach
                        </select>
                    </div>


                <div class="mb-4 text-gray-700">
                    <label for="quantity" class="block text-sm font-medium text-gray-700">Link da Imagem</label>
                    <input type="text" id="image" name="image" class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                </div>

                <div id="attributes" name="attributes" class="flex flex-col gap-2 text-gray-500">
                    
                </div>

                    <div class="flex mt-12">
                        <button type="submit" class="px-6 py-3 text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Cadastrar</button>
                    </div>
                </form>
            </div>

        </div>

        <script>
            function fetchAttributes(categoryId) {
                fetch(`/categories/${categoryId}/attributes`)
                    .then(response => response.json())
                    .then(data => {
                        let attributesDiv = document.getElementById('attributes');
                        attributesDiv.innerHTML = ''; // Clear previous attributes

                        data.forEach(attribute => {
                            let label = document.createElement('label');
                            label.textContent = attribute.name;
        
                            let input = document.createElement('input');
                            input.name = `attributes[${attribute.id}]`;
                            input.placeholder = attribute.name;

                            if (attribute.tags && attribute.tags.required) {
                                label.innerHTML += ' <span style="color: red;">* Required</span>';
                                input.required = true;
                            }

                            attributesDiv.appendChild(label);
                            attributesDiv.appendChild(input);
                        });
                    })
                    .catch(error => console.error('Error fetching attributes:', error));
            }
        </script>
        
        

    </body>
</html>
