<x-app-layout>
    <x-slot name="header">
        <div class="w-full flex justify-between">
            <div class="text-xl">{{ __('Users') }}</div>
            @can('user-create')
                <div>
                    <a
                        href="{{ route('user.create') }}"
                        class="bg-transparent text-sm hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-1 px-4 border border-blue-500 hover:border-transparent rounded"
                    >
                        + {{ __('Create User') }}
                    </a>
                </div>
            @endcan
        </div>
    </x-slot>

    <div class="w-full mt-8">
        <table class="w-full" id="users-table">
            <thead>
            <tr>
                <th>{{ __('ID') }}</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Email') }}</th>
                <th>{{ __('Action') }}</th>
            </tr>
            </thead>
        </table>
    </div>
    <x-slot name="script">
        <script type="text/javascript" src="{{ mix('js/datatable.js') }}"></script>
        <script type="text/javascript">
            $('#users-table').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: '{{ route('user.index') }}',
                    dataSrc(response) {
                        response.data.map(function (item) {
                            item.action = actionIcons({
                                'show': '{{ route('user.show', '@') }}'.replace('@', item.id),
                                @can('user-update')
                                'portal': '{{ route('user.portal', '@') }}'.replace('@', item.id),
                                'edit': '{{ route('user.edit', '@') }}'.replace('@', item.id),
                                @endcan
                                @can('user-delete')
                                'delete': '{{ route('user.destroy', '@') }}'.replace('@', item.id),
                                @endcan
                            });
                            return item;
                        });
                        return response.data;
                    }
                },
                columns: [
                    {data: 'id'},
                    {data: 'name'},
                    {data: 'email'},
                    {data: 'action', orderable: false, searchable: false},
                ]
            });
        </script>
    </x-slot>
</x-app-layout>
