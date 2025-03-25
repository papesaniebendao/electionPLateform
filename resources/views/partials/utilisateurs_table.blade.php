@foreach($utilisateurs as $utilisateur)
    <tr>
        <td style="text-align: center; vertical-align: middle;">
            <img src="{{ asset('assets/icons8-contact-24.png') }}" width="24" height="24" style="display: block; margin: auto;">
        </td>
        <td style="vertical-align: middle;">{{ $utilisateur->prenom }} {{ $utilisateur->nom }}</td>
        <td style="vertical-align: middle;">{{ $utilisateur->email }}</td>
        <td style="vertical-align: middle;">{{ $utilisateur->departement }}</td>
        <td style="vertical-align: middle;">
            <div class="switch-container">
                <label class="switch">
                    <input type="checkbox" 
                           id="checkbox{{ $utilisateur->id }}" 
                           {{ $utilisateur->is_active ? 'checked' : '' }} 
                           {{ !$modificationActive ? 'disabled' : '' }} />
                    <span class="slider"></span>
                </label>
            </div>
        </td>
    </tr>
@endforeach