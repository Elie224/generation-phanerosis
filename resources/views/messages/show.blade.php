@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-8">
    <!-- Header moderne amélioré -->
    <div class="flex items-center gap-4 mb-6 p-4 rounded-2xl bg-white shadow-lg border border-gray-200 relative">
        <!-- Bouton retour (mobile) -->
        <a href="{{ url()->previous() }}" class="absolute left-4 top-1/2 -translate-y-1/2 md:hidden flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 hover:bg-blue-100 text-blue-600 shadow transition-all" title="Retour">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        </a>
        <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-200 to-blue-400 flex items-center justify-center text-3xl font-extrabold text-blue-800 shadow-lg border-4 border-white">
            {{ strtoupper(substr($other->name, 0, 1)) }}
        </div>
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2">
                <span class="font-extrabold text-xl text-blue-900 truncate">{{ $other->name }}</span>
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500 border-2 border-white"></span>
                </span>
            </div>
            <div class="text-xs text-gray-500 mt-1">En ligne</div>
        </div>
    </div>
    <!-- Zone messages simplifiée et visible -->
    <div class="bg-white rounded-2xl shadow-lg max-h-[60vh] overflow-y-auto mb-4 px-4 py-6 flex flex-col gap-3 border border-gray-200" id="messages-area">
        @php
            $lastDate = null;
        @endphp
        @foreach($messages as $msg)
            @php
                $msgDate = $msg->created_at->format('Y-m-d');
                if ($lastDate !== $msgDate) {
                    echo '<div class="text-center my-3 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg px-4 py-2 mx-auto">'.
                        (\Carbon\Carbon::parse($msgDate)->isToday() ? 'Aujourd\'hui' : (\Carbon\Carbon::parse($msgDate)->isYesterday() ? 'Hier' : \Carbon\Carbon::parse($msgDate)->translatedFormat('d F Y')))
                        .'</div>';
                    $lastDate = $msgDate;
                }
            @endphp
            <div class="flex items-end gap-2 {{ $msg->from_id == auth()->id() ? 'justify-end' : 'justify-start' }}">
                @if($msg->from_id != auth()->id())
                    <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-xs font-bold text-white shadow">
                        {{ strtoupper(substr($other->name, 0, 1)) }}
                    </div>
                @endif
                
                <!-- Message bubble simple et visible -->
                <div class="relative max-w-[70%]">
                    <div class="px-4 py-3 rounded-2xl shadow-md text-sm message-bubble
                        {{ $msg->from_id == auth()->id() 
                            ? 'bg-blue-600 text-white' 
                            : 'bg-gray-100 text-gray-900 border border-gray-200' }}">
                        
                        <!-- Contenu du message -->
                    @if($msg->type === 'text')
                            <div class="whitespace-pre-wrap break-words">{!! nl2br(e($msg->content)) !!}</div>
                            
                            <!-- Liens avec aperçu simple -->
                            @php
                                preg_match_all('/https?:\/\/[\w\-\.\/?#=&;%:~+@!\[\]\(\),]+/i', $msg->content, $matches);
                            @endphp
                            @foreach($matches[0] ?? [] as $url)
                                @php
                                    $og = null;
                                    try {
                                        $html = @file_get_contents($url);
                                        if ($html) {
                                            preg_match('/<meta property="og:title" content="([^"]*)"/i', $html, $ogTitle);
                                            preg_match('/<meta property="og:description" content="([^"]*)"/i', $html, $ogDesc);
                                            preg_match('/<meta property="og:image" content="([^"]*)"/i', $html, $ogImg);
                                            $og = [
                                                'title' => $ogTitle[1] ?? null,
                                                'desc' => $ogDesc[1] ?? null,
                                                'img' => $ogImg[1] ?? null,
                                            ];
                                        }
                                    } catch (Exception $e) {}
                                @endphp
                                <a href="{{ $url }}" target="_blank" class="block mt-2 rounded-lg border border-blue-300 bg-white p-3 shadow hover:bg-blue-50 transition-all">
                                    @if($og && $og['img'])
                                        <img src="{{ $og['img'] }}" alt="aperçu" class="w-full max-h-32 object-cover rounded mb-2">
                                    @endif
                                    <div class="font-semibold text-blue-700 text-sm truncate">{{ $og['title'] ?? $url }}</div>
                                    @if($og && $og['desc'])
                                        <div class="text-xs text-gray-600 mt-1">{{ $og['desc'] }}</div>
                                    @endif
                                </a>
                            @endforeach
                            
                    @elseif($msg->type === 'image' && $msg->attachment_path)
                            <img src="{{ asset('storage/'.$msg->attachment_path) }}" alt="Image" class="max-w-full max-h-48 rounded">
                            
                    @elseif($msg->type === 'video' && $msg->attachment_path)
                            <video controls class="max-w-full max-h-48 rounded">
                            <source src="{{ asset('storage/'.$msg->attachment_path) }}">
                            Vidéo non supportée.
                        </video>
                            
                    @elseif($msg->type === 'audio' && $msg->audio_path)
                            <div class="bg-white/20 rounded-lg p-3">
                                <audio controls class="w-full">
                            <source src="{{ asset('storage/'.$msg->audio_path) }}">
                            Audio non supporté.
                        </audio>
                            </div>
                            
                    @elseif($msg->type === 'file' && $msg->attachment_path)
                            <a href="{{ asset('storage/'.$msg->attachment_path) }}" target="_blank" class="flex items-center gap-3 bg-white/20 rounded-lg p-3 hover:bg-white/30 transition-all">
                                <div class="w-8 h-8 rounded-full bg-white/30 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-semibold text-white">Fichier joint</div>
                                    <div class="text-xs text-white/80">Cliquez pour télécharger</div>
                                </div>
                            </a>
                    @endif
                        
                        <!-- Timestamp et actions -->
                        <div class="flex items-center justify-between mt-2 pt-2 border-t {{ $msg->from_id == auth()->id() ? 'border-white/20' : 'border-gray-200' }}">
                            <span class="text-xs {{ $msg->from_id == auth()->id() ? 'text-white/80' : 'text-gray-500' }} font-medium">
                                {{ $msg->created_at->format('H:i') }}
                            </span>
                            
                    @if($msg->from_id == auth()->id())
                                <!-- Bouton de menu contextuel -->
                                <button type="button" class="message-menu-btn text-white/60 hover:text-white/80 transition-colors p-1 rounded-full hover:bg-white/10" data-message-id="{{ $msg->id }}" title="Options du message">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                    </svg>
                                </button>
                                
                                <!-- Menu contextuel -->
                                <div class="message-menu hidden absolute top-0 right-0 mt-2 mr-2 bg-white rounded-lg shadow-lg border border-gray-200 z-50 min-w-[200px]">
                                    <div class="py-1">
                            <form action="{{ route('messages.deleteForMe', $msg->id) }}" method="POST" onsubmit="return confirm('Supprimer ce message pour vous ?');" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                                Supprimer pour moi
                                            </button>
                            </form>
                            <form action="{{ route('messages.destroy', $msg->id) }}" method="POST" onsubmit="return confirm('Supprimer ce message pour tout le monde ?');" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                                Supprimer pour tout le monde
                                            </button>
                            </form>
                                    </div>
                        </div>
                    @endif
                </div>
                    </div>
                </div>
                
                @if($msg->from_id == auth()->id())
                    <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-xs font-bold text-white shadow">
                        Moi
                    </div>
                @endif
            </div>
        @endforeach
    </div>
    @if($errors->any())
        <div class="mb-4 p-2 bg-red-100 text-red-700 rounded">
            <ul class="text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <!-- Zone de saisie moderne avec fonctionnalités avancées -->
    <form action="{{ route('messages.store', $other->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-lg px-4 py-3 mt-2 sticky bottom-0 border border-gray-200" id="message-form">
        @csrf
        <!-- Zone de prévisualisation des fichiers -->
        <div id="preview-area" class="mb-3 hidden">
            <div class="flex flex-wrap gap-2" id="file-previews"></div>
        </div>
        
                    <!-- Zone de saisie principale avec style unifié -->
            <div class="flex items-end relative">
                <!-- Zone de texte avec tous les boutons à l'intérieur -->
                <div class="flex-1 relative">
                    <div class="relative flex items-center bg-white focus-within:ring-2 focus-within:ring-blue-400 focus-within:shadow-lg transition-all duration-300 border-2 border-gray-300 hover:border-blue-400 shadow-md message-input-container" style="border-radius: 9999px !important; -webkit-border-radius: 9999px !important; -moz-border-radius: 9999px !important;">
                                                <!-- Bouton fichier à gauche avec menu contextuel -->
                        <div class="relative">
                            <button type="button" class="file-menu-btn cursor-pointer p-3 flex items-center transition-all duration-200 hover:bg-gray-100 rounded-full" title="Joindre un fichier">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.586-6.586a2 2 0 10-2.828-2.828z" />
                                </svg>
                            </button>
                            
                            <!-- Menu contextuel pour les fichiers -->
                            <div class="file-menu hidden absolute bottom-full left-0 mb-2 bg-white rounded-lg shadow-lg border border-gray-200 z-50 min-w-[200px]">
                                <div class="py-1">
                                    <!-- Photos et vidéos -->
                                    <label class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors flex items-center gap-3 cursor-pointer">
                                        <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                        </svg>
                                        Photos et vidéos
                                        <input type="file" name="attachment" class="hidden" accept="image/*,video/*" multiple>
        </label>
                                    
                                    <!-- Documents -->
                                    <label class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors flex items-center gap-3 cursor-pointer">
                                        <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                        </svg>
                                        Documents
                                        <input type="file" name="attachment" class="hidden" accept="application/pdf,.doc,.docx,.xls,.xlsx,.txt" multiple>
                                    </label>
                                    
                                    <!-- Caméra -->
                                    <button type="button" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors flex items-center gap-3" id="camera-btn">
                                        <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586l-1.707-1.707A1 1 0 0012.586 3H7.414a1 1 0 00-.707.293L5.586 5H4zm6 5a2 2 0 100 4 2 2 0 000-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Caméra
        </button>
                                    
                                    <!-- Enregistrement audio -->
                                    <button type="button" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors flex items-center gap-3" id="audio-record-btn">
                                        <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M7 4a3 3 0 016 0v4a3 3 0 11-6 0V4zm4 10.93A7.001 7.001 0 0017 8a1 1 0 10-2 0A5 5 0 015 8a1 1 0 00-2 0 7.001 7.001 0 006 6.93V17H6a1 1 0 100 2h8a1 1 0 100-2h-3v-2.07z" clip-rule="evenodd"/>
                                        </svg>
                                        Enregistrement audio
                                    </button>
                                    
                                    <!-- Localisation -->
                                    <button type="button" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors flex items-center gap-3" id="location-btn">
                                        <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                        </svg>
                                        Localisation
                                    </button>
                                    
                                    <!-- Contact -->
                                    <button type="button" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors flex items-center gap-3" id="contact-btn">
                                        <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                                        </svg>
                                        Contact
                                    </button>
                                    
                                    <!-- Galerie -->
                                    <button type="button" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors flex items-center gap-3" id="gallery-btn">
                                        <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        Galerie
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Bouton emoji à gauche du texte -->
                        <button type="button" id="emoji-btn" class="p-3 transition-all duration-200" title="Insérer un emoji">
                            <span class="text-xl text-gray-600">😊</span>
                        </button>
                        
                        <!-- Zone de texte au centre -->
                        <textarea name="content" id="message-input" rows="1" class="flex-1 border-none bg-transparent px-4 py-3 resize-none focus:outline-none text-gray-900 placeholder-gray-500 font-medium rounded-3xl" placeholder="Entrez un message" style="min-height: 48px; line-height: 1.4;"></textarea>
                        
                        <!-- Bouton vocal à droite -->
                        <button type="button" id="record-btn" class="p-3 transition-all duration-200" title="Enregistrer un message vocal">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <!-- Picker d'emojis style Messenger -->
                <div id="emoji-picker" class="hidden absolute bottom-full right-0 mb-2 bg-white border border-gray-200 rounded-xl shadow-xl z-50 min-w-[320px] max-w-[360px]">
                    <!-- Header avec catégories -->
                    <div class="flex border-b border-gray-100 p-2">
                        <button type="button" class="emoji-category active px-3 py-1 rounded-lg text-sm font-medium text-blue-600 bg-blue-50" data-category="recent">Récents</button>
                    </div>
                    <!-- Grille d'emojis : 9 par ligne, plusieurs lignes -->
                    <div class="p-3 max-h-64 overflow-y-auto">
                        <div class="flex flex-wrap gap-2 justify-start" id="emoji-grid">
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😀">😀</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😁">😁</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😂">😂</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🤣">🤣</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😃">😃</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😄">😄</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😅">😅</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😆">😆</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😉">😉</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😊">😊</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😋">😋</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😎">😎</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😍">😍</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😘">😘</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🥰">🥰</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😗">😗</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😙">😙</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😚">😚</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🙂">🙂</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🤗">🤗</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🤩">🤩</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🤔">🤔</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🤨">🤨</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😐">😐</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😑">😑</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😶">😶</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😯">😯</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😦">😦</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😧">😧</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😮">😮</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😲">😲</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😴">😴</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🤤">🤤</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😪">😪</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😵">😵</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🤐">🤐</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🥴">🥴</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😷">😷</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🤒">🤒</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🤕">🤕</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🤢">🤢</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🤮">🤮</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🤧">🤧</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😈">😈</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="👿">👿</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="👹">👹</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="👺">👺</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="💀">💀</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="☠️">☠️</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="👻">👻</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="👽">👽</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="👾">👾</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🤖">🤖</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😺">😺</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😸">😸</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😹">😹</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😻">😻</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😼">😼</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😽">😽</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🙀">🙀</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😿">😿</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="😾">😾</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🙈">🙈</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🙉">🙉</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🙊">🙊</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="💌">💌</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="💘">💘</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="💝">💝</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="💖">💖</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="💗">💗</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="💙">💙</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="💚">💚</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="💛">💛</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🧡">🧡</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="💜">💜</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🖤">🖤</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="💔">💔</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="❣️">❣️</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="💕">💕</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="💞">💞</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="💓">💓</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="💗">💗</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="💟">💟</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="💠">💠</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="💢">💢</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="💣">💣</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="💤">💤</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="💥">💥</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="💦">💦</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="💨">💨</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="💩">💩</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="💪">💪</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="👈">👈</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="👉">👉</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="👆">👆</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🖕">🖕</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="👇">👇</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="☝️">☝️</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="👋">👋</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🤚">🤚</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🖐️">🖐️</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="✋">✋</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🖖">🖖</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="👌">👌</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🤌">🤌</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🤏">🤏</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="✌️">✌️</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🤞">🤞</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🤟">🤟</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🤘">🤘</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🤙">🤙</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="👈">👈</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="👉">👉</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="👆">👆</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🖕">🖕</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="👇">👇</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="☝️">☝️</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="👍">👍</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="👎">👎</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="👊">👊</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="✊">✊</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🤛">🤛</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🤜">🤜</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="👏">👏</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🙌">🙌</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="👐">👐</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🤲">🤲</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🤝">🤝</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🙏">🙏</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="✍️">✍️</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="💪">💪</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🦾">🦾</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🦿">🦿</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🦵">🦵</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🦶">🦶</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="👂">👂</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🦻">🦻</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="👃">👃</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🧠">🧠</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🫀">🫀</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🫁">🫁</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🦷">🦷</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🦴">🦴</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="👀">👀</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="👁️">👁️</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="👅">👅</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="👄">👄</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="💋">💋</button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🩸">🩸</button>
                            <!-- Drapeaux des pays européens et autres -->
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇫🇷">
                                <img src="https://flagcdn.com/w40/fr.png" alt="France" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇺🇸">
                                <img src="https://flagcdn.com/w40/us.png" alt="États-Unis" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇬🇧">
                                <img src="https://flagcdn.com/w40/gb.png" alt="Royaume-Uni" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇩🇪">
                                <img src="https://flagcdn.com/w40/de.png" alt="Allemagne" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇮🇹">
                                <img src="https://flagcdn.com/w40/it.png" alt="Italie" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇪🇸">
                                <img src="https://flagcdn.com/w40/es.png" alt="Espagne" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇨🇦">
                                <img src="https://flagcdn.com/w40/ca.png" alt="Canada" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇦🇺">
                                <img src="https://flagcdn.com/w40/au.png" alt="Australie" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇯🇵">
                                <img src="https://flagcdn.com/w40/jp.png" alt="Japon" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇰🇷">
                                <img src="https://flagcdn.com/w40/kr.png" alt="Corée du Sud" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇨🇳">
                                <img src="https://flagcdn.com/w40/cn.png" alt="Chine" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇧🇷">
                                <img src="https://flagcdn.com/w40/br.png" alt="Brésil" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇷🇺">
                                <img src="https://flagcdn.com/w40/ru.png" alt="Russie" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇮🇳">
                                <img src="https://flagcdn.com/w40/in.png" alt="Inde" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇲🇽">
                                <img src="https://flagcdn.com/w40/mx.png" alt="Mexique" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇦🇷">
                                <img src="https://flagcdn.com/w40/ar.png" alt="Argentine" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇨🇭">
                                <img src="https://flagcdn.com/w40/ch.png" alt="Suisse" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇸🇪">
                                <img src="https://flagcdn.com/w40/se.png" alt="Suède" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇳🇴">
                                <img src="https://flagcdn.com/w40/no.png" alt="Norvège" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇩🇰">
                                <img src="https://flagcdn.com/w40/dk.png" alt="Danemark" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇫🇮">
                                <img src="https://flagcdn.com/w40/fi.png" alt="Finlande" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇳🇱">
                                <img src="https://flagcdn.com/w40/nl.png" alt="Pays-Bas" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇧🇪">
                                <img src="https://flagcdn.com/w40/be.png" alt="Belgique" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇦🇹">
                                <img src="https://flagcdn.com/w40/at.png" alt="Autriche" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇵🇱">
                                <img src="https://flagcdn.com/w40/pl.png" alt="Pologne" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇨🇿">
                                <img src="https://flagcdn.com/w40/cz.png" alt="République tchèque" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇭🇺">
                                <img src="https://flagcdn.com/w40/hu.png" alt="Hongrie" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇷🇴">
                                <img src="https://flagcdn.com/w40/ro.png" alt="Roumanie" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇧🇬">
                                <img src="https://flagcdn.com/w40/bg.png" alt="Bulgarie" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇭🇷">
                                <img src="https://flagcdn.com/w40/hr.png" alt="Croatie" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇸🇰">
                                <img src="https://flagcdn.com/w40/sk.png" alt="Slovaquie" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇸🇮">
                                <img src="https://flagcdn.com/w40/si.png" alt="Slovénie" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇪🇪">
                                <img src="https://flagcdn.com/w40/ee.png" alt="Estonie" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇱🇻">
                                <img src="https://flagcdn.com/w40/lv.png" alt="Lettonie" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇱🇹">
                                <img src="https://flagcdn.com/w40/lt.png" alt="Lituanie" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇮🇪">
                                <img src="https://flagcdn.com/w40/ie.png" alt="Irlande" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇵🇹">
                                <img src="https://flagcdn.com/w40/pt.png" alt="Portugal" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇬🇷">
                                <img src="https://flagcdn.com/w40/gr.png" alt="Grèce" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇹🇷">
                                <img src="https://flagcdn.com/w40/tr.png" alt="Turquie" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇮🇱">
                                <img src="https://flagcdn.com/w40/il.png" alt="Israël" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇸🇦">
                                <img src="https://flagcdn.com/w40/sa.png" alt="Arabie Saoudite" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇦🇪">
                                <img src="https://flagcdn.com/w40/ae.png" alt="Émirats arabes unis" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇶🇦">
                                <img src="https://flagcdn.com/w40/qa.png" alt="Qatar" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇰🇼">
                                <img src="https://flagcdn.com/w40/kw.png" alt="Koweït" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇧🇭">
                                <img src="https://flagcdn.com/w40/bh.png" alt="Bahreïn" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇴🇲">
                                <img src="https://flagcdn.com/w40/om.png" alt="Oman" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇾🇪">
                                <img src="https://flagcdn.com/w40/ye.png" alt="Yémen" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇯🇴">
                                <img src="https://flagcdn.com/w40/jo.png" alt="Jordanie" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇱🇧">
                                <img src="https://flagcdn.com/w40/lb.png" alt="Liban" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇸🇾">
                                <img src="https://flagcdn.com/w40/sy.png" alt="Syrie" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇮🇶">
                                <img src="https://flagcdn.com/w40/iq.png" alt="Irak" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇮🇷">
                                <img src="https://flagcdn.com/w40/ir.png" alt="Iran" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇦🇫">
                                <img src="https://flagcdn.com/w40/af.png" alt="Afghanistan" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇵🇰">
                                <img src="https://flagcdn.com/w40/pk.png" alt="Pakistan" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇧🇩">
                                <img src="https://flagcdn.com/w40/bd.png" alt="Bangladesh" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇱🇰">
                                <img src="https://flagcdn.com/w40/lk.png" alt="Sri Lanka" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇲🇲">
                                <img src="https://flagcdn.com/w40/mm.png" alt="Myanmar" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇹🇭">
                                <img src="https://flagcdn.com/w40/th.png" alt="Thaïlande" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇻🇳">
                                <img src="https://flagcdn.com/w40/vn.png" alt="Vietnam" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇰🇭">
                                <img src="https://flagcdn.com/w40/kh.png" alt="Cambodge" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇱🇦">
                                <img src="https://flagcdn.com/w40/la.png" alt="Laos" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇲🇾">
                                <img src="https://flagcdn.com/w40/my.png" alt="Malaisie" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇸🇬">
                                <img src="https://flagcdn.com/w40/sg.png" alt="Singapour" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇵🇭">
                                <img src="https://flagcdn.com/w40/ph.png" alt="Philippines" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇮🇩">
                                <img src="https://flagcdn.com/w40/id.png" alt="Indonésie" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇲🇳">
                                <img src="https://flagcdn.com/w40/mn.png" alt="Mongolie" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇰🇿">
                                <img src="https://flagcdn.com/w40/kz.png" alt="Kazakhstan" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇺🇿">
                                <img src="https://flagcdn.com/w40/uz.png" alt="Ouzbékistan" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇰🇬">
                                <img src="https://flagcdn.com/w40/kg.png" alt="Kirghizistan" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇹🇯">
                                <img src="https://flagcdn.com/w40/tj.png" alt="Tadjikistan" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇹🇲">
                                <img src="https://flagcdn.com/w40/tm.png" alt="Turkménistan" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇦🇿">
                                <img src="https://flagcdn.com/w40/az.png" alt="Azerbaïdjan" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇬🇪">
                                <img src="https://flagcdn.com/w40/ge.png" alt="Géorgie" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇦🇲">
                                <img src="https://flagcdn.com/w40/am.png" alt="Arménie" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇦🇱">
                                <img src="https://flagcdn.com/w40/al.png" alt="Albanie" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇲🇰">
                                <img src="https://flagcdn.com/w40/mk.png" alt="Macédoine du Nord" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇽🇰">
                                <img src="https://flagcdn.com/w40/xk.png" alt="Kosovo" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇲🇪">
                                <img src="https://flagcdn.com/w40/me.png" alt="Monténégro" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇧🇦">
                                <img src="https://flagcdn.com/w40/ba.png" alt="Bosnie-Herzégovine" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇷🇸">
                                <img src="https://flagcdn.com/w40/rs.png" alt="Serbie" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇺🇦">
                                <img src="https://flagcdn.com/w40/ua.png" alt="Ukraine" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇧🇾">
                                <img src="https://flagcdn.com/w40/by.png" alt="Biélorussie" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇲🇩">
                                <img src="https://flagcdn.com/w40/md.png" alt="Moldavie" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇮🇸">
                                <img src="https://flagcdn.com/w40/is.png" alt="Islande" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇫🇴">
                                <img src="https://flagcdn.com/w40/fo.png" alt="Îles Féroé" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇬🇱">
                                <img src="https://flagcdn.com/w40/gl.png" alt="Groenland" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇧🇿">
                                <img src="https://flagcdn.com/w40/bz.png" alt="Belize" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇬🇹">
                                <img src="https://flagcdn.com/w40/gt.png" alt="Guatemala" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇸🇻">
                                <img src="https://flagcdn.com/w40/sv.png" alt="El Salvador" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇭🇳">
                                <img src="https://flagcdn.com/w40/hn.png" alt="Honduras" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇳🇮">
                                <img src="https://flagcdn.com/w40/ni.png" alt="Nicaragua" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇨🇷">
                                <img src="https://flagcdn.com/w40/cr.png" alt="Costa Rica" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇵🇦">
                                <img src="https://flagcdn.com/w40/pa.png" alt="Panama" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇨🇴">
                                <img src="https://flagcdn.com/w40/co.png" alt="Colombie" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇪🇨">
                                <img src="https://flagcdn.com/w40/ec.png" alt="Équateur" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇵🇪">
                                <img src="https://flagcdn.com/w40/pe.png" alt="Pérou" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇧🇴">
                                <img src="https://flagcdn.com/w40/bo.png" alt="Bolivie" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇨🇱">
                                <img src="https://flagcdn.com/w40/cl.png" alt="Chili" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇵🇾">
                                <img src="https://flagcdn.com/w40/py.png" alt="Paraguay" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇺🇾">
                                <img src="https://flagcdn.com/w40/uy.png" alt="Uruguay" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇬🇾">
                                <img src="https://flagcdn.com/w40/gy.png" alt="Guyana" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇸🇷">
                                <img src="https://flagcdn.com/w40/sr.png" alt="Suriname" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇨🇾">
                                <img src="https://flagcdn.com/w40/cy.png" alt="Chypre" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇲🇹">
                                <img src="https://flagcdn.com/w40/mt.png" alt="Malte" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇻🇦">
                                <img src="https://flagcdn.com/w40/va.png" alt="Vatican" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇸🇲">
                                <img src="https://flagcdn.com/w40/sm.png" alt="Saint-Marin" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇲🇨">
                                <img src="https://flagcdn.com/w40/mc.png" alt="Monaco" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇦🇩">
                                <img src="https://flagcdn.com/w40/ad.png" alt="Andorre" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇱🇮">
                                <img src="https://flagcdn.com/w40/li.png" alt="Liechtenstein" class="w-6 h-4 object-cover rounded">
                            </button>
                            <button type="button" class="emoji-btn w-9 h-9 flex items-center justify-center text-xl hover:bg-gray-100 rounded-lg transition-colors" data-emoji="🇱🇺">
                                <img src="https://flagcdn.com/w40/lu.png" alt="Luxembourg" class="w-6 h-4 object-cover rounded">
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Bouton envoi (caché par défaut) -->
            <button type="submit" id="send-btn" class="hidden bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-full font-semibold shadow-lg transition-all duration-200 transform hover:scale-105 flex items-center justify-center ml-2 border-2 border-blue-500" title="Envoyer le message">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
        </button>
        </div>
        
        <!-- Inputs cachés -->
        <input type="file" name="audio" class="hidden" accept="audio/*" id="audio-input">
        
        <!-- Picker d'emojis -->
        <div id="emoji-picker" class="hidden absolute bottom-full mb-2 bg-white border border-gray-200 rounded-lg shadow-lg p-2 z-50">
            <div class="grid grid-cols-8 gap-1 max-h-48 overflow-y-auto">
                <!-- Emojis populaires -->
                <button type="button" class="emoji-btn p-2 hover:bg-gray-100 rounded text-xl" data-emoji="😊">😊</button>
                <button type="button" class="emoji-btn p-2 hover:bg-gray-100 rounded text-xl" data-emoji="😂">😂</button>
                <button type="button" class="emoji-btn p-2 hover:bg-gray-100 rounded text-xl" data-emoji="❤️">❤️</button>
                <button type="button" class="emoji-btn p-2 hover:bg-gray-100 rounded text-xl" data-emoji="👍">👍</button>
                <button type="button" class="emoji-btn p-2 hover:bg-gray-100 rounded text-xl" data-emoji="🙏">🙏</button>
                <button type="button" class="emoji-btn p-2 hover:bg-gray-100 rounded text-xl" data-emoji="🎉">🎉</button>
                <button type="button" class="emoji-btn p-2 hover:bg-gray-100 rounded text-xl" data-emoji="🔥">🔥</button>
                <button type="button" class="emoji-btn p-2 hover:bg-gray-100 rounded text-xl" data-emoji="✨">✨</button>
                <button type="button" class="emoji-btn p-2 hover:bg-gray-100 rounded text-xl" data-emoji="😍">😍</button>
                <button type="button" class="emoji-btn p-2 hover:bg-gray-100 rounded text-xl" data-emoji="🤔">🤔</button>
                <button type="button" class="emoji-btn p-2 hover:bg-gray-100 rounded text-xl" data-emoji="😭">😭</button>
                <button type="button" class="emoji-btn p-2 hover:bg-gray-100 rounded text-xl" data-emoji="😎">😎</button>
                <button type="button" class="emoji-btn p-2 hover:bg-gray-100 rounded text-xl" data-emoji="🤗">🤗</button>
                <button type="button" class="emoji-btn p-2 hover:bg-gray-100 rounded text-xl" data-emoji="😇">😇</button>
                <button type="button" class="emoji-btn p-2 hover:bg-gray-100 rounded text-xl" data-emoji="🥰">🥰</button>
                <button type="button" class="emoji-btn p-2 hover:bg-gray-100 rounded text-xl" data-emoji="😘">😘</button>
            </div>
        </div>
    </form>
    <style>
        .animate-fade-in { animation: fadeIn 0.5s; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px);} to { opacity: 1; transform: translateY(0);} }
        .animate-slide-in { animation: slideIn 0.5s; }
        @keyframes slideIn { from { opacity: 0; transform: translateY(20px);} to { opacity: 1; transform: translateY(0);} }
        
        /* Animations améliorées pour la zone de texte */
        .animate-pulse { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .5; } }
        
        .animate-bounce { animation: bounce 1s infinite; }
        @keyframes bounce { 0%, 20%, 53%, 80%, 100% { transform: translate3d(0,0,0); } 40%, 43% { transform: translate3d(0, -30px, 0); } 70% { transform: translate3d(0, -15px, 0); } 90% { transform: translate3d(0,-4px,0); } }
        
        /* Effet de focus amélioré */
        .focus-within\:shadow-lg:focus-within { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }
        
        /* Transition fluide pour les boutons */
        .transition-all { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        
        /* Effet de glassmorphism pour la zone de texte */
        .glass-effect { backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.9); }
        
        /* Forcer l'arrondi de la zone de texte */
        .message-input-container {
            border-radius: 9999px !important;
            -webkit-border-radius: 9999px !important;
            -moz-border-radius: 9999px !important;
        }
        
        /* Améliorations pour les messages */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Animation pour les nouveaux messages */
        @keyframes messageSlideIn {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .animate-slide-in {
            animation: messageSlideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Effet de survol pour les bulles de message */
        .message-bubble {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .message-bubble:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        /* Scrollbar personnalisée pour la zone de messages */
        #messages-area::-webkit-scrollbar {
            width: 6px;
        }
        
        #messages-area::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 10px;
        }
        
        #messages-area::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }
        
        #messages-area::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 0, 0, 0.3);
        }
        
        /* Styles pour les menus contextuels des messages */
        .message-menu {
            animation: menuSlideIn 0.2s ease-out;
        }
        
        @keyframes menuSlideIn {
            from {
                opacity: 0;
                transform: translateY(-10px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .message-menu-btn {
            opacity: 0;
            transition: opacity 0.2s ease;
        }
        
        .message-bubble:hover .message-menu-btn {
            opacity: 1;
        }
        
        /* Amélioration du positionnement du menu */
        .message-menu {
            transform-origin: top right;
        }
        
        /* Styles pour le menu des fichiers */
        .file-menu {
            animation: fileMenuSlideIn 0.2s ease-out;
            transform-origin: bottom left;
        }
        
        @keyframes fileMenuSlideIn {
            from {
                opacity: 0;
                transform: translateY(10px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        /* Effet de survol pour le bouton de menu des fichiers */
        .file-menu-btn:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }
        
        /* Styles pour les éléments du menu des fichiers */
        .file-menu label,
        .file-menu button {
            transition: all 0.2s ease;
        }
        
        .file-menu label:hover,
        .file-menu button:hover {
            background-color: #f3f4f6;
            transform: translateX(2px);
        }
        
        /* Styles pour l'interface de caméra */
        #camera-modal {
            backdrop-filter: blur(4px);
        }
        
        #camera-video {
            object-fit: cover;
        }
        
        #capture-photo {
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        #capture-photo:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
        }
        
        #capture-photo:active {
            transform: scale(0.95);
        }
        
        /* Animation pour l'aperçu de photo */
        #photo-preview {
            animation: slideUp 0.3s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Styles pour le sélecteur d'utilisateurs */
        #user-selector-modal {
            backdrop-filter: blur(4px);
        }
        
        .user-item {
            transition: all 0.2s ease;
        }
        
        .user-item:hover {
            transform: translateX(4px);
        }
        
        .send-photo-btn {
            opacity: 0.8;
            transition: all 0.2s ease;
        }
        
        .user-item:hover .send-photo-btn {
            opacity: 1;
            transform: scale(1.05);
        }
        
        /* Animation pour les options d'envoi */
        #photo-preview .space-y-2 > * {
            animation: slideInRight 0.3s ease-out;
            animation-fill-mode: both;
        }
        
        #photo-preview .space-y-2 > *:nth-child(1) { animation-delay: 0.1s; }
        #photo-preview .space-y-2 > *:nth-child(2) { animation-delay: 0.2s; }
        #photo-preview .space-y-2 > *:nth-child(3) { animation-delay: 0.3s; }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sélecteurs
            const attachmentInput = document.getElementById('attachment-input');
            const previewArea = document.getElementById('preview-area');
            const filePreviews = document.getElementById('file-previews');
            const emojiBtn = document.getElementById('emoji-btn');
            const emojiPicker = document.getElementById('emoji-picker');
            const messageInput = document.getElementById('message-input');
            const sendBtn = document.getElementById('send-btn');
            const recordBtn = document.getElementById('record-btn');
            const audioInput = document.getElementById('audio-input');
            const messageForm = document.getElementById('message-form');
            let isRecording = false;
            let mediaRecorder = null;
            let audioChunks = [];
            let cancelBtn = null;
            
            // Gestion des menus contextuels des messages
            let activeMenu = null;
            
            // Fermer tous les menus
            function closeAllMenus() {
                const menus = document.querySelectorAll('.message-menu');
                menus.forEach(menu => {
                    menu.classList.add('hidden');
                });
                activeMenu = null;
            }
            
            // Gestion des boutons de menu
            document.addEventListener('click', function(e) {
                if (e.target.closest('.message-menu-btn')) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const menuBtn = e.target.closest('.message-menu-btn');
                    const menu = menuBtn.nextElementSibling;
                    
                    // Fermer le menu actif s'il est différent
                    if (activeMenu && activeMenu !== menu) {
                        activeMenu.classList.add('hidden');
                    }
                    
                    // Basculer le menu actuel
                    menu.classList.toggle('hidden');
                    activeMenu = menu.classList.contains('hidden') ? null : menu;
                } else if (!e.target.closest('.message-menu')) {
                    // Fermer le menu si on clique ailleurs
                    closeAllMenus();
                }
            });
            
            // Fermer le menu avec Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeAllMenus();
                }
            });
            
            // Gestion du menu des fichiers
            let fileMenuActive = false;
            
            // Gestion du bouton de menu des fichiers
            document.addEventListener('click', function(e) {
                if (e.target.closest('.file-menu-btn')) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const fileMenu = document.querySelector('.file-menu');
                    fileMenu.classList.toggle('hidden');
                    fileMenuActive = !fileMenu.classList.contains('hidden');
                } else if (!e.target.closest('.file-menu')) {
                    // Fermer le menu si on clique ailleurs
                    const fileMenu = document.querySelector('.file-menu');
                    fileMenu.classList.add('hidden');
                    fileMenuActive = false;
                }
            });
            
            // Gestion des inputs de fichiers dans le menu
            document.addEventListener('change', function(e) {
                if (e.target.matches('.file-menu input[type="file"]')) {
                    // Fermer le menu après sélection
                    const fileMenu = document.querySelector('.file-menu');
                    fileMenu.classList.add('hidden');
                    fileMenuActive = false;
                    
                    // Mettre à jour l'aperçu
                    updatePreviewArea();
                }
            });
            
            // Gestion des boutons du menu des fichiers
            document.getElementById('camera-btn')?.addEventListener('click', function() {
                // Créer l'interface de caméra
                createCameraInterface();
                
                // Fermer le menu
                document.querySelector('.file-menu').classList.add('hidden');
                fileMenuActive = false;
            });
            
            // Fonction pour créer l'interface de caméra
            function createCameraInterface() {
                // Créer le modal de caméra
                const cameraModal = document.createElement('div');
                cameraModal.className = 'fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50';
                cameraModal.id = 'camera-modal';
                
                cameraModal.innerHTML = `
                    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Caméra</h3>
                            <button type="button" class="close-camera text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="p-4">
                            <div class="relative">
                                <video id="camera-video" class="w-full h-64 bg-gray-900 rounded-lg" autoplay playsinline></video>
                                <canvas id="camera-canvas" class="hidden"></canvas>
                                
                                <!-- Contrôles de la caméra -->
                                <div class="flex items-center justify-center gap-4 mt-4">
                                    <button type="button" id="switch-camera" class="p-3 bg-gray-100 hover:bg-gray-200 rounded-full transition-colors" title="Changer de caméra">
                                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                    </button>
                                    
                                    <button type="button" id="capture-photo" class="p-4 bg-blue-600 hover:bg-blue-700 rounded-full transition-colors">
                                        <div class="w-8 h-8 bg-white rounded-full"></div>
                                    </button>
                                    
                                    <button type="button" id="flash-toggle" class="p-3 bg-gray-100 hover:bg-gray-200 rounded-full transition-colors" title="Flash">
                                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </button>
                                </div>
                                
                                <!-- Aperçu de la photo prise -->
                                <div id="photo-preview" class="hidden mt-4">
                                    <img id="captured-image" class="w-full h-32 object-cover rounded-lg" alt="Photo prise">
                                    
                                    <!-- Options d'envoi -->
                                    <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                                        <h4 class="text-sm font-medium text-gray-700 mb-2">Envoyer la photo à :</h4>
                                        <div class="space-y-2">
                                                                <button type="button" id="send-to-current" class="w-full text-left px-3 py-2 bg-blue-100 hover:bg-blue-200 rounded-lg transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                        </svg>
                        <span class="text-sm text-blue-700">Utilisateur actuel</span>
                    </button>
                    
                    <button type="button" id="test-route" class="w-full text-left px-3 py-2 bg-red-100 hover:bg-red-200 rounded-lg transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM8 7a2 2 0 114 0 2 2 0 01-4 0zm2 8a2 2 0 100-4 2 2 0 000 4z"/>
                        </svg>
                        <span class="text-sm text-red-700">Test Route</span>
                    </button>
                                            
                                            <button type="button" id="send-to-other" class="w-full text-left px-3 py-2 bg-green-100 hover:bg-green-200 rounded-lg transition-colors flex items-center gap-2">
                                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/>
                                                </svg>
                                                <span class="text-sm text-green-700">Autre utilisateur</span>
                                            </button>
                                            
                                            <button type="button" id="save-to-gallery" class="w-full text-left px-3 py-2 bg-purple-100 hover:bg-purple-200 rounded-lg transition-colors flex items-center gap-2">
                                                <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                                <span class="text-sm text-purple-700">Sauvegarder dans la galerie</span>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="flex gap-2 mt-3">
                                        <button type="button" id="retake-photo" class="flex-1 px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                                            Reprendre
                                        </button>
                                        <button type="button" id="use-photo" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                            Utiliser ici
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                document.body.appendChild(cameraModal);
                
                // Variables pour la caméra
                let stream = null;
                let facingMode = 'environment'; // Caméra arrière par défaut
                let capturedImageData = null;
                
                // Démarrer la caméra
                startCamera();
                
                // Gestionnaires d'événements
                document.querySelector('.close-camera').addEventListener('click', closeCamera);
                document.getElementById('switch-camera').addEventListener('click', switchCamera);
                document.getElementById('capture-photo').addEventListener('click', capturePhoto);
                document.getElementById('retake-photo').addEventListener('click', retakePhoto);
                document.getElementById('use-photo').addEventListener('click', usePhoto);
                document.getElementById('send-to-current').addEventListener('click', sendToCurrentUser);
                document.getElementById('send-to-other').addEventListener('click', sendToOtherUser);
                document.getElementById('save-to-gallery').addEventListener('click', saveToGallery);
                
                // Event listener pour le test de route
                document.getElementById('test-route').addEventListener('click', function() {
                    const currentUserId = '{{ $other->id }}';
                    testRoute(currentUserId);
                });
                
                // Fonction pour démarrer la caméra
                async function startCamera() {
                    try {
                        const constraints = {
                            video: {
                                facingMode: facingMode,
                                width: { ideal: 1280 },
                                height: { ideal: 720 }
                            }
                        };
                        
                        stream = await navigator.mediaDevices.getUserMedia(constraints);
                        const video = document.getElementById('camera-video');
                        video.srcObject = stream;
                    } catch (error) {
                        console.error('Erreur d\'accès à la caméra:', error);
                        alert('Impossible d\'accéder à la caméra. Vérifiez les permissions.');
                        closeCamera();
                    }
                }
                
                // Fonction pour changer de caméra
                async function switchCamera() {
                    if (stream) {
                        stream.getTracks().forEach(track => track.stop());
                    }
                    
                    facingMode = facingMode === 'environment' ? 'user' : 'environment';
                    await startCamera();
                }
                
                // Fonction pour prendre une photo
                function capturePhoto() {
                    const video = document.getElementById('camera-video');
                    const canvas = document.getElementById('camera-canvas');
                    const preview = document.getElementById('photo-preview');
                    const capturedImage = document.getElementById('captured-image');
                    
                    // Configurer le canvas
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    
                    // Dessiner l'image de la vidéo sur le canvas
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                    
                    // Obtenir les données de l'image
                    capturedImageData = canvas.toDataURL('image/jpeg', 0.8);
                    
                    // Afficher l'aperçu
                    capturedImage.src = capturedImageData;
                    preview.classList.remove('hidden');
                    
                    // Masquer la vidéo
                    video.classList.add('hidden');
                }
                
                // Fonction pour reprendre une photo
                function retakePhoto() {
                    const video = document.getElementById('camera-video');
                    const preview = document.getElementById('photo-preview');
                    
                    // Masquer l'aperçu et remontrer la vidéo
                    preview.classList.add('hidden');
                    video.classList.remove('hidden');
                    
                    capturedImageData = null;
                }
                
                // Fonction pour utiliser la photo
                function usePhoto() {
                    if (capturedImageData) {
                        // Convertir l'image en fichier
                        fetch(capturedImageData)
                            .then(res => res.blob())
                            .then(blob => {
                                const file = new File([blob], `photo_${Date.now()}.jpg`, { type: 'image/jpeg' });
                                
                                // Ajouter le fichier à l'input principal
                                const mainInput = document.querySelector('input[name="attachment"]');
                                if (mainInput) {
                                    // Créer un nouveau DataTransfer avec les fichiers existants + le nouveau
                                    const dt = new DataTransfer();
                                    
                                    // Ajouter les fichiers existants
                                    if (mainInput.files.length > 0) {
                                        for (let i = 0; i < mainInput.files.length; i++) {
                                            dt.items.add(mainInput.files[i]);
                                        }
                                    }
                                    
                                    // Ajouter le nouveau fichier
                                    dt.items.add(file);
                                    mainInput.files = dt.files;
                                    
                                    // Mettre à jour l'aperçu
                                    updatePreviewArea();
                                    
                                    // Afficher une notification
                                    showNotification('Photo ajoutée avec succès !', 'success');
                                }
                            })
                            .catch(error => {
                                console.error('Erreur lors de l\'ajout de la photo:', error);
                                showNotification('Erreur lors de l\'ajout de la photo', 'error');
                            });
                    }
                    
                    closeCamera();
                }
                
                // Fonction pour envoyer à l'utilisateur actuel
                function sendToCurrentUser() {
                    if (capturedImageData) {
                        // Récupérer l'ID de l'utilisateur actuel de la conversation
                        const currentUserId = '{{ $other->id }}';
                        console.log('ID utilisateur actuel:', currentUserId);
                        console.log('Image data:', capturedImageData);
                        sendPhotoToUser(capturedImageData, currentUserId);
                    } else {
                        console.error('Aucune image capturée');
                        showNotification('Aucune image à envoyer', 'error');
                    }
                }
                
                // Fonction pour envoyer à un autre utilisateur
                function sendToOtherUser() {
                    if (capturedImageData) {
                        // Ouvrir le sélecteur d'utilisateurs
                        showUserSelector(capturedImageData);
                    }
                }
                
                // Fonction pour sauvegarder dans la galerie
                function saveToGallery() {
                    if (capturedImageData) {
                        // Créer un lien de téléchargement
                        const link = document.createElement('a');
                        link.href = capturedImageData;
                        link.download = `photo_${Date.now()}.jpg`;
                        link.click();
                        
                        showNotification('Photo sauvegardée dans la galerie !', 'success');
                        closeCamera();
                    }
                }
                
                // Fonction de test pour vérifier la route
                function testRoute(userId) {
                    console.log('=== TEST ROUTE ===');
                    console.log('Utilisateur ID:', userId);
                    
                    // Utiliser le formulaire existant pour le test
                    const messageInput = document.getElementById('message-input');
                    const sendBtn = document.getElementById('send-btn');
                    
                    // Ajouter un message de test
                    messageInput.value = 'Test message - ' + new Date().toLocaleTimeString();
                    
                    // Déclencher l'envoi
                    if (sendBtn && !sendBtn.hidden) {
                        sendBtn.click();
                        showNotification('Test envoyé !', 'success');
                    } else {
                        showNotification('Bouton d\'envoi non disponible', 'error');
                    }
                }
                
                // Fonction pour envoyer une photo à un utilisateur
                function sendPhotoToUser(imageData, userId) {
                    console.log('=== DÉBUT ENVOI PHOTO ===');
                    console.log('Utilisateur ID:', userId);
                    
                    if (!imageData) {
                        showNotification('Aucune image à envoyer', 'error');
                        return;
                    }
                    
                    // Convertir l'image en fichier
                    fetch(imageData)
                        .then(res => res.blob())
                        .then(blob => {
                            console.log('Blob créé, taille:', blob.size);
                            
                            // Créer un fichier
                            const file = new File([blob], `photo_${Date.now()}.jpg`, { type: 'image/jpeg' });
                            
                            // Utiliser le formulaire existant au lieu de créer une nouvelle requête
                            const attachmentInput = document.getElementById('attachment-input');
                            const messageInput = document.getElementById('message-input');
                            
                            // Créer un DataTransfer pour ajouter le fichier
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(file);
                            attachmentInput.files = dataTransfer.files;
                            
                            // Ajouter du contenu au message
                            messageInput.value = 'Photo prise avec la caméra';
                            
                            // Déclencher l'envoi du formulaire
                            const sendBtn = document.getElementById('send-btn');
                            if (sendBtn && !sendBtn.hidden) {
                                sendBtn.click();
                            } else {
                                // Si le bouton est caché, simuler l'envoi avec Enter
                                const enterEvent = new KeyboardEvent('keydown', {
                                    key: 'Enter',
                                    code: 'Enter',
                                    keyCode: 13,
                                    which: 13,
                                    bubbles: true
                                });
                                messageInput.dispatchEvent(enterEvent);
                            }
                            
                            showNotification('Photo ajoutée au message !', 'success');
                        })
                        .catch(error => {
                            console.error('Erreur:', error);
                            showNotification('Erreur lors de la préparation de la photo', 'error');
                        });
                    
                    closeCamera();
                }
                
                // Fonction pour afficher le sélecteur d'utilisateurs
                function showUserSelector(imageData) {
                    // Créer le modal de sélection d'utilisateur
                    const userModal = document.createElement('div');
                    userModal.className = 'fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50';
                    userModal.id = 'user-selector-modal';
                    
                    userModal.innerHTML = `
                        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                            <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">Sélectionner un utilisateur</h3>
                                <button type="button" class="close-user-selector text-gray-400 hover:text-gray-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="p-4">
                                <div class="mb-4">
                                    <input type="text" id="user-search" placeholder="Rechercher un utilisateur..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                
                                <div id="users-list" class="max-h-64 overflow-y-auto space-y-2">
                                    <!-- Liste des utilisateurs sera chargée ici -->
                                </div>
                            </div>
                        </div>
                    `;
                    
                    document.body.appendChild(userModal);
                    
                    // Charger la liste des utilisateurs
                    loadUsersList();
                    
                    // Gestionnaires d'événements
                    document.querySelector('.close-user-selector').addEventListener('click', () => {
                        document.body.removeChild(userModal);
                    });
                    
                    // Recherche d'utilisateurs
                    document.getElementById('user-search').addEventListener('input', function() {
                        const searchTerm = this.value.toLowerCase();
                        const userItems = document.querySelectorAll('.user-item');
                        
                        userItems.forEach(item => {
                            const userName = item.querySelector('.user-name').textContent.toLowerCase();
                            if (userName.includes(searchTerm)) {
                                item.style.display = 'block';
                            } else {
                                item.style.display = 'none';
                            }
                        });
                    });
                }
                
                // Fonction pour charger la liste des utilisateurs
                function loadUsersList() {
                    fetch('/api/users')
                        .then(response => response.json())
                        .then(users => {
                            const usersList = document.getElementById('users-list');
                            usersList.innerHTML = '';
                            
                            users.forEach(user => {
                                const userItem = document.createElement('div');
                                userItem.className = 'user-item flex items-center gap-3 p-3 hover:bg-gray-50 rounded-lg cursor-pointer transition-colors';
                                userItem.innerHTML = `
                                    <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                                        ${user.name.charAt(0).toUpperCase()}
                                    </div>
                                    <div class="flex-1">
                                        <div class="user-name font-medium text-gray-900">${user.name}</div>
                                        <div class="text-sm text-gray-500">${user.email}</div>
                                    </div>
                                    <button type="button" class="send-photo-btn px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors" data-user-id="${user.id}">
                                        Envoyer
                                    </button>
                                `;
                                
                                usersList.appendChild(userItem);
                                
                                // Gestionnaire pour l'envoi
                                userItem.querySelector('.send-photo-btn').addEventListener('click', function() {
                                    const userId = this.getAttribute('data-user-id');
                                    sendPhotoToUser(capturedImageData, userId);
                                    document.body.removeChild(document.getElementById('user-selector-modal'));
                                });
                            });
                        })
                        .catch(error => {
                            console.error('Erreur lors du chargement des utilisateurs:', error);
                            document.getElementById('users-list').innerHTML = '<p class="text-gray-500 text-center py-4">Erreur lors du chargement des utilisateurs</p>';
                        });
                }
                
                // Fonction pour fermer la caméra
                function closeCamera() {
                    if (stream) {
                        stream.getTracks().forEach(track => track.stop());
                    }
                    document.body.removeChild(cameraModal);
                }
            }
            
            document.getElementById('audio-record-btn')?.addEventListener('click', function() {
                // Activer l'enregistrement audio
                if (!isRecording) {
                    startRecording();
                } else {
                    stopRecording();
                }
                
                // Fermer le menu
                document.querySelector('.file-menu').classList.add('hidden');
                fileMenuActive = false;
            });
            
            document.getElementById('location-btn')?.addEventListener('click', function() {
                // Demander la localisation
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        const location = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };
                        console.log('Localisation:', location);
                        // Ici vous pouvez ajouter la logique pour envoyer la localisation
                    }, function(error) {
                        console.error('Erreur de géolocalisation:', error);
                        alert('Impossible d\'obtenir votre localisation');
                    });
                } else {
                    alert('La géolocalisation n\'est pas supportée par votre navigateur');
                }
                
                // Fermer le menu
                document.querySelector('.file-menu').classList.add('hidden');
                fileMenuActive = false;
            });
            
            document.getElementById('contact-btn')?.addEventListener('click', function() {
                // Ouvrir le sélecteur de contacts
                if (navigator.contacts) {
                    navigator.contacts.select(['name', 'tel', 'email'], {multiple: true})
                        .then(function(contacts) {
                            console.log('Contacts sélectionnés:', contacts);
                            // Ici vous pouvez ajouter la logique pour partager les contacts
                        })
                        .catch(function(error) {
                            console.error('Erreur de sélection de contacts:', error);
                            alert('Impossible d\'accéder aux contacts');
                        });
                } else {
                    alert('L\'accès aux contacts n\'est pas supporté par votre navigateur');
                }
                
                // Fermer le menu
                document.querySelector('.file-menu').classList.add('hidden');
                fileMenuActive = false;
            });
            
            document.getElementById('gallery-btn')?.addEventListener('click', function() {
                // Ouvrir la galerie
                const galleryInput = document.createElement('input');
                galleryInput.type = 'file';
                galleryInput.accept = 'image/*,video/*';
                galleryInput.multiple = true;
                galleryInput.click();
                
                galleryInput.addEventListener('change', function() {
                    if (this.files.length > 0) {
                        // Traiter les fichiers de la galerie
                        console.log('Fichiers sélectionnés de la galerie:', this.files.length);
                        // Ici vous pouvez ajouter la logique pour traiter les fichiers
                    }
                });
                
                // Fermer le menu
                document.querySelector('.file-menu').classList.add('hidden');
                fileMenuActive = false;
            });

            // Aperçu fichiers multiples
            function updatePreviewArea() {
                if (!attachmentInput.files.length) {
                    previewArea.classList.add('hidden');
                    filePreviews.innerHTML = '';
                    return;
                }
                previewArea.classList.remove('hidden');
                filePreviews.innerHTML = '';
                Array.from(attachmentInput.files).forEach(file => {
                    const preview = document.createElement('div');
                    preview.className = 'flex items-center gap-2 bg-gray-50 px-2 py-1 rounded-md text-sm';
                    if (file.type.startsWith('image/')) {
                        const img = document.createElement('img');
                        img.src = URL.createObjectURL(file);
                        img.className = 'max-w-[50px] max-h-[50px] rounded';
                        preview.appendChild(img);
                    } else if (file.type.startsWith('video/')) {
                        const video = document.createElement('video');
                        video.src = URL.createObjectURL(file);
                        video.controls = true;
                        video.className = 'max-w-[70px] max-h-[50px] rounded';
                        preview.appendChild(video);
                    } else {
                        const icon = document.createElement('span');
                        icon.innerHTML = '📄';
                        preview.appendChild(icon);
                    }
                    const name = document.createElement('span');
                    name.textContent = file.name;
                    preview.appendChild(name);
                    filePreviews.appendChild(preview);
                });
            }
            if (attachmentInput) {
                attachmentInput.addEventListener('change', updatePreviewArea);
            }

            // Picker d'emojis style Messenger
            function toggleEmojiPicker() {
                emojiPicker.classList.toggle('hidden');
            }
            if (emojiBtn && emojiPicker) {
                emojiBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleEmojiPicker();
                });
                document.addEventListener('click', function(e) {
                    if (!emojiPicker.contains(e.target) && !emojiBtn.contains(e.target)) {
                        emojiPicker.classList.add('hidden');
                    }
                });
                
                // Gestion des catégories d'emojis
                const categoryBtns = emojiPicker.querySelectorAll('.emoji-category');
                categoryBtns.forEach(btn => {
                    btn.addEventListener('click', function() {
                        // Retirer la classe active de tous les boutons
                        categoryBtns.forEach(b => {
                            b.classList.remove('active', 'text-blue-600', 'bg-blue-50');
                            b.classList.add('text-gray-600');
                        });
                        // Ajouter la classe active au bouton cliqué
                        this.classList.add('active', 'text-blue-600', 'bg-blue-50');
                        this.classList.remove('text-gray-600');
                        
                        // Ici on pourrait changer les emojis selon la catégorie
                        // Pour l'instant, on garde tous les emojis visibles
                    });
                });
                
                            // Gestion des emojis améliorée
            emojiPicker.querySelectorAll('.emoji-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const emoji = this.dataset.emoji;
                    const start = messageInput.selectionStart;
                    const end = messageInput.selectionEnd;
                    const text = messageInput.value;
                    
                    messageInput.value = text.substring(0, start) + emoji + text.substring(end);
                    messageInput.selectionStart = messageInput.selectionEnd = start + emoji.length;
                    
                    updateCharCount();
                    emojiPicker.classList.add('hidden');
                    messageInput.focus();
                    checkSendBtn();
                    
                    // Animation de feedback
                    this.classList.add('animate-bounce');
                    setTimeout(() => this.classList.remove('animate-bounce'), 1000);
                });
            });
            }

            // Gestion de l'affichage du bouton d'envoi
            function updateCharCount() {
                const len = messageInput.value.length;
                const hasText = messageInput.value.trim().length > 0;
                const hasAttachments = attachmentInput && attachmentInput.files.length > 0;
                const hasAudio = audioInput && audioInput.files.length > 0;
                
                // Afficher le bouton d'envoi seulement s'il y a du texte ou des pièces jointes
                if (hasText || hasAttachments || hasAudio) {
                    sendBtn.classList.remove('hidden');
                    sendBtn.classList.add('flex');
                } else {
                    sendBtn.classList.add('hidden');
                    sendBtn.classList.remove('flex');
                }
            }
            
            function checkSendBtn() {
                // Cette fonction est maintenant gérée par updateCharCount
                updateCharCount();
            }
            if (messageInput) {
                messageInput.addEventListener('input', function() {
                    updateCharCount();
                    checkSendBtn();
                    autoResize();
                });
                
                // Auto-redimensionnement de la zone de texte (sans limite)
                function autoResize() {
                    this.style.height = 'auto';
                    this.style.height = this.scrollHeight + 'px';
                }
                
                // Gestion des touches clavier
                messageInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();
                        if (!sendBtn.classList.contains('hidden')) {
                            sendMessage();
                        }
                    }
                });
            }
            if (attachmentInput) {
                attachmentInput.addEventListener('change', checkSendBtn);
            }
            if (audioInput) {
                audioInput.addEventListener('change', checkSendBtn);
            }

            // Enregistrement vocal
            if (recordBtn && audioInput) {
                recordBtn.addEventListener('click', async function() {
                    if (isRecording) {
                        mediaRecorder.stop();
                        recordBtn.innerHTML = `<svg class='w-6 h-6 text-red-500' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z' /></svg>`;
                        isRecording = false;
                        if (cancelBtn) cancelBtn.remove();
                        return;
                    }
                    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                        alert('Votre navigateur ne supporte pas l\'enregistrement audio.');
                        return;
                    }
                    try {
                        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                        mediaRecorder = new MediaRecorder(stream);
                        audioChunks = [];
                        mediaRecorder.ondataavailable = e => { if (e.data.size > 0) audioChunks.push(e.data); };
                        mediaRecorder.onstop = () => {
                            const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                            if (audioBlob.size === 0) return;
                            const file = new File([audioBlob], 'audio_message.webm', { type: 'audio/webm' });
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(file);
                            audioInput.files = dataTransfer.files;
                            // Prévisualisation
                            previewArea.classList.remove('hidden');
                            filePreviews.innerHTML = '';
                                const audio = document.createElement('audio');
                                audio.controls = true;
                                audio.src = URL.createObjectURL(audioBlob);
                            filePreviews.appendChild(audio);
                            checkSendBtn();
                        };
                        mediaRecorder.start();
                        isRecording = true;
                        recordBtn.innerHTML = `<svg class='w-6 h-6 text-gray-700' fill='none' stroke='currentColor' viewBox='0 0 24 24'><rect x='6' y='6' width='12' height='12' rx='2' fill='currentColor' /></svg>`;
                        // Bouton annuler
                        if (!cancelBtn) {
                            cancelBtn = document.createElement('button');
                            cancelBtn.type = 'button';
                            cancelBtn.textContent = 'Annuler';
                            cancelBtn.className = 'ml-2 bg-gray-300 text-xs px-2 py-1 rounded';
                            cancelBtn.onclick = function() {
                                if (isRecording && mediaRecorder) mediaRecorder.stop();
                                audioInput.value = '';
                                previewArea.classList.add('hidden');
                                recordBtn.innerHTML = `<svg class='w-6 h-6 text-red-500' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z' /></svg>`;
                                isRecording = false;
                                cancelBtn.remove();
                                cancelBtn = null;
                                checkSendBtn();
                            };
                            recordBtn.parentNode.insertBefore(cancelBtn, recordBtn.nextSibling);
                        }
                    } catch (err) {
                        alert('Erreur lors de l\'accès au micro : ' + err.message);
                    }
                });
            }

            // Scroll auto sur nouveaux messages
            const messagesArea = document.getElementById('messages-area');
            if (messagesArea) {
                messagesArea.scrollTop = messagesArea.scrollHeight;
                const observer = new MutationObserver(() => {
                    messagesArea.scrollTop = messagesArea.scrollHeight;
                });
                observer.observe(messagesArea, { childList: true });
            }

            // Notification sonore/visuelle (préparation)
            function playNotificationSound() {
                const audio = new Audio('/notification.mp3'); // Placez un son dans public/
                audio.play();
            }
            function showNotification(title, body) {
                if (Notification.permission === 'granted') {
                    new Notification(title, { body });
                }
            }
            if (window.Notification && Notification.permission !== 'granted') {
                Notification.requestPermission();
            }

            // Initialisation
            updateCharCount();
            checkSendBtn();
        });
    </script>
</div>
@endsection


