<div class="mt-10">
    <h2 class="text-sm font-semibold text-[#1E293B] mb-3">Questions</h2>

    @forelse($questions as $question)
        <div class="rounded-xl border border-[#E2E8F0] bg-[#FAFAFF] p-4 mb-3 shadow-sm">
            <div class="flex items-center gap-2">
                <p class="text-sm font-semibold text-[#1E293B]">{{ $question->user->name ?? 'Utilisateur' }}</p>
                <span class="text-xs text-[#333333]/50">{{ $question->created_at->diffForHumans() }}</span>
            </div>
            <p class="text-sm text-[#333333] mt-1">{{ $question->content }}</p>

            {{-- Réponses --}}
            @foreach($question->replies as $reply)
                <div class="mt-3 rounded-lg border-l-4 border-[#1E3D59] bg-white p-3">
                    <p class="text-sm font-medium text-[#1E3D59]">
                        {{ $reply->user->name ?? $product->company->name }}
                        @if(!$reply->user)
                            <span class="text-xs font-normal text-[#4A3B5C]">· Réponse du commerce</span>
                        @endif
                    </p>
                    <p class="text-sm text-[#333333] mt-1">{{ $reply->content }}</p>
                </div>
            @endforeach

            {{-- Formulaire de réponse --}}
            @auth
                <div class="mt-3">
                    @if(!($showReplyForm[$question->id] ?? false))
                        <button wire:click="toggleReplyForm({{ $question->id }})" class="text-sm text-[#1E3D59] font-medium hover:underline">
                            Répondre
                        </button>
                    @else
                        <div class="flex gap-2 mt-2">
                            <input type="text" wire:model="replyContent.{{ $question->id }}"
                                   placeholder="Ta réponse..."
                                   class="flex-1 rounded-xl border border-[#E2E8F0] bg-white px-4 py-2 text-sm text-[#333333] focus:border-[#1E3D59] focus:outline-none focus:ring-2 focus:ring-[#1E3D59]/20">
                            <button wire:click="submitReply({{ $question->id }})"
                                    class="rounded-full bg-[#1E3D59] px-4 py-2 text-sm font-semibold text-[#FDFBF7] hover:bg-[#16293F]">
                                Envoyer
                            </button>
                        </div>
                    @endif
                </div>
            @endauth
        </div>
    @empty
        <p class="text-sm text-[#333333]/60 mb-4">Aucune question pour l'instant.</p>
    @endforelse

    <div class="mt-6">
        @auth
            @if($errors->has('auth'))
                <p class="text-sm text-red-600 mb-2">{{ $errors->first('auth') }}</p>
            @endif

            <div class="flex gap-2">
                <input type="text" id="new-question-input" wire:model="newQuestion" placeholder="Pose une question sur ce produit/service..."
       class="flex-1 rounded-xl border border-[#E2E8F0] bg-[#FDFBF7] px-4 py-2.5 text-sm text-[#333333] focus:border-[#1E3D59] focus:outline-none focus:ring-2 focus:ring-[#1E3D59]/20">
                <button wire:click="submitQuestion"
                        class="rounded-full bg-[#1E3D59] px-5 py-2 text-sm font-semibold text-[#FDFBF7] hover:bg-[#16293F]">
                    Envoyer
                </button>
            </div>
        @else
            <p class="text-sm text-[#333333]/60">
                <a href="{{ route('login') }}" wire:navigate class="text-[#1E3D59] font-medium hover:underline">Connecte-toi</a>
                pour poser une question.
            </p>
        @endauth
    </div>
</div>