<?php

namespace App\Console\Commands\Parse;

use App\Enums\GunsEnum;
use App\Enums\QualityEnum;
use App\Enums\RarityEnum;
use App\Models\Skin;
use Illuminate\Console\Command;
use PHPHtmlParser\Dom;

class Skins extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:skins';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parsing skins';

    protected $baseUrl = 'https://csgostash.com/';

    private $costMultiplier = 0.18;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->parseByType(GunsEnum::TYPE_CZ75AUTO, 'weapon/CZ75-Auto', 'cz цз чешка');
        $this->parseByType(GunsEnum::TYPE_DESERT_EAGLE, 'weapon/Desert+Eagle', 'deagle дигл дезерт игл');
        $this->parseByType(GunsEnum::TYPE_DUAL_BERETTA, 'weapon/Dual+Berettas', 'беретты береты дуалы dual');
        $this->parseByType(GunsEnum::TYPE_FIVE_SEVEN, 'weapon/Five-SeveN', 'fs 57 five seven файв севен файв сэвэн файф');
        $this->parseByType(GunsEnum::TYPE_GLOCK, 'weapon/Glock-18', 'глок glok');
        $this->parseByType(GunsEnum::TYPE_P2000, 'weapon/P2000', 'p2000 п2000');
        $this->parseByType(GunsEnum::TYPE_P250, 'weapon/P250', 'п250');
        $this->parseByType(GunsEnum::TYPE_REVOLVER, 'weapon/R8+Revolver', 'револьвер');
        $this->parseByType(GunsEnum::TYPE_TEC9, 'weapon/Tec-9', 'тек 9 тэк 9');
        $this->parseByType(GunsEnum::TYPE_USP, 'weapon/USP-S', 'юсп юспик');

        $this->parseByType(GunsEnum::TYPE_AK47, 'weapon/AK-47', 'ака калаш калашников ак47 ak47');
        $this->parseByType(GunsEnum::TYPE_AUG, 'weapon/AUG', 'aug ауг');
        $this->parseByType(GunsEnum::TYPE_AWP, 'weapon/AWP', 'awp авп');
        $this->parseByType(GunsEnum::TYPE_FAMAS, 'weapon/FAMAS', 'famas фамас');
        $this->parseByType(GunsEnum::TYPE_G3SG1, 'weapon/G3SG1', 'g3 плетка плётка');
        $this->parseByType(GunsEnum::TYPE_GALIL, 'weapon/Galil+AR', 'галиль галил galil');
        $this->parseByType(GunsEnum::TYPE_M4A1, 'weapon/M4A1-S', 'm4a1s м4а4 эмка эска');
        $this->parseByType(GunsEnum::TYPE_M4A4, 'weapon/M4A4', 'm4a4 м4а4 без глушака без глушителя 30');
        $this->parseByType(GunsEnum::TYPE_SCAR, 'weapon/SCAR-20', 'скар скорострелка скоро стрелка');
        $this->parseByType(GunsEnum::TYPE_SG553, 'weapon/SG+553', 'сг сиг');
        $this->parseByType(GunsEnum::TYPE_SSG, 'weapon/SSG+08', 'муха скаут scout');

        $this->parseByType(GunsEnum::TYPE_MAC10, 'weapon/MAC-10', 'mac мак мак10 мак-10');
        $this->parseByType(GunsEnum::TYPE_MP5, 'weapon/MP5-SD', 'mp мп5 мп');
        $this->parseByType(GunsEnum::TYPE_MP7, 'weapon/MP7', 'mp мп7 мп');
        $this->parseByType(GunsEnum::TYPE_MP9, 'weapon/MP9', 'mp мп9 мп');
        $this->parseByType(GunsEnum::TYPE_BIZON, 'weapon/PP-Bizon', 'бизон bison bizon');
        $this->parseByType(GunsEnum::TYPE_P90, 'weapon/P90', 'п90 петух питух петушок питушок');
        $this->parseByType(GunsEnum::TYPE_UMP, 'weapon/UMP-45', 'ump юмп юмпик');

        $this->parseByType(GunsEnum::TYPE_MAG7, 'weapon/MAG-7', 'маг маг7 mag');
        $this->parseByType(GunsEnum::TYPE_NOVA, 'weapon/Nova', 'nova нова');
        $this->parseByType(GunsEnum::TYPE_SAWED_OFF, 'weapon/Sawed-Off', 'саведофф савед офф саведов sawedoff ружбайка');
        $this->parseByType(GunsEnum::TYPE_XM1014, 'weapon/XM1014', 'хм иксэм иксэмка');
        $this->parseByType(GunsEnum::TYPE_M249, 'weapon/M249', 'пулемёт м249');
        $this->parseByType(GunsEnum::TYPE_NEGEV, 'weapon/Negev', 'негев');

        $this->parseByType(GunsEnum::TYPE_KNIFE, 'weapon/Nomad+Knife', 'номад нож номад бродяга');
        $this->parseByType(GunsEnum::TYPE_KNIFE, 'weapon/Skeleton+Knife', 'скелетный скелетон скелет');
        $this->parseByType(GunsEnum::TYPE_KNIFE, 'weapon/Survival+Knife', 'выживания нож выживальщика сурвивал сурвайвал');
        $this->parseByType(GunsEnum::TYPE_KNIFE, 'weapon/Paracord+Knife', 'паракорд паракорд-нож');
        $this->parseByType(GunsEnum::TYPE_KNIFE, 'weapon/Classic+Knife', 'классик классический');
        $this->parseByType(GunsEnum::TYPE_KNIFE, 'weapon/Bayonet', 'байонет');
        $this->parseByType(GunsEnum::TYPE_KNIFE, 'weapon/Bowie+Knife', 'боуи бови');
        $this->parseByType(GunsEnum::TYPE_KNIFE, 'weapon/Butterfly+Knife', 'бабочка нож-бабочка');
        $this->parseByType(GunsEnum::TYPE_KNIFE, 'weapon/Falchion+Knife', 'фальшион фальшон');
        $this->parseByType(GunsEnum::TYPE_KNIFE, 'weapon/Flip+Knife', 'складной нож флип');
        $this->parseByType(GunsEnum::TYPE_KNIFE, 'weapon/Gut+Knife', 'нож с лезвием крюком нож с крюком');
        $this->parseByType(GunsEnum::TYPE_KNIFE, 'weapon/Huntsman+Knife', 'охотничий нож охотника');
        $this->parseByType(GunsEnum::TYPE_KNIFE, 'weapon/Karambit', 'керамбит керыч');
        $this->parseByType(GunsEnum::TYPE_KNIFE, 'weapon/M9+Bayonet', 'байонет штык м9 m9 штык-нож м9');
        $this->parseByType(GunsEnum::TYPE_KNIFE, 'weapon/Navaja+Knife', 'наваха какаха');
        $this->parseByType(GunsEnum::TYPE_KNIFE, 'weapon/Shadow+Daggers', 'тычки тычковые затычки');
        $this->parseByType(GunsEnum::TYPE_KNIFE, 'weapon/Stiletto+Knife', 'скелет скелетный');
        $this->parseByType(GunsEnum::TYPE_KNIFE, 'weapon/Talon+Knife', 'коготь');
        $this->parseByType(GunsEnum::TYPE_KNIFE, 'weapon/Ursus+Knife', 'медвежий нож');

        $this->parseByType(GunsEnum::TYPE_GLOVES, 'gloves?page=1', 'перчатки перчи перч');
        $this->parseByType(GunsEnum::TYPE_GLOVES, 'gloves?page=2', 'перчатки перчи перч');

        return 0;
    }

    protected function parseByType($type, $suffix, $aliases)
    {
        $url = $this->baseUrl . $suffix;
        $dom = new Dom();
        $dom->loadFromUrl($url);

        $containers = $dom->find('.details-link');
        /** @var Dom $container */
        foreach ($containers as $container) {
            $skinUrl = $container->find('p')->find('a')->getAttribute('href');
            $gunDom = new Dom();
            $gunDom->loadFromUrl($skinUrl);
            $inspector = $gunDom->find('.result-box');
            $skinName = strip_tags($inspector->find('h2'));
            $table = $gunDom->find('.table-hover');
            $rowsByQuality = $table->find('tbody')->find('tr');
            foreach ($rowsByQuality as $row) {
                $skin = new Skin();
                $skin->name = $skinName;
                $skin->aliases = $aliases;
                $skin->gun_type = $type;
                $skin->image = $inspector->find('.main-skin-img')->getAttribute('src');
                $skin->rarity = $this->getRarity($inspector->find('.quality'));
                $columns = $row->find('td');
                $i = 1;
                foreach ($columns as $column) {
                    if ($i == 1) {
                        $qualityName = $column->innerHtml;
                        $qualityName = strip_tags($qualityName);
                        $isStatTrack = (bool) strstr($qualityName, 'StatTrak');
                        $isSouvenir = (bool) strstr($qualityName, 'Souvenir');
                        $skin->stat_track = $isStatTrack;
                        $skin->souvenir = $isSouvenir;
                        $skin->quality = $this->getQuality($qualityName);
                        $exists = Skin::query()
                            ->where('gun_type', $skin->gun_type)
                            ->where('name', $skin->name)
                            ->where('quality', $skin->quality)
                            ->where('stat_track', $skin->stat_track)
                            ->where('souvenir', $skin->souvenir)
                            ->first();
                        if ($exists) {
                            $skin = $exists;
                        }
                    }
                    if ($i == 2) {
                        $cost = trim(strip_tags($column->innerHtml));
                        $cost = explode(' ', $cost);
                        $dirtyLast = $cost[array_key_last($cost)];
                        $dirtyLast = '1' . $dirtyLast;
                        $dirtyLast = intval($dirtyLast);
                        $dirtyLast = substr($dirtyLast, 1);
                        $cost[array_key_last($cost)] = $dirtyLast;
                        $cost = (int) implode('', $cost);
                        $skin->cost = $this->getCost($cost);
                        break;
                    }
                    $i += 1;
                }
                $skin->save();
            }
        }
    }

    protected function getQuality($qualityName)
    {
        $qualityName = trim($qualityName);
        switch ($qualityName) {
            case 'StatTrak Factory New':
            case 'Souvenir Factory New':
            case 'Factory New':
                return QualityEnum::QUALITY_FACTORY_NEW;
            case 'StatTrak Minimal Wear':
            case 'Souvenir Minimal Wear':
            case 'Minimal Wear':
                return QualityEnum::QUALITY_MINIMAL_WEAR;
            case 'StatTrak Field-Tested':
            case 'Souvenir Field-Tested':
            case 'Field-Tested':
                return QualityEnum::QUALITY_FIELD_TESTED;
            case 'StatTrak Well-Worn':
            case 'Souvenir Well-Worn':
            case 'Well-Worn':
                return QualityEnum::QUALITY_WELL_WORN;
            case 'StatTrak Battle-Scarred':
            case 'Souvenir Battle-Scarred':
            case 'Battle-Scarred':
                return QualityEnum::QUALITY_BATTLE_SCARRED;
        }

        return QualityEnum::QUALITY_FACTORY_NEW;
    }

    protected function getRarity($domElement)
    {
        $class = $domElement->getAttribute('class');
        $class = explode(' ', $class)[1];
        $class = explode('-', $class)[1];

        switch ($class) {
            case 'covert':
                return RarityEnum::GRADE_COVERT;
            case 'classified':
                return RarityEnum::GRADE_CLASSIFIED;
            case 'restricted':
                return RarityEnum::GRADE_RESTRICTED;
            case 'milspec':
                return RarityEnum::GRADE_MIL_SPEC;
            case 'industrial':
                return RarityEnum::GRADE_INDUSTRIAL;
            case 'consumer':
                return RarityEnum::GRADE_CONSUMER;
            case 'contraband':
                return RarityEnum::GRADE_CONTRABAND;
        }

        return RarityEnum::GRADE_LEGENDARY;
    }

    protected function getCost($cost)
    {
        return (int) ($cost * $this->costMultiplier);
    }
}
