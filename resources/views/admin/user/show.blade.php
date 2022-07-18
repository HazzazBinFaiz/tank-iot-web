<x-admin-app-layout :title="__('User Details')">


    <div class="py-6 flex justify-between">
        <div class="text-3xl">{{ __('User Details') }}</div>
        <div>
            <a class="text-primary-700 underline font-semibold" href="{{ route('admin.user.index') }}">{{ __('Users') }}</a>
        </div>
    </div>

    <div class="w-full bg-white flex flex-wrap justify-end p-4">
        <div class="w-full md:w-1/2 lg:w-1/3 flex justify-center p-2">
            <img class="h-64 w-64" src="{{ $user->avatar }}" alt="Avatar of {{ $user->name }}"/>
        </div>
        <div class="w-full md:w-1/2 lg:w-1/3">
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
</x-admin-app-layout>
