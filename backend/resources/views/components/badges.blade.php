@foreach($answers as $a)
<a href="{{route('questions.index', ['answer' => $a->name])}}"><span class="answers badge rounded-pill bg-primary">{{$a->name}}</span></a>
@endforeach