<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <a href="{{ route('product.create') }}" class="px-4 py-2 text-white duration-150 ease-in bg-blue-600 rounded-md btn btn-primary hover:bg-sky-500 " > Add New Products to Mercado Livre</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
    