<x-app-layout :title="$title = __($heading['index'] ?? (string) Str::of($name)->title()->replace(['_', '-'], ' ')->plural())">
    <x-slot name="header">
        <div class="w-full flex justify-between">
            <div class="text-xl">{{ $title }}</div>
            @can($name.'-create')
                <div>
                    @if(Route::has($name.'.create'))
                    <a
                        href="{{ route($name.'.create') }}"
                        class="bg-transparent text-sm hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-1 px-4 border border-blue-500 hover:border-transparent rounded"
                    >
                        + {{ __($heading['create'] ?? 'Create '.Str::of($name)->title()->replace(['_', '-'], ' ')) }}
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
                    @if($column instanceof \App\Lib\Column)
                        @continue(!$column->visible)
                        <th>{{ __((string) Str::of($column->label ?? $column->name)->title()->replace(['_', '.'], ' ')) }}</th>
                    @else
                        <th>{{ __((string) Str::of($column)->title()->replace(['_', '.'], ' ')) }}</th>
                    @endif
                @endforeach
                @if($action ?? true)
                <th>{{ __('Action') }}</th>
                @endif
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
                    url: '{{ route($name.'.index', isset($appendParameters) ? request()->only($appendParameters) : []) }}',
                    beforeSend: function(){
                        this.url = encodeURI(
                            decodeURI(this.url).split('&').map(segment => {
                                if (segment.startsWith('search[value]=') && segment.length > 'search[value]='.length && segment.match(/search\[value\]=(GM|DGM|DC|TC|FA)\d+/)) {
                                    return segment.replace(/search\[value\]=(GM|DGM|DC|TC|FA)(\d+)/, 'search[value]=$2')
                                }
                                return segment
                            }).join('&')
                        );
                    },
                    dataSrc(response) {
                        response.data.map(function (item) {
                            item.action = actionIcons({
                                @if(Route::has($name.'.show'))
                                'show': '{{ route($name.'.show', '@') }}'.replace('@', item.id),
                                @endif
                                @can($name.'-update')
                                    @if(Route::has($name.'.edit'))'edit': '{{ route($name.'.edit', '@') }}'.replace('@', item.id),@endif
                                @endcan
                                @can($name.'-approve')
                                    @if(Route::has($name.'.update'))'approve': '{{ route($name.'.update', '@') }}'.replace('@', item.id),@endif
                                @endcan
                                @can($name.'-delete')
                                    @if(Route::has($name.'.destroy'))'delete': '{{ route($name.'.destroy', '@') }}'.replace('@', item.id),@endif
                                @endcan
                            });
                            @isset($statusMap)
                                item.status = @js($statusMap::asSelectArray())[item.status]
                            @endisset
                            if (item.date) {
                                item.date = new Date(item.date).toDateString()
                            }
                            if (item.created_at) {
                                item.created_at = new Date(item.created_at).toLocaleString()
                            }
                            if (item.updated_at) {
                                item.updated_at = new Date(item.updated_at).toLocaleString()
                            }
                            item.id = '{{ $idPrefix ?? '' }}'+item.id;
                            return item;
                        });
                        return response.data;
                    }
                },
                columns: [
                    @foreach($columns as $column)
                        @if($column instanceof \App\Lib\Column)
                        @continue(!$column->visible)
                        {data: '{{ $column }}', orderable: {{ $column->dataOrder ? 'true' : 'false' }}, searchable: {{ $column->dataSearch ? 'true' : 'false' }} },
                        @else
                        {data: '{{ $column }}' },
                        @endif
                    @endforeach
                    @if($action ?? true)
                        {data: 'action', orderable: false, searchable: false},
                    @endif
                ]
            });
        </script>
    </x-slot>
</x-app-layout>
