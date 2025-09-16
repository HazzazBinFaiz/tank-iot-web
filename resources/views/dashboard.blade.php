<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="w-full p-2">
        <div class="w-full p-2 bg-white">
            API Key : {{ \App\Lib\Hexer::encode(auth()->id()) }}
        </div>
    </div>

    <div class="w-full flex flex-wrap">
        @foreach($cards as $card)
            <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 p-4">
                <div class="rounded w-full bg-slate-800 text-white shadow">
                    <div class="w-full p-4">
                        <div class="w-full text-xl">{{ $card->title }}</div>
                        <div class="w-full text-2xl text-right font-semibold">{{ $card->value }}</div>
                    </div>
                    @foreach($card->kv as $k => $v)
                        <div class="w-full border-t flex justify-between">
                            <span class="p-2">{{ $k }}</span>
                            <span class="p-2 font-semibold">{{ $v }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
