@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="{{ $dataType->icon }}"></i> {{ $dataType->getTranslatedAttribute('display_name_singular') }}
        </h1>
        @include('voyager::multilingual.language-selector')
    </div>
@endsection

@section('content')
    <div class="page-content read-page">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="{{ $dataType->icon }}"></i>
                            {{ $dataType->getTranslatedAttribute('display_name_singular') }}
                            <small class="text-muted">{{ __('voyager::bread.viewing') }}</small>
                        </h3>
                        <div class="panel-actions">
                            @can('edit', $dataTypeContent)
                                <a href="{{ route('voyager.transactions.edit', $dataTypeContent->getKey()) }}" class="btn btn-sm btn-primary edit">
                                    <i class="voyager-edit"></i> <span>{{ __('voyager::bread.edit') }}</span>
                                </a>
                            @endcan
                            @can('delete', $dataTypeContent)
                                <a href="javascript:;" data-id="{{ $dataTypeContent->getKey() }}" class="btn btn-sm btn-danger delete">
                                    <i class="voyager-trash"></i> <span>{{ __('voyager::bread.delete') }}</span>
                                </a>
                            @endcan
                            <a href="{{ route('voyager.transactions.index') }}" class="btn btn-sm btn-warning">
                                <i class="voyager-list"></i> <span>{{ __('voyager::bread.return_to_list') }}</span>
                            </a>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="row">
                            @foreach($dataType->readRows as $row)
                                <div class="col-md-{{ $row->col_width ?? 12 }}">
                                    <div class="form-group">
                                        <label class="control-label" for="{{ $row->field }}">{{ $row->getTranslatedAttribute('display_name') }}</label>
                                        <div class="read-value">
                                            @if($row->type == 'image')
                                                <img src="@if( !filter_var($dataTypeContent->{$row->field}, FILTER_VALIDATE_URL)){{ Voyager::image( $dataTypeContent->{$row->field} ) }}@else{{ $dataTypeContent->{$row->field} }}@endif" style="width:200px">
                                            @elseif($row->type == 'relationship')
                                                @include('voyager::formfields.relationship.partials.read')
                                            @elseif($row->type == 'select_multiple')
                                                @if(property_exists($row->details, 'relationship'))

                                                    @foreach($dataTypeContent->{$row->field} as $item)
                                                        {{ $item->{$row->field} }}
                                                    @endforeach

                                                @elseif(property_exists($row->details, 'options'))
                                                    @if(count(json_decode($dataTypeContent->{$row->field})) > 0)
                                                        @foreach(json_decode($dataTypeContent->{$row->field}) as $item)
                                                            @if(@$row->details->options->{$item})
                                                                {{ $row->details->options->{$item} }}
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        {{ __('voyager::bread.none') }}
                                                    @endif
                                                @endif
                                            @elseif($row->type == 'multiple_checkbox' && property_exists($row->details, 'options'))
                                                @if(@$dataTypeContent->{$row->field})
                                                    @foreach($dataTypeContent->{$row->field} as $item)
                                                        @if(@$row->details->options->{$item})
                                                            {{ $row->details->options->{$item} . (!$loop->last ? ', ' : '') }}
                                                        @endif
                                                    @endforeach
                                                @else
                                                    {{ __('voyager::bread.none') }}
                                                @endif
                                            @elseif($row->type == 'select_dropdown' && property_exists($row->details, 'options'))
                                                {!! $row->details->options->{$dataTypeContent->{$row->field}} ?? '' !!}
                                            @elseif($row->type == 'date')
                                                @if( property_exists($row->details, 'format') && !is_null($dataTypeContent->{$row->field}) )
                                                    {{ \Carbon\Carbon::parse($dataTypeContent->{$row->field})->formatLocalized($row->details->format) }}
                                                @else
                                                    {{ $dataTypeContent->{$row->field} }}
                                                @endif
                                            @elseif($row->type == 'checkbox')
                                                @if($dataTypeContent->{$row->field})
                                                    <i class="voyager-check"></i>
                                                @else
                                                    <i class="voyager-x"></i>
                                                @endif
                                            @elseif($row->type == 'color')
                                                <span class="badge badge-lg" style="background-color: {{ $dataTypeContent->{$row->field} }}">{{ $dataTypeContent->{$row->field} }}</span>
                                            @elseif($row->type == 'coordinates')
                                                @include('voyager::partials.coordinates-static-image')
                                            @elseif($row->type == 'file')
                                                @if(!empty($dataTypeContent->{$row->field}) )
                                                    @include('voyager::formfields.file.partials.read')
                                                @else
                                                    {{ __('voyager::bread.none') }}
                                                @endif
                                            @elseif($row->type == 'multiple_images')
                                                @include('voyager::formfields.multiple_images.partials.read')
                                            @elseif($row->type == 'media_picker')
                                                @include('voyager::formfields.media_picker.partials.read')
                                            @elseif($row->type == 'rich_text_box')
                                                @include('voyager::multilingual.input-hidden-bread-read')
                                                {!! $dataTypeContent->{$row->field} !!}
                                            @elseif($row->type == 'text')
                                                @include('voyager::multilingual.input-hidden-bread-read')
                                                <div>{{ $dataTypeContent->{$row->field} }}</div>
                                            @elseif($row->type == 'text_area')
                                                @include('voyager::multilingual.input-hidden-bread-read')
                                                <div>{{ $dataTypeContent->{$row->field} }}</div>
                                            @else
                                                @include('voyager::multilingual.input-hidden-bread-read')
                                                <span>{{ $dataTypeContent->{$row->field} }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i> {{ __('voyager::bread.delete_question') }} {{ strtolower($dataType->getTranslatedAttribute('display_name_singular')) }}?</h4>
                </div>
                <div class="modal-body">
                    <p>{{ __('voyager::bread.delete_confirm') }} <b>{{ strtolower($dataType->getTranslatedAttribute('display_name_singular')) }}</b></p>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('voyager.transactions.destroy', $dataTypeContent->getKey()) }}" id="delete_form" method="POST">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ __('voyager::bread.cancel') }}</button>
                        <button type="submit" class="btn btn-danger" id="confirm_delete">{{ __('voyager::bread.delete_confirm') }} {{ strtolower($dataType->getTranslatedAttribute('display_name_singular')) }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        $('#delete_form').submit(function (e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: $(this).serialize(),
                success: function (response) {
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    } else {
                        toastr.success(response.message || 'Deleted successfully!');
                    }
                },
                error: function (xhr) {
                    toastr.error('An error occurred while deleting.');
                }
            });
        });
    </script>
@endsection
