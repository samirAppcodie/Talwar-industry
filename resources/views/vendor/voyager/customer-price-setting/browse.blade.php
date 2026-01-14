@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' '.$dataType->getTranslatedAttribute('display_name_plural'))

@section('page_header')
@section('css')
@if(!$dataType->server_side && config('dashboard.data_tables.responsive')) <link rel="stylesheet" href="{{ voyager_asset('lib/css/responsive.dataTables.min.css') }}">
@endif

```
<style>
    /* Global font */
    body, .page-content, table, select, input, button {
        font-family: 'Poppins', sans-serif;
    }

    /* Filter form styling */
    .form-filters .row {
        align-items: flex-end;
        margin-bottom: 20px;
        gap: 10px;
    }
    .form-filters label {
        margin-bottom: 5px;
        font-weight: 600;
        font-size: 14px;
        color: #4a4a4a;
    }
    .form-filters select.form-control,
    .form-filters input.form-control {
        border-radius: 6px;
        padding: 8px 12px;
        border: 1px solid #ccc;
        font-size: 14px;
        transition: 0.3s;
    }
    .form-filters select.form-control:focus,
    .form-filters input.form-control:focus {
        border-color: #5A67D8;
        box-shadow: 0 0 6px rgba(90, 103, 216, 0.3);
    }

    /* Buttons */
    .form-filters button,
    .btn-primary,
    .btn-add-new {
        border-radius: 8px;
        padding: 10px 18px;
        font-weight: 600;
        font-size: 14px;
        color: #fff;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .form-filters button:hover,
    .btn-primary:hover,
    .btn-add-new:hover {
        background: linear-gradient(135deg, #5a67d8, #5b3ca0);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }
    .btn-add-new {
        margin-right: 12px;
    }

    /* Table styling */
    table#dataTable {
        border-collapse: separate !important;
        border-spacing: 0 12px;
        width: 100%;
    }
    table#dataTable thead th {
        font-weight: 700;
        font-size: 14px;
        color: #2d2d2d;
        background-color: #f7f7f9;
        text-align: left;
        padding: 12px 15px;
        border-bottom: 2px solid #e1e1e1;
    }
    table#dataTable tbody tr {
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    table#dataTable tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    table#dataTable tbody tr td {
        vertical-align: middle !important;
        padding: 12px 15px;
        font-size: 14px;
        color: #4a4a4a;
    }

    /* Checkbox */
    input[type="checkbox"].select_all {
        margin-top: 0;
    }

    /* Action icons */
    .bread-actions .btn {
        margin-left: 5px;
        border-radius: 6px;
        padding: 5px 8px;
        transition: all 0.3s ease;
    }
    .bread-actions .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    }

    /* Responsive tweaks */
    @media (max-width: 768px) {
        .form-filters .col-md-2,
        .form-filters .col-md-3 {
            margin-bottom: 12px;
        }
    }
    .voyager .btn.btn-warning {
    background: darkcyan;
    border: 0;
    border-radius: 7px;
    color: #fff;
    opacity: .9;
}
#dataTable .bread-actions .btn, .actions a.btn {
    font-size: 12px;
    padding: 5px 10px;
    border-radius: 7px;
}
</style>

@stop
        <h1 class="page-title">
            <i class="{{ $dataType->icon }}"></i> {{ $dataType->getTranslatedAttribute('display_name_plural') }}
        </h1>
        @can('add', app($dataType->model_name))
            <a href="{{ route('voyager.'.$dataType->slug.'.create') }}" class="btn btn-success btn-add-new">
                <i class="voyager-plus"></i> <span>{{ __('voyager::generic.add_new') }}</span>
            </a>
        @endcan
        @can('delete', app($dataType->model_name))
            @include('voyager::partials.bulk-delete')
        @endcan
        @can('edit', app($dataType->model_name))
            @if(!empty($dataType->order_column) && !empty($dataType->order_display_column))
                <a href="{{ route('voyager.'.$dataType->slug.'.order') }}" class="btn btn-primary btn-add-new">
                    <i class="voyager-list"></i> <span>{{ __('voyager::bread.order') }}</span>
                </a>
            @endif
        @endcan
        @can('delete', app($dataType->model_name))
            @if($usesSoftDeletes)
                <input type="checkbox" @if ($showSoftDeleted) checked @endif id="show_soft_deletes" data-toggle="toggle" data-on="{{ __('voyager::bread.soft_deletes_off') }}" data-off="{{ __('voyager::bread.soft_deletes_on') }}">
            @endif
        @endcan
        @foreach($actions as $action)
            @if (method_exists($action, 'massAction'))
                @include('voyager::bread.partials.actions', ['action' => $action, 'data' => null])
            @endif
        @endforeach
        @include('voyager::multilingual.language-selector')
    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        @if ($isServerSide)
                            <form method="get" class="form-search">
                                <div id="search-input">
                                    <div class="col-2">
                                        <select id="search_key" name="key">
                                            @foreach($searchNames as $key => $name)
                                                <option value="{{ $key }}" @if($search->key == $key || (empty($search->key) && $key == $defaultSearchKey)) selected @endif>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <select id="filter" name="filter">
                                            <option value="contains" @if($search->filter == "contains") selected @endif>{{ __('voyager::generic.contains') }}</option>
                                            <option value="equals" @if($search->filter == "equals") selected @endif>=</option>
                                        </select>
                                    </div>
                                    <div class="input-group col-md-12">
                                        <input type="text" class="form-control" placeholder="{{ __('voyager::generic.search') }}" name="s" value="{{ $search->value }}">
                                        <span class="input-group-btn">
                                            <button class="btn btn-info btn-lg" type="submit">
                                                <i class="voyager-search"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                @if (Request::has('sort_order') && Request::has('order_by'))
                                    <input type="hidden" name="sort_order" value="{{ Request::get('sort_order') }}">
                                    <input type="hidden" name="order_by" value="{{ Request::get('order_by') }}">
                                @endif
                            </form>

                            <!-- Filters Form -->
                            <form method="get" class="form-filters" style="margin-top: 20px;">
                                <div class="row">
                                    <!-- Customer Filter -->
                                    <div class="col-md-3">
                                        <label for="customer_id">Customer</label>
                                        <select id="customer_id" name="customer_id" class="form-control">
                                            <option value="">All Customers</option>
                                            @foreach(\App\Models\User::where('role_id', 2)->get() as $customer)
                                                <option value="{{ $customer->id }}" @if(isset($filters['customer_id']) && $filters['customer_id'] == $customer->id) selected @endif>
                                                    {{ $customer->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Duration Filter -->
                                    <div class="col-md-3">
                                        <label for="duration">Duration</label>
                                        <select id="duration" name="duration" class="form-control">
                                            <option value="">Custom Range</option>
                                            <option value="today" {{ isset($filters['duration']) && $filters['duration'] == 'today' ? 'selected' : '' }}>Today</option>
                                            <option value="yesterday" {{ isset($filters['duration']) && $filters['duration'] == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                                            <option value="last_7_days" {{ isset($filters['duration']) && $filters['duration'] == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                                            <option value="last_30_days" {{ isset($filters['duration']) && $filters['duration'] == 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                                            <option value="this_month" {{ isset($filters['duration']) && $filters['duration'] == 'this_month' ? 'selected' : '' }}>This Month</option>
                                            <option value="last_month" {{ isset($filters['duration']) && $filters['duration'] == 'last_month' ? 'selected' : '' }}>Last Month</option>
                                        </select>
                                    </div>

                                    <!-- Date From -->
                                    <div class="col-md-2">
                                        <label for="date_from">Date From</label>
                                        <input type="date" id="date_from" name="date_from" class="form-control" value="{{ $filters['date_from'] ?? '' }}">
                                    </div>

                                    <!-- Date To -->
                                    <div class="col-md-2">
                                        <label for="date_to">Date To</label>
                                        <input type="date" id="date_to" name="date_to" class="form-control" value="{{ $filters['date_to'] ?? '' }}">
                                    </div>

                                    <!-- Transaction Type -->
                                    <div class="col-md-2">
                                        <label for="transaction_type">Transaction Type</label>
                                        <select id="transaction_type" name="transaction_type" class="form-control">
                                            <option value="">All Types</option>
                                            <option value="deposit" {{ isset($filters['transaction_type']) && $filters['transaction_type'] == 'deposit' ? 'selected' : '' }}>Deposit</option>
                                            <option value="withdraw_flour" {{ isset($filters['transaction_type']) && $filters['transaction_type'] == 'withdraw_flour' ? 'selected' : '' }}>Withdraw Flour</option>
                                            <option value="withdraw_cash" {{ isset($filters['transaction_type']) && $filters['transaction_type'] == 'withdraw_cash' ? 'selected' : '' }}>Withdraw Cash</option>
                                            <option value="grinding" {{ isset($filters['transaction_type']) && $filters['transaction_type'] == 'grinding' ? 'selected' : '' }}>Grinding</option>
                                            <option value="balance_adjustment" {{ isset($filters['transaction_type']) && $filters['transaction_type'] == 'balance_adjustment' ? 'selected' : '' }}>Balance Adjustment</option>
                                        </select>
                                    </div>

                                    <!-- Filter Button -->
                                    <div class="col-md-2">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn btn-primary form-control">Filter</button>
                                    </div>
                                </div>

                                <!-- Preserve existing search/sort params -->
                                @if (Request::has('sort_order') && Request::has('order_by'))
                                    <input type="hidden" name="sort_order" value="{{ Request::get('sort_order') }}">
                                    <input type="hidden" name="order_by" value="{{ Request::get('order_by') }}">
                                @endif
                                @if ($search->value)
                                    <input type="hidden" name="s" value="{{ $search->value }}">
                                    <input type="hidden" name="key" value="{{ $search->key }}">
                                    <input type="hidden" name="filter" value="{{ $search->filter }}">
                                @endif
                            </form>
                        @endif

                        <div class="table-responsive" style="margin-top: 20px;">
                            <table id="dataTable" class="table table-hover">
                                <thead>
                                    <tr>
                                        @if($showCheckboxColumn)
                                            <th class="dt-not-orderable">
                                                <input type="checkbox" class="select_all">
                                            </th>
                                        @endif
                                        @foreach($dataType->browseRows as $row)
                                            <th>
                                                @if ($isServerSide && in_array($row->field, $sortableColumns))
                                                    <a href="{{ $row->sortByUrl($orderBy, $sortOrder) }}">
                                                @endif
                                                {{ $row->getTranslatedAttribute('display_name') }}
                                                @if ($isServerSide && $row->isCurrentSortField($orderBy))
                                                    @if ($sortOrder == 'asc')
                                                        <i class="voyager-angle-up pull-right"></i>
                                                    @else
                                                        <i class="voyager-angle-down pull-right"></i>
                                                    @endif
                                                @endif
                                                @if ($isServerSide && in_array($row->field, $sortableColumns))
                                                    </a>
                                                @endif
                                            </th>
                                        @endforeach
                                        <th class="actions text-right dt-not-orderable">{{ __('voyager::generic.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dataTypeContent as $data)
                                        <tr>
                                            @if($showCheckboxColumn)
                                                <td>
                                                    <input type="checkbox" name="row_id" id="checkbox_{{ $data->getKey() }}" value="{{ $data->getKey() }}">
                                                </td>
                                            @endif
                                            @foreach($dataType->browseRows as $row)
                                                @php
                                                    if ($data->{$row->field.'_browse'}) {
                                                        $data->{$row->field} = $data->{$row->field.'_browse'};
                                                    }
                                                @endphp
                                                <td>
                                                    @if (isset($row->details->view_browse))
                                                        @include($row->details->view_browse, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $data->{$row->field}, 'view' => 'browse', 'options' => $row->details])
                                                    @elseif (isset($row->details->view))
                                                        @include($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $data->{$row->field}, 'action' => 'browse', 'view' => 'browse', 'options' => $row->details])
                                                    @elseif($row->type == 'image')
                                                        <img src="@if( !filter_var($data->{$row->field}, FILTER_VALIDATE_URL)){{ Voyager::image( $data->{$row->field} ) }}@else{{ $data->{$row->field} }}@endif" style="width:100px">
                                                    @elseif($row->type == 'relationship')
                                                        @include('voyager::formfields.relationship', ['view' => 'browse','options' => $row->details])
                                                    @elseif($row->type == 'select_multiple')
                                                        @if(property_exists($row->details, 'relationship'))
                                                            @foreach($data->{$row->field} as $item)
                                                                {{ $item->{$row->field} }}
                                                            @endforeach
                                                        @elseif(property_exists($row->details, 'options'))
                                                            @if (!empty(json_decode($data->{$row->field})))
                                                                @foreach(json_decode($data->{$row->field}) as $item)
                                                                    @if (@$row->details->options->{$item})
                                                                        {{ $row->details->options->{$item} . (!$loop->last ? ', ' : '') }}
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                {{ __('voyager::generic.none') }}
                                                            @endif
                                                        @endif
                                                    @elseif(($row->type == 'select_dropdown' || $row->type == 'radio_btn') && property_exists($row->details, 'options'))
                                                        {!! $row->details->options->{$data->{$row->field}} ?? '' !!}
                                                    @elseif($row->type == 'date' || $row->type == 'timestamp')
                                                        @if ( property_exists($row->details, 'format') && !is_null($data->{$row->field}) )
                                                            {{ \Carbon\Carbon::parse($data->{$row->field})->formatLocalized($row->details->format) }}
                                                        @else
                                                            {{ $data->{$row->field} }}
                                                        @endif
                                                    @elseif($row->type == 'checkbox')
                                                        @if(property_exists($row->details, 'on') && property_exists($row->details, 'off'))
                                                            @if($data->{$row->field})
                                                                <span class="label label-info">{{ $row->details->on }}</span>
                                                            @else
                                                                <span class="label label-primary">{{ $row->details->off }}</span>
                                                            @endif
                                                        @else
                                                            {{ $data->{$row->field} }}
                                                        @endif
                                                    @elseif($row->type == 'color')
                                                        <span class="badge badge-lg" style="background-color: {{ $data->{$row->field} }}">{{ $data->{$row->field} }}</span>
                                                    @elseif($row->type == 'text')
                                                        @include('voyager::multilingual.input-hidden-bread-browse')
                                                        <div>{{ mb_strlen($data->{$row->field}) > 200 ? mb_substr($data->{$row->field}, 0, 200).'...' : $data->{$row->field} }}</div>
                                                    @elseif($row->type == 'text_area')
                                                        @include('voyager::multilingual.input-hidden-bread-browse')
                                                        <div>{{ mb_strlen($data->{$row->field}) > 200 ? mb_substr($data->{$row->field}, 0, 200).'...' : $data->{$row->field} }}</div>
                                                    @elseif($row->type == 'rich_text_box')
                                                        @include('voyager::multilingual.input-hidden-bread-browse')
                                                        <div>{{ mb_strlen(strip_tags($data->{$row->field}, '<b><i><u>')) > 200 ? mb_substr(strip_tags($data->{$row->field}, '<b><i><u>'), 0, 200).'...' : strip_tags($data->{$row->field}, '<b><i><u>') }}</div>
                                                    @else
                                                        @include('voyager::multilingual.input-hidden-bread-browse')
                                                        <span>{{ $data->{$row->field} }}</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                            <td class="no-sort no-click bread-actions text-right">
                                                @foreach($actions as $action)
                                                    @if (!method_exists($action, 'massAction'))
                                                        @include('voyager::bread.partials.actions', ['action' => $action])
                                                    @endif
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if ($isServerSide)
                            <div class="pull-left">
                                <div role="status" class="show-res" aria-live="polite">
                                    {{ trans_choice('voyager::generic.showing_entries', $dataTypeContent->total(), [
                                        'from' => $dataTypeContent->firstItem(),
                                        'to' => $dataTypeContent->lastItem(),
                                        'all' => $dataTypeContent->total()
                                    ]) }}
                                </div>
                            </div>
                            <div class="pull-right">
                                {{ $dataTypeContent->appends(array_merge([
                                    's' => $search->value,
                                    'filter' => $search->filter,
                                    'key' => $search->key,
                                    'order_by' => $orderBy,
                                    'sort_order' => $sortOrder,
                                    'showSoftDeleted' => $showSoftDeleted,
                                ], $filters))->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i> {{ __('voyager::generic.delete_question') }} {{ strtolower($dataType->getTranslatedAttribute('display_name_singular')) }}?</h4>
                </div>
                <div class="modal-footer">
                    <form action="#" id="delete_form" method="POST">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="{{ __('voyager::generic.delete_confirm') }}">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    @if(!$dataType->server_side && config('dashboard.data_tables.responsive'))
        <link rel="stylesheet" href="{{ voyager_asset('lib/css/responsive.dataTables.min.css') }}">
    @endif
    <style>
        .form-filters .row { align-items: end; }
        .form-filters label { margin-bottom: 5px; font-weight: 600; }
    </style>
@stop

@section('javascript')
    @if(!$dataType->server_side && config('dashboard.data_tables.responsive'))
        <script src="{{ voyager_asset('lib/js/dataTables.responsive.min.js') }}"></script>
    @endif

    <script>
        $(document).ready(function () {
            @if (!$dataType->server_side)
                var table = $('#dataTable').DataTable({!! json_encode(
                    array_merge([
                        "order" => $orderColumn,
                        "language" => __('voyager::datatable'),
                        "columnDefs" => [
                            ['targets' => 'dt-not-orderable', 'searchable' => false, 'orderable' => false],
                        ],
                    ], config('voyager.dashboard.data_tables', []))
                , true) !!});
            @else
                $('#search-input select').select2({ minimumResultsForSearch: Infinity });
            @endif

            @if ($isModelTranslatable)
                $('.side-body').multilingual();
                $('#dataTable').on('draw.dt', function(){
                    $('.side-body').data('multilingual').init();
                });
            @endif

            $('.select_all').on('click', function(e) {
                $('input[name="row_id"]').prop('checked', $(this).prop('checked')).trigger('change');
            });

            // Duration → Auto-fill Date From/To
            function setDateRange(from, to) {
                $('#date_from').val(from);
                $('#date_to').val(to);
            }

            $('#duration').on('change', function () {
                const value = $(this).val();
                if (!value || value === '') {
                    return; // Custom range: don't override
                }

                const today = new Date();
                const yyyy = today.getFullYear();
                let mm = String(today.getMonth() + 1).padStart(2, '0');
                let dd = String(today.getDate()).padStart(2, '0');
                const todayStr = `${yyyy}-${mm}-${dd}`;

                let from = '';
                let to = todayStr;

                switch (value) {
                    case 'today':
                        from = todayStr;
                        break;
                    case 'yesterday':
                        const yesterday = new Date(today);
                        yesterday.setDate(today.getDate() - 1);
                        from = yesterday.toISOString().split('T')[0];
                        to = from;
                        break;
                    case 'last_7_days':
                        const last7 = new Date(today);
                        last7.setDate(today.getDate() - 6);
                        from = last7.toISOString().split('T')[0];
                        break;
                    case 'last_30_days':
                        const last30 = new Date(today);
                        last30.setDate(today.getDate() - 29);
                        from = last30.toISOString().split('T')[0];
                        break;
                    case 'this_month':
                        from = `${yyyy}-${mm}-01`;
                        break;
                    case 'last_month':
                        const lastMonthDate = new Date(today);
                        lastMonthDate.setMonth(today.getMonth() - 1);
                        const lmYear = lastMonthDate.getFullYear();
                        const lmMonth = String(lastMonthDate.getMonth() + 1).padStart(2, '0');
                        from = `${lmYear}-${lmMonth}-01`;
                        const lastDay = new Date(lmYear, lastMonthDate.getMonth() + 1, 0);
                        to = `${lmYear}-${lmMonth}-${String(lastDay.getDate()).padStart(2, '0')}`;
                        break;
                }

                setDateRange(from, to);
            });

            // Trigger on page load if duration is already selected
            if ($('#duration').val()) {
                $('#duration').trigger('change');
            }

            // Delete modal
            var deleteFormAction;
            $('td').on('click', '.delete', function (e) {
                $('#delete_form')[0].action = '{{ route('voyager.'.$dataType->slug.'.destroy', '__id') }}'.replace('__id', $(this).data('id'));
                $('#delete_modal').modal('show');
            });

            @if($usesSoftDeletes)
                $(function() {
                    $('#show_soft_deletes').change(function() {
                        const params = new URLSearchParams(window.location.search);
                        params.set('showSoftDeleted', $(this).is(':checked') ? 1 : 0);
                        window.location = window.location.pathname + '?' + params.toString();
                    });
                });
            @endif

            $('input[name="row_id"]').on('change', function () {
                const ids = [];
                $('input[name="row_id"]:checked').each(function() {
                    ids.push($(this).val());
                });
                $('.selected_ids').val(ids);
            });
        });
    </script>
@stop