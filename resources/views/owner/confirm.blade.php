<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('変更情報確認') }}
        </h2>
    </x-slot>
    <div class="font-semibold text-2xl text-blue-600 leading-tight mt-4 mb-4 text-center">
      {{ $name }}様
    </div>
    <x-flash-message status="session('status')" />
    <div class="font-semibold text-xl text-gray-600 leading-tight mt-4 mb-4 ml-12">
      変更情報確認
    </div>

        <form method="POST" action="{{ route('owner.update', ['owner' => $owner->id]) }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="name" value="{{ $name }}">
            <input type="hidden" name="email" value="{{ $email }}">
            <input type="hidden" name="password" value="{{ $password }}">
            
            <div class="flex justify-center mb-24">
                <div class="w-3/4">
                    <table class="table-auto w-full">
                        <tr>
                            <td class="px-4 py-2 w-1/4 font-semibold text-base text-gray-700 border border-gray-200">
                                <p class="w-full px-3 py-2">名前</p>
                            </td>
                            <td class="px-4 py-2 w-3/4 border border-gray-200">
                                <p class="w-full px-3 py-2">{{ $name }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 w-1/4 font-semibold text-base text-gray-700 border border-gray-200">
                                <p class="w-full px-3 py-2">メールアドレス</p>
                            </td>
                            <td class="px-4 py-2 w-3/4 border border-gray-200">
                                <p class="w-full px-3 py-2">{{ $email }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 w-1/4 font-semibold text-base text-gray-700 border border-gray-200">
                                <p class="w-full px-3 py-2">パスワード</p>
                            </td>
                            <td class="px-4 py-2 w-3/4 border border-gray-200">
                                <p class="w-full px-3 py-2">{{ $password }}</p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
    
            <div class="flex mt-4 mb-24 items-center text-sm font-medium justify-around">
              <button type="button" onclick="location.href='{{ route('owner.dashboard') }}'" class="text-black bg-gray-200 border-0 py-2 px-8 focus:outline-none hover:bg-gray-400 rounded text-lg">戻る</button>
              <button type="submit" class="text-white bg-purple-500 border-0 py-2 px-8 focus:outline-none hover:bg-purple-600 rounded text-lg">登録</button>
            </div>
         
        </form>
</x-app-layout>