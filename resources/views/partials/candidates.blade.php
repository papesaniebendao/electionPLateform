@foreach($candidats as $candidat)
    <div class="col-md-4">
        <div class="candidate-card">
            <img src="{{ asset('assets/icone.jpg') }}" alt="Candidat" class="candidate-img">
            <div class="candidate-info">
                <p class="candidate-name">{{ $candidat->user->prenom }} {{ $candidat->user->nom }}</p>
                <p>Niveau: {{ $candidat->user->niveau }}</p>
                <p>Liste: {{ $candidat->list->nom_liste }}</p>
                <!-- <button class="vote-button">Voter</button> -->

                <form class ="voteForm" action="{{ route('vote.submit') }}" method="POST">
                    @csrf
                    <input type="hidden" name="candidat_id" value="{{ $candidat->id }}">
                    <button type="submit" class="vote-button">Voter</button>
                </form>
            </div>
        </div>
    </div>
@endforeach
