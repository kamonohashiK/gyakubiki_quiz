<a href="{{route('questions.show', $q->id)}}" class="no-decoration">
    <div class="card">
        <div class="card-body">
            <p class="card-text">{{$q->content}}</p>
            <h6 class="card-subtitle mb-2 text-muted">{{$q->user->name}} {{date('Y年n月j日 H:i:s', strtotime($q->updated_at))}}</h6>{{-- TODO: 作者名の表示　--}}
        </div>
    </div>
</a>