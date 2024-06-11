<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('ホーム') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-wrap">
                            @foreach ($products as $product)
                            @php
                                $imageFilename = $product->imageFirst->filename ?? $product->imageSecond->filename ?? $product->imageThird->filename ?? $product->imageFourth->filename ?? '';
                            @endphp
                            <div class="w-1/4 p-2 md:p-4">
                                <a href="">
                                        <div class="border rounded-md p-2 md:p-4">
                                      <x-thumbnail filename="{{ $imageFilename ?? ''}}" type="products" />
                                          <div class="text-gray-700">{{ $product->name }}</div>
                                    </div>
                                </a>
                            </div>
                             @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
