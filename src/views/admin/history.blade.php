@extends('coanda::admin.layout.main')

@section('page_title', 'Signoff History')

@section('content')

<div class="row">

	<div class="breadcrumb-nav">
		<ul class="breadcrumb">
			<li><a href="{{ Coanda::adminUrl('contentsignoff') }}">Signoff</a></li>
			<li>History</li>
		</ul>
	</div>
</div>

<div class="row">
	<div class="page-name col-md-12">
		<h1 class="pull-left">Signoff history</h1>
		<div class="page-status pull-right">
			<span class="label label-default">Total {{ $requests->getTotal() }}</span>
		</div>
	</div>
</div>

<div class="row">
	<div class="page-options col-md-12"></div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="page-tabs">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#history" data-toggle="tab">All requests</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="history">

					@if ($requests->count() > 0)
						<table class="table table-striped">
							@foreach ($requests as $request)
							<tr>
								<td>Version <span class="badge badge-default">#{{ $request->version }}</span> of <a href="{{ Coanda::adminUrl('contentsignoff/request/' . $request->id) }}">{{ $request->page_name }}</a> from {{ $request->requester_name() }}</td>
							    <td>
							        <span class="label label-{{ $request->status == 'declined' ? 'danger' : 'success' }}">{{ ucfirst($request->status) }}</span> by {{ $request->actioner_name() }} on {{ $request->updated_at->format(Config::get('coanda::coanda.datetime_format')) }}
							    </td>
							</tr>
							@endforeach
						</table>

						{{ $requests->links() }}
					@else
						<p>No pending requests!</p>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>

@stop