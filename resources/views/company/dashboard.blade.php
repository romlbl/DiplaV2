<x-layouts::public>
    <div class="p-6">
        <h1 class="text-xl font-semibold text-[#14171A]">Tableau de bord entreprise</h1>
        <p class="text-[#14171A]/70 mt-2">Connecté en tant que : {{ auth('company')->user()->name }}</p>

        <form method="POST" action="{{ route('company.logout') }}" class="mt-6">
            @csrf
            <flux:button type="submit" variant="ghost">Se déconnecter</flux:button>
        </form>
    </div>
</x-layouts::public>