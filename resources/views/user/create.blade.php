<x-app-layout :title="__('Create User')">
    <x-slot name="header">
        <div class="w-full flex justify-between">
            <div class="text-xl">{{ __('Create Users') }}</div>
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


    <form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="flex flex-wrap justify-center w-full bg-white p-4">
            <x-labeled-input name="name" required class="w-full p-1 md:w-1/2"/>
            <x-labeled-input name="email" required class="w-full p-1 md:w-1/2"/>
            <x-labeled-input type="password" name="password" required class="w-full p-1 md:w-1/2"/>
            <x-labeled-input type="password" name="password_confirmation" required class="w-full p-1 md:w-1/2"/>
            <x-labeled-input type="file" accept="image/jpeg,image/png" name="avatar" class="w-full p-1 md:w-1/2"/>
            <div class="w-full pt-8 py-4 flex justify-center">
                <x-button>{{ __('Create') }}</x-button>
            </div>
        </div>
    </form>
</x-app-layout>
