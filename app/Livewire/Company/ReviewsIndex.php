<?php

namespace App\Livewire\Company;

use App\Models\Review;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.company')]
class ReviewsIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $productId = '';

    #[Url]
    public string $rating = '';

    #[Url]
    public string $sort = 'recent'; // recent | rating_high | rating_low

    public array $replyContent = [];
    public ?int $editingReplyId = null;

    protected $paginationTheme = 'tailwind';

    public function updated($property): void
    {
        if (in_array($property, ['search', 'productId', 'rating', 'sort'])) {
            $this->resetPage();
        }
    }

    public function startReply(int $reviewId, ?string $existing = null): void
    {
        $this->editingReplyId = $reviewId;
        $this->replyContent[$reviewId] = $existing ?? ($this->replyContent[$reviewId] ?? '');
    }

    public function cancelReply(): void
    {
        $this->editingReplyId = null;
    }

    public function submitReply(int $reviewId): void
    {
        $this->replyContent[$reviewId] = trim($this->replyContent[$reviewId] ?? '');

        $this->validate(
            ["replyContent.$reviewId" => ['required', 'string', 'max:2000']],
            [],
            ["replyContent.$reviewId" => 'réponse'],
        );

        $content = $this->replyContent[$reviewId];

        // Uniquement les avis portant sur les produits de cette entreprise.
        $review = Review::whereIn('product_id', auth('company')->user()->products()->select('id'))
            ->findOrFail($reviewId);

        $review->update([
            'company_reply' => $content,
            'company_replied_at' => now(),
        ]);

        $this->editingReplyId = null;
    }

    public function render()
    {
        $company = auth('company')->user();
        $productIds = $company->products()->pluck('id');

        $baseReviews = Review::whereIn('product_id', $productIds);

        $ratingCounts = (clone $baseReviews)
            ->selectRaw('rating, count(*) as total')
            ->groupBy('rating')
            ->orderByDesc('total')
            ->pluck('total', 'rating');

        $stats = [
            'avg_rating' => round((clone $baseReviews)->avg('rating') ?? 0, 1),
            'total_reviews' => (clone $baseReviews)->count(),
            'most_common_rating' => $ratingCounts->keys()->first(),
            'most_common_rating_count' => $ratingCounts->first(),
        ];

        $query = Review::whereIn('product_id', $productIds)->with(['user', 'product.images']);

        if ($this->search !== '') {
            $query->search(mb_substr(trim($this->search), 0, 100));
        }

        if ($this->productId !== '') {
            $query->where('product_id', $this->productId);
        }

        if ($this->rating !== '') {
            $query->where('rating', $this->rating);
        }

        match ($this->sort) {
            'rating_high' => $query->orderByDesc('rating'),
            'rating_low' => $query->orderBy('rating'),
            default => $query->latest(),
        };

        $reviews = $query->paginate(6);

        $products = $company->products()->orderBy('title')->pluck('title', 'id');

        return view('livewire.company.reviews-index', compact('reviews', 'stats', 'products'));
    }
}