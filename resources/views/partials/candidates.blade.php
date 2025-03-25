@foreach($candidats as $candidat)
@if (session('success') || session('error'))
    <div class="alert-container">
        <div class="alert {{ session('success') ? 'alert-success' : 'alert-danger' }}">
            <span>{{ session('success') ?? session('error') }}</span>
        </div>
    </div>

    <script>
        // Cacher le message après 5 secondes
        setTimeout(function() {
            document.querySelector('.alert-container').style.display = 'none';
        }, 5000);  // 5000ms = 5 secondes
    </script>
@endif
    <div class="col-md-4">
        <div class="candidate-card">
            <img src="{{ $candidat->user->photo ? asset('storage/' . $candidat->user->photo) : asset('assets/icone.jpg') }}" 
                 alt="Candidat" class="candidate-img">
            <div class="candidate-info">
                <p class="candidate-name">{{ $candidat->user->prenom }} {{ $candidat->user->nom }}</p>
                <p>Poste: {{  $candidat->user->niveau }} {{ $candidat->user->departement }}</p>
                <p>Liste: {{ $candidat->list->nom_liste }}</p>
                <p>Votes: <span class="votes-count">{{ $candidat->votes_count }}</span></p>
                <!-- <button class="vote-button">Voter</button> -->

                <form class ="voteForm" action="{{ route('vote.submit') }}" method="POST">
                    @csrf
                    <input type="hidden" name="candidat_id" value="{{ $candidat->id }}">
                    <button type="button" class="vote-button" onclick="voteForCandidate(event, this)" data-candidat-id="{{ $candidat->id }}">Voter</button>
                </form>
            </div>
        </div>
    </div>
@endforeach





