<ul>
    <li>
        Un joueur est représenté par un indice et une couleur (lors du développement de l'IA, le joueur courant est toujours considéré avec l'indice 1)
    </li>

    <li>
        Il existe deux types de planète :
        <ul>
            <li>militaire (représentée par un carré)</li>
            <li>économique (représentée par un cercle)</li>
        </ul>
    </li>

    <li>
        Chaque planète possède des coordonnées ainsi qu'une population
    </li>
    
    <h3>Les planètes économiques</h3>
    <li>
        Les planètes économiques peuvent fournir un approvisionnement (en troupe) à une planète militaire :
        <ul>
            <li>le ravitallement doit se faire vers une planète militaire</li>
            <li>à son arrivée, il incrémente le nombre de la planète avec la population de la planète économique</li>
            <li>s'il arrive sur une planète qui n'a pas le bon propriétaire, le ravitaillement échoue</li>
            <!-- ACTUELLEMENT -->
            <li>le ravitaillement est envoyé de manière automatique à la planète militaire la plus proche appartenant au même propriétaire</li>
            <!-- FUTURE
            <li>le joueur est responsable de la destination du ravitaillement, si aucune destination n'est spécifiée, aucun ravitaillement n'est fait.</li>
            -->
        </ul>
    </li>

    <h3>Les planètes militaires</h3>
    <li>
        Les planètes militaires permettent d'acquérir de nouvelles planètes
        <ul>
            <li>le joueur peut envoyer des convois militaires vers toutes les planètes</li>
            <li>si le convoi arrive sur une planète d'un propriétaire différent, on soustrait les troupes à la population locale, si la population passe à 0, la planète est acquise</li>
            <li>si le convoi arrive sur une planète du même propriétaire, on ajoute les troupes à la population locale.</li>
        </ul>
    </li>
</ul>
