@props(['href' => null, 'text'])

@if($href)
    <a
        href="{{ $href }}"
        class="w-full py-2 px-4 flex justify-between items-center hover:bg-slate-900 border-l-4 border-transparent hover:border-teal-400"
    >
        <span>{{ $text }}</span>
    </a>
@else
    <div class="w-full flex flex-col" x-data="{ open: false }">
        <a
            href="#"
            x-on:click.prevent="open = !open"
            class="w-full py-2 px-4 flex justify-between items-center border-l-4 border-transparent"
            x-bind:class="open ? 'bg-slate-700' : 'hover:bg-slate-900'"
        >
            <span>{{ $text }}</span>
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 text-slate-400 transition duration-200"
                x-bind:class="open ? '-rotate-90' : ''"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 19l-7-7 7-7"
                />
            </svg>
        </a>
        <div
            class="w-full flex flex-col pl-4 bg-slate-900"
            x-show="open"
            x-transition:enter="transition origin-top ease-out duration-100"
            x-transition:enter-start="opacity-0 scale-y-0"
            x-transition:enter-end="opacity-100 scale-y-100"
            x-transition:leave="transition origin-top ease-in duration-100"
            x-transition:leave-start="opacity-100 scale-y-100"
            x-transition:leave-end="opacity-0 scale-y-0"
            x-on:active="open = true;"
        >
            {{ $slot }}
        </div>
    </div>
@endif
