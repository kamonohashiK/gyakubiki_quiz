<a href="{{route('questions.show', $q->id)}}" class="no-decoration">
    <div class="card">
        <div class="card-body">
            <p class="card-text">{{$q->content}}</p>
            <h6 class="card-subtitle mb-2 text-muted">作者名 {{$q->updated_at}}</h6>{{-- TODO: 作者名の表示　--}}
        </div>
    </div>
</a>