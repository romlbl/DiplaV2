<?php

namespace App\Livewire\Product;

use App\Models\Discussion;
use App\Models\Product;
use Livewire\Component;

class Discussions extends Component
{
    public Product $product;

    public string $newQuestion = '';

    public array $replyContent = []; // [discussion_id => texte en cours de saisie]
    public array $showReplyForm = []; // [discussion_id => bool]

    public function mount(Product $product): void
    {
        $this->product = $product;
    }

    public function submitQuestion(): void
    {
        if (!auth()->check()) {
            $this->addError('auth', 'Connecte-toi pour poser une question.');
            return;
        }

        $this->validate([
            'newQuestion' => ['required', 'string', 'max:1000'],
        ]);

        Discussion::create([
            'user_id' => auth()->id(),
            'product_id' => $this->product->id,
            'parent_id' => null,
            'content' => $this->newQuestion,
        ]);

        $this->reset('newQuestion');
    }

    public function toggleReplyForm(int $discussionId): void
    {
        $this->showReplyForm[$discussionId] = !($this->showReplyForm[$discussionId] ?? false);
    }

    public function submitReply(int $discussionId): void
    {
        if (!auth()->check() && !auth('company')->check()) {
            $this->addError('auth', 'Connecte-toi pour répondre.');
            return;
        }

        $content = trim($this->replyContent[$discussionId] ?? '');

        if ($content === '') {
            return;
        }

        Discussion::create([
            'user_id' => auth()->check() ? auth()->id() : null,
            'product_id' => $this->product->id,
            'parent_id' => $discussionId,
            'content' => $content,
        ]);

        $this->replyContent[$discussionId] = '';
        $this->showReplyForm[$discussionId] = false;
    }

    public function render()
    {
        $questions = $this->product->discussions()
            ->with(['user', 'replies.user'])
            ->latest()
            ->get();

        return view('livewire.product.discussions', compact('questions'));
    }
}