@extends('coanda::admin.layout.main')

@section('page_title', 'Signoff')

@section('content')

<div class="row">

	<div class="breadcrumb-nav">
		<ul class="breadcrumb">
			<li><a href="{{ Coanda::adminUrl('contentsignoff') }}">Signoff</a></li>

			@if ($request->status !== 'pending')
				<li><a href="{{ Coanda::adminUrl('contentsignoff/history') }}">History</a></li>
			@endif

			<li>Request to sign off</li>
		</ul>
	</div>
</div>

<div class="row">
	<div class="page-name col-md-12">
		<h1 class="pull-left">Request to signoff</h1>
		<div class="page-status pull-right">
			<span class="label label-{{ $request->status == 'declined' ? 'danger' : 'success' }}">{{ ucfirst($request->status) }}</span>
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
				<li class="active"><a href="#request" data-toggle="tab">Request details</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="request">

					@if (Session::has('declined'))
						<div class="alert alert-danger">
							Request declined.
						</div>
					@endif

					@if (Session::has('accepted'))
						<div class="alert alert-success">
							Request accepted.
						</div>
					@endif

					@if ($request->status !== 'pending')
						<p><span class="label label-{{ $request->status == 'declined' ? 'danger' : 'success' }}">{{ ucfirst($request->status) }}</span> by {{ $request->actioner_name() }} on {{ $request->updated_at->format(Config::get('coanda::coanda.datetime_format')) }}</p>
					@endif

					<table class="table table-striped">
						<tr>
							<th>Creator</th>
							<td>{{ $request->version->creator_name }}</td>
						</tr>
						<tr>
							<th>Created</th>
							<td>{{ $request->version->updated_at->format(Config::get('coanda::coanda.datetime_format')) }}</td>
						</tr>
						<tr>
							<th>Page</th>
							<td>{{ $request->version->page->name }}</td>
						</tr>
						<tr>
							<th>Version</th>
							<td>#{{ $request->version->version }}</td>
						</tr>
						@if ($request->status == 'pending')
							<tr>
								<th>URL</th>
								<td>
									{{ $request->version->parent_slug }}/{{ $request->version->slug }}
								</td>
							</tr>
							<tr>
								<th>Preview</th>
								<td>{{ url($request->version->preview_url) }} <a class="new-window" href="{{ url($request->version->preview_url) }}"><i class="fa fa-share-square-o"></i></a></td>
							</tr>
						@else
							<tr>
								<th>Message</th>
								<td>
									<div class="well well-sm" style="margin-bottom: 0;">{{ $request->message !== '' ? nl2br($request->message) : '<em>No message</em>' }}</div>
								</td>
							</tr>
						@endif

					</table>

					@if ($request->status == 'pending')
						<hr>

						{{ Form::open(['url' => Coanda::adminUrl('contentsignoff/request/' . $request->id)]) }}

							<div class="form-group">
								<label for="message">Message</label>
								<textarea class="form-control" id="message" name="message" rows="5"></textarea>
								<span class="help-block">This message will be shared with {{ $request->version->creator_name }}</span>
							</div>

							{{ Form::button('Accept and publish this version', ['name' => 'accept', 'value' => 'true', 'type' => 'submit', 'class' => 'btn btn-success']) }}
							{{ Form::button('Decline and revert to draft', ['name' => 'decline', 'value' => 'true', 'type' => 'submit', 'class' => 'btn btn-danger']) }}

						{{ Form::close() }}
					@endif
				</div>
			</div>
		</div>
	</div>
</div>

@stop