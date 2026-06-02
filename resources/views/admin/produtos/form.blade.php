@extends('admin.layout')

@section('title', $produto ? __('messages.admin_edit_product') : __('messages.admin_add_product'))

@section('extra-styles')
<style>
    .form-card {
        background: rgba(0,26,32,0.6); border: 1px solid rgba(253,233,162,0.12);
        border-radius: 16px; padding: 32px; max-width: 800px;
    }
    .form-section-title {
        font-family: 'Crimson Pro', serif; font-size: 15px; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.1em; color: var(--teal-light);
        margin-bottom: 16px; padding-bottom: 8px;
        border-bottom: 1px solid rgba(144,221,232,0.15);
    }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px; }
    .form-group { display: flex; flex-direction: column; gap: 6px; }
    .form-group.full { grid-column: 1 / -1; }
    .form-label { font-size: 12px; font-weight: 600; color: var(--yellow-light); text-transform: uppercase; letter-spacing: 0.05em; }
    .form-hint { font-size: 11px; color: rgba(255,255,255,0.3); }
    .form-input, .form-textarea {
        width: 100%; padding: 12px 14px; background: rgba(0,40,48,0.8);
        border: 1px solid rgba(255,255,255,0.1); border-radius: 10px;
        color: var(--white); font-family: 'Inter', sans-serif; font-size: 14px;
        outline: none; transition: border-color 0.25s;
    }
    .form-input:focus, .form-textarea:focus { border-color: var(--yellow-gold); }
    .form-input::placeholder, .form-textarea::placeholder { color: rgba(255,255,255,0.2); }
    .form-textarea { resize: vertical; min-height: 100px; }
    .plat-checks { display: flex; flex-wrap: wrap; gap: 10px; }
    .plat-check {
        display: flex; align-items: center; gap: 6px;
        padding: 6px 14px; border-radius: 8px;
        border: 1px solid rgba(255,255,255,0.1); background: rgba(0,40,48,0.5);
        cursor: pointer; transition: all 0.2s; font-size: 13px; color: rgba(255,255,255,0.6);
    }
    .plat-check:hover { border-color: rgba(253,233,162,0.3); }
    .plat-check input { accent-color: var(--yellow-gold); cursor: pointer; }
    .plat-check.checked { border-color: var(--yellow-gold); background: rgba(253,233,162,0.08); color: var(--yellow-light); }
    .editions-list { display: flex; flex-direction: column; gap: 10px; margin-bottom: 12px; }
    .edition-row {
        display: flex; gap: 10px; align-items: center;
        padding: 10px 14px; background: rgba(0,40,48,0.5);
        border: 1px solid rgba(255,255,255,0.06); border-radius: 10px;
    }
    .edition-row input { flex: 1; }
    .btn-remove-edition {
        width: 32px; height: 32px; border-radius: 6px; border: 1px solid rgba(255,80,80,0.3);
        background: transparent; color: #ff6b6b; cursor: pointer; font-size: 14px;
        display: flex; align-items: center; justify-content: center; transition: all 0.2s; flex-shrink: 0;
    }
    .btn-remove-edition:hover { background: rgba(255,80,80,0.1); border-color: #ff6b6b; }
    .btn-add-edition {
        padding: 8px 16px; background: transparent; border: 1px dashed rgba(253,233,162,0.25);
        border-radius: 8px; color: rgba(253,233,162,0.5); font-size: 13px; font-weight: 600;
        cursor: pointer; transition: all 0.2s; width: fit-content;
    }
    .btn-add-edition:hover { border-color: var(--yellow-gold); color: var(--yellow-gold); }
    .toggle-wrap { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; }
    .toggle-switch {
        position: relative; width: 48px; height: 26px; background: rgba(255,255,255,0.1);
        border-radius: 13px; cursor: pointer; transition: background 0.3s;
    }
    .toggle-switch input { display: none; }
    .toggle-switch .slider {
        position: absolute; top: 3px; left: 3px; width: 20px; height: 20px;
        background: rgba(255,255,255,0.4); border-radius: 50%; transition: all 0.3s;
    }
    .toggle-switch input:checked + .slider { left: 25px; background: var(--yellow-gold); }
    .toggle-label { font-size: 14px; font-weight: 600; color: rgba(255,255,255,0.6); }
    .form-actions { display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.06); }
    .btn-cancel {
        padding: 12px 24px; background: transparent; border: 1px solid rgba(255,255,255,0.15);
        border-radius: 10px; color: rgba(255,255,255,0.5); font-size: 14px; font-weight: 600;
        cursor: pointer; text-decoration: none; transition: all 0.2s;
    }
    .btn-cancel:hover { border-color: rgba(255,255,255,0.4); color: white; }
    .btn-save {
        padding: 12px 28px; background: var(--admin-accent); border: none;
        border-radius: 10px; color: #fff; font-family: 'Gasoek One', sans-serif;
        font-size: 14px; cursor: pointer; transition: all 0.2s;
    }
    .btn-save:hover { background: #ff8555; transform: translateY(-1px); }
    .current-image { margin-top: 8px; }
    .current-image img { width: 120px; height: 70px; object-fit: cover; border-radius: 8px; border: 1px solid rgba(253,233,162,0.2); }
    .gallery-preview { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 10px; }
    .gallery-item { position: relative; }
    .gallery-item img { width: 100px; height: 65px; object-fit: cover; border-radius: 8px; border: 1px solid rgba(253,233,162,0.2); }
    .gallery-item label {
        position: absolute; top: -6px; right: -6px; width: 20px; height: 20px;
        background: rgba(255,80,80,0.9); border-radius: 50%; display: flex;
        align-items: center; justify-content: center; cursor: pointer;
        font-size: 11px; color: #fff; font-weight: 700; transition: transform 0.2s;
    }
    .gallery-item label:hover { transform: scale(1.15); }
    .gallery-item input { display: none; }
    .trailer-preview { margin-top: 10px; aspect-ratio: 16/9; max-width: 320px; border-radius: 8px; overflow: hidden; background: #000; }
    .trailer-preview iframe { width: 100%; height: 100%; border: none; }
    #requisitosSection { display: none; }
    #requisitosSection.visible { display: block; }
    @media (max-width: 600px) { .form-grid { grid-template-columns: 1fr; } .edition-row { flex-wrap: wrap; } }
</style>
@endsection

@section('content')

<p class="page-title">{{ $produto ? __('messages.admin_edit_product') : __('messages.admin_add_product') }}</p>

<div class="form-card">
    <form action="{{ $produto ? route('admin.produtos.atualizar', $produto->id) : route('admin.produtos.salvar') }}"
          method="POST" enctype="multipart/form-data">
        @csrf
        @if($produto) @method('PUT') @endif

        {{-- Info basica --}}
        <p class="form-section-title">Informacoes</p>
        <div class="form-grid">
            <div class="form-group full">
                <label class="form-label">{{ __('messages.admin_product_name') }}</label>
                <input class="form-input" type="text" name="nome" value="{{ old('nome', $produto->nome ?? '') }}" required placeholder="Ex: God of War Ragnarok">
                @error('nome') <span style="color: #ff6b6b; font-size: 12px;">{{ $message }}</span> @enderror
            </div>
            <div class="form-group full">
                <label class="form-label">{{ __('messages.admin_product_desc') }}</label>
                <textarea class="form-textarea" name="descricao" required placeholder="Descricao do produto...">{{ old('descricao', $produto->descricao ?? '') }}</textarea>
            </div>
            <div class="form-group">
                <label class="form-label">{{ __('messages.admin_product_genre') }}</label>
                <input class="form-input" type="text" name="genero" value="{{ old('genero', $produto->genero ?? '') }}" placeholder="Ex: Acao, RPG">
            </div>
            <div class="form-group">
                <label class="form-label">{{ __('messages.admin_product_dev') }}</label>
                <input class="form-input" type="text" name="desenvolvedor" value="{{ old('desenvolvedor', $produto->desenvolvedor ?? '') }}" placeholder="Ex: Santa Monica Studio">
            </div>
            <div class="form-group">
                <label class="form-label">{{ __('messages.admin_product_pub') }}</label>
                <input class="form-input" type="text" name="publisher" value="{{ old('publisher', $produto->publisher ?? '') }}" placeholder="Ex: Sony Interactive">
            </div>
            <div class="form-group">
                <label class="form-label">{{ __('messages.admin_product_image') }}</label>
                <input class="form-input" type="file" name="imagem_principal" accept=".jpg,.jpeg,.webp" {{ $produto ? '' : 'required' }}>
                <span class="form-hint">Formatos: JPG, JPEG, WEBP (max 4MB)</span>
                @if($produto && $produto->imagem_principal)
                    <div class="current-image">
                        <img src="{{ str_starts_with($produto->imagem_principal, 'http') ? $produto->imagem_principal : asset('images/' . $produto->imagem_principal) }}" alt="Atual">
                    </div>
                @endif
                @error('imagem_principal') <span style="color: #ff6b6b; font-size: 12px;">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Galeria --}}
        <p class="form-section-title">Galeria de Imagens</p>
        <div class="form-grid" style="margin-bottom: 24px;">
            <div class="form-group full">
                <label class="form-label">Adicionar Fotos</label>
                <input class="form-input" type="file" name="galeria[]" accept=".jpg,.jpeg,.webp" multiple>
                <span class="form-hint">Selecione multiplas imagens (JPG, JPEG, WEBP). Elas serao adicionadas as existentes.</span>
                @error('galeria.*') <span style="color: #ff6b6b; font-size: 12px;">{{ $message }}</span> @enderror

                @if($produto && is_array($produto->galeria) && count($produto->galeria) > 0)
                <div class="gallery-preview">
                    @foreach($produto->galeria as $gImg)
                    <div class="gallery-item">
                        <img src="{{ str_starts_with($gImg, 'http') ? $gImg : asset('images/' . $gImg) }}" alt="Galeria" onerror="this.parentElement.style.display='none'">
                        <label title="Remover esta imagem">
                            <input type="checkbox" name="remover_galeria[]" value="{{ $gImg }}">
                            X
                        </label>
                    </div>
                    @endforeach
                </div>
                <span class="form-hint" style="margin-top: 6px;">Clique no X para marcar imagens para remocao ao salvar.</span>
                @endif
            </div>
        </div>

        {{-- Trailer --}}
        <p class="form-section-title">Trailer</p>
        <div class="form-grid" style="margin-bottom: 24px;">
            <div class="form-group full">
                <label class="form-label">URL do Trailer (YouTube)</label>
                <input class="form-input" type="url" name="trailer_url" id="trailerInput"
                       value="{{ old('trailer_url', $produto->trailer_url ?? '') }}"
                       placeholder="https://www.youtube.com/watch?v=XXXXX"
                       oninput="previewTrailer(this.value)">
                <span class="form-hint">Cole o link do YouTube. O trailer sera exibido na pagina do produto.</span>
                @error('trailer_url') <span style="color: #ff6b6b; font-size: 12px;">{{ $message }}</span> @enderror
                <div class="trailer-preview" id="trailerPreview" style="display: none;"></div>
            </div>
        </div>

        {{-- Plataformas --}}
        <p class="form-section-title">{{ __('messages.admin_product_platforms') }}</p>
        @php
            $allPlats = ['PlayStation 5', 'PlayStation 4', 'Xbox', 'Nintendo Switch', 'PC'];
            $selectedPlats = old('plataformas', $produto->plataformas ?? []);
        @endphp
        <div class="plat-checks" id="platChecks" style="margin-bottom: 24px;">
            @foreach($allPlats as $plat)
            <label class="plat-check {{ in_array($plat, $selectedPlats) ? 'checked' : '' }}" onclick="this.classList.toggle('checked'); toggleRequisitos();">
                <input type="checkbox" name="plataformas[]" value="{{ $plat }}" {{ in_array($plat, $selectedPlats) ? 'checked' : '' }}>
                {{ $plat }}
            </label>
            @endforeach
        </div>
        @error('plataformas') <span style="color: #ff6b6b; font-size: 12px;">{{ $message }}</span> @enderror

        {{-- Requisitos de Sistema (PC) --}}
        @php
            $req = old('req_min_cpu') ? null : ($produto->requisitos ?? null);
        @endphp
        <div id="requisitosSection" class="{{ in_array('PC', $selectedPlats) ? 'visible' : '' }}">
            <p class="form-section-title">Requisitos de Sistema (PC)</p>
            <div class="form-grid">
                <div class="form-group full" style="margin-bottom: 4px;">
    <label class="form-label" style="color: #6bffb5;">Minimo</label>
</div>
<div class="form-group">
    <label class="form-label">SO</label>
    <input class="form-input" type="text" name="req_min_so" value="{{ old('req_min_so', $req['minimo']['so'] ?? '') }}" placeholder="Ex: Windows 10 64-bit">
</div>
<div class="form-group">
    <label class="form-label">CPU</label>
                    <input class="form-input" type="text" name="req_min_cpu" value="{{ old('req_min_cpu', $req['minimo']['cpu'] ?? '') }}" placeholder="Ex: Intel Core i5-6600">
                </div>
                <div class="form-group">
                    <label class="form-label">GPU</label>
                    <input class="form-input" type="text" name="req_min_gpu" value="{{ old('req_min_gpu', $req['minimo']['gpu'] ?? '') }}" placeholder="Ex: GTX 1060 6GB">
                </div>
                <div class="form-group">
                    <label class="form-label">RAM</label>
                    <input class="form-input" type="text" name="req_min_ram" value="{{ old('req_min_ram', $req['minimo']['ram'] ?? '') }}" placeholder="Ex: 8 GB">
                </div>
                <div class="form-group">
                    <label class="form-label">Armazenamento</label>
                    <input class="form-input" type="text" name="req_min_storage" value="{{ old('req_min_storage', $req['minimo']['storage'] ?? '') }}" placeholder="Ex: 50 GB SSD">
                </div>

                <div class="form-group full" style="margin-bottom: 4px; margin-top: 8px;">
    <label class="form-label" style="color: #FDE9A2;">Recomendado</label>
</div>
<div class="form-group">
    <label class="form-label">SO</label>
    <input class="form-input" type="text" name="req_rec_so" value="{{ old('req_rec_so', $req['recomendado']['so'] ?? '') }}" placeholder="Ex: Windows 11 64-bit">
</div>
<div class="form-group">
    <label class="form-label">CPU</label>
                    <input class="form-input" type="text" name="req_rec_cpu" value="{{ old('req_rec_cpu', $req['recomendado']['cpu'] ?? '') }}" placeholder="Ex: Intel Core i7-8700">
                </div>
                <div class="form-group">
                    <label class="form-label">GPU</label>
                    <input class="form-input" type="text" name="req_rec_gpu" value="{{ old('req_rec_gpu', $req['recomendado']['gpu'] ?? '') }}" placeholder="Ex: RTX 3070">
                </div>
                <div class="form-group">
                    <label class="form-label">RAM</label>
                    <input class="form-input" type="text" name="req_rec_ram" value="{{ old('req_rec_ram', $req['recomendado']['ram'] ?? '') }}" placeholder="Ex: 16 GB">
                </div>
                <div class="form-group">
                    <label class="form-label">Armazenamento</label>
                    <input class="form-input" type="text" name="req_rec_storage" value="{{ old('req_rec_storage', $req['recomendado']['storage'] ?? '') }}" placeholder="Ex: 100 GB SSD">
                </div>
            </div>
        </div>

        {{-- Edicoes --}}
        <p class="form-section-title">{{ __('messages.admin_product_editions') }}</p>
        <div class="editions-list" id="editionsList">
            @php $edicoes = old('edicao_nome') ? array_map(null, old('edicao_nome'), old('edicao_preco')) : ($produto && $produto->edicoes ? $produto->edicoes : [['nome' => 'Standard', 'preco' => '']]); @endphp
            @foreach($edicoes as $i => $ed)
            <div class="edition-row">
                <input class="form-input" type="text" name="edicao_nome[]" value="{{ is_array($ed) ? ($ed['nome'] ?? ($ed[0] ?? '')) : $ed }}" placeholder="{{ __('messages.admin_edition_name') }}" required>
                <input class="form-input" type="number" step="0.01" name="edicao_preco[]" value="{{ is_array($ed) ? ($ed['preco'] ?? ($ed[1] ?? '')) : '' }}" placeholder="{{ __('messages.admin_edition_price') }}" required style="max-width: 140px;">
                <button type="button" class="btn-remove-edition" onclick="this.parentElement.remove()">X</button>
            </div>
            @endforeach
        </div>
        <button type="button" class="btn-add-edition" onclick="addEdition()">{{ __('messages.admin_add_edition') }}</button>

        {{-- Ativo --}}
        <div class="toggle-wrap" style="margin-top: 24px;">
            <label class="toggle-switch">
                <input type="checkbox" name="ativo" value="1" {{ old('ativo', $produto->ativo ?? true) ? 'checked' : '' }}>
                <span class="slider"></span>
            </label>
            <span class="toggle-label">{{ __('messages.admin_product_active') }}</span>
        </div>

        {{-- Acoes --}}
        <div class="form-actions">
            <a href="{{ route('admin.produtos') }}" class="btn-cancel">{{ __('messages.cancel') }}</a>
            <button type="submit" class="btn-save">{{ __('messages.admin_save') }}</button>
        </div>
    </form>
</div>

<script>
function addEdition() {
    const list = document.getElementById('editionsList');
    const row = document.createElement('div');
    row.className = 'edition-row';
    row.innerHTML = '<input class="form-input" type="text" name="edicao_nome[]" placeholder="Nome da edicao" required>' +
        '<input class="form-input" type="number" step="0.01" name="edicao_preco[]" placeholder="Preco" required style="max-width: 140px;">' +
        '<button type="button" class="btn-remove-edition" onclick="this.parentElement.remove()">X</button>';
    list.appendChild(row);
}

function previewTrailer(url) {
    var preview = document.getElementById('trailerPreview');
    var match = url.match(/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/);
    if (match && match[1]) {
        preview.style.display = 'block';
        preview.innerHTML = '<iframe src="https://www.youtube.com/embed/' + match[1] + '" allowfullscreen></iframe>';
    } else {
        preview.style.display = 'none';
        preview.innerHTML = '';
    }
}

function toggleRequisitos() {
    var checks = document.querySelectorAll('#platChecks input[type="checkbox"]');
    var pcChecked = false;
    checks.forEach(function(c) {
        if (c.value === 'PC' && c.checked) pcChecked = true;
    });
    var section = document.getElementById('requisitosSection');
    if (pcChecked) {
        section.classList.add('visible');
    } else {
        section.classList.remove('visible');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var input = document.getElementById('trailerInput');
    if (input && input.value) previewTrailer(input.value);
    toggleRequisitos();
});
</script>

@endsection