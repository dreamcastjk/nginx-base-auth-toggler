<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @lang('navigations.headers.disabledDomainsList')
        </h2>
    </x-slot>

    <section class="base-auth">
        <div class="base-auth__container container">
            <div class="base-auth__inner">

                @if (session('success'))
                    <div class="base-auth__alert-success alert alert-success">
                        {!! session('success') !!}
                    </div>
                @endif

                @if (session('error'))
                    <div class="base-auth__alert-error alert alert-danger">
                        {!! session('error') !!}
                    </div>
                @endif

                @php /* @var Illuminate\Pagination\LengthAwarePaginator $domains  */ @endphp
                @if (!$domains->count())
                    @lang('navigations.messages.emptyList')
                @else
                        {{ $domains->links() }}

                        <table class="main-table" style="width:100%">
                            <tr>
                                <th>#</th>
                                <th>@lang('navigations.disabledList.tableColumns.domains.domain')</th>
                                <th>@lang('navigations.disabledList.tableColumns.domains.filePath')</th>
                                <th>@lang('navigations.disabledList.tableColumns.timestamps.createdAt')</th>
                                <th>@lang('navigations.disabledList.tableColumns.domains.disabledBy')</th>
                                <th>@lang('navigations.disabledList.tableColumns.actions.enable')</th>
                            </tr>
                            @php /* @var \App\Models\Domain $domain */ @endphp
                            @foreach($domains as $domain)
                                <tr>
                                    <th>{{ $loop->iteration }}.</th>
                                    <th>
                                        <a href="//{{ $domain->domain }}">{{ $domain->domain }}</a>
                                    </th>
                                    <th>{{ $domain->file_path }}</th>
                                    <th>{{ $domain->created_at }}</th>
                                    <th>{{ $domain->user->name }}</th>
                                    <th>
                                        <a href="{{ route('enable', [$domain->id]) }}">
                                            <i class="material-icons custom">cached</i>
                                        </a>
                                    </th>
                                </tr>
                            @endforeach
                        </table>
                @endif
            </div>
        </div>
    </section>
</x-app-layout>
