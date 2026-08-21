<div>
    <h1 class="text-2xl font-semibold text-[#1E293B]">Mon compte</h1>
    <p class="text-sm text-[#333333] mt-1">
        Connecté en tant que <span class="font-medium text-[#1E3D59]">{{ auth()->user()->name }}</span>
    </p>

    {{-- Onglets --}}
    <div class="flex gap-2 mt-6 mb-6 overflow-x-auto pb-1">
        <button wire:click="setTab('favorites')"
                class="shrink-0 rounded-full px-5 py-2 text-sm font-medium transition {{ $tab === 'favorites' ? 'bg-[#1E3D59] text-[#FDFBF7]' : 'border border-[#E2E8F0] text-[#1E293B] hover:bg-[#FAFAFF]' }}">
            Favoris
        </button>
        <button wire:click="setTab('reviews')"
                class="shrink-0 rounded-full px-5 py-2 text-sm font-medium transition {{ $tab === 'reviews' ? 'bg-[#1E3D59] text-[#FDFBF7]' : 'border border-[#E2E8F0] text-[#1E293B] hover:bg-[#FAFAFF]' }}">
            Avis
        </button>
        <button wire:click="setTab('questions')"
                class="shrink-0 rounded-full px-5 py-2 text-sm font-medium transition {{ $tab === 'questions' ? 'bg-[#1E3D59] text-[#FDFBF7]' : 'border border-[#E2E8F0] text-[#1E293B] hover:bg-[#FAFAFF]' }}">
            Questions
        </button>
        <button wire:click="setTab('history')"
                class="shrink-0 rounded-full px-5 py-2 text-sm font-medium transition {{ $tab === 'history' ? 'bg-[#1E3D59] text-[#FDFBF7]' : 'border border-[#E2E8F0] text-[#1E293B] hover:bg-[#FAFAFF]' }}">
            Historique
        </button>
    </div>

    {{-- Favoris --}}
    @if($tab === 'favorites')
        @forelse($data as $product)
            <div class="flex items-center justify-between rounded-2xl border border-[#E2E8F0] bg-[#FAFAFF] p-4 mb-3 shadow-sm">
                <a href="{{ route('products.show', $product) }}" wire:navigate class="flex items-center gap-4">
                    @if($product->images->isNotEmpty())
                        <img src="{{ $product->images->first()->url }}" alt="" class="w-14 h-14 rounded-lg object-cover border border-[#E2E8F0]">
                    @else
                        <div class="w-14 h-14 rounded-lg bg-[#E2E8F0]"></div>
                    @endif
                    <div>
                        <p class="font-medium text-[#1E293B]">{{ $product->title }}</p>
                        <p class="text-sm font-mono text-[#333333]/70">{{ number_format($product->price, 2) }} €</p>
                    </div>
                </a>
                <button wire:click="removeFavorite({{ $product->id }})" class="text-sm text-[#4A3B5C] hover:underline">
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
        <div class="flex gap-4 rounded-2xl border border-[#E2E8F0] bg-[#FAFAFF] p-4 mb-3 shadow-sm">
            <a href="{{ route('products.show', $review->product) }}" wire:navigate class="shrink-0">
                @if($review->product->images->isNotEmpty())
                    <img src="{{ $review->product->images->first()->url }}" alt="" class="w-14 h-14 rounded-lg object-cover border border-[#E2E8F0]">
                @else
                    <div class="w-14 h-14 rounded-lg bg-[#E2E8F0]"></div>
                @endif
            </a>
            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <a href="{{ route('products.show', $review->product) }}" wire:navigate class="font-medium text-[#1E293B] hover:underline">
                        {{ $review->product->title }}
                    </a>
                    <span class="text-sm font-mono text-[#1E3D59]">★ {{ $review->rating }}</span>
                </div>
                <p class="text-sm text-[#333333] mt-1">{{ $review->content }}</p>
                <button wire:click="deleteReview({{ $review->id }})" class="text-sm text-[#4A3B5C] hover:underline mt-2">
                    Supprimer
                </button>
            </div>
        </div>
    @empty
        <p class="text-sm text-[#333333]/60">Aucun avis publié pour l'instant.</p>
    @endforelse
    @endif

    {{-- Questions --}}
    @if($tab === 'questions')
        @forelse($data as $question)
            <div class="flex gap-4 rounded-2xl border border-[#E2E8F0] bg-[#FAFAFF] p-4 mb-3 shadow-sm">
                <a href="{{ route('products.show', $question->product) }}" wire:navigate class="shrink-0">
                    @if($question->product->images->isNotEmpty())
                        <img src="{{ $question->product->images->first()->url }}" alt="" class="w-14 h-14 rounded-lg object-cover border border-[#E2E8F0]">
                    @else
                        <div class="w-14 h-14 rounded-lg bg-[#E2E8F0]"></div>
                    @endif
                </a>
                <div class="flex-1">
                    <a href="{{ route('products.show', $question->product) }}" wire:navigate class="font-medium text-[#1E293B] hover:underline">
                        {{ $question->product->title }}
                    </a>
                    <p class="text-sm text-[#333333] mt-1">{{ $question->content }}</p>

                    @foreach($question->replies as $reply)
                        <div class="ml-4 mt-3 pl-4 border-l-2 border-[#E2E8F0]">
                            <p class="text-sm font-medium text-[#1E3D59]">
                                {{ $reply->user->name ?? $question->product->company->name }}
                                @if(!$reply->user)
                                    <span class="text-xs font-normal text-[#4A3B5C]">· Réponse du commerce</span>
                                @endif
                            </p>
                            <p class="text-sm text-[#333333] mt-1">{{ $reply->content }}</p>
                        </div>
                    @endforeach

                    <button wire:click="deleteQuestion({{ $question->id }})" class="text-sm text-[#4A3B5C] hover:underline mt-3">
                        Supprimer
                    </button>
                </div>
            </div>
        @empty
            <p class="text-sm text-[#333333]/60">Aucune question posée pour l'instant.</p>
        @endforelse
    @endif

    {{-- Historique --}}
    @if($tab === 'history')
        @if($data->isNotEmpty())
            <button wire:click="clearHistory" class="text-sm text-[#4A3B5C] hover:underline mb-4">
                Vider l'historique
            </button>
        @endif

        @forelse($data as $entry)
            @if($entry->product)
                <a href="{{ route('products.show', $entry->product) }}" wire:navigate
                   class="flex items-center gap-4 rounded-2xl border border-[#E2E8F0] bg-[#FAFAFF] p-4 mb-3 shadow-sm">
                    @if($entry->product->images->isNotEmpty())
                        <img src="{{ $entry->product->images->first()->url }}" alt="" class="w-14 h-14 rounded-lg object-cover border border-[#E2E8F0]">
                    @else
                        <div class="w-14 h-14 rounded-lg bg-[#E2E8F0]"></div>
                    @endif
                    <div>
                        <p class="font-medium text-[#1E293B]">{{ $entry->product->title }}</p>
                        <p class="text-xs text-[#333333]/50">Consulté {{ $entry->viewed_at->diffForHumans() }}</p>
                    </div>
                </a>
            @endif
        @empty
            <p class="text-sm text-[#333333]/60">Aucune consultation récente.</p>
        @endforelse
    @endif
</div>