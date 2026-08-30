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
                    <div class="flex items-center justify-between gap-4 rounded-xl border border-[#E2E8F0] bg-white p-4">
                        <a href="{{ route('products.show', $product) }}" wire:navigate class="flex items-center gap-4 min-w-0">
                            <div class="w-14 aspect-[2/3] shrink-0 overflow-hidden rounded-lg border border-[#E2E8F0] bg-[#E2E8F0]">
                                @if($product->images->isNotEmpty())
                                    <img src="{{ $product->images->first()->url }}" alt="" class="h-full w-full object-cover">
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="font-medium text-[#1E293B] truncate">{{ $product->title }}</p>
                                <p class="text-sm font-mono text-[#333333]/70">{{ number_format($product->price, 2) }} €</p>
                                @if($product->address)
                                    <p class="mt-1 flex items-center gap-1 text-xs text-[#333333]/60">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span class="truncate">{{ $product->address }}</span>
                                    </p>
                                @endif
                            </div>
                        </a>
                        <button wire:click="removeFavorite({{ $product->id }})"
                                class="shrink-0 self-end text-sm font-medium text-red-600 hover:text-red-700 hover:underline">
                            Retirer
                        </button>
                    </div>
                @empty
                    <p class="text-sm text-[#333333]/60">Aucun favori pour l'instant.</p>
                @endforelse
            @endif

            {{-- Avis --}}
            @if($tab === 'reviews')
                @forelse($data as $review)
                    <div class="flex gap-4 rounded-xl border border-[#E2E8F0] bg-white p-4">
                        <a href="{{ route('products.show', $review->product) }}" wire:navigate class="shrink-0">
                            <div class="w-16 aspect-[2/3] overflow-hidden rounded-lg border border-[#E2E8F0] bg-[#E2E8F0]">
                                @if($review->product->images->isNotEmpty())
                                    <img src="{{ $review->product->images->first()->url }}" alt="" class="h-full w-full object-cover">
                                @endif
                            </div>
                        </a>
                        <div class="flex-1 min-w-0 flex flex-col">
                            <div class="flex items-start justify-between gap-3">
                                <a href="{{ route('products.show', $review->product) }}" wire:navigate class="font-medium text-[#1E293B] hover:underline truncate">
                                    {{ $review->product->title }}
                                </a>
                                <span class="shrink-0 text-sm font-mono text-[#1E3D59]">★ {{ $review->rating }}</span>
                            </div>
                            <p class="text-sm text-[#333333] mt-1 break-words">{{ $review->content }}</p>

                            <div class="mt-2 flex justify-end">
                                <button type="button" @click="confirmingDeleteReview = {{ $review->id }}"
                                        class="text-sm font-medium text-red-600 hover:text-red-700 hover:underline">
                                    Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-[#333333]/60">Aucun avis publié pour l'instant.</p>
                @endforelse
            @endif

            {{-- Questions --}}
            @if($tab === 'questions')
                @forelse($data as $question)
                    <div class="flex gap-4 rounded-xl border border-[#E2E8F0] bg-white p-4">
                        <a href="{{ route('products.show', $question->product) }}" wire:navigate class="shrink-0">
                            <div class="w-16 aspect-[2/3] overflow-hidden rounded-lg border border-[#E2E8F0] bg-[#E2E8F0]">
                                @if($question->product->images->isNotEmpty())
                                    <img src="{{ $question->product->images->first()->url }}" alt="" class="h-full w-full object-cover">
                                @endif
                            </div>
                        </a>
                        <div class="flex-1 min-w-0 flex flex-col">
                            <a href="{{ route('products.show', $question->product) }}" wire:navigate class="font-medium text-[#1E293B] hover:underline truncate">
                                {{ $question->product->title }}
                            </a>
                            <p class="text-sm text-[#333333] mt-1 break-words">{{ $question->content }}</p>

                            @foreach($question->replies as $reply)
                                <div class="ml-4 mt-3 pl-4 border-l-2 border-[#E2E8F0]">
                                    <p class="text-sm font-medium text-[#1E3D59]">
                                        {{ $reply->user->name ?? $question->product->company->name }}
                                        @if(!$reply->user)
                                            <span class="text-xs font-normal text-[#4A3B5C]">· Réponse du commerce</span>
                                        @endif
                                    </p>
                                    <p class="text-sm text-[#333333] mt-1 break-words">{{ $reply->content }}</p>
                                </div>
                            @endforeach

                            <div class="mt-2 flex justify-end">
                                <button type="button" @click="confirmingDeleteQuestion = {{ $question->id }}"
                                        class="text-sm font-medium text-red-600 hover:text-red-700 hover:underline">
                                    Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-[#333333]/60">Aucune question posée pour l'instant.</p>
                @endforelse
            @endif

            {{-- Historique --}}
            @if($tab === 'history')
                @if($data->isNotEmpty())
                    <div class="flex justify-end -mt-2 mb-1">
                        <button wire:click="clearHistory" class="text-sm font-medium text-red-600 hover:text-red-700 hover:underline">
                            Vider l'historique
                        </button>
                    </div>
                @endif

                @forelse($data as $entry)
                    @if($entry->product)
                        <a href="{{ route('products.show', $entry->product) }}" wire:navigate
                           class="flex items-center gap-4 rounded-xl border border-[#E2E8F0] bg-white p-4">
                            <div class="w-14 aspect-[2/3] shrink-0 overflow-hidden rounded-lg border border-[#E2E8F0] bg-[#E2E8F0]">
                                @if($entry->product->images->isNotEmpty())
                                    <img src="{{ $entry->product->images->first()->url }}" alt="" class="h-full w-full object-cover">
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="font-medium text-[#1E293B] truncate">{{ $entry->product->title }}</p>
                                @if($entry->product->address)
                                    <p class="mt-1 flex items-center gap-1 text-xs text-[#333333]/60">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span class="truncate">{{ $entry->product->address }}</span>
                                    </p>
                                @endif
                                <p class="text-xs text-[#333333]/50 mt-0.5">Consulté {{ $entry->viewed_at->diffForHumans() }}</p>
                            </div>
                        </a>
                    @endif
                @empty
                    <p class="text-sm text-[#333333]/60">Aucune consultation récente.</p>
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