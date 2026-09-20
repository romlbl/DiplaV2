<x-legal.page title="Conditions d'utilisation (commerces)" updated="20 septembre 2026">

    <x-legal.section title="Objet">
        <p>
            Ces conditions encadrent l'utilisation de Dipla par les commerces qui y créent un compte et publient
            des produits ou services. En créant un compte commerce, vous les acceptez.
        </p>
        <p>
            Dipla est actuellement un site de démonstration (portfolio) : aucun contrat réel n'est conclu avec de vrais commerces.
        </p>
    </x-legal.section>

    <x-legal.section title="Rôle de Dipla">
        <p>
            Dipla est une vitrine qui aide les particuliers à trouver des commerces proches. Dipla ne vend rien,
            ne prend aucun paiement et n'est pas partie à la vente entre vous et vos clients.
            Vous restez seul responsable de vos offres, de leur disponibilité et de vos relations avec vos clients.
        </p>
    </x-legal.section>

    <x-legal.section title="Votre compte">
        <ul class="list-disc space-y-2 pl-5">
            <li>Les informations du compte (nom, adresse, e-mail) doivent être exactes et à jour.</li>
            <li>Vous gardez votre mot de passe confidentiel et répondez des actions faites depuis votre compte.</li>
            <li>Un commerce doit être réellement localisé à l'adresse indiquée.</li>
        </ul>
    </x-legal.section>

    <x-legal.section title="Produits et services publiés">
        <p>Vous garantissez que chaque fiche publiée :</p>
        <ul class="list-disc space-y-2 pl-5">
            <li>décrit fidèlement le produit ou service (titre, description, photos, mots-clés) ;</li>
            <li>affiche un prix exact, toutes taxes comprises, conforme à la loi ;</li>
            <li>respecte la réglementation applicable à votre activité (étiquetage, sécurité, informations légales, professions réglementées) ;</li>
            <li>ne porte pas atteinte aux droits d'autrui (marques, droit d'auteur, image des personnes).</li>
        </ul>
    </x-legal.section>

    <x-legal.section title="Contenus interdits">
        <ul class="list-disc space-y-2 pl-5">
            <li>produits ou services illégaux, contrefaits, dangereux ou dont la vente est interdite (armes, stupéfiants, etc.) ;</li>
            <li>contenus trompeurs, injurieux, discriminatoires, violents ou à caractère sexuel ;</li>
            <li>fausses informations, faux avis, ou tentative de manipuler les notes ;</li>
            <li>coordonnées ou liens visant à détourner les utilisateurs vers des pratiques frauduleuses.</li>
        </ul>
    </x-legal.section>

    <x-legal.section title="Photos et textes que vous publiez">
        <p>
            Vous conservez vos droits. Vous accordez à Dipla une licence non exclusive, gratuite, limitée au fonctionnement du site,
            pour héberger et afficher vos contenus (fiches, photos, description, horaires) tant que votre compte existe.
            Vous confirmez avoir le droit de publier ces contenus.
        </p>
    </x-legal.section>

    <x-legal.section title="Avis et questions des clients">
        <p>
            Vous pouvez répondre publiquement aux avis et questions. Vos réponses doivent rester courtoises et factuelles.
            Vous ne pouvez pas supprimer un avis ; pour signaler un avis abusif, utilisez le
            <a href="{{ route('contact', ['motif' => 'content']) }}" wire:navigate class="font-medium text-[#1E3D59] underline underline-offset-2 hover:text-[#16293F]">formulaire de contact</a>.
        </p>
    </x-legal.section>

    <x-legal.section title="Modération et suspension">
        <p>
            Dipla peut retirer un contenu manifestement illicite ou contraire à ces conditions, et suspendre ou supprimer un compte
            en cas de manquement, avec ou sans préavis selon la gravité.
        </p>
    </x-legal.section>

    <x-legal.section title="Responsabilité">
        <p>
            Dipla ne contrôle pas chaque fiche et n'est pas responsable de l'exactitude des offres, de leur qualité,
            ni des litiges entre commerces et clients. Le site est fourni « en l'état », sans garantie de disponibilité continue.
            Vous êtes responsable des dommages causés par vos contenus ou votre non-respect de ces conditions.
        </p>
    </x-legal.section>

    <x-legal.section title="Fermeture du compte">
        <p>
            Vous pouvez supprimer votre compte à tout moment depuis les paramètres : vos produits, photos, avis et questions associés sont alors supprimés.
        </p>
    </x-legal.section>

    <x-legal.section title="Données personnelles">
        <p>
            Voir la
            <a href="{{ route('legal.privacy') }}" wire:navigate class="font-medium text-[#1E3D59] underline underline-offset-2 hover:text-[#16293F]">politique de confidentialité</a>.
            Les informations de votre devanture et de vos produits sont publiques.
        </p>
    </x-legal.section>

    <x-legal.section title="Modifications et droit applicable">
        <p>
            Ces conditions peuvent évoluer ; la date de mise à jour figure en haut de la page.
            Elles sont soumises au droit français.
        </p>
    </x-legal.section>

</x-legal.page>