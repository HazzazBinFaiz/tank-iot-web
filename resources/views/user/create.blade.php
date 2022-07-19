<x-app-layout :title="__('Create User')">

    <div class="py-6 flex justify-between">
        <div class="text-3xl">{{ __('Create User') }}</div>
        <div>
            <a class="text-primary-700 underline font-semibold" href="{{ route('user.index') }}">{{ __('Users') }}</a>
        </div>
    </div>

    <form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="flex flex-wrap justify-center w-full bg-white p-4">
            <x-labeled-input name="name" required class="w-full p-1 md:w-1/2 lg:w-1/3"/>
            <x-labeled-input name="email" required class="w-full p-1 md:w-1/2 lg:w-1/3"/>
            <x-labeled-input type="password" name="password" required class="w-full p-1 md:w-1/2 lg:w-1/3"/>
            <x-labeled-input type="password" name="password_confirmation" required class="w-full p-1 md:w-1/2 lg:w-1/3"/>
            <x-labeled-input type="file" accept="image/jpeg,image/png" name="avatar" class="w-full p-1 md:w-1/2 lg:w-1/3"/>
            <div class="w-full pt-4 flex justify-end">
                <x-button>{{ __('Create') }}</x-button>
            </div>
        </div>
    </form>
</x-app-layout>
