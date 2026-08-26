<?php

namespace App\Livewire\Company;

use App\Models\Discussion;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.company')]
class QuestionsIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $productId = '';

    #[Url]
    public string $status = ''; // '' | pending | answered

    #[Url]
    public string $sort = 'recent';

    public array $replyContent = [];
    public ?int $editingReplyId = null;

    protected $paginationTheme = 'tailwind';

    public function updated($property): void
    {
        if (in_array($property, ['search', 'productId', 'status', 'sort'])) {
            $this->resetPage();
        }
    }

    public function startReply(int $discussionId, ?string $existing = null): void
    {
        $this->editingReplyId = $discussionId;
        $this->replyContent[$discussionId] = $existing ?? ($this->replyContent[$discussionId] ?? '');
    }

    public function cancelReply(): void
    {
        $this->editingReplyId = null;
    }

    public function submitReply(int $discussionId): void
    {
        $content = trim($this->replyContent[$discussionId] ?? '');

        if ($content === '') {
            return;
        }

        $productIds = auth('company')->user()->products()->pluck('id');

        $question = Discussion::whereIn('product_id', $productIds)
            ->whereNull('parent_id')
            ->with('replies')
            ->findOrFail($discussionId);

        $existingReply = $question->replies->first();

        if ($existingReply) {
            $existingReply->update(['content' => $content]);
        } else {
            Discussion::create([
                'user_id' => null,
                'product_id' => $question->product_id,
                'parent_id' => $question->id,
                'content' => $content,
            ]);
        }

        $this->replyContent[$discussionId] = '';
        $this->editingReplyId = null;
    }

    protected function formatResponseTime(?float $minutes): string
    {
        if ($minutes === null) {
            return '—';
        }

        if ($minutes < 60) {
            return round($minutes).' min';
        }

        $hours = $minutes / 60;

        if ($hours < 24) {
            return round($hours, 1).' h';
        }

        return round($hours / 24, 1).' j';
    }

    public function render()
    {
        $company = auth('company')->user();
        $productIds = $company->products()->pluck('id');

        $baseQuestions = Discussion::whereIn('product_id', $productIds)->whereNull('parent_id');

        $answeredQuestions = (clone $baseQuestions)->has('replies')->with(['replies' => fn ($r) => $r->oldest()])->get();

        $avgResponseMinutes = $answeredQuestions->isNotEmpty()
            ? $answeredQuestions->avg(fn ($q) => $q->created_at->diffInMinutes($q->replies->first()->created_at))
            : null;

        $stats = [
            'pending_count' => (clone $baseQuestions)->doesntHave('replies')->count(),
            'total_count' => (clone $baseQuestions)->count(),
            'answered_count' => $answeredQuestions->count(),
            'avg_response_label' => $this->formatResponseTime($avgResponseMinutes),
        ];

        $query = Discussion::whereIn('product_id', $productIds)
            ->whereNull('parent_id')
            ->with(['user', 'product.images', 'replies.user']);

        if ($this->search !== '') {
            $query->where(function ($q) {
                $q->where('content', 'like', '%'.$this->search.'%')
                    ->orWhereHas('product', fn ($p) => $p->where('title', 'like', '%'.$this->search.'%'));
            });
        }

        if ($this->productId !== '') {
            $query->where('product_id', $this->productId);
        }

        if ($this->status === 'pending') {
            $query->doesntHave('replies');
        } elseif ($this->status === 'answered') {
            $query->has('replies');
        }

        match ($this->sort) {
            'oldest' => $query->oldest(),
            default => $query->latest(),
        };

        $questions = $query->paginate(6);

        $products = $company->products()->orderBy('title')->pluck('title', 'id');

        return view('livewire.company.questions-index', compact('questions', 'stats', 'products'));
    }
}