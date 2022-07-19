<x-app-layout :title="__('User Details')">
    <x-slot name="header">
        <div class="w-full flex justify-between">
            <div class="text-xl">{{ __('User Details') }}</div>
            @can('user-read')
                <div>
                    <a
                        href="{{ route('user.index') }}"
                        class="bg-transparent text-sm hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-1 px-4 border border-blue-500 hover:border-transparent rounded"
                    >{{ __('Users') }}</a>
                </div>
            @endcan
        </div>
    </x-slot>

    <div class="w-full bg-white flex flex-wrap p-4">
        <div class="w-full md:w-1/2 flex justify-center p-2">
            <img class="h-64 w-64" src="{{ $user->avatar }}" alt="Avatar of {{ $user->name }}"/>
        </div>
        <div class="w-full md:w-1/2">
            <table>
                <tr>
                    <td class="p-2 font-semibold">{{ __('Name') }}</td>
                    <td class="p-2">{{ $user->name }}</td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold">{{ __('Email') }}</td>
                    <td class="p-2">{{ $user->email }}</td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold">{{ __('Email Verified') }}</td>
                    <td class="p-2 flex">
                        @if($user->hasVerifiedEmail())
                            <div class="rounded bg-green-300 py-1 px-2 text-xs font-semibold text-green-800">{{ __('Yes') }}</div>
                        @else
                            <div class="rounded bg-red-200 py-1 px-2 text-xs font-semibold text-red-800">{{ __('No') }}</div>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>
</x-app-layout>
