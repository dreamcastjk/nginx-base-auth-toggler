<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @lang('navigations.headers.baseAuth')
        </h2>
    </x-slot>

    <section class="base-auth">
        <div class="base-auth__container container">
            <div class="base-auth__inner">

                @if($errors->any())
                    <div class="base-auth__alert-warning alert alert-success">
                        {!! implode('', $errors->all('<div>:message</div>')) !!}
                    </div>
                @endif

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

                <form class="base-auth__form" action="{{ route('disable') }}" method="POST">
                    @csrf
                    <div class="base-auth__inner form-group">
                        <label class="base-auth__label" for="exampleInputEmail1">@lang('navigations.tags.baseAuth.domainLabel')</label>
                        <input class="base-auth__input form-control input-dark" type="text" id="domain" name="domain" placeholder="@lang('navigations.tags.baseAuth.domainInputPlaceholder')">
                    </div>

                    <button class="base-auth__button button btn btn-primary" type="submit">@lang('navigations.tags.baseAuth.disableButtonText')</button>
                </form>
            </div>
        </div>
    </section>
</x-app-layout>
