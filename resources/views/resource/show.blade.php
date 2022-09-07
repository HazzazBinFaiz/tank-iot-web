<x-app-layout :title="$title = __($heading['show'] ?? Str::of($name)->title()->replace(['_', '-'], ' ').' Details')">
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

    <div class="w-full bg-white flex flex-wrap p-4">
        <div class="w-full">
            <table>
                @foreach($columns as $column)
                    <tr>
                        <td class="p-2 font-semibold">{{ __((string) Str::of($column)->title()->replace('_', ' ')) }}</td>
                        @if(is_string($column))
                            <td class="p-2">{{ $model->{$column} instanceof \BenSampo\Enum\Enum ? $model->{$column}->key : $model->{$column} }}</td>
                        @else
                            <td class="p-2">{{ $column instanceof \App\Lib\Column ? $column->getValue($model) : ($model->{$column} instanceof \BenSampo\Enum\Enum ? $model->{$column}->key : $model->{$column}) }}</td>
                        @endisset
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</x-app-layout>
