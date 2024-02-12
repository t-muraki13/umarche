<x-tests.app>
    <x-slot name="header">
      header1
    </x-slot>
    test

    <x-tests.card title="タイトル" content="本文" :message="$message" />
    <x-tests.card title="タイトル2" />
    <x-tests.card title="CSSを変更したい。" class="bg-red-500" />

</x-tests.app>