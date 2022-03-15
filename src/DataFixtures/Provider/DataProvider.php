<?php

namespace App\DataFixtures\Provider;

class DataProvider
{
    // Array with genres data for our Fixtures
    private $genres = [
        'Policier',
        'Fantastique',
        'Dystopie',
        'Horreur',
        'Développement personnel',
        'Erotique',
        'Shonen',
        'Thriller',
        'Science Fiction'
    ];

        // Array with reviews data for our Fixtures
        private $review = [
            'A méditer ..' => 'Ca m\'a fait pas mal réfléchir sur certains points mais c\'était quand même pas très accessible je trouve',
            'Une belle découverte' => 'On m\'en a beaucoup parlé et je ne m\'attendais pas à ça du tout je dois dire mais ça en vaut le détour, je pense que je le recommanderai autour de moi',
            'Ca aurait pu être pire' => 'J\'ai pas trouvé ça super',
            'A lire sans modération' => 'Lu, lu et relu, c\'est une merveille et je recommande !',
            'Un de ces romans qu\'on ne fait plus' => 'Vous m\'auriez dit que j\'allais apprécier je ne vous aurais pas cru',
            'A ne pas mettre entre toutes les mains' => 'Alors, c\'est non. J\'ai beau avoir essayé mais j\'ai abandonné au bout d\'une cinquaines de pages. Vraiment, très peu pour moi.',
            'Si un mollusque pouvait écrire ça aurait ressemblé à ça...' => 'Je ne comprends pas l\'engouement sur ce livre, j\'ai trouvé que c\'était pas écrit d\'une manière incroyable. Essayez quand même mais à vos risques et périls.',
            'Surprenant' => 'J\'ai été scotché tout du long. J\'aurai quand même préféré un autre procédé dans la rédaction mais je ne suis pas déçu !',
            'Ca passe' => 'Lecture sympa mais sans plus.',
            'Je recommande' => 'Pour moi la meilleure découverte littéraire de ces dernières semaines ! J\'ai vraiment a-do-ré ! A lire de toute urgence si ce n\'est pas déjà fait !!'
        ];

    
    /**
     * Return random genres
     */
    public function getBookGenre()
    {
        return $this->genres[array_rand($this->genres)];
    }

    /**
     * Return random reviews
     */
    public function getBookReview()
    {
        return $this->review[array_rand($this->review)];
    }

}