<x-admin-layout :title="__('Edit User')">

    <div class="py-6 flex justify-between">
        <div class="text-3xl">{{ __('Edit User') }}</div>
        <div>
            <a class="text-primary-700 underline font-semibold" href="{{ route('admin.user.index') }}">{{ __('Users') }}</a>
        </div>
    </div>

    <form action="{{ route('admin.user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="flex flex-wrap justify-center w-full bg-white p-4">
            <x-labeled-input name="name" required value="{{ $user->name }}" class="w-full p-1 md:w-1/2 lg:w-1/3"/>
            <x-labeled-input name="email" required value="{{ $user->email }}" class="w-full p-1 md:w-1/2 lg:w-1/3"/>
            <x-labeled-input type="password" name="password" class="w-full p-1 md:w-1/2 lg:w-1/3"/>
            <x-labeled-input type="password" name="password_confirmation" class="w-full p-1 md:w-1/2 lg:w-1/3"/>
            <x-labeled-input type="file" name="avatar" class="w-full p-1 md:w-1/2 lg:w-1/3"/>
            <div class="w-full pt-4 flex justify-end">
                <x-button>{{ __('Edit') }}</x-button>
            </div>
        </div>
    </form>
</x-admin-layout>
