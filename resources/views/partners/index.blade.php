@extends('layouts.app')

@section('title', __('partner.list'))

@section('content')
<div class="mb-3">
    <div class="float-right">
        @can('create', new App\Models\Partner)
            {{ link_to_route('partners.create', __('partner.create'), [], ['class' => 'btn btn-success']) }}
        @endcan
    </div>
    <h1 class="page-title">{{ __('partner.list') }} <small>{{ __('app.total') }} : {{ $partners->total() }} {{ __('partner.partner') }}</small></h1>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                {{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
                {!! FormField::text('q', ['label' => __('partner.search'), 'placeholder' => __('partner.search_text'), 'class' => 'mx-sm-2']) !!}
                {{ Form::submit(__('partner.search'), ['class' => 'btn btn-secondary']) }}
                {{ link_to_route('partners.index', __('app.reset'), [], ['class' => 'btn btn-link']) }}
                {{ Form::close() }}
            </div>
            <table class="table table-sm table-responsive-sm table-hover">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th>{{ __('partner.name') }}</th>
                        <th>{{ __('partner.description') }}</th>
                        <th class="text-center">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($partners as $key => $partner)
                    <tr>
                        <td class="text-center">{{ $partners->firstItem() + $key }}</td>
                        <td>{{ $partner->name_link }}</td>
                        <td>{{ $partner->description }}</td>
                        <td class="text-center">
                            @can('view', $partner)
                                {{ link_to_route(
                                    'partners.show',
                                    __('app.show'),
                                    [$partner],
                                    ['id' => 'show-partner-' . $partner->id]
                                ) }}
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="card-body">{{ $partners->appends(Request::except('page'))->render() }}</div>
        </div>
    </div>
</div>
@endsection
