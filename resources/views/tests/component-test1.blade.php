<x-tests.app>
    <x-slot name="header">
      header1
    </x-slot>
    test

    <x-tests.card title="タイトル" content="本文" :message="$message" />
</x-tests.app>