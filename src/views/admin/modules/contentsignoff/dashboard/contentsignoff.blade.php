<div class="panel panel-default">
    @set('requests', Coanda::module('contentsignoff')->manager()->pending(10))
    <div class="panel-heading">
        <span class="pull-right">
            Total
            <span class="badge badge-default">
                {{ $requests->getTotal() }}
            </span>
        </span>
        Pending Signoff Requests
    </div>
    <div class="panel-body">
        <div class="tab-pane active" id="forms">
            @if ($requests->count() > 0)
                <table class="table table-striped">
                    @foreach ($requests as $request)
                    <tr>
                        <td>Version <span class="badge badge-default">#{{ $request->version }}</span> of <a href="{{ Coanda::adminUrl('contentsignoff/request/' . $request->id) }}">{{ $request->page_name }}</a> from {{ $request->requester_name() }}</td>
                        <td class="tight">
                            <span class="label label-warning">{{ ucfirst($request->status) }}</span>
                        </td>
                    </tr>
                    @endforeach
                </table>

                <div class="buttons">
                    <a href="{{ Coanda::adminUrl('contentsignoff/history') }}" class="btn btn-default pull-right">View History</a>

                    <a href="{{ Coanda::adminUrl('contentsignoff') }}" class="btn btn-default pull-right" style="margin-right: 10px;">View All</a>
                </div>
            @else
                <p>No pending requests!</p>

                <div class="buttons">
                    <a href="{{ Coanda::adminUrl('contentsignoff/history') }}" class="btn btn-default pull-right">View History</a>
                </div>
            @endif
        </div>
    </div>
</div>