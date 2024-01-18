@if(session('masuk'))
<audio src="{{ asset('masuk.mp3') }}" autoplay></audio>
@endif

@if(session('pulang'))
<audio src="{{ asset('pulang.mp3') }}" autoplay></audio>
@endif