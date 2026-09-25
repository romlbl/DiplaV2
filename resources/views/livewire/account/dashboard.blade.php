<div x-data="{ confirmingDeleteReview: null, confirmingDeleteQuestion: null }" class="space-y-6 md:space-y-8">

    {{-- Carte profil --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl border border-[#E2E8F0] bg-[#FAFAFF] p-5 md:p-6 shadow-sm">
        <div class="min-w-0">
            <p class="text-xs font-medium uppercase tracking-wider text-[#333333]/50 mb-1">
                Membre depuis le {{ auth()->user()->created_at->format('d/m/Y') }}
            </p>
            <h1 class="text-xl md:text-2xl font-semibold text-[#1E293B] truncate">{{ auth()->user()->name }}</h1>
            @if(auth()->user()->address)
                <p class="text-sm text-[#333333]/70 mt-1 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="truncate">{{ auth()->user()->address }}</span>
                </p>
            @endif
            <p class="text-sm text-[#333333]/70 mt-1 truncate">{{ auth()->user()->email }}</p>
        </div>

        <a href="{{ route('account.settings') }}" wire:navigate
           class="shrink-0 inline-flex items-center justify-center rounded-full border border-[#E2E8F0] bg-white px-5 py-2.5 text-sm font-medium text-[#1E3D59] transition hover:bg-[#FDFBF7]">
            Modifier mon profil
        </a>
    </div>

    {{-- Onglets --}}
    <div class="rounded-2xl border border-[#E2E8F0] bg-[#FAFAFF] p-5 md:p-6 shadow-sm">
        <div class="flex gap-2 mb-6 overflow-x-auto pb-1">
            <button wire:click="setTab('favorites')"
                    class="shrink-0 rounded-full px-5 py-2 text-sm font-medium transition {{ $tab === 'favorites' ? 'bg-[#1E3D59] text-[#FDFBF7]' : 'border border-[#E2E8F0] text-[#1E293B] hover:bg-white' }}">
                Favoris
            </button>
            <button wire:click="setTab('reviews')"
                    class="shrink-0 rounded-full px-5 py-2 text-sm font-medium transition {{ $tab === 'reviews' ? 'bg-[#1E3D59] text-[#FDFBF7]' : 'border border-[#E2E8F0] text-[#1E293B] hover:bg-white' }}">
                Avis
            </button>
            <button wire:click="setTab('questions')"
                    class="shrink-0 rounded-full px-5 py-2 text-sm font-medium transition {{ $tab === 'questions' ? 'bg-[#1E3D59] text-[#FDFBF7]' : 'border border-[#E2E8F0] text-[#1E293B] hover:bg-white' }}">
                Questions
            </button>
            <button wire:click="setTab('history')"
                    class="shrink-0 rounded-full px-5 py-2 text-sm font-medium transition {{ $tab === 'history' ? 'bg-[#1E3D59] text-[#FDFBF7]' : 'border border-[#E2E8F0] text-[#1E293B] hover:bg-white' }}">
                Historique
            </button>
        </div>

        <div class="flex flex-col gap-4">

            {{-- Favoris --}}
            @if($tab === 'favorites')
                @forelse($data as $product)
                    <div class="group relative flex overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-3.5 md:p-4 shadow-xs hover:shadow-md transition-all duration-300">
                        
                        {{-- Image produit --}}
                        <div class="w-24 max-w-[95px] aspect-[2/3] md:w-1/3 md:max-w-[120px] shrink-0 overflow-hidden rounded-xl border border-slate-200/80 bg-slate-100">
                            <a href="{{ route('products.show', $product) }}" target="_blank" rel="noopener" class="block h-full w-full hover:opacity-90 transition-opacity">
                                @if($product->images->isNotEmpty())
                                    <img src="{{ $product->images->first()->url }}" alt="" class="h-full w-full object-cover">
                                @endif
                            </a>
                        </div>

                        {{-- Contenu & Actions --}}
                        <div class="flex-1 min-w-0 pl-3.5 md:pl-4 flex flex-col justify-between">
                            <div>
                                {{-- Titre du produit --}}
                                <a href="{{ route('products.show', $product) }}" target="_blank" rel="noopener" class="block group/title">
                                    <p class="text-xs md:text-sm font-bold text-slate-800 group-hover/title:text-[#1E3D59] truncate transition-colors">
                                        {{ $product->title }}
                                    </p>
                                </a>

                                {{-- Prix --}}
                                <p class="text-xs md:text-sm font-mono font-semibold text-slate-700 mt-1">
                                    {{ $product->priceLabel() }}
                                </p>

                                {{-- Adresse --}}
                                @if($product->address)
                                    <p class="mt-1 flex items-center gap-1 text-[11px] md:text-xs text-slate-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span class="truncate">{{ $product->address }}</span>
                                    </p>
                                @endif
                            </div>

                            {{-- Bouton Retirer (placé en bas à droite pour libérer la largeur du titre) --}}
                            <div class="mt-2.5 flex justify-end">
                                <button wire:click="removeFavorite({{ $product->id }})"
                                        class="inline-flex items-center gap-1.5 text-xs md:text-sm font-medium text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100/80 px-2.5 py-1 rounded-lg transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Retirer
                                </button>
                            </div>
                        </div>

                    </div>
                @empty
                    <p class="text-sm text-slate-500 italic">Aucun favori pour l'instant.</p>
                @endforelse
            @endif

            {{-- Avis --}}
            @if($tab === 'reviews')
                @forelse($data as $review)
                    <div class="group relative flex flex-col overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs hover:shadow-md transition-all duration-300">
                        
                        {{-- Ligne principale : Image + Contenu de l'avis --}}
                        <div class="flex min-w-0 p-3.5 md:p-4">
                            
                            {{-- Image produit --}}
                            <div class="w-24 max-w-[95px] shrink-0 md:w-1/3 md:max-w-[120px] transition-all duration-300 ease-out md:-ml-[136px] md:group-hover:ml-0">
                                <a href="{{ route('products.show', $review->product) }}" target="_blank" rel="noopener" 
                                class="block relative w-full aspect-[2/3] overflow-hidden rounded-xl bg-slate-100 border border-slate-200/80 shadow-xs hover:opacity-95 transition-opacity">
                                    @if($review->product->images->isNotEmpty())
                                        <img src="{{ $review->product->images->first()->url }}" alt="" class="h-full w-full object-cover">
                                    @endif
                                </a>
                            </div>

                            {{-- Contenu de l'avis --}}
                            <div class="flex-1 min-w-0 pl-3.5 md:pl-4 flex flex-col justify-between">
                                <div>
                                    {{-- En-tête : Titre du produit & Badge de note --}}
                                    <div class="flex items-start justify-between gap-2">
                                        <p class="text-xs md:text-sm font-bold text-slate-800 truncate tracking-tight">
                                            {{ $review->product->title }}
                                        </p>
                                        <span class="shrink-0 inline-flex items-center gap-1 rounded-full bg-amber-50 border border-amber-200/80 px-2.5 py-0.5 text-xs font-bold text-amber-700 shadow-2xs">
                                            ★ {{ $review->rating }}
                                        </span>
                                    </div>

                                    {{-- Sujet & Message --}}
                                    <p class="text-xs md:text-sm font-semibold text-slate-900 mt-1.5">
                                        {{ $review->subject }}
                                    </p>
                                    <p class="text-xs md:text-sm text-slate-600 mt-1 break-words leading-relaxed">
                                        {{ $review->content }}
                                    </p>
                                </div>

                                {{-- Réponse du commerce : Version Desktop (intégrée sous le texte) --}}
                                @if($review->company_reply)
                                    <div class="hidden md:block mt-3.5 rounded-xl border border-slate-200/80 bg-slate-50/80 p-3">
                                        <div class="flex items-center gap-1.5 mb-1 text-xs font-bold text-[#1E3D59]">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 8 4.03 8z" />
                                            </svg>
                                            Réponse du commerce
                                        </div>
                                        <p class="text-xs md:text-sm text-slate-600 leading-relaxed break-words">{{ $review->company_reply }}</p>
                                    </div>
                                @endif

                                {{-- Bouton Supprimer --}}
                                <div class="mt-3 flex justify-end">
                                    <button type="button" @click="confirmingDeleteReview = {{ $review->id }}"
                                            class="inline-flex items-center gap-1.5 text-xs md:text-sm font-medium text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100/80 px-2.5 py-1 rounded-lg transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Supprimer
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Réponse du commerce : Version Mobile (sous l'image et l'avis avec scroll) --}}
                        @if($review->company_reply)
                            <div class="block md:hidden px-3.5 pb-3.5 pt-0">
                                <div class="rounded-xl border border-slate-200/80 bg-slate-50/80 p-2.5">
                                    <div class="flex items-center gap-1.5 mb-1 text-[11px] font-bold text-[#1E3D59]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 8 4.03 8z" />
                                        </svg>
                                        Réponse du commerce
                                    </div>
                                    <div class="max-h-28 overflow-y-auto text-xs text-slate-600 leading-relaxed break-words pr-1">
                                        {{ $review->company_reply }}
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                @empty
                    <p class="text-sm text-slate-500 italic">Aucun avis publié pour l'instant.</p>
                @endforelse
            @endif

            {{-- Questions --}}
            @if($tab === 'questions')
                @forelse($data as $question)
                    <div class="group relative flex flex-col overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs hover:shadow-md transition-all duration-300">
                        
                        {{-- Ligne principale : Image + Question --}}
                        <div class="flex min-w-0 p-3.5 md:p-4">
                            
                            {{-- Image produit (seul élément cliquable vers la fiche) --}}
                            <div class="w-24 max-w-[95px] shrink-0 md:w-1/3 md:max-w-[120px] transition-all duration-300 ease-out md:-ml-[136px] md:group-hover:ml-0">
                                <a href="{{ route('products.show', $question->product) }}" target="_blank" rel="noopener" 
                                class="block relative w-full aspect-[2/3] overflow-hidden rounded-xl bg-slate-100 border border-slate-200/80 shadow-xs hover:opacity-95 transition-opacity">
                                    @if($question->product->images->isNotEmpty())
                                        <img src="{{ $question->product->images->first()->url }}" alt="" class="h-full w-full object-cover">
                                    @endif
                                </a>
                            </div>

                            {{-- Contenu de la question --}}
                            <div class="flex-1 min-w-0 pl-3.5 md:pl-4 flex flex-col justify-between">
                                <div>
                                    {{-- En-tête : Titre du produit & Date --}}
                                    <div class="flex items-start justify-between gap-2">
                                        <p class="text-xs md:text-sm font-bold text-slate-800 truncate tracking-tight">
                                            {{ $question->product->title }}
                                        </p>
                                        {{-- Date version Desktop --}}
                                        <span class="hidden md:inline-flex shrink-0 items-center gap-1 rounded-full bg-slate-100 border border-slate-200/80 px-2.5 py-0.5 text-xs font-medium text-slate-500 shadow-2xs">
                                            {{ $question->created_at->diffForHumans() }}
                                        </span>
                                    </div>

                                    {{-- Date version Mobile (placée sous le titre pour libérer toute la largeur) --}}
                                    <p class="text-[11px] font-medium text-slate-400 md:hidden mt-0.5">
                                        {{ $question->created_at->diffForHumans() }}
                                    </p>

                                    {{-- Texte de la question --}}
                                    <p class="text-xs md:text-sm text-slate-600 mt-1.5 break-words leading-relaxed">
                                        {{ $question->content }}
                                    </p>
                                </div>

                                {{-- Réponses : Version Desktop (intégrées sous la question) --}}
                                @if($question->replies->isNotEmpty())
                                    <div class="hidden md:block mt-3.5 space-y-2">
                                        @foreach($question->replies as $reply)
                                            <div class="rounded-xl border border-slate-200/80 bg-slate-50/80 p-3">
                                                <div class="flex items-center gap-1.5 mb-1 text-xs font-bold text-[#1E3D59]">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 8 4.03 8z" />
                                                    </svg>
                                                    <span>{{ $reply->user->name ?? $question->product->company->name }}</span>
                                                    @if(!$reply->user)
                                                        <span class="text-[11px] font-normal text-slate-500">· Réponse du commerce</span>
                                                    @endif
                                                </div>
                                                <p class="text-xs md:text-sm text-slate-600 leading-relaxed break-words">{{ $reply->content }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Bouton Supprimer --}}
                                <div class="mt-3 flex justify-end">
                                    <button type="button" @click="confirmingDeleteQuestion = {{ $question->id }}"
                                            class="inline-flex items-center gap-1.5 text-xs md:text-sm font-medium text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100/80 px-2.5 py-1 rounded-lg transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Supprimer
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Réponses : Version Mobile (sous la question avec défilement si plusieurs réponses) --}}
                        @if($question->replies->isNotEmpty())
                            <div class="block md:hidden px-3.5 pb-3.5 pt-0">
                                <div class="max-h-36 overflow-y-auto space-y-2 pr-1">
                                    @foreach($question->replies as $reply)
                                        <div class="rounded-xl border border-slate-200/80 bg-slate-50/80 p-2.5">
                                            <div class="flex items-center gap-1.5 mb-1 text-[11px] font-bold text-[#1E3D59]">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 8 4.03 8z" />
                                                </svg>
                                                <span>{{ $reply->user->name ?? $question->product->company->name }}</span>
                                                @if(!$reply->user)
                                                    <span class="text-[10px] font-normal text-slate-500">· Commerce</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-slate-600 leading-relaxed break-words">{{ $reply->content }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                @empty
                    <p class="text-sm text-slate-500 italic">Aucune question posée pour l'instant.</p>
                @endforelse
            @endif

            {{-- Historique --}}
            @if($tab === 'history')
                @if($data->isNotEmpty())
                    <div class="flex justify-end mb-3">
                        <button wire:click="clearHistory" 
                                class="inline-flex items-center gap-1.5 text-xs md:text-sm font-medium text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100/80 px-3 py-1.5 rounded-xl transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Vider l'historique
                        </button>
                    </div>
                @endif

                @forelse($data as $entry)
                    @if($entry->product)
                        <div class="group relative flex overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-3.5 md:p-4 shadow-xs hover:shadow-md transition-all duration-300">
                            
                            {{-- Image produit --}}
                            <div class="w-24 max-w-[95px] aspect-[2/3] md:w-1/3 md:max-w-[120px] shrink-0 overflow-hidden rounded-xl border border-slate-200/80 bg-slate-100">
                                <a href="{{ route('products.show', $entry->product) }}" target="_blank" rel="noopener" class="block h-full w-full hover:opacity-90 transition-opacity">
                                    @if($entry->product->images->isNotEmpty())
                                        <img src="{{ $entry->product->images->first()->url }}" alt="" class="h-full w-full object-cover">
                                    @endif
                                </a>
                            </div>

                            {{-- Contenu & Actions --}}
                            <div class="flex-1 min-w-0 pl-3.5 md:pl-4 flex flex-col justify-between">
                                <div>
                                    {{-- Titre du produit --}}
                                    <a href="{{ route('products.show', $entry->product) }}" target="_blank" rel="noopener" class="block group/title">
                                        <p class="text-xs md:text-sm font-bold text-slate-800 group-hover/title:text-[#1E3D59] truncate transition-colors">
                                            {{ $entry->product->title }}
                                        </p>
                                    </a>

                                    {{-- Prix --}}
                                    <p class="text-xs md:text-sm font-mono font-semibold text-slate-700 mt-1">
                                        {{ $entry->product->priceLabel() }}
                                    </p>

                                    {{-- Adresse --}}
                                    @if($entry->product->address)
                                        <p class="mt-1 flex items-center gap-1 text-[11px] md:text-xs text-slate-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span class="truncate">{{ $entry->product->address }}</span>
                                        </p>
                                    @endif
                                </div>

                                {{-- Bouton Retirer --}}
                                <div class="mt-2.5 flex justify-end">
                                    <button type="button" wire:click="removeHistory({{ $entry->id }})"
                                            class="inline-flex items-center gap-1.5 text-xs md:text-sm font-medium text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100/80 px-2.5 py-1 rounded-lg transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Retirer
                                    </button>
                                </div>
                            </div>

                        </div>
                    @endif
                @empty
                    <p class="text-sm text-slate-500 italic">Aucune consultation récente.</p>
                @endforelse
            @endif
        </div>
    </div>

    {{-- Modale de confirmation : suppression d'un avis --}}
    <div x-show="confirmingDeleteReview !== null" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4"
         style="display: none;">
        <div @click.outside="confirmingDeleteReview = null"
             class="w-full max-w-sm rounded-2xl bg-[#FDFBF7] p-6 shadow-lg">
            <h3 class="text-base font-semibold text-[#1E293B]">Supprimer cet avis ?</h3>
            <p class="text-sm text-[#333333] mt-1">Cette action est définitive.</p>

            <div class="flex gap-3 mt-5">
                <button type="button" @click="confirmingDeleteReview = null"
                        class="flex-1 rounded-full border border-[#E2E8F0] px-4 py-2 text-sm font-medium text-[#1E293B] hover:bg-white transition">
                    Annuler
                </button>
                <button type="button" @click="$wire.deleteReview(confirmingDeleteReview); confirmingDeleteReview = null"
                        class="flex-1 rounded-full bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 transition">
                    Supprimer
                </button>
            </div>
        </div>
    </div>

    {{-- Modale de confirmation : suppression d'une question --}}
    <div x-show="confirmingDeleteQuestion !== null" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4"
         style="display: none;">
        <div @click.outside="confirmingDeleteQuestion = null"
             class="w-full max-w-sm rounded-2xl bg-[#FDFBF7] p-6 shadow-lg">
            <h3 class="text-base font-semibold text-[#1E293B]">Supprimer cette question ?</h3>
            <p class="text-sm text-[#333333] mt-1">Cette action est définitive et supprimera aussi les réponses associées.</p>

            <div class="flex gap-3 mt-5">
                <button type="button" @click="confirmingDeleteQuestion = null"
                        class="flex-1 rounded-full border border-[#E2E8F0] px-4 py-2 text-sm font-medium text-[#1E293B] hover:bg-white transition">
                    Annuler
                </button>
                <button type="button" @click="$wire.deleteQuestion(confirmingDeleteQuestion); confirmingDeleteQuestion = null"
                        class="flex-1 rounded-full bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 transition">
                    Supprimer
                </button>
            </div>
        </div>
    </div>
</div>