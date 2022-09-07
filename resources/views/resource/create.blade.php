<x-app-layout :title="$title = __($heading['create'] ?? 'Create '.Str::of($name)->title()->replace(['_', '-'], ' '))">
    <x-slot name="header">
        <div class="w-full flex justify-between">
            <div class="text-xl">{{ $title }}</div>
            @if(Route::has($name.'.index'))
            @can($name.'-read')
                <div>
                    <a
                        href="{{ route($name.'.index') }}"
                        class="bg-transparent text-sm hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-1 px-4 border border-blue-500 hover:border-transparent rounded"
                    >{{ __($heading['index'] ?? (string) Str::of($name)->title()->replace(['_', '-'], ' ')->plural()) }}</a>
                </div>
            @endcan
            @endif
        </div>
    </x-slot>


    <form action="{{ route($name.'.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="flex flex-wrap justify-center w-full bg-white p-4">
            @php
            $fieldCount = count($fields);
            if ($fieldCount === 1) $inputClass = 'w-full p-1';
            elseif ($fieldCount < 9) $inputClass = 'w-full p-1 md:w-1/2';
            else $inputClass = 'w-full p-1 md:w-1/2 lg:w-1/3';
            @endphp
            @foreach($fields as $field)
                @if($field->type === 'select')
                    <x-labeled-select :name="$field->name" :required="$field->required" :class="$inputClass.' '.$field->class" :extra-attributes="$field->extraAttributes">
                        @foreach($field->options as $value => $label)
                            <option @if(old($field->name, $field->value) == $value) selected @endif value="{{ $value }}" @if($label instanceof \App\Lib\Option) data-parent="{{ $label->parent }}" @endif>{{ $label instanceof \App\Lib\Option ? $label->label : $label }}</option>
                        @endforeach
                    </x-labeled-select>
                @else
                <x-labeled-input :type="$field->type" :value="$field->value" :name="$field->name" :required="$field->required" :class="$inputClass.' '.$field->class" :extra-attributes="$field->extraAttributes"/>
                @endif
            @endforeach
            <div class="w-full pt-8 py-4 flex justify-center">
                <x-button>{{ __('Create') }}</x-button>
            </div>
        </div>
    </form>

    <script type="text/javascript" src="{{ mix('js/depend-on.js') }}"></script>
</x-app-layout>
