@extends('coanda::admin.layout.main')

@section('page_title', 'Signoff')

@section('content')

<div class="row">

	<div class="breadcrumb-nav">
		<ul class="breadcrumb">
			<li><a href="{{ Coanda::adminUrl('signoff') }}">Signoff</a></li>
		</ul>
	</div>
</div>

<div class="row">
	<div class="page-name col-md-12">
		<h1 class="pull-left">Sign off requests</h1>
		<div class="page-status pull-right">
			<span class="label label-default">Total {{ $requests->getTotal() }}</span>
		</div>
	</div>
</div>

<div class="row">
	<div class="page-options col-md-12">
		<a href="{{ Coanda::adminUrl('contentsignoff/history') }}" class="btn btn-primary"><i class="fa fa-history"></i> History</a>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="page-tabs">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#forms" data-toggle="tab">Pending requests</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="forms">

					@if ($requests->count() > 0)
						<table class="table table-striped">
							@foreach ($requests as $request)
							<tr>
								<td>Version <span class="badge badge-default">#{{ $request->version->version }}</span> of <a href="{{ Coanda::adminUrl('contentsignoff/request/' . $request->id) }}">{{ $request->version->page->name }}</a> from {{ $request->version->creator_name }}</td>
							    <td class="tight">
									<span class="label label-warning">{{ ucfirst($request->status) }}</span>
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