<?php

namespace App\DataFixtures;

use Faker;
use DateTime;
use App\Entity\Book;
use App\Entity\User;
use App\Entity\Genre;
use App\Entity\Author;
use App\Entity\Review;
use App\Entity\Reading;
use App\Entity\Favorite;
use Doctrine\DBAL\Connection;
use App\Entity\SuggestionHistory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{

    /**
     * Properties that will host services needed by the Fixtures class
     */
    private $connection;

    /**
     * Get useful services from constructor for our app
     */
    public function __construct(Connection $connection)
    {
        // Get database connection to execute manual requests (in SQL)
        $this->connection = $connection;
    }

    /**
     * Allows to TRUNCATE tables and to reset the AI to 1
     * It deletes data inside a table but not the table itself
     */
    private function truncate()
    {

        // SQL mode
        // Deactivation of the Foreign Keys constraint check

        $this->connection->executeQuery('SET foreign_key_checks = 0');
        // We truncate
        $this->connection->executeQuery('TRUNCATE TABLE author');
        $this->connection->executeQuery('TRUNCATE TABLE book');
        $this->connection->executeQuery('TRUNCATE TABLE favorite');
        $this->connection->executeQuery('TRUNCATE TABLE genre');
        $this->connection->executeQuery('TRUNCATE TABLE reading');
        $this->connection->executeQuery('TRUNCATE TABLE suggestion_history');
        $this->connection->executeQuery('TRUNCATE TABLE review');
        $this->connection->executeQuery('TRUNCATE TABLE user');
        $this->connection->executeQuery('TRUNCATE TABLE author_book');
        $this->connection->executeQuery('TRUNCATE TABLE book_genre');
    }


    public function load(ObjectManager $manager): void
    {

        // We manually truncate
        $this->truncate();

        // use the factory to create a Faker\Generator instance
        $faker = Faker\Factory::create('fr_FR');

        // To have always the same datas (algorithm)
        $faker->seed(2021);

        // Custom users for our fixtures
        $alan = new User();
        $alan->setUsername('alan');
        $alan->setPicture('https://www.zupimages.net/up/22/05/leu7.png');
        $alan->setEmail('alan@user.com');
        $alan->setRoles(['ROLE_USER']);
        $alan->setPassword('$2y$13$AhXbu/Rf/oC8pWRnZYiLsO1Uxek4tbfSA1b5hDi5hVY9g32IU0dX2');
        $alan->setCreatedAt($faker->dateTimeBetween('-3 years'));
        $alan->setUpdatedAt($faker->dateTimeBetween('-3 years'));
        $manager->persist($alan);

        $anissa = new User();
        $anissa->setUsername('anissa');
        $anissa->setPicture('https://www.zupimages.net/up/22/05/j3bw.png');
        $anissa->setEmail('anissa@user.com');
        $anissa->setRoles(['ROLE_USER']);
        $anissa->setPassword('$2y$13$XoBHKWrxmzoSd5HsrkrliuaCr1A30cVftxLkWKlKJF2qquLDtODLm');
        $anissa->setCreatedAt($faker->dateTimeBetween('-3 years'));
        $anissa->setUpdatedAt($faker->dateTimeBetween('-3 years'));
        $manager->persist($anissa);

        $virginie = new User();
        $virginie->setUsername('virginie');
        $virginie->setPicture('https://www.zupimages.net/up/22/05/c5i7.png');
        $virginie->setEmail('virginie@user.com');
        $virginie->setRoles(['ROLE_USER']);
        $virginie->setPassword('$2y$13$g4mhAHWXiGMXWOSxsgMmC.x5Y.PV06fCLfdDmq2zdV0UezRjzLSN2');
        $virginie->setCreatedAt($faker->dateTimeBetween('-3 years'));
        $virginie->setUpdatedAt($faker->dateTimeBetween('-3 years'));
        $manager->persist($virginie);
        
        $nolwenn = new User();
        $nolwenn->setUsername('nolwenn');
        $nolwenn->setPicture('https://www.zupimages.net/up/22/05/au6l.gif');
        $nolwenn->setEmail('nolwenn@user.com');
        $nolwenn->setRoles(['ROLE_USER']);
        $nolwenn->setPassword('$2y$13$dow9WTwMX.R1HqPYjbVLHe2Pg.P2ixlk09cuSRVjercqp4dbiWY6C');
        $nolwenn->setCreatedAt($faker->dateTimeBetween('-3 years'));
        $nolwenn->setUpdatedAt($faker->dateTimeBetween('-3 years'));
        $manager->persist($nolwenn);

        $olivier = new User();
        $olivier->setUsername('olivier');
        $olivier->setPicture('https://www.zupimages.net/up/22/05/2dkn.jpeg');
        $olivier->setEmail('olivier@user.com');
        $olivier->setRoles(['ROLE_USER']);
        $olivier->setPassword('$2y$13$P6pWNgUNdIMO4ub.GfDgYuUnn1G1jr1Yj/oJCTHE.IH.ulhnglYDO');
        $olivier->setCreatedAt($faker->dateTimeBetween('-3 years'));
        $olivier->setUpdatedAt($faker->dateTimeBetween('-3 years'));
        $manager->persist($olivier);

        // array with custom users for the demo
        // use it later to randomize favorite and readings
        $usersList = [
            $alan,
            $anissa,
            $nolwenn,
            $virginie,
            $olivier
        ];

        // Genres list with real datas (demo)

        $genreAnalyse = new Genre();
        $genreAnalyse->setName('Analyse de texte');
        $manager->persist($genreAnalyse);

        $genreBiographie = new Genre();
        $genreBiographie->setName('Biographie');
        $manager->persist($genreBiographie);

        $genrePolicier = new Genre();
        $genrePolicier->setName('Policier');
        $manager->persist($genrePolicier);

        $genreFantastique = new Genre();
        $genreFantastique->setName('Fantastique');
        $manager->persist($genreFantastique);

        $genreSF = new Genre();
        $genreSF->setName('Science-Fiction');
        $manager->persist($genreSF);

        $genreHorreur = new Genre();
        $genreHorreur->setName('Horreur');
        $manager->persist($genreHorreur);

        $genreDevPerso = new Genre();
        $genreDevPerso->setName('D??veloppement personnel');
        $manager->persist($genreDevPerso);

        $genreDystopie = new Genre();
        $genreDystopie->setName('Dystopie');
        $manager->persist($genreDystopie);

        $genreRoman = new Genre();
        $genreRoman->setName('Roman');
        $manager->persist($genreRoman);


        // Authors list with real datas (demo)
        
        $vincentFerre = new Author;
        $vincentFerre->setName('Vincent Ferr??');
        $manager->persist($vincentFerre);

        $tolkien = new Author;
        $tolkien->setName('John Ronald Reuel Tolkien');
        $manager->persist($tolkien);

        $jkrowling = new Author;
        $jkrowling->setName('J. K. Rowling');
        $manager->persist($jkrowling);

        $werber = new Author;
        $werber->setName('Bernard Werber');
        $manager->persist($werber);

        $lovecraft = new Author;
        $lovecraft->setName('H.P. Lovecraft');
        $manager->persist($lovecraft);

        $agathaChristie = new Author;
        $agathaChristie->setName('Agatha Christie');
        $manager->persist($agathaChristie);

        $fabienOlicard = new Author;
        $fabienOlicard->setName('Fabien Olicard');
        $manager->persist($fabienOlicard);

        $suzanneCollins = new Author;
        $suzanneCollins->setName('Suzanne Collins');
        $manager->persist($suzanneCollins);

        $jamesDashner = new Author;
        $jamesDashner->setName('James Dashner');
        $manager->persist($jamesDashner);

        // Custom book data fixtures for the demo

        $book1 = new Book();
        $book1->setIsbn("9782266242912");
        $book1->setTitle('Lire J. R. R. Tolkien');
        $book1->setDescription('Comment Tolkien aurait-il jug?? les adaptations cin??matographiques de son oeuvre ? Pourquoi le roi Arthur est-il cach?? au coeur de son univers fictionnel ? Qui a ??crit Le Seigneur des Anneaux, dont l\'histoire se d??roule... avant m??me l\'invention de l\'??criture ? Turin est-il le fr??re de Tristan ? Que lire de Tolkien, lorsque l\'on a aim?? Le Hobbit et Le Seigneur des Anneaux ? Pourquoi a-t-il marqu?? si durablement J.K. Rowling et G.R.R. Martin ? Ce livre propose quelques cl??s pour comprendre la cr??ation de la Terre du Milieu -dont l\'imaginaire s\'appuie sur une connaissance parfaite de textes m??di??vaux-, mais aussi pour mieux appr??cier la fantasy moderne, qui doit tant ?? Tolkien ! Une invitation, faite ?? tous, amateurs ou non, de lire J.R.R. Tolkien.');
        $book1->setCover('https://products-images.di-static.com/image/vincent-ferre-lire-j/9782266242912-475x500-1.webp');
        $book1->setPublisher('Pocket');
        $book1->setPublicationDate(new \DateTime('2014'));
        $book1->setSubtitle('Tout ce que vous ne savez pas encore sur le Seigneur des Anneaux');
        $book1->setMatureRating(false);
        $book1->addAuthor($vincentFerre);
        $book1->addGenre($genreAnalyse);
        $manager->persist($book1);

        $book2 = new Book();
        $book2->setIsbn("9782266166041");
        $book2->setTitle('Lettres');
        $book2->setDescription('J. R. R. Tolkien, cr??ateur de la Terre du Milieu et de l\'univers du Seigneur des Anneaux, de Bilbo le Hobbit et du Silmarillion, fut l\'auteur de l\'une des correspondances les plus prolifiques du XXe si??cle. Pendant soixante ans, il ??crivit ?? ses ??diteurs, ?? sa femme et ?? ses enfants, ?? ses amis (C. S. Lewis, W. H. Auden, pour les plus c??l??bres) ainsi qu\'aux admirateurs de ses livres. Ces Lettres constituent un portrait fascinant et plein de nuances de l\'homme sous toutes ses facettes - comme conteur, p??re, universitaire ?? Oxford, croyant et observateur du monde moderne - et relatent la gen??se de ses ??uvres magistrales. Ce volume ??claire de mani??re irrempla??able le g??nie cr??ateur de J. R. R. Tolkien et l\'extraordinaire architecture, pens??e et pr??vue dans ses moindres d??tails, du monde du Seigneur des Anneaux.');
        $book2->setCover('https://products-images.di-static.com/image/john-ronald-reuel-tolkien-lettres/9782266166041-475x500-1.webp');
        $book2->setPublisher('Pocket');
        $book2->setPublicationDate(new \DateTime('2005'));
        $book2->setMatureRating(false);
        $book2->addAuthor($tolkien);
        $book2->addGenre($genreBiographie);
        $manager->persist($book2);

        $book3 = new Book();
        $book3->setIsbn("9782070584628");
        $book3->setTitle('Harry Potter ?? l\'??cole des sorciers');
        $book3->setSubtitle('Tome 1');
        $book3->setDescription('Le jour de ses onze ans, Harry Potter, un orphelin ??lev?? par un oncle et une tante qui le d??testent, voit son existence boulevers??e. Un g??ant vient le chercher pour l\'emmener ?? Poudlard, une ??cole de sorcellerie ! Voler en balai, jeter des sorts, combattre les trolls : Harry se r??v??le un sorcier dou??. Mais un myst??re entoure sa naissance et l\'effroyable V., le mage dont personne n\'ose prononcer le nom.');
        $book3->setCover('https://products-images.di-static.com/image/j-k-rowling-harry-potter-tome-1-harry-potter-a-l-ecole-des-sorciers/9782070584628-475x500-1.webp');
        $book3->setPublisher('Gallimard Jeunesse');
        $book3->setPublicationDate(new \DateTime('2017-10-12'));
        $book3->setMatureRating(false);
        $book3->addAuthor($jkrowling);
        $book3->addGenre($genreFantastique);
        $book3->addGenre($genreRoman);
        $manager->persist($book3);

        $book4 = new Book();
        $book4->setIsbn("9782070584642");
        $book4->setTitle('Harry Potter et la Chambre des secrets');
        $book4->setSubtitle('Tome 2');
        $book4->setDescription('Une rentr??e fracassante en voiture volante, une ??trange mal??diction qui s\'abat sur les ??l??ves, cette deuxi??me ann??e ?? l\'??cole des sorciers ne s\'annonce pas de tout repos ! Entre les cours de potions magiques, les matchs de Quidditch et les combats de mauvais sorts, Harry et ses amis Ron et Hermione trouveront-ils le temps de percer le myst??re de la Chambre des Secrets ?');
        $book4->setCover('https://products-images.di-static.com/image/j-k-rowling-harry-potter-tome-2-harry-potter-et-la-chambre-des-secrets/9782070584642-475x500-1.webp');
        $book4->setPublisher('Gallimard Jeunesse');
        $book4->setPublicationDate(new \DateTime('2017-10-12'));
        $book4->setMatureRating(false);
        $book4->addAuthor($jkrowling);
        $book4->addGenre($genreFantastique);
        $book4->addGenre($genreRoman);
        $manager->persist($book4);

        $book5 = new Book();
        $book5->setIsbn("9782253087182");
        $book5->setTitle('Bienvenue au Paradis');
        $book5->setDescription('Le Paradis ? Un jour vous aussi vous y viendrez. Alors pr??parez-vous au Jugement dernier. Il y aura un avocat (votre ange gardien), un procureur (votre d??mon) et un juge (de pr??f??rence impartial). Mais les valeurs au Paradis ne sont pas les m??mes que sur Terre. Anatole Pichon va en faire l\'amusante exp??rience.');
        $book5->setCover('https://products-images.di-static.com/image/bernard-werber-bienvenue-au-paradis/9782253087182-475x500-1.webp');
        $book5->setPublisher('LGF/Le Livre de Poche');
        $book5->setPublicationDate(new \DateTime('2015-05-06'));
        $book5->setMatureRating(false);
        $book5->addAuthor($werber);
        $book5->addGenre($genreSF);
        $book5->addGenre($genreRoman);
        $manager->persist($book5);

        $book6 = new Book();
        $book6->setIsbn("9782352949107");
        $book6->setTitle('Cthulhu : Le Mythe');
        $book6->setSubtitle('Tome 1');
        $book6->setDescription('Howard Phillips Lovecraft est sans nul doute l???auteur fantastique le plus influent du XXe si??cle. Son imaginaire unique et terrifiant n???a cess?? d???inspirer des g??n??rations d?????crivains, de cin??astes, d???artistes ou de cr??ateurs d???univers de jeux, de Neil Gaiman ?? Michel Houellebecq en passant par Metallica. Le Mythe de Cthulhu est au c??ur de cette ??uvre : un panth??on de dieux et d?????tres monstrueux venus du cosmos et de la nuit des temps ressurgissent pour reprendre possession de notre monde. Ceux qui en sont t??moins sont vou??s ?? la folie et ?? la destruction. Les neuf r??cits essentiels du mythe sont ici r??unis dans une toute nouvelle traduction. ?? votre tour, vous allez pousser la porte de la vieille b??tisse hant??e qu???est la Maison de la Sorci??re, rejoindre un myst??rieux festival o?? l???on c??l??bre un rite impie, d??couvrir une cit?? antique enfouie sous le sable, ou ??chouer dans une ville portuaire d??peupl??e dont les derniers habitants sont atrocement d??form??s... Ce recueil inclut des illustrations originales ainsi que le portfolio ?? Les terres de Lovecraft en images ?? : 16 pages de photographies des paysages et des lieux dont s???est inspir?? le ma??tre de l???effroi. Le mythe de Cthulhu n???a jamais ??t?? aussi r??el.');
        $book6->setCover('https://products-images.di-static.com/image/h-p-lovecraft-cthulhu-le-mythe-tome-1/9782352949107-475x500-1.webp');
        $book6->setPublisher('Bragelonne');
        $book6->setPublicationDate(new \DateTime('2015-10-21'));
        $book6->setMatureRating(false);
        $book6->addAuthor($lovecraft);
        $book6->addGenre($genreHorreur);
        $book6->addGenre($genreRoman);
        $manager->persist($book6);

        $book7 = new Book();
        $book7->setIsbn("9782702435830");
        $book7->setTitle('Le meurtre de Roger Ackroyd (Nouvelle traduction r??vis??e)');
        $book7->setDescription('Roger Ackroyd se confie un soir ?? son vieil ami le Dr Sheppard. Il ??tait sur le point d?????pouser une jeune et richissime veuve quand celle-ci a mis fin ?? ses jours pour ??chapper ?? un affreux chantage. Dans sa derni??re lettre elle lui livre un secret terrible : un an plus t??t, elle a assassin?? son mari ! Traduit de l???anglais par Fran??oise Jamoul.');
        $book7->setCover('https://products-images.di-static.com/image/agatha-christie-le-meurtre-de-roger-ackroyd/9782702435830-475x500-1.webp');
        $book7->setPublisher('Masque');
        $book7->setPublicationDate(new \DateTime('2011-04-27'));
        $book7->setMatureRating(false);
        $book7->addAuthor($agathaChristie);
        $book7->addGenre($genrePolicier);
        $book7->addGenre($genreRoman);
        $manager->persist($book7);

        $book8 = new Book();
        $book8->setIsbn("9782412049402");
        $book8->setTitle('Votre temps est infini');
        $book8->setSubtitle('Et si votre journ??e ??tait plus longue que vous ne le pensiez ?');
        $book8->setDescription('Fabien Olicard est un s??rieux procrastinateur abstinent... C\'est justement pourquoi il sait mieux que personne par o?? commencer pour devenir le meilleur de soi-m??me. Il vous raconte ici ses exp??riences, celles qui lui ont permis d\'avancer, celles qui l\'ont fait r??fl??chir, et bien s??r toute la m??thode qu\'il applique d??sormais dans son quotidien. D??couvrez avec lui vos propres mantras, faites le tri dans votre vie et devenez aussi productif qu\'??panoui ! Et surtout, suivez ses conseils et ses hacks pour gagner du temps ?? chaque instant. En appliquant cette m??thode, Fabien Olicard a r??ussi simultan??ment, en 3 ans, ?? cr??er plus de 700 vid??os sur sa cha??ne YouTube, ?? ??crire 3 livres et 1 nouveau spectacle qu\'il a ??t?? jusqu\'?? autoproduire ?? l\'Olymia, ?? donner plus de 500 repr??sentations dans toute la France, tout en ayant du temps pour lui. Bref : ?? faire des choses extraordinaires qui le rendent heureux ! C\'est ?? votre tour, rejoignez le mouvement !');
        $book8->setCover('https://products-images.di-static.com/image/fabien-olicard-votre-temps-est-infini/9782412049402-475x500-1.webp');
        $book8->setPublisher('First');
        $book8->setPublicationDate(new \DateTime('2019-10-03'));
        $book8->setMatureRating(false);
        $book8->addAuthor($fabienOlicard);
        $book8->addGenre($genreDevPerso);
        $manager->persist($book8);

        $book9 = new Book();
        $book9->setIsbn("9782266260770");
        $book9->setTitle('Hunger Games');
        $book9->setSubtitle('Tome 1');
        $book9->setDescription('Dans un futur sombre, sur les ruines des Etats-Unis, un jeu t??l??vis?? est cr???? pour contr??ler le peuple par la terreur. Douze gar??ons et douze filles tir??s au sort participent ?? cette sinistre t??l??r??alit??, que tout le monde est forc?? de regarder en direct. Une seule r??gle dans l\'ar??ne : survivre, ?? tout prix. Quand sa petite soeur est appel??e pour participer aux Hunger Games, Katniss n\'h??site pas une seconde. Elle prend sa place, consciente du danger. A seize ans, Katniss a d??j?? ??t?? confront??e plusieurs fois ?? la mort. Chez elle, survivre est comme une seconde nature.');
        $book9->setCover('https://products-images.di-static.com/image/suzanne-collins-hunger-games-tome-1/9782266260770-475x500-1.webp');
        $book9->setPublisher('Pocket Jeunesse');
        $book9->setPublicationDate(new \DateTime('2015-06-04'));
        $book9->setMatureRating(false);
        $book9->addAuthor($suzanneCollins);
        $book9->addGenre($genreDystopie);
        $book9->addGenre($genreRoman);
        $manager->persist($book9);

        $book10 = new Book();
        $book10->setIsbn("9782266260787");
        $book10->setTitle('Hunger Games');
        $book10->setSubtitle('Tome 2 - L\'embrasement');
        $book10->setDescription('Apr??s le succ??s des derniers Hunger Games, le peuple de Panem est impatient de retrouver Katniss et Peeta pour la Tourn??e de la victoire. Mais pour Katniss, il s\'agit surtout d\'une tourn??e de la derni??re chance. Celle qui a os?? d??fier le Capitole est devenue le symbole d\'une r??bellion qui pourrait bien embraser Panem. Si elle ??choue ?? ramener le calme dans les districts, le pr??sident Snow n\'h??sitera pas ?? noyer dans le sang le feu de la r??volte. A l\'aube des Jeux de l\'Expiation, le pi??ge du Capitole se referme sur Katniss...');
        $book10->setCover('https://products-images.di-static.com/image/suzanne-collins-hunger-games-tome-2-l-embrasement/9782266260787-475x500-1.webp');
        $book10->setPublisher('Pocket Jeunesse');
        $book10->setPublicationDate(new \DateTime('2015-06-04'));
        $book10->setMatureRating(false);
        $book10->addAuthor($suzanneCollins);
        $book10->addGenre($genreDystopie);
        $book10->addGenre($genreRoman);
        $manager->persist($book10);

        $book11 = new Book();
        $book11->setIsbn("9782266260794");
        $book11->setTitle('Hunger Games');
        $book11->setSubtitle('Tome 3 - La r??volte');
        $book11->setDescription('Contre toute attente, Katniss a surv??cu une seconde fois aux Hunger Games. Mais le Capitole crie vengeance. Katniss doit payer les humiliations qu\'elle lui a fait subir. Et le pr??sident Snow a ??t?? tr??s clair : Katniss n\'est pas la seule ?? risquer sa vie. Sa famille, ses amis et tous les anciens habitants du district Douze sont vis??s par la col??re sanglante du pouvoir. Pour sauver les siens, Katniss doit redevenir le geai moqueur, le symbole de la r??bellion. Quel que soit le prix ?? payer.');
        $book11->setCover('https://products-images.di-static.com/image/suzanne-collins-hunger-games-tome-3-la-revolte/9782266260794-475x500-1.webp');
        $book11->setPublisher('Pocket Jeunesse');
        $book11->setPublicationDate(new \DateTime('2015-06-04'));
        $book11->setMatureRating(false);
        $book11->addAuthor($suzanneCollins);
        $book11->addGenre($genreDystopie);
        $book11->addGenre($genreRoman);
        $manager->persist($book11);

         // array with custom books for the demo
        // use it later to randomize favorites, readings and suggestion history
        $booksList = [
            $book1,
            $book2,
            $book3,
            $book4,
            $book5,
            $book6,
            $book7,
            $book8,
            $book9,
            $book10,
            $book11
        ];


        // To generate other books fixtures we use full Faker component
        // We loop to have 8 entries in the database
        for ($i = 1; $i <= 8; $i++) {

            // Favorite fixtures
            $randomFavorite = new Favorite();
            $randomFavorite->setAddedAt($faker->dateTimeBetween('-2 years'));
            $randomFavorite->setBook($booksList[mt_rand(1, 10)]);
            $randomFavorite->setUser($usersList[mt_rand(0, 4)]);

            $favoritesList[] = $randomFavorite;

            $manager->persist($randomFavorite);

            // Reading fixtures
            $randomReading = new Reading();
            $randomReading->setAddedAt($faker->dateTimeBetween('-2 years'));
            $randomReading->setBook($booksList[mt_rand(1, 10)]);
            $randomReading->setUser($usersList[mt_rand(0, 4)]);

            $readingsList[] = $randomReading;

            $manager->persist($randomReading);

        }

        for ($i = 1; $i <= 3; $i++) {
            // Suggestion fixtures
            $randomSuggestion = new SuggestionHistory();
            $randomSuggestion->setCreatedAt($faker->dateTimeBetween('-4 days'));
            $randomSuggestion->setUpdatedAt(new \Datetime('now'));
            $randomSuggestion->setBook($booksList[rand(1, 10)]);

            $suggestionsList[] = $randomSuggestion;

            $manager->persist($randomSuggestion);
        }

        $manager->flush();
    }
}