@extends('usuario.layout')

@section('title', __('messages.edit_profile'))

@section('extra-styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<style>
    .toast-warning { position: fixed; bottom: -100px; left: 50%; transform: translateX(-50%); background: var(--yellow-gold); color: #001A20; padding: 12px 24px; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 14px; font-weight: 700; display: flex; align-items: center; gap: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.4); transition: bottom 0.4s ease; z-index: 10000; }
    .toast-warning.show { bottom: 30px; }
    .page-title { font-family: 'Gasoek One', sans-serif; font-size: 28px; color: var(--yellow-light); margin-bottom: 28px; }
    .edit-layout { display: grid; grid-template-columns: 260px 1fr; gap: 24px; align-items: start; }
    .avatar-card { background: rgba(0, 26, 32, 0.6); border: 1px solid rgba(253,233,162,0.2); border-radius: 16px; padding: 28px 20px; text-align: center; }
    .avatar-atual-wrap { display: inline-block; margin-bottom: 20px; position: relative; }
    .avatar-atual { width: 130px; height: 130px; border-radius: 50%; border: 4px solid var(--yellow-gold); object-fit: cover; background: #1F6D7E; display: block; transition: border-color 0.2s; }
    .avatar-card-title { font-family: 'Inter', sans-serif; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.4); margin-bottom: 14px; }
    .avatar-actions { display: flex; flex-direction: column; gap: 10px; }
    .btn-avatar { padding: 10px 14px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; border-radius: 8px; font-family: 'Crimson Pro', serif; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.2s; }
    .btn-avatar:hover { background: rgba(255,255,255,0.2); border-color: var(--yellow-gold); }
    .form-hint-avatar { font-size: 11px; color: rgba(255,255,255,0.4); margin-top: 8px; }
    .crop-modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 9999; align-items: center; justify-content: center; }
    .crop-container { background: #001A20; padding: 20px; border-radius: 12px; border: 1px solid var(--yellow-gold); width: 90%; max-width: 500px; }
    .img-container { width: 100%; height: 300px; margin-bottom: 20px; background: #000; }
    .img-container img { max-width: 100%; }
    .crop-actions { display: flex; justify-content: space-between; gap: 10px; }
    .crop-actions div { display: flex; gap: 10px; }
    .form-card { background: rgba(0, 26, 32, 0.6); border: 1px solid rgba(253,233,162,0.2); border-radius: 16px; padding: 32px; }
    .form-section-title { font-family: 'Crimson Pro', serif; font-size: 16px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--teal-light); margin-bottom: 18px; padding-bottom: 8px; border-bottom: 1px solid rgba(144,221,232,0.15); }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 28px; }
    .form-group { display: flex; flex-direction: column; gap: 6px; }
    .form-group.full { grid-column: 1 / -1; }
    .form-label { font-family: 'Crimson Pro', serif; font-weight: 600; font-size: 16px; color: var(--yellow-light); }
    .form-input { width: 100%; padding: 13px 16px; background: rgba(0,40,48,0.8); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; color: var(--white); font-family: 'Inter', sans-serif; font-size: 14px; outline: none; transition: border-color 0.25s; }
    .form-input:focus { border-color: var(--yellow-gold); }
    .form-input::placeholder { color: rgba(255,255,255,0.25); }
    .form-input[disabled] { opacity: 0.5; cursor: not-allowed; }
    .form-hint { font-family: 'Crimson Pro', serif; font-size: 14px; color: rgba(255,255,255,0.35); margin-top: 2px; }
    .form-actions { display: flex; gap: 12px; justify-content: flex-end; margin-top: 28px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.06); }
    .alert-success { background: rgba(107,255,181,0.1); border: 1px solid rgba(107,255,181,0.3); color: #6bffb5; border-radius: 10px; padding: 12px 18px; font-family: 'Inter', sans-serif; font-size: 14px; margin-bottom: 20px; display: none; }
    .danger-zone { background: rgba(255,80,80,0.06); border: 1px solid rgba(255,80,80,0.2); border-radius: 12px; padding: 20px 24px; margin-top: 24px; display: flex; align-items: center; justify-content: space-between; gap: 20px; }
    .danger-text .dt-title { font-family: 'Inter', sans-serif; font-weight: 700; font-size: 14px; color: #ff6b6b; margin-bottom: 4px; }
    .danger-text .dt-sub { font-family: 'Inter', sans-serif; font-size: 12px; color: rgba(255,255,255,0.35); }
    .btn-danger { padding: 10px 22px; background: transparent; border: 1px solid #ff6b6b; color: #ff6b6b; border-radius: 8px; font-family: 'Inter', sans-serif; font-weight: 600; font-size: 13px; cursor: pointer; transition: all 0.2s; white-space: nowrap; }
    .btn-danger:hover { background: rgba(255,80,80,0.15); }
    @media (max-width: 900px) { .edit-layout { grid-template-columns: 1fr; } .form-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')

<p class="page-title">{{ __('messages.edit_profile') }}</p>

<div class="edit-layout">

    <div class="avatar-card">
        <div class="avatar-atual-wrap">
            <img class="avatar-atual" id="avatarPreview"
                 src="{{ $usuario->avatar === 'icone1.svg' || !$usuario->avatar ? asset('images/icone1.svg') : asset('storage/' . $usuario->avatar) }}"
                 alt="Avatar atual">
        </div>
        <p class="avatar-card-title">{{ __('messages.your_photo') }}</p>

        <div class="avatar-actions">
            <input type="file" id="uploadAvatar" accept="image/png, image/jpeg, image/jpg" style="display: none;">
            <button type="button" class="btn-avatar" onclick="document.getElementById('uploadAvatar').click()">{{ __('messages.upload_new_photo') }}</button>
            <button type="button" class="btn-avatar" id="btnResetAvatar">{{ __('messages.use_default') }}</button>
        </div>
        <p class="form-hint-avatar">{{ __('messages.min_size') }}</p>
    </div>

    <div class="form-card">

        <div class="alert-success" id="alertSuccess">
            <i class="fa-solid fa-check-circle" style="margin-right: 8px;"></i>
            {{ __('messages.profile_updated') }}
        </div>

        <form action="{{ route('usuario.editar.salvar') }}" method="POST" id="editForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <input type="hidden" name="avatar_base64" id="avatarInputHidden" value="">

            <p class="form-section-title">{{ __('messages.personal_data') }}</p>
            <div class="form-grid">
                <div class="form-group full">
                    <label class="form-label">{{ __('messages.full_name') }}</label>
                    <input class="form-input" type="text" name="name" value="{{ old('name', $usuario->name) }}" placeholder="{{ __('messages.full_name') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('messages.username') }}</label>
                    <input class="form-input" type="text" name="nickname" id="nicknameInput" value="{{ old('nickname', $usuario->nickname ?? $usuario->name) }}" placeholder="@seunickname">
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('messages.cpf_label') }}</label>
                    <input class="form-input" type="text" name="cpf" value="{{ $usuario->cpf }}" disabled>
                    <span class="form-hint">{{ __('messages.cpf_not_editable') }}</span>
                </div>
            </div>

            <p class="form-section-title">{{ __('messages.contact') }}</p>
            <div class="form-grid">
                <div class="form-group full">
                    <label class="form-label">{{ __('messages.email') }}</label>
                    <input class="form-input" type="email" name="email" value="{{ old('email', $usuario->email) }}" placeholder="{{ __('messages.email') }}">
                </div>
            </div>

            <p class="form-section-title">{{ __('messages.change_password') }}</p>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">{{ __('messages.current_password') }}</label>
                    <input class="form-input" type="password" name="current_password" placeholder="••••••••">
                </div>
                <div class="form-group"></div>
                <div class="form-group">
                    <label class="form-label">{{ __('messages.new_password') }}</label>
                    <input class="form-input" type="password" name="password" placeholder="••••••••">
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('messages.confirm_new_password') }}</label>
                    <input class="form-input" type="password" name="password_confirmation" placeholder="••••••••">
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('usuario.perfil') }}" class="btn-outline">{{ __('messages.cancel') }}</a>
                <button type="submit" class="btn-main">{{ __('messages.save_changes') }}</button>
            </div>
        </form>

        <div class="danger-zone">
            <div class="danger-text">
                <p class="dt-title">{{ __('messages.delete_account') }}</p>
                <p class="dt-sub">{{ __('messages.delete_permanent') }}</p>
            </div>
            <form method="POST" action="{{ route('usuario.excluir.conta') }}" id="formExcluirConta">
                @csrf
                @method('DELETE')
                <button type="button" class="btn-danger" id="btnExcluir">{{ __('messages.delete_my_account') }}</button>
            </form>
        </div>
    </div>
</div>

<div class="crop-modal" id="cropModal">
    <div class="crop-container">
        <p class="form-section-title" style="margin-bottom: 10px;">{{ __('messages.adjust_photo') }}</p>
        <div class="img-container">
            <img id="imageToCrop" src="">
        </div>
        <div class="crop-actions">
            <div>
                <button type="button" class="btn-avatar" id="btnZoomIn"><i class="fa-solid fa-magnifying-glass-plus"></i> +</button>
                <button type="button" class="btn-avatar" id="btnZoomOut"><i class="fa-solid fa-magnifying-glass-minus"></i> -</button>
            </div>
            <div>
                <button type="button" class="btn-avatar" id="btnCancelCrop" style="background: transparent; color: #ff6b6b; border-color: #ff6b6b;">{{ __('messages.cancel') }}</button>
                <button type="button" class="btn-avatar" id="btnApplyCrop" style="background: var(--yellow-gold); color: #000;">{{ __('messages.apply') }}</button>
            </div>
        </div>
    </div>
</div>

<div id="toastSaveWarning" class="toast-warning">
    <i class="fa-solid fa-triangle-exclamation"></i>
    <span>{{ __('messages.photo_changed_toast') }}</span>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
    const defaultAvatarSrc = "{{ asset('images/icone1.svg') }}";
    const uploadInput = document.getElementById('uploadAvatar');
    const avatarPreview = document.getElementById('avatarPreview');
    const avatarInputHidden = document.getElementById('avatarInputHidden');
    const btnResetAvatar = document.getElementById('btnResetAvatar');
    const cropModal = document.getElementById('cropModal');
    const imageToCrop = document.getElementById('imageToCrop');
    let cropper;

    const msgMinSize = @json(__('messages.min_size'));
    const msgEnterPwd = @json(__('messages.enter_password_delete'));
    const msgPwdRequired = @json(__('messages.password_required_delete'));

    btnResetAvatar.addEventListener('click', () => {
        avatarPreview.src = defaultAvatarSrc;
        avatarInputHidden.value = 'reset_to_default';
        uploadInput.value = '';
        const toast = document.getElementById('toastSaveWarning');
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 4000);
    });

    uploadInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(event) {
            const img = new Image();
            img.onload = function() {
                if (this.width < 300 || this.height < 300) {
                    alert(msgMinSize);
                    uploadInput.value = '';
                    return;
                }
                imageToCrop.src = event.target.result;
                cropModal.style.display = 'flex';
                if (cropper) cropper.destroy();
                cropper = new Cropper(imageToCrop, {
                    aspectRatio: 1, viewMode: 1, dragMode: 'move', autoCropArea: 1,
                    restore: false, guides: false, center: false, highlight: false,
                    cropBoxMovable: true, cropBoxResizable: true, toggleDragModeOnDblclick: false,
                });
            };
            img.src = event.target.result;
        };
        reader.readAsDataURL(file);
    });

    document.getElementById('btnZoomIn')?.addEventListener('click', () => cropper?.zoom(0.1));
    document.getElementById('btnZoomOut')?.addEventListener('click', () => cropper?.zoom(-0.1));

    document.getElementById('btnCancelCrop')?.addEventListener('click', () => {
        cropModal.style.display = 'none';
        uploadInput.value = '';
        if (cropper) cropper.destroy();
    });

    document.getElementById('btnApplyCrop')?.addEventListener('click', () => {
        if (!cropper) return;
        const canvas = cropper.getCroppedCanvas({ width: 300, height: 300 });
        const base64Image = canvas.toDataURL('image/jpeg');
        avatarPreview.src = base64Image;
        avatarInputHidden.value = base64Image;
        cropModal.style.display = 'none';
        if (cropper) cropper.destroy();
        const toast = document.getElementById('toastSaveWarning');
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 4000);
    });

    document.getElementById('btnExcluir')?.addEventListener('click', function() {
        let senha = prompt(msgEnterPwd);
        if (senha && senha.trim() !== '') {
            let form = document.getElementById('formExcluirConta');
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'current_password';
            input.value = senha;
            form.appendChild(input);
            form.submit();
        } else {
            alert(msgPwdRequired);
        }
    });
</script>
@endsection