<table class="election-table">
    <thead>
        <tr>
            <th>CANDIDATS</th>
            <th>LISTE ÉLECTORALE</th>
            <th>Departement</th>
            <th>Niveau</th>
            <th>NOMBRE DE VOIX</th>
        </tr>
    </thead>
    <tbody>
        @foreach($candidats as $candidat)
            <tr>
                <td class="candidate-cell">
                    <div class="candidate-profile">
                        <img src="{{ $candidat->user->photo ? asset('storage/' . $candidat->user->photo) : asset('assets/icone.jpg') }}" 
                            alt="Candidat" class="candidate-avatar">
                        <div class="candidate-info">
                            <div class="candidate-name">{{ $candidat->user->prenom }} {{ $candidat->user->nom }}</div>
                        </div>
                    </div>
                </td>
                <td>{{ $candidat->list->nom_liste }}</td>
                <td>{{ $candidat->user->departement }}</td>
                <td>{{ $candidat->user->niveau }}</td>
                <td class="vote-count" >{{ $candidat->count_votes }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<style>
.election-table {
    width: 100%;
    border-collapse: collapse;
    margin: 25px 0;
    font-size: 0.9em;
    min-width: 400px;
    border-radius: 8px 8px 0 0;
    overflow: hidden;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
}

.election-table thead tr {
    background-color: #3f51b5;
    color: #ffffff;
    text-align: left;
}

.election-table th,
.election-table td {
    padding: 15px 20px;
    width: 33.33%;
}

.election-table tbody tr {
    border-bottom: 1px solid #f0f0f0;
    transition: all 0.2s;
}

.election-table tbody tr:nth-of-type(even) {
    background-color: #f8f9fa;
}

.election-table tbody tr:last-of-type {
    border-bottom: 2px solid #3f51b5;
}

.election-table tbody tr:hover {
    background-color: #f1f3ff;
    transform: translateX(4px);
}

.candidate-profile {
    display: flex;
    align-items: center;
    gap: 15px;
}

.candidate-avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e0e0e0;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.candidate-name {
    font-weight: 600;
    color: #2d3436;
}

.vote-count {
    font-weight: bold;
    color: #3f51b5;
    text-align: center;
}
</style>