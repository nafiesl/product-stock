@extends('layouts.app')

@section('title', __('partner.edit'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        @if (request('action') == 'delete' && $partner)
        @can('delete', $partner)
            <div class="card">
                <div class="card-header">{{ __('partner.delete') }}</div>
                <div class="card-body">
                    <label class="control-label text-primary">{{ __('partner.name') }}</label>
                    <p>{{ $partner->name }}</p>
                    <label class="control-label text-primary">{{ __('partner.description') }}</label>
                    <p>{{ $partner->description }}</p>
                    {!! $errors->first('partner_id', '<span class="form-error small">:message</span>') !!}
                </div>
                <hr style="margin:0">
                <div class="card-body text-danger">{{ __('partner.delete_confirm') }}</div>
                <div class="card-footer">
                    {!! FormField::delete(
                        ['route' => ['partners.destroy', $partner]],
                        __('app.delete_confirm_button'),
                        ['class' => 'btn btn-danger'],
                        ['partner_id' => $partner->id]
                    ) !!}
                    {{ link_to_route('partners.edit', __('app.cancel'), [$partner], ['class' => 'btn btn-link']) }}
                </div>
            </div>
        @endcan
        @else
        <div class="card">
            <div class="card-header">{{ __('partner.edit') }}</div>
            {{ Form::model($partner, ['route' => ['partners.update', $partner], 'method' => 'patch']) }}
            <div class="card-body">
                {!! FormField::text('name', ['required' => true, 'label' => __('partner.name')]) !!}
                {!! FormField::textarea('description', ['label' => __('partner.description')]) !!}
            </div>
            <div class="card-footer">
                {{ Form::submit(__('partner.update'), ['class' => 'btn btn-success']) }}
                {{ link_to_route('partners.show', __('app.cancel'), [$partner], ['class' => 'btn btn-link']) }}
                @can('delete', $partner)
                    {{ link_to_route('partners.edit', __('app.delete'), [$partner, 'action' => 'delete'], ['class' => 'btn btn-danger float-right', 'id' => 'del-partner-'.$partner->id]) }}
                @endcan
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endif
@endsection
