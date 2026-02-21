<?php

namespace App\DataFixtures;

use App\Entity\Breed;
use App\Entity\Dog;
use App\Entity\Kennel;
use App\Entity\Litter;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $manager): void
    {
        $slugger = new AsciiSlugger();

        // ── Breeds ─────────────────────────────────────────────
        $breedsData = [
            ['Zlatý retriever', 'Golden Retriever', 'Zlatý retriever', 'VIII/1', '8', 'large'],
            ['Labrador retriever', 'Labrador Retriever', 'Labradorský retriever', 'VIII/1', '8', 'large'],
            ['Německý ovčák', 'German Shepherd', 'Nemecký ovčiak', 'I/1', '1', 'large'],
            ['Border kolie', 'Border Collie', 'Border kólia', 'I/1', '1', 'medium'],
            ['Malinois', 'Belgian Malinois', 'Malinois', 'I/1', '1', 'medium'],
            ['Husky', 'Siberian Husky', 'Sibírsky husky', 'V/1', '5', 'medium'],
            ['Rottweiler', 'Rottweiler', 'Rotvajler', 'II/1', '2', 'large'],
            ['Jezevčík', 'Dachshund', 'Jazvečík', 'IV', '4', 'small'],
            ['Yorkshire teriér', 'Yorkshire Terrier', 'Yorkšírsky teriér', 'III/4', '3', 'small'],
            ['Mops', 'Pug', 'Mops', 'IX/11', '9', 'small'],
            ['Bígel', 'Beagle', 'Bígl', 'VI/1', '6', 'medium'],
            ['Samuraj', 'Samoyed', 'Samojed', 'V/1', '5', 'large'],
            ['Shih-tzu', 'Shih Tzu', 'Shih-tzu', 'IX/5', '9', 'small'],
            ['Čivava', 'Chihuahua', 'Čivava', 'IX/6', '9', 'toy'],
            ['Dobrman', 'Dobermann', 'Doberman', 'II/1', '2', 'large'],
            ['Flat Coated Retriever', 'Flat Coated Retriever', 'Flat coated retriever', 'VIII/1', '8', 'large'],
            ['Weimarský ohař', 'Weimaraner', 'Weimarský stavač', 'VII/1', '7', 'large'],
            ['Irský setr', 'Irish Setter', 'Írsky seter', 'VII/2', '7', 'large'],
            ['Australský ovčák', 'Australian Shepherd', 'Austrálsky ovčiak', 'I/1', '1', 'medium'],
            ['Bernský salašnický pes', 'Bernese Mountain Dog', 'Bernský salašnický pes', 'II/3', '2', 'giant'],
        ];

        $breeds = [];
        foreach ($breedsData as [$cz, $en, $sk, $fic, $grp, $size]) {
            $breed = new Breed();
            $breed->setNameCz($cz)->setNameEn($en)->setNameSk($sk)
                  ->setFicCode($fic)->setGroup($grp)->setSize($size);
            $manager->persist($breed);
            $breeds[] = $breed;
        }

        // ── Admin user ──────────────────────────────────────────
        $admin = new User();
        $admin->setEmail('admin@pawbreeders.cz')
              ->setFirstName('Admin')
              ->setLastName('PaW')
              ->setRoles(['ROLE_ADMIN'])
              ->setPassword($this->hasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);

        // ── Kennel owners + kennels ─────────────────────────────
        $kennelsData = [
            [
                'email' => 'jana.novakova@email.cz',
                'first' => 'Jana', 'last' => 'Nováková',
                'name' => 'Ze Zlatého Háje',
                'breeds' => [0, 1],
                'purposes' => ['show', 'family'],
                'city' => 'Praha', 'region' => 'Praha', 'country' => 'CZE',
                'phone' => '+420 721 123 456',
                'desc_cz' => 'Jsme rodinná chovatelská stanice se zaměřením na zlaté retrievery a labradorské retrievery. Chováme psy s důrazem na zdraví, povahu a pracovní vlastnosti.',
                'desc_en' => 'We are a family kennel focused on Golden Retrievers and Labrador Retrievers.',
            ],
            [
                'email' => 'petr.dvorak@email.cz',
                'first' => 'Petr', 'last' => 'Dvořák',
                'name' => 'Z Moravských Luk',
                'breeds' => [2, 4],
                'purposes' => ['work', 'sport'],
                'city' => 'Brno', 'region' => 'Jihomoravský kraj', 'country' => 'CZE',
                'phone' => '+420 602 456 789',
                'desc_cz' => 'Stanice zaměřená na pracovní německé ovčáky a belgické malinoise. Naši psi jsou aktivní ve sportu, záchranářství i asistenční práci.',
                'desc_en' => 'Kennel focused on working German Shepherds and Belgian Malinois.',
            ],
            [
                'email' => 'marie.horakova@email.sk',
                'first' => 'Mária', 'last' => 'Horáková',
                'name' => 'Spod Tatier',
                'breeds' => [5, 11],
                'purposes' => ['show', 'sport'],
                'city' => 'Poprad', 'region' => 'Prešovský kraj', 'country' => 'SVK',
                'phone' => '+421 911 234 567',
                'desc_cz' => 'Slovenská stanice soustředěná na nordické plemena — sibiřské husky a samojedy.',
                'desc_en' => 'Slovak kennel focused on Nordic breeds — Siberian Husky and Samoyed.',
            ],
            [
                'email' => 'tomas.kral@email.cz',
                'first' => 'Tomáš', 'last' => 'Král',
                'name' => 'Z Královské Zahrady',
                'breeds' => [8, 12],
                'purposes' => ['show', 'companion'],
                'city' => 'Olomouc', 'region' => 'Olomoucký kraj', 'country' => 'CZE',
                'phone' => '+420 777 654 321',
                'desc_cz' => 'Chováme yorkšírské teriéry a shih-tzu s důrazem na charakter a zdraví.',
                'desc_en' => 'We breed Yorkshire Terriers and Shih Tzus with emphasis on character and health.',
            ],
            [
                'email' => 'lucie.cernikova@email.cz',
                'first' => 'Lucie', 'last' => 'Černíková',
                'name' => 'Od Říčních Břehů',
                'breeds' => [3, 18],
                'purposes' => ['sport', 'herding', 'work'],
                'city' => 'České Budějovice', 'region' => 'Jihočeský kraj', 'country' => 'CZE',
                'phone' => '+420 608 789 012',
                'desc_cz' => 'Border kolie a australšctí ovčáci ze správné pracovní linie.',
                'desc_en' => 'Border Collies and Australian Shepherds from proper working lines.',
            ],
            [
                'email' => 'ondrej.vesely@email.cz',
                'first' => 'Ondřej', 'last' => 'Veselý',
                'name' => 'Bavorská Hájenka',
                'breeds' => [16, 17],
                'purposes' => ['hunting', 'show'],
                'city' => 'Plzeň', 'region' => 'Plzeňský kraj', 'country' => 'CZE',
                'phone' => '+420 733 987 654',
                'desc_cz' => 'Chovatelská stanice loveckých psů — weimarský ohař a irský setr. Tradice od roku 1995.',
                'desc_en' => 'Hunting dog kennel — Weimaraner and Irish Setter. Tradition since 1995.',
            ],
        ];

        $kennelEntities = [];
        foreach ($kennelsData as $i => $kd) {
            $user = new User();
            $user->setEmail($kd['email'])
                 ->setFirstName($kd['first'])
                 ->setLastName($kd['last'])
                 ->setPassword($this->hasher->hashPassword($user, 'heslo123'));
            $manager->persist($user);

            $kennel = new Kennel();
            $kennel->setName($kd['name'])
                   ->setSlug(strtolower($slugger->slug($kd['name'])->toString()) . '-' . ($i + 1))
                   ->setOwner($user)
                   ->setCity($kd['city'])
                   ->setRegion($kd['region'])
                   ->setCountry($kd['country'])
                   ->setPhone($kd['phone'])
                   ->setEmail($kd['email'])
                   ->setPurposes($kd['purposes'])
                   ->setDescriptionCz($kd['desc_cz'])
                   ->setDescriptionEn($kd['desc_en'])
                   ->setIsActive(true)
                   ->setIsVerified($i % 2 === 0);

            foreach ($kd['breeds'] as $breedIdx) {
                $kennel->addBreed($breeds[$breedIdx]);
            }

            $manager->persist($kennel);
            $kennelEntities[] = ['kennel' => $kennel, 'breedIdx' => $kd['breeds']];
        }

        $manager->flush();

        // ── Dogs ────────────────────────────────────────────────
        $femaleNames = ['Bella', 'Luna', 'Mia', 'Nala', 'Sasha'];
        $maleNames = ['Argo', 'Max', 'Rex', 'Duke', 'Bruno'];

        foreach ($kennelEntities as $ke) {
            $kennel = $ke['kennel'];
            $breedObj = $breeds[$ke['breedIdx'][0]];

            for ($d = 0; $d < 2; $d++) {
                $isMale = $d === 0;
                $dog = new Dog();
                $dog->setName($isMale ? $maleNames[array_rand($maleNames)] : $femaleNames[array_rand($femaleNames)])
                    ->setGender($isMale ? 'male' : 'female')
                    ->setBreed($breedObj)
                    ->setKennel($kennel)
                    ->setBornAt(new \DateTime('-' . rand(2, 5) . ' years'))
                    ->setIsActive(true)
                    ->setIsStud($isMale);
                $manager->persist($dog);
            }
        }

        $manager->flush();

        // ── Litters ─────────────────────────────────────────────
        foreach ($kennelEntities as $ke) {
            $kennel = $ke['kennel'];
            $males = $kennel->getDogs()->filter(fn($d) => $d->isMale())->toArray();
            $females = $kennel->getDogs()->filter(fn($d) => !$d->isMale())->toArray();

            $litter = new Litter();
            $litter->setKennel($kennel)
                   ->setLitterLetter(chr(65 + rand(0, 3)))
                   ->setBornAt(new \DateTime('-' . rand(1, 6) . ' months'))
                   ->setTotalPuppies(rand(4, 8))
                   ->setMalePuppies(rand(2, 4))
                   ->setFemalePuppies(rand(2, 4))
                   ->setHasPuppiesAvailable(rand(0, 1) === 1)
                   ->setDescription('Zdravá štěňata z prověřených rodičů. Zápisnická, odčervená, očkovaná.')
                   ->setIsPlanned(false);
            if (!empty($females)) $litter->setMother($females[0]);
            if (!empty($males)) $litter->setFather($males[0]);
            $manager->persist($litter);
        }

        $manager->flush();
    }
}
