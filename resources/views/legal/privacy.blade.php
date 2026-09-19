<x-legal.page title="Politique de confidentialité" updated="19 septembre 2026">

    <x-legal.section title="Qui est responsable de tes données ?">
        <p>
            Romain Lo Bello, éditeur de Dipla —
            <a href="mailto:lobelloromain@gmail.com" class="font-medium text-[#1E3D59] underline underline-offset-2 hover:text-[#16293F]">lobelloromain@gmail.com</a>.
        </p>
        <p>
            Dipla est un site de démonstration. Tu peux y créer un compte, mais évite d'y saisir des informations
            personnelles réelles que tu ne souhaites pas voir traitées.
        </p>
    </x-legal.section>

    <x-legal.section title="Les données que nous traitons">
        <ul class="list-disc space-y-2 pl-5">
            <li>
                <strong>Compte utilisateur</strong> : nom, e-mail, mot de passe (stocké uniquement sous forme hachée, jamais lisible),
                adresse facultative et ses coordonnées GPS. Finalité : gérer ton compte et te proposer des résultats à proximité.
                Base légale : fonctionnement du service que tu demandes ; ton consentement pour l'adresse facultative, que tu renseignes volontairement.
            </li>
            <li>
                <strong>Compte commerce</strong> : nom, e-mail, téléphone facultatif, adresse et coordonnées, photos, description, horaires, produits et services.
                Les informations de la devanture et des produits sont publiques.
            </li>
            <li>
                <strong>Contenus et activité</strong> : avis, questions et réponses (publics, affichés avec ton nom), favoris,
                historique des 7 derniers produits consultés (visible par toi seul).
            </li>
            <li>
                <strong>Sécurité de la connexion</strong> : si tu les actives, un secret d'authentification à deux facteurs (chiffré) et des clés d'accès (passkeys).
            </li>
            <li>
                <strong>Journaux techniques</strong> : adresse IP et requêtes, conservées par l'hébergeur pour la sécurité et le bon fonctionnement du site
                (intérêt légitime).
            </li>
        </ul>
    </x-legal.section>

    <x-legal.section title="Ta position et la géolocalisation">
        <p>
            Ta position n'est utilisée que si tu la choisis (adresse, carte) ou si tu autorises ton navigateur à la partager.
            Elle sert à calculer les distances, les résultats « à proximité » et les itinéraires.
        </p>
        <p>
            Elle est mémorisée <strong>sur ton appareil uniquement</strong> (stockage local du navigateur) et n'est pas enregistrée dans notre base de données,
            sauf l'adresse que tu ajoutes volontairement à ton compte. Pour effectuer les recherches, les coordonnées sont transmises au site
            (elles apparaissent dans l'adresse de la page de recherche) et peuvent donc figurer dans les journaux techniques de l'hébergeur.
        </p>

        <div x-data="{ cleared: false }" class="flex flex-wrap items-center gap-3 pt-1">
            <button type="button"
                    @click="$store.searchLocation.clear(); cleared = true"
                    class="inline-flex min-h-[44px] items-center justify-center rounded-full border border-[#E2E8F0] bg-white px-5 py-2 text-sm font-medium text-[#1E3D59] transition hover:bg-[#FDFBF7]">
                Effacer ma position mémorisée
            </button>
            <span x-show="cleared" x-cloak role="status" class="text-sm text-emerald-700">Position effacée de cet appareil.</span>
        </div>
        <p class="text-sm text-[#333333]/70">
            Si tu es connecté avec une adresse enregistrée dans ton compte, elle sera de nouveau utilisée à la prochaine visite.
        </p>
    </x-legal.section>

    <x-legal.section title="Cookies et stockage local">
        <p>
            Dipla n'utilise que des traceurs indispensables à son fonctionnement ou demandés par toi. Aucun outil publicitaire,
            statistique ou de suivi n'est utilisé : c'est pourquoi aucun choix « accepter / refuser » n'est demandé.
            Les polices de caractères sont hébergées par le site lui-même.
        </p>

        <div class="overflow-x-auto rounded-xl border border-[#E2E8F0] bg-white">
            <table class="w-full min-w-[36rem] text-left text-sm">
                <thead class="bg-[#FDFBF7] text-[#1E293B]">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Nom</th>
                        <th class="px-4 py-3 font-semibold">Type</th>
                        <th class="px-4 py-3 font-semibold">Rôle</th>
                        <th class="px-4 py-3 font-semibold">Durée</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0] align-top">
                    <tr>
                        <td class="px-4 py-3 font-mono text-xs">{{ config('session.cookie') }}</td>
                        <td class="px-4 py-3">Cookie</td>
                        <td class="px-4 py-3">Te garder connecté pendant ta visite</td>
                        <td class="px-4 py-3">{{ config('session.lifetime') }} min d'inactivité</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 font-mono text-xs">XSRF-TOKEN</td>
                        <td class="px-4 py-3">Cookie</td>
                        <td class="px-4 py-3">Protéger les formulaires contre les envois frauduleux</td>
                        <td class="px-4 py-3">{{ config('session.lifetime') }} min</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 font-mono text-xs">dipla-search-location</td>
                        <td class="px-4 py-3">Stockage local</td>
                        <td class="px-4 py-3">Retenir la position de recherche que tu as choisie</td>
                        <td class="px-4 py-3">Jusqu'à effacement</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 font-mono text-xs">dipla-notice-seen</td>
                        <td class="px-4 py-3">Stockage local</td>
                        <td class="px-4 py-3">Ne plus afficher le bandeau d'information</td>
                        <td class="px-4 py-3">Jusqu'à effacement</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </x-legal.section>

    <x-legal.section title="Prestataires qui interviennent">
        <ul class="list-disc space-y-2 pl-5">
            <li><strong>Render</strong> : hébergement du site (États-Unis).</li>
            <li><strong>Neon</strong> : base de données, région Europe (Francfort).</li>
            <li><strong>Cloudinary</strong> : stockage et diffusion des photos.</li>
            <li>
                <strong>OpenStreetMap, Nominatim et OSRM</strong> : affichage des cartes, recherche d'adresses et calcul d'itinéraires.
                Ton navigateur contacte directement ces services : ils reçoivent ton adresse IP, et les adresses ou coordonnées nécessaires à la carte,
                à la recherche ou à l'itinéraire.
            </li>
        </ul>
        <p>
            Certains de ces prestataires sont situés hors de l'Union européenne ; les transferts sont soumis aux garanties prévues
            par le RGPD, détaillées dans leurs propres politiques de confidentialité.
        </p>
    </x-legal.section>

    <x-legal.section title="Durées de conservation">
        <ul class="list-disc space-y-2 pl-5">
            <li>Compte : jusqu'à sa suppression depuis les paramètres. La suppression efface aussi tes avis, questions, favoris et historique ; pour un commerce, ses produits, photos, avis et questions associés.</li>
            <li>Historique de consultation : les 7 derniers produits, les plus anciens sont supprimés automatiquement.</li>
            <li>Session de connexion : {{ config('session.lifetime') }} minutes d'inactivité.</li>
            <li>Journaux techniques et sauvegardes : selon la durée de conservation propre à chaque prestataire.</li>
        </ul>
    </x-legal.section>

    <x-legal.section title="Tes droits">
        <p>
            Tu disposes des droits d'accès, de rectification, d'effacement, de limitation, d'opposition et de portabilité,
            ainsi que du droit de retirer ton consentement à tout moment.
        </p>
        <p>
            Tu peux modifier ou supprimer ton compte directement dans tes paramètres, ou exercer tes droits par e-mail à
            <a href="mailto:lobelloromain@gmail.com" class="font-medium text-[#1E3D59] underline underline-offset-2 hover:text-[#16293F]">lobelloromain@gmail.com</a>.
            Une réponse te sera apportée dans un délai d'un mois ; un justificatif d'identité peut être demandé en cas de doute.
        </p>
        <p>
            Si tu estimes que tes droits ne sont pas respectés, tu peux introduire une réclamation auprès de la CNIL (cnil.fr).
        </p>
    </x-legal.section>

    <x-legal.section title="Sécurité">
        <p>
            Les mots de passe sont hachés (bcrypt), les échanges sont chiffrés (HTTPS) et une authentification à deux facteurs ou par clé d'accès est proposée.
        </p>
    </x-legal.section>

    <x-legal.section title="Modifications">
        <p>
            Cette politique peut évoluer, notamment si de nouveaux services sont ajoutés. La date de mise à jour figure en haut de la page.
        </p>
    </x-legal.section>

</x-legal.page>