<div>
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-[#1E293B]">Avis Clients &amp; Évaluations Produits</h1>
        <p class="text-sm text-[#333333]/70 mt-1">Gérez et répondez aux retours de vos clients.</p>
    </div>

    {{-- KPIs --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="rounded-2xl border border-[#E2E8F0] bg-[#FAFAFF] p-5 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wide text-[#333333]/50 mb-2">Note moyenne globale</p>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-mono font-semibold text-[#1E293B]">{{ $stats['avg_rating'] }}</span>
                <span class="text-[#333333]/50">/5</span>
            </div>
            <div class="flex text-amber-500 mt-2">
                @for($i = 1; $i <= 5; $i++)
                    <span>{{ $i <= round($stats['avg_rating']) ? '★' : '☆' }}</span>
                @endfor
            </div>
        </div>

        <div class="rounded-2xl border border-[#E2E8F0] bg-[#FAFAFF] p-5 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wide text-[#333333]/50 mb-2">Total des avis</p>
            <span class="text-3xl font-mono font-semibold text-[#1E293B]">{{ $stats['total_reviews'] }}</span>
            <p class="text-sm text-[#333333]/60 mt-2">avis publiés</p>
        </div>

        <div class="rounded-2xl border border-[#E2E8F0] bg-[#FAFAFF] p-5 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wide text-[#333333]/50 mb-2">Note la plus fréquente</p>
            @if($stats['most_common_rating'])
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-mono font-semibold text-[#1E293B]">{{ $stats['most_common_rating'] }}</span>
                    <span class="text-amber-500 text-xl">★</span>
                </div>
                <p class="text-sm text-[#333333]/60 mt-2">
                    {{ $stats['most_common_rating_count'] }} avis sur {{ $stats['total_reviews'] }}
                </p>
            @else
                <span class="text-3xl font-mono font-semibold text-[#1E293B]">—</span>
                <p class="text-sm text-[#333333]/60 mt-2">Pas encore d'avis</p>
            @endif
        </div>
    </div>

    {{-- Filtres --}}
    <div class="flex flex-col md:flex-row gap-3 mb-6 p-4 rounded-xl border border-[#E2E8F0] bg-[#FAFAFF] shadow-sm">
        <div class="relative flex-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-[#333333]/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
            </svg>
            <input type="text" wire:model.live.debounce.300ms="search"
                   placeholder="Mots-clés, produits..."
                   class="w-full rounded-full border border-[#E2E8F0] bg-white pl-11 pr-4 py-2.5 text-sm text-[#333333] focus:border-[#1E3D59] focus:outline-none focus:ring-2 focus:ring-[#1E3D59]/10">
        </div>

        <div class="flex gap-3 overflow-x-auto">

            <select wire:model.live="rating"
                    class="rounded-full border border-[#E2E8F0] bg-white px-4 py-2.5 text-sm text-[#333333] focus:border-[#1E3D59] focus:outline-none min-w-[130px]">
                <option value="">Toutes les notes</option>
                @for($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}">{{ $i }} étoile{{ $i > 1 ? 's' : '' }}</option>
                @endfor
            </select>

            <select wire:model.live="sort"
                    class="rounded-full border border-[#E2E8F0] bg-white px-4 py-2.5 text-sm text-[#333333] focus:border-[#1E3D59] focus:outline-none min-w-[150px]">
                <option value="recent">Plus récents</option>
                <option value="rating_high">Notes hautes d'abord</option>
                <option value="rating_low">Notes basses d'abord</option>
            </select>
        </div>
    </div>

    {{-- Liste des avis --}}
    @if($reviews->isEmpty())
        <div class="rounded-2xl border border-dashed border-[#E2E8F0] p-10 text-center">
            <p class="text-[#333333]">Aucun avis ne correspond à ces filtres.</p>
        </div>
    @else
        <div class="flex flex-col gap-5">
            @foreach($reviews as $review)
                <div wire:key="review-{{ $review->id }}"
                     class="rounded-xl border border-[#E2E8F0] bg-[#FAFAFF] p-5 md:p-6 shadow-sm">
                    <div class="flex flex-col md:flex-row gap-5">
                        {{-- Produit --}}
                        <div class="w-full md:w-40 shrink-0 flex items-center gap-3 md:flex-col md:items-start">
                            <div class="w-16 h-16 md:w-full md:h-auto md:aspect-[2/3] shrink-0 rounded-lg overflow-hidden border border-[#E2E8F0] bg-[#E2E8F0]">
                                @if($review->product->images->isNotEmpty())
                                    <img src="{{ $review->product->images->first()->url }}" alt=""
                                         class="h-full w-full object-cover">
                                @endif
                            </div>
                            <div>
                                <h3 class="font-medium text-[#1E293B] text-sm">{{ $review->product->title }}</h3>
                                <div class="flex text-amber-500 text-sm mt-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span>{{ $i <= $review->rating ? '★' : '☆' }}</span>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        {{-- Contenu --}}
                        <div class="flex-1 flex flex-col">
                            <div class="flex items-start justify-between gap-3 mb-2">
                                <div>
                                    <p class="font-semibold text-[#1E293B]">{{ $review->subject }}</p>
                                    <p class="text-sm text-[#333333]/60 mt-0.5">
                                        {{ $review->user->name ?? 'Utilisateur' }} · {{ $review->created_at->diffForHumans() }}
                                    </p>
                                </div>

                                @if(!$review->company_reply)
                                    <span class="shrink-0 rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                                        En attente
                                    </span>
                                @endif
                            </div>

                            <p class="text-sm text-[#333333]">{{ $review->content }}</p>

                            {{-- Réponse existante --}}
                            @if($review->company_reply && $editingReplyId !== $review->id)
                                <div class="mt-4 rounded-lg border-l-4 border-[#1E3D59] bg-white p-3">
                                    <div class="flex items-center justify-between mb-1">
                                        <p class="text-xs font-semibold text-[#1E3D59]">Votre réponse</p>
                                        <button type="button" wire:click="startReply({{ $review->id }}, @js($review->company_reply))"
                                                class="text-xs font-medium text-[#1E3D59] hover:underline">
                                            Modifier
                                        </button>
                                    </div>
                                    <p class="text-sm text-[#333333]/80">{{ $review->company_reply }}</p>
                                </div>
                            @endif

                            {{-- Formulaire de réponse (nouvelle ou édition) --}}
                            @if(!$review->company_reply || $editingReplyId === $review->id)
                                <div class="mt-4 border-t border-[#E2E8F0] pt-4">
                                    <textarea wire:model="replyContent.{{ $review->id }}" rows="2"
                                              placeholder="Écrire une réponse publique..."
                                              class="w-full rounded-lg border border-[#E2E8F0] bg-white px-3 py-2 text-sm text-[#333333] focus:border-[#1E3D59] focus:outline-none focus:ring-2 focus:ring-[#1E3D59]/10 resize-none"></textarea>

                                    <div class="flex justify-end gap-2 mt-2">
                                        @if($editingReplyId === $review->id)
                                            <button type="button" wire:click="cancelReply"
                                                    class="rounded-full border border-[#E2E8F0] px-4 py-1.5 text-xs font-medium text-[#1E293B] hover:bg-[#FDFBF7] transition">
                                                Annuler
                                            </button>
                                        @endif
                                        <button type="button" wire:click="submitReply({{ $review->id }})"
                                                class="rounded-full bg-[#1E3D59] px-5 py-1.5 text-xs font-semibold text-[#FDFBF7] hover:bg-[#16293F] transition">
                                            Publier la réponse
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $reviews->links() }}
        </div>
    @endif
</div>