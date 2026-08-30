<?php

namespace App\Livewire\Account;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.user')]
class Dashboard extends Component
{
    #[Url]
    public string $tab = 'favorites'; // favorites | reviews | questions | history

    public function setTab(string $tab): void
    {
        $this->tab = $tab;
    }

    public function removeFavorite(int $productId): void
    {
        auth()->user()->favorites()->detach($productId);
    }

    public function deleteReview(int $reviewId): void
    {
        auth()->user()->reviews()->where('id', $reviewId)->delete();
    }

    public function deleteQuestion(int $discussionId): void
    {
        auth()->user()->discussions()->where('id', $discussionId)->whereNull('parent_id')->delete();
    }

    public function clearHistory(): void
    {
        auth()->user()->viewHistory()->delete();
    }

    public function render()
    {
        $user = auth()->user();

        $data = match ($this->tab) {
            'favorites' => $user->favorites()->with('images')->get(),
            'questions' => $user->discussions()->whereNull('parent_id')->with(['product.images', 'replies.user'])->latest()->get(),
            'history' => $user->viewHistory()->with('product.images')->latest('viewed_at')->get(),
            default => $user->reviews()->with('product.images')->latest()->get(),
        };

        return view('livewire.account.dashboard', compact('data'));
    }
}