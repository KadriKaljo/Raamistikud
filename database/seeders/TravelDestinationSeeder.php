<?php

namespace Database\Seeders;

use App\Models\TravelDestination;
use Illuminate\Database\Seeder;

class TravelDestinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $destinations = [
            [
                'title' => 'Kyoto',
                'image' => 'https://picsum.photos/seed/travel-kyoto/800/500',
                'description' => 'Traditsioonilised templid, aiad ja kevadine sakura teevad Kyotost ideaalse kultuuri- ja looduspuhkuse sihtkoha.',
                'country' => 'Japan',
                'best_season' => 'Spring',
            ],
            [
                'title' => 'Reykjavik',
                'image' => 'https://picsum.photos/seed/travel-reykjavik/800/500',
                'description' => 'Põhjamaa pealinn, kust on lihtne avastada geisreid, kuumaveeallikaid ja virmalisi.',
                'country' => 'Iceland',
                'best_season' => 'Winter',
            ],
            [
                'title' => 'Barcelona',
                'image' => 'https://picsum.photos/seed/travel-barcelona/800/500',
                'description' => 'Gaudi arhitektuur, Vahemere rand ja elav linnamelu teevad Barcelonast suurepärase linnapuhkuse valiku.',
                'country' => 'Spain',
                'best_season' => 'Autumn',
            ],
            [
                'title' => 'Bali',
                'image' => 'https://picsum.photos/seed/travel-bali/800/500',
                'description' => 'Troopiline loodus, riisiterrassid ja rannad sobivad nii puhkuseks kui ka aktiivseks reisiks.',
                'country' => 'Indonesia',
                'best_season' => 'Summer',
            ],
            [
                'title' => 'Queenstown',
                'image' => 'https://picsum.photos/seed/travel-queenstown/800/500',
                'description' => 'Adrenaliinisõprade lemmik: mäed, matkarajad, järved ja talispordialad ühes kohas.',
                'country' => 'New Zealand',
                'best_season' => 'Winter',
            ],
            [
                'title' => 'Lisbon',
                'image' => 'https://picsum.photos/seed/travel-lisbon/800/500',
                'description' => 'Päikeseline pealinn, ajaloolised trammitänavad ja suurepärane toidukultuur.',
                'country' => 'Portugal',
                'best_season' => 'Spring',
            ],
            [
                'title' => 'Cappadocia',
                'image' => 'https://picsum.photos/seed/travel-cappadocia/800/500',
                'description' => 'Unikaalsed kivimoodustised ja kuumaõhupallid loovad erilise ja meeldejääva reisikogemuse.',
                'country' => 'Turkey',
                'best_season' => 'Autumn',
            ],
            [
                'title' => 'Banff',
                'image' => 'https://picsum.photos/seed/travel-banff/800/500',
                'description' => 'Kanada rahvuspargi türkiissinised järved ja mägimaastik sobivad ideaalselt matkajatele.',
                'country' => 'Canada',
                'best_season' => 'Summer',
            ],
            [
                'title' => 'Santorini',
                'image' => 'https://picsum.photos/seed/travel-santorini/800/500',
                'description' => 'Valged majad, sinised kuplid ja vaated Egeuse merele pakuvad klassikalist Kreeka saarepuhkust.',
                'country' => 'Greece',
                'best_season' => 'Summer',
            ],
            [
                'title' => 'Prague',
                'image' => 'https://picsum.photos/seed/travel-prague/800/500',
                'description' => 'Ajalooline vanalinn, kindlus ja sillad teevad Prahast Euroopa klassikalise city break sihtkoha.',
                'country' => 'Czech Republic',
                'best_season' => 'Spring',
            ],
            [
                'title' => 'Marrakech',
                'image' => 'https://picsum.photos/seed/travel-marrakech/800/500',
                'description' => 'Värvikad turud, kohalik käsitöö ja maitseelamused annavad täiesti teistsuguse kultuurikogemuse.',
                'country' => 'Morocco',
                'best_season' => 'Autumn',
            ],
            [
                'title' => 'Swiss Alps',
                'image' => 'https://picsum.photos/seed/travel-swiss-alps/800/500',
                'description' => 'Alpide maastik, mägikülad ja rongireisid sobivad nii suviseks matkaks kui talviseks suusapuhkuseks.',
                'country' => 'Switzerland',
                'best_season' => 'Winter',
            ],
        ];

        foreach ($destinations as $destination) {
            TravelDestination::updateOrCreate(
                ['title' => $destination['title']],
                $destination
            );
        }
    }
}
