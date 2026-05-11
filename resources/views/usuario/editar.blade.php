@extends('usuario.layout')

@section('title', 'Editar Perfil')

@section('extra-styles')
<style>
    .page-title {
        font-family: 'Gasoek One', sans-serif;
        font-size: 28px;
        color: var(--yellow-gold);
        font-style: italic;
        text-decoration: underline;
        margin-bottom: 28px;
    }

    .edit-layout {
        display: grid;
        grid-template-columns: 260px 1fr;
        gap: 24px;
        align-items: start;
    }

    /* ===== CARD DO AVATAR ===== */
    .avatar-card {
        background: rgba(0, 26, 32, 0.6);
        border: 1px solid rgba(253,233,162,0.2);
        border-radius: 16px;
        padding: 28px 20px;
        text-align: center;
    }

    .avatar-atual-wrap {
        display: inline-block;
        margin-bottom: 20px;
    }

    .avatar-atual {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        border: 4px solid var(--yellow-gold);
        object-fit: cover;
        background: #1F6D7E;
        display: block;
        image-rendering: pixelated;
        transition: border-color 0.2s;
    }

    .avatar-card-title {
        font-family: 'Inter', sans-serif;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: rgba(255,255,255,0.4);
        margin-bottom: 14px;
    }

    /* Grade de avatares para escolher */
    .avatar-options {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .avatar-option {
        position: relative;
        cursor: pointer;
    }

    .avatar-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    .avatar-option img {
        width: 100%;
        aspect-ratio: 1;
        border-radius: 50%;
        border: 3px solid transparent;
        object-fit: cover;
        background: #1F6D7E;
        image-rendering: pixelated;
        transition: border-color 0.2s, transform 0.2s;
        display: block;
    }

    .avatar-option input[type="radio"]:checked + img {
        border-color: var(--yellow-gold);
        transform: scale(1.06);
        box-shadow: 0 0 12px rgba(245,200,66,0.5);
    }

    .avatar-option img:hover {
        border-color: rgba(253,233,162,0.5);
        transform: scale(1.04);
    }

    /* Checkmark no avatar selecionado */
    .avatar-option input[type="radio"]:checked ~ .avatar-check {
        display: flex;
    }
    .avatar-check {
        display: none;
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 20px;
        height: 20px;
        background: var(--yellow-gold);
        border-radius: 50%;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        color: #001A20;
        font-weight: 700;
    }

    /* ===== FORM ===== */
    .form-card {
        background: rgba(0, 26, 32, 0.6);
        border: 1px solid rgba(253,233,162,0.2);
        border-radius: 16px;
        padding: 32px;
    }

    .form-section-title {
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--teal-light);
        margin-bottom: 18px;
        padding-bottom: 8px;
        border-bottom: 1px solid rgba(144,221,232,0.15);
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 28px;
    }

    .form-group { display: flex; flex-direction: column; gap: 6px; }
    .form-group.full { grid-column: 1 / -1; }

    .form-label {
        font-family: 'Crimson Pro', serif;
        font-style: italic;
        font-weight: 600;
        font-size: 14px;
        color: var(--yellow-light);
    }

    .form-input {
        width: 100%;
        padding: 13px 16px;
        background: rgba(0,40,48,0.8);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 10px;
        color: var(--white);
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        outline: none;
        transition: border-color 0.25s;
    }
    .form-input:focus { border-color: var(--yellow-gold); }
    .form-input::placeholder { color: rgba(255,255,255,0.25); }
    .form-input[disabled] { opacity: 0.5; cursor: not-allowed; }

    .form-hint {
        font-family: 'Inter', sans-serif;
        font-size: 11px;
        color: rgba(255,255,255,0.35);
        margin-top: 2px;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 28px;
        padding-top: 20px;
        border-top: 1px solid rgba(255,255,255,0.06);
    }

    .alert-success {
        background: rgba(107,255,181,0.1);
        border: 1px solid rgba(107,255,181,0.3);
        color: #6bffb5;
        border-radius: 10px;
        padding: 12px 18px;
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        margin-bottom: 20px;
        display: none;
    }

    .danger-zone {
        background: rgba(255,80,80,0.06);
        border: 1px solid rgba(255,80,80,0.2);
        border-radius: 12px;
        padding: 20px 24px;
        margin-top: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .danger-text .dt-title { font-family: 'Inter', sans-serif; font-weight: 700; font-size: 14px; color: #ff6b6b; margin-bottom: 4px; }
    .danger-text .dt-sub   { font-family: 'Inter', sans-serif; font-size: 12px; color: rgba(255,255,255,0.35); }

    .btn-danger {
        padding: 10px 22px;
        background: transparent;
        border: 1px solid #ff6b6b;
        color: #ff6b6b;
        border-radius: 8px;
        font-family: 'Inter', sans-serif;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
    }
    .btn-danger:hover { background: rgba(255,80,80,0.15); }

    @media (max-width: 900px) {
        .edit-layout { grid-template-columns: 1fr; }
        .form-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')

<p class="page-title">Editar Perfil</p>

<div class="edit-layout">

    <div class="avatar-card">

        <div class="avatar-atual-wrap">
            <img class="avatar-atual" id="avatarPreview"
                 src="{{ asset('images/icone2.svg') }}"
                 alt="Avatar atual">
        </div>

        <p class="avatar-card-title">Escolha seu avatar</p>

        <div class="avatar-options">

            <label class="avatar-option">
                <input type="radio" name="avatar" value="icone1.svg" checked onchange="trocarAvatar(this)">
                <img src="{{ asset('images/icone1.svg') }}" alt="Avatar 1"
                     onerror="this.src='https://via.placeholder.com/80/1F6D7E/FFDC74?text=1'">
                <span class="avatar-check"><i class="fa-solid fa-check"></i></span>
            </label>

            <label class="avatar-option">
                <input type="radio" name="avatar" value="icone2.svg" onchange="trocarAvatar(this)">
                <img src="{{ asset('images/icone2.svg') }}" alt="Avatar 2"
                     onerror="this.src='https://via.placeholder.com/80/1F6D7E/FFDC74?text=2'">
                <span class="avatar-check"><i class="fa-solid fa-check"></i></span>
            </label>

            <label class="avatar-option">
                <input type="radio" name="avatar" value="icone3.svg" onchange="trocarAvatar(this)">
                <img src="{{ asset('images/icone3.svg') }}" alt="Avatar 3"
                     onerror="this.src='https://via.placeholder.com/80/1F6D7E/FFDC74?text=3'">
                <span class="avatar-check"><i class="fa-solid fa-check"></i></span>
            </label>

            <label class="avatar-option">
                <input type="radio" name="avatar" value="icone4.svg" onchange="trocarAvatar(this)">
                <img src="{{ asset('images/icone4.svg') }}" alt="Avatar 4"
                     onerror="this.src='https://via.placeholder.com/80/1F6D7E/FFDC74?text=4'">
                <span class="avatar-check"><i class="fa-solid fa-check"></i></span>
            </label>

        </div>
    </div>

    <div class="form-card">

        <div class="alert-success" id="alertSuccess">
            <i class="fa-solid fa-check-circle" style="margin-right: 8px;"></i>
            Perfil atualizado com sucesso!
        </div>

        <form action="{{ route('usuario.editar.salvar') }}" method="POST" id="editForm">
            @csrf
            @method('PUT')

            <input type="hidden" name="avatar" id="avatarSelecionado" value="icone1.svg">

            {{-- DADOS PESSOAIS --}}
            <p class="form-section-title">Dados Pessoais</p>
            <div class="form-grid">
                <div class="form-group full">
                    <label class="form-label">Nome completo</label>
                    <input class="form-input" type="text" name="name" value="Michael (MJ) Jackson" placeholder="Seu nome completo">
                </div>
                <div class="form-group">
                    <label class="form-label">Nickname</label>
                    <input class="form-input" type="text" name="nickname" value="MJ Jackson" placeholder="Seu nickname">
                </div>
                <div class="form-group">
                    <label class="form-label">CPF</label>
                    <input class="form-input" type="text" name="cpf" value="000.000.000-00" disabled>
                    <span class="form-hint">O CPF não pode ser alterado.</span>
                </div>
            </div>

            {{-- CONTATO --}}
            <p class="form-section-title">Contato</p>
            <div class="form-grid">
                <div class="form-group full">
                    <label class="form-label">E-mail</label>
                    <input class="form-input" type="email" name="email" value="MJ@gmail.com" placeholder="seu@email.com">
                </div>
            </div>

            {{-- SENHA --}}
            <p class="form-section-title">Alterar Senha</p>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Senha atual</label>
                    <input class="form-input" type="password" name="current_password" placeholder="••••••••">
                </div>
                <div class="form-group"></div>
                <div class="form-group">
                    <label class="form-label">Nova senha</label>
                    <input class="form-input" type="password" name="password" placeholder="••••••••">
                </div>
                <div class="form-group">
                    <label class="form-label">Confirmar nova senha</label>
                    <input class="form-input" type="password" name="password_confirmation" placeholder="••••••••">
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('usuario.perfil') }}" class="btn-outline">Cancelar</a>
                <button type="submit" class="btn-main">Salvar Alterações</button>
            </div>

        </form>

        <div class="danger-zone">
            <div class="danger-text">
                <p class="dt-title">Excluir conta</p>
                <p class="dt-sub">Esta ação é permanente e não poderá ser desfeita.</p>
            </div>
            <button type="button" class="btn-danger">Excluir minha conta</button>
        </div>

    </div>
</div>

<script>
    // Atualiza o avatar em destaque quando o usuário clica numa opção
    function trocarAvatar(radio) {
        const novoSrc = radio.closest('.avatar-option').querySelector('img').src;
        document.getElementById('avatarPreview').src = novoSrc;
        document.getElementById('avatarSelecionado').value = radio.value;
    }

    // Simula sucesso ao salvar (remover quando integrar ao back-end)
    document.getElementById('editForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const alerta = document.getElementById('alertSuccess');
        alerta.style.display = 'block';
        setTimeout(() => alerta.style.display = 'none', 4000);
    });
</script>

@endsection