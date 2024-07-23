<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('オーナー情報') }}
        </h2>
    </x-slot>
    @if (session('status'))
        <div class="alert alert-{{ session('status') }}">
            {{ session('message') }}
        </div>
    @endif
    <div class="font-semibold text-2xl text-blue-600 leading-tight mt-4 mb-4 text-center">
      {{ $name }}様
    </div>
    <div class="font-semibold text-xl text-gray-600 leading-tight mt-4 mb-4 ml-12">
      オーナー情報
    </div>
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
          </table>
      </div>
    </div>

    <div class="flex mt-4 items-center text-sm font-medium justify-around">
        <button type="button" onclick="location.href='{{ route('owner.dashboard') }}'" class="text-black bg-gray-200 border-0 py-2 px-8 focus:outline-none hover:bg-gray-400 rounded text-lg">戻る</button>
    </div>

    <div class="font-semibold text-xl text-gray-600 leading-tight mt-4 mb-4 ml-12">
      オーナー情報変更は下記からお願いします
    </div>

    <form method="POST" action="{{ route('owner.confirm', ['owner' => $owner->id]) }}">
      @csrf
      <div class="flex justify-center">
          <div class="w-3/4">
              <table class="table-auto w-full border-collapse bg-blue-300 rounded-lg">
                  <tr>
                      <td class="px-4 py-2 w-1/4 font-semibold text-base text-gray-100 border border-gray-200">
                          <label for="name">名前</label>
                      </td>
                      <td class="px-4 py-2 w-3/4 border border-gray-200">
                          <input class="w-full bg-gray-100 focus:outline-none focus:bg-white border border-gray-300 rounded-md px-3 py-2" type="text" name="name" id="name" value="{{ $name }}" required>
                      </td>
                  </tr>
                  <tr>
                      <td class="px-4 py-2 w-1/4 font-semibold text-base text-gray-100 border border-gray-200">
                          <label for="email">メールアドレス</label>
                      </td>
                      <td class="px-4 py-2 border border-gray-200">
                          <input id="email" class="w-full bg-gray-100 focus:outline-none focus:bg-white border border-gray-300 rounded-md px-3 py-2" type="email" name="email" value="{{ $email }}" required>
                      </td>
                  </tr>
                  <tr>
                      <td class="px-4 py-2 w-1/4 font-semibold text-base text-gray-100 border border-gray-200">
                          <label for="password">パスワード</label>
                      </td>
                      <td class="px-4 py-2 border border-gray-200">
                          <input id="password" class="w-full bg-gray-100 focus:outline-none focus:bg-white border border-gray-300 rounded-md px-3 py-2" type="password" name="password" required>
                      </td>
                  </tr>
                  <tr>
                      <td class="px-4 py-2 w-1/4 font-semibold text-base text-gray-100 border border-gray-200">
                          <label for="password_confirmation">パスワード確認</label>
                      </td>
                      <td class="px-4 py-2 border border-gray-200">
                          <input id="password_confirmation" class="w-full bg-gray-100 focus:outline-none focus:bg-white border border-gray-300 rounded-md px-3 py-2" type="password" name="password_confirmation" required>
                      </td>
                  </tr>
              </table>
          </div>
      </div>
      
      
    
      <div class="flex mt-4 mb-24 items-center text-sm font-medium justify-around">
        <button type="button" onclick="location.href='{{ route('owner.dashboard') }}'" class="text-black bg-gray-200 border-0 py-2 px-8 focus:outline-none hover:bg-gray-400 rounded text-lg">戻る</button>
        <button type="submit" class="text-white bg-purple-500 border-0 py-2 px-8 focus:outline-none hover:bg-purple-600 rounded text-lg">確認</button>
      </div>      
    </form>
</x-app-layout>



