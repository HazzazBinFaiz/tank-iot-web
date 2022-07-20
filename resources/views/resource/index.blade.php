<x-app-layout>
    <x-slot name="header">
        <div class="w-full flex justify-between">
            <div class="text-xl">{{ __((string) Str::of($name)->studly()->plural()) }}</div>
            @can($name.'-create')
                <div>
                    @if(Route::has($name.'.create'))
                    <a
                        href="{{ route($name.'.create') }}"
                        class="bg-transparent text-sm hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-1 px-4 border border-blue-500 hover:border-transparent rounded"
                    >
                        + {{ __('Create '.Str::studly($name)) }}
                    </a>
                    @endif
                </div>
            @endcan
        </div>
    </x-slot>

    <div class="w-full mt-8">
        <table class="w-full" id="index-table">
            <thead>
            <tr>
                @foreach($columns as $column)
                    <th>{{ __((string) Str::of($column)->title()->replace('_', ' ')) }}</th>
                @endforeach
                <th>{{ __('Action') }}</th>
            </tr>
            </thead>
        </table>
    </div>
    <x-slot name="script">
        <script type="text/javascript" src="{{ mix('js/datatable.js') }}"></script>
        <script type="text/javascript">
            $('#index-table').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: '{{ route($name.'.index') }}',
                    dataSrc(response) {
                        response.data.map(function (item) {
                            item.action = actionIcons({
                                @if(Route::has($name.'.show'))
                                'show': '{{ route($name.'.show', '@') }}'.replace('@', item.id),
                                @endif
                                @can($name.'-update')
                                    @if(Route::has($name.'.edit'))'edit': '{{ route($name.'.edit', '@') }}'.replace('@', item.id),@endif
                                @endcan
                                @can($name.'-delete')
                                    @if(Route::has($name.'.edit'))'delete': '{{ route($name.'.destroy', '@') }}'.replace('@', item.id),@endif
                                @endcan

                            });
                            @isset($statusMap)
                                item.status = @js($statusMap::asSelectArray())[item.status]
                            @endisset
                            return item;
                        });
                        return response.data;
                    }
                },
                columns: [
                    @foreach($columns as $column)
                    {data: '{{ $column }}'},
                    @endforeach
                    {data: 'action', orderable: false, searchable: false},
                ]
            });
        </script>
    </x-slot>
</x-app-layout>
