@extends('layouts.app')

@section('title', 'Mon Crush - Hear Me Out')

@section('content')
@php
    $titleMax = config('content.crush.title_max', 100);
    $textMin = config('content.crush.text_min', 10);
    $textMax = config('content.crush.text_max', 1000);
    $imageMb = config('content.crush.image_max_mb', 3);
@endphp
<link rel="stylesheet" href="{{ asset('css/crush.css') }}">
<h2 style="margin-bottom: 1.5rem;">Mon Crush</h2>
@if(!$crush)
    <div style="background: #f8f9fa; padding: 2rem; border-radius: 8px; margin-bottom: 2rem;">
        <h3 style="margin-bottom: 1rem;">Créer mon crush</h3>
        <form method="POST" action="{{ route('crush.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="title">Titre (optionnel) <small style="color: #6c757d;">(max {{ $titleMax }} caractères)</small></label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" maxlength="{{ $titleMax }}">
            </div>

            <div class="form-group">
                <label for="text">Texte * <small style="color: #6c757d;">(max {{ $textMax }} caractères)</small></label>
                <textarea id="text" name="text" required maxlength="{{ $textMax }}" 
                          oninput="document.getElementById('charCount').textContent = this.value.length">{{ old('text') }}</textarea>
                <small style="color: #6c757d; display: block; margin-top: 0.5rem;">
                    <span id="charCount">0</span>/{{ $textMax }} caractères
                </small>
            </div>

            <div class="form-group">
                <label for="categories">Catégories (sélection multiple)</label>
                <div style="border: 1px solid #ddd; border-radius: 8px; padding: 1rem; background: #f8f9fa; max-height: 300px; overflow-y: auto;">
                    @foreach($categories as $category)
                        <label style="display: flex; align-items: center; padding: 0.5rem; cursor: pointer; transition: background 0.2s; border-radius: 4px;"
                               onmouseover="this.style.background='#e9ecef';"
                               onmouseout="this.style.background='transparent';">
                            <input type="checkbox" name="categories[]" value="{{ $category->id }}" 
                                   style="width: 18px; height: 18px; margin-right: 0.75rem; cursor: pointer;"
                                   {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                            <span style="font-size: 1rem;">{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>
                <small style="color: #6c757d; margin-top: 0.5rem; display: block;">Sélectionnez une ou plusieurs catégories pour votre crush</small>
            </div>

            <div class="form-group">
                <label for="image">Image (optionnel)</label>
                <div class="crush-image-container">
                    <div class="drag-drop-zone" id="dropZone">
                        <span class="plus-icon">+</span>
                        <p class="upload-text">Glissez une image ici<br>ou cliquez pour en sélectionner une<br>
                        <small>(Max: {{ $imageMb }}MB, jpg, png, gif)</small></p>
                    </div>
                </div>
                <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/gif" style="display: none;">
            </div>

            <script>
            const dropZone = document.getElementById('dropZone');
            const imageInput = document.getElementById('image');
            const maxSizeMB = {{ $imageMb }};

            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.classList.add('drag-over');
            });

            dropZone.addEventListener('dragleave', () => {
                dropZone.classList.remove('drag-over');
            });

            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.classList.remove('drag-over');
                const file = e.dataTransfer.files[0];
                handleFile(file);
            });

            dropZone.addEventListener('click', () => {
                imageInput.click();
            });

            imageInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                handleFile(file);
            });

            function handleFile(file) {
                if (!file) return;

                if (file.size > maxSizeMB * 1024 * 1024) {
                    alert('Le fichier est trop volumineux. Taille maximum: ' + maxSizeMB + 'MB');
                    return;
                }

                const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Format de fichier non supporté. Utilisez JPG, PNG ou GIF.');
                    return;
                }

                // Créer un aperçu de l'image
                const reader = new FileReader();
                reader.onload = (e) => {
                    const preview = document.createElement('img');
                    preview.src = e.target.result;
                    preview.style.width = '100%';
                    preview.style.height = '100%';
                    preview.style.objectFit = 'cover';
                    preview.style.borderRadius = '8px';
                    
                    // Remplacer le contenu de la zone de drop
                    dropZone.innerHTML = '';
                    dropZone.appendChild(preview);
                };
                reader.readAsDataURL(file);

                // Mettre à jour l'input file
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                imageInput.files = dataTransfer.files;
            }

            // Initialiser le compteur de caractères
            document.addEventListener('DOMContentLoaded', function() {
                const textArea = document.getElementById('text');
                if (textArea && textArea.value) {
                    document.getElementById('charCount').textContent = textArea.value.length;
                }
            });
            </script>

            <div style="text-align: center; margin-top: 2rem;">
                <button type="submit" class="btn btn-create-crush">
                    <span class="plus-circle">+</span>
                    <span>Créer mon crush</span>
                </button>
            </div>

            <style>
                .btn-create-crush {
                    display: inline-flex;
                    align-items: center;
                    gap: 1rem;
                    padding: 1.25rem 3rem;
                    font-size: 1.3rem;
                    font-weight: 700;
                    background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
                    color: white;
                    border: none;
                    border-radius: 16px;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    box-shadow: 0 6px 20px rgba(46, 204, 113, 0.3);
                    position: relative;
                    overflow: hidden;
                }

                .btn-create-crush::before {
                    content: '';
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    width: 0;
                    height: 0;
                    border-radius: 50%;
                    background: rgba(255, 255, 255, 0.2);
                    transform: translate(-50%, -50%);
                    transition: width 0.6s, height 0.6s;
                }

                .btn-create-crush:hover::before {
                    width: 400px;
                    height: 400px;
                }

                .btn-create-crush:hover {
                    transform: translateY(-3px);
                    box-shadow: 0 10px 30px rgba(46, 204, 113, 0.4);
                }

                .btn-create-crush:active {
                    transform: translateY(0);
                }

                .plus-circle {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    width: 35px;
                    height: 35px;
                    background: rgba(255, 255, 255, 0.2);
                    border-radius: 50%;
                    font-size: 1.8rem;
                    font-weight: 300;
                    transition: all 0.3s ease;
                }

                .btn-create-crush:hover .plus-circle {
                    transform: rotate(90deg);
                    background: rgba(255, 255, 255, 0.3);
                }
            </style>
        </form>
    </div>
@else
    <div style="background: #f8f9fa; padding: 2rem; border-radius: 8px; margin-bottom: 2rem;">
        <h3 style="margin-bottom: 1rem;">Modifier mon crush</h3>
        <form method="POST" action="{{ route('crush.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="title">Titre</label>
                <input type="text" id="title" name="title" value="{{ old('title', $crush->title) }}" maxlength="{{ $titleMax }}" oninput="document.getElementById('editTitleCharCount').textContent = this.value.length">
                <small style="color: #666; display: block; margin-top: 0.25rem;">
                    <span id="editTitleCharCount">{{ strlen($crush->title) }}</span>/{{ $titleMax }} caractères
                </small>
            </div>

            <div class="form-group">
                <label for="text">Texte *</label>
                <textarea id="text" name="text" required maxlength="{{ $textMax }}" oninput="document.getElementById('editTextCharCount').textContent = this.value.length">{{ old('text', $crush->text) }}</textarea>
                <small style="color: #666; display: block; margin-top: 0.25rem;">
                    <span id="editTextCharCount">{{ strlen($crush->text) }}</span>/{{ $textMax }} caractères
                </small>
            </div>

            <div class="form-group">
                <label for="image">Image</label>
                @if($crush->image_path)
                    <img src="{{ Storage::url($crush->image_path) }}" alt="Crush image" style="max-width: 200px; display: block; margin-bottom: 0.5rem; border-radius: 4px;">
                @endif
                <input type="file" id="image" name="image" accept="image/*">
                <small style="color: #666; display: block; margin-top: 0.25rem;">
                    Modifier l'image ou le titre réinitialisera les statistiques
                </small>
            </div>

            <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 2rem; flex-wrap: wrap;">
                <button type="submit" class="btn btn-update-crush">
                    <span class="edit-icon">✏️</span>
                    <span>Mettre à jour</span>
                </button>
                <button type="button" onclick="if(confirm('Êtes-vous sûr de vouloir supprimer votre crush ?')) document.getElementById('delete-form').submit();" class="btn btn-delete-crush">
                    <span class="trash-icon">🗑️</span>
                    <span>Supprimer</span>
                </button>
            </div>

            <style>
                .btn-update-crush, .btn-delete-crush {
                    display: inline-flex;
                    align-items: center;
                    gap: 0.75rem;
                    padding: 1rem 2.5rem;
                    font-size: 1.2rem;
                    font-weight: 700;
                    border: none;
                    border-radius: 14px;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    position: relative;
                    overflow: hidden;
                }

                .btn-update-crush {
                    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
                    color: white;
                    box-shadow: 0 5px 18px rgba(52, 152, 219, 0.3);
                }

                .btn-delete-crush {
                    background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
                    color: white;
                    box-shadow: 0 5px 18px rgba(231, 76, 60, 0.3);
                }

                .btn-update-crush::before, .btn-delete-crush::before {
                    content: '';
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    width: 0;
                    height: 0;
                    border-radius: 50%;
                    background: rgba(255, 255, 255, 0.2);
                    transform: translate(-50%, -50%);
                    transition: width 0.6s, height 0.6s;
                }

                .btn-update-crush:hover::before, .btn-delete-crush:hover::before {
                    width: 300px;
                    height: 300px;
                }

                .btn-update-crush:hover {
                    transform: translateY(-3px);
                    box-shadow: 0 8px 25px rgba(52, 152, 219, 0.4);
                }

                .btn-delete-crush:hover {
                    transform: translateY(-3px);
                    box-shadow: 0 8px 25px rgba(231, 76, 60, 0.4);
                }

                .btn-update-crush:active, .btn-delete-crush:active {
                    transform: translateY(0);
                }

                .edit-icon, .trash-icon {
                    font-size: 1.3rem;
                    transition: transform 0.3s ease;
                }

                .btn-update-crush:hover .edit-icon {
                    transform: rotate(-15deg);
                }

                .btn-delete-crush:hover .trash-icon {
                    animation: shake 0.5s ease;
                }

                @keyframes shake {
                    0%, 100% { transform: translateX(0); }
                    25% { transform: translateX(-5px); }
                    75% { transform: translateX(5px); }
                }
            </style>
        </form>

        <form id="delete-form" method="POST" action="{{ route('crush.destroy') }}" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    </div>

    <div style="background: #fff; padding: 2rem; border-radius: 8px; border: 1px solid #ddd; margin-bottom: 2rem;">
        <h3 style="margin-bottom: 1rem;">Statistiques</h3>
        @php
            $stats = $crush->getVoteStats();
            $total = array_sum($stats);
        @endphp
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem;">
            <div style="text-align: center; padding: 1rem; background: #e8f5e9; border-radius: 4px;">
                <div style="font-size: 2rem; font-weight: bold; color: #2ecc71;">{{ $stats['oui'] }}</div>
                <div>Oui</div>
            </div>
            <div style="text-align: center; padding: 1rem; background: #ffebee; border-radius: 4px;">
                <div style="font-size: 2rem; font-weight: bold; color: #e74c3c;">{{ $stats['non'] }}</div>
                <div>Non</div>
            </div>
            <div style="text-align: center; padding: 1rem; background: #fff3e0; border-radius: 4px;">
                <div style="font-size: 2rem; font-weight: bold; color: #f39c12;">{{ $stats['non_tare'] }}</div>
                <div>Non, taré</div>
            </div>
            <div style="text-align: center; padding: 1rem; background: #f3e5f5; border-radius: 4px;">
                <div style="font-size: 2rem; font-weight: bold; color: #9b59b6;">{{ $stats['tare_mais_oui'] }}</div>
                <div>Taré mais oui</div>
            </div>
        </div>
        <p style="text-align: center; margin-top: 1rem; color: #666;">Total des votes: {{ $total }}</p>
    </div>

    <div style="background: #fff; padding: 2rem; border-radius: 8px; border: 1px solid #ddd;">
        <h3 style="margin-bottom: 1rem;">Commentaires ({{ $crush->comments->count() }})</h3>
        
        @forelse($crush->comments as $comment)
            <div style="border-bottom: 1px solid #eee; padding: 1rem 0;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <strong>{{ $comment->user->name }}</strong>
                    <small style="color: #666;">{{ $comment->created_at->diffForHumans() }}</small>
                </div>
                <p style="margin-bottom: 0.5rem;">{{ $comment->text }}</p>
                <div style="display: flex; gap: 1rem; font-size: 0.9rem; color: #666;">
                    <span>👍 {{ $comment->getLikesCount() }}</span>
                    <span>👎 {{ $comment->getDislikesCount() }}</span>
                </div>
            </div>
        @empty
            <p style="color: #666; text-align: center;">Aucun commentaire pour le moment</p>
        @endforelse
    </div>
@endif
@endsection
