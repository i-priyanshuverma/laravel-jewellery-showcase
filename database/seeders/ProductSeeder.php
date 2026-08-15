<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\StoneType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $approvedVendors = User::where('role', 'vendor')->where('status', 'approved')->get();
        if ($approvedVendors->isEmpty()) {
            return;
        }

        $categories = Category::all()->keyBy('name');
        if ($categories->isEmpty()) {
            return;
        }

        $stoneTypes = StoneType::all()->keyBy('name');

        $jewelleryCatalog = [
            // ==========================================
            // 1. RINGS (5 Products)
            // ==========================================
            [
                'name' => 'Gold Solitaire Diamond Ring',
                'category' => 'Rings',
                'description' => 'A timeless classic handcrafted in certified 18K & 22K Yellow Gold. Features a brilliant hand-selected solitaire diamond elevated in a precision 4-prong cathedral setting that maximizes light refraction and natural scintillation.',
                'image_prefix' => 'Gold-ring-01',
                'variants' => [
                    [
                        'sku' => 'GSR-18K-YG-12',
                        'metal' => 'Gold', 'purity' => '18K', 'colour' => 'Yellow Gold', 'size' => 'Size 12',
                        'weight' => 4.200, 'price' => 45000, 'stock' => 10,
                        'stones' => [['type' => 'Diamond', 'carat' => 0.500, 'clarity' => 'VS1', 'setting' => '4-Prong']],
                    ],
                    [
                        'sku' => 'GSR-22K-YG-14',
                        'metal' => 'Gold', 'purity' => '22K', 'colour' => 'Yellow Gold', 'size' => 'Size 14',
                        'weight' => 5.100, 'price' => 58000, 'stock' => 8,
                        'stones' => [['type' => 'Diamond', 'carat' => 0.500, 'clarity' => 'VVS2', 'setting' => '4-Prong']],
                    ],
                ],
            ],
            [
                'name' => 'Brilliant Halo Diamond Ring',
                'category' => 'Rings',
                'description' => 'A show-stopping halo ring showcasing a center brilliant-cut diamond encircled by a micro-pavé halo in 18K White & Rose Gold.',
                'image_prefix' => 'Diamond-ring-01',
                'variants' => [
                    [
                        'sku' => 'BHD-18K-WG-12',
                        'metal' => 'Gold', 'purity' => '18K', 'colour' => 'White Gold', 'size' => 'Size 12',
                        'weight' => 4.800, 'price' => 72000, 'stock' => 5,
                        'stones' => [
                            ['type' => 'Diamond', 'carat' => 0.750, 'clarity' => 'VVS1', 'setting' => 'Prong'],
                            ['type' => 'Diamond', 'carat' => 0.350, 'clarity' => 'VS1', 'setting' => 'Micro-Pavé'],
                        ],
                    ],
                    [
                        'sku' => 'BHD-18K-RG-14',
                        'metal' => 'Gold', 'purity' => '18K', 'colour' => 'Rose Gold', 'size' => 'Size 14',
                        'weight' => 4.900, 'price' => 74000, 'stock' => 4,
                        'stones' => [
                            ['type' => 'Diamond', 'carat' => 0.750, 'clarity' => 'VVS1', 'setting' => 'Prong'],
                            ['type' => 'Diamond', 'carat' => 0.350, 'clarity' => 'VS1', 'setting' => 'Micro-Pavé'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Royal Blue Sapphire Gold Ring',
                'category' => 'Rings',
                'description' => 'An oval-cut Ceylon royal blue sapphire takes center stage, flanked by tapered baguette diamonds in solid 18K Yellow Gold.',
                'image_prefix' => 'Blue-Gold-ring-01',
                'variants' => [
                    [
                        'sku' => 'RBS-18K-YG-12',
                        'metal' => 'Gold', 'purity' => '18K', 'colour' => 'Yellow Gold', 'size' => 'Size 12',
                        'weight' => 5.200, 'price' => 88000, 'stock' => 3,
                        'stones' => [
                            ['type' => 'Sapphire', 'carat' => 1.800, 'clarity' => 'Eye Clean', 'setting' => 'Prong'],
                            ['type' => 'Diamond', 'carat' => 0.400, 'clarity' => 'VS1', 'setting' => 'Channel'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Platinum Cushion Solitaire Ring',
                'category' => 'Rings',
                'description' => 'Pure 950 Platinum band holding an exceptional cushion-cut solitaire diamond with mirror-finish comfort fit shank.',
                'image_prefix' => 'Platinum-ring-01',
                'variants' => [
                    [
                        'sku' => 'PCS-950-PT-12',
                        'metal' => 'Platinum', 'purity' => '950 Platinum', 'colour' => 'Platinum', 'size' => 'Size 12',
                        'weight' => 6.500, 'price' => 110000, 'stock' => 4,
                        'stones' => [['type' => 'Diamond', 'carat' => 1.000, 'clarity' => 'VVS1', 'setting' => '4-Prong']],
                    ],
                ],
            ],
            [
                'name' => 'Silver Artisan Heritage Ring',
                'category' => 'Rings',
                'description' => 'Hand-engraved 925 Sterling Silver ring with filigree scrollwork and a bezel-set untreated natural gemstone.',
                'image_prefix' => 'Silver-ring-01',
                'variants' => [
                    [
                        'sku' => 'SAH-925-SL-14',
                        'metal' => 'Silver', 'purity' => '925 Silver', 'colour' => 'Silver', 'size' => 'Size 14',
                        'weight' => 7.200, 'price' => 8500, 'stock' => 15,
                        'stones' => [['type' => 'Topaz', 'carat' => 2.200, 'clarity' => 'VVS', 'setting' => 'Bezel']],
                    ],
                ],
            ],

            // ==========================================
            // 2. BANGLES (10 Products)
            // ==========================================
            [
                'name' => 'Heritage 22K Gold Temple Kada Bangle',
                'category' => 'Bangles',
                'description' => 'Masterpiece 22K Yellow Gold temple kada featuring antique hand-repoussé Nakshi work depicting peacocks and floral vines.',
                'image_prefix' => 'Gold-bangle-01',
                'variants' => [
                    [
                        'sku' => 'HGK-22K-YG-24',
                        'metal' => 'Gold', 'purity' => '22K', 'colour' => 'Yellow Gold', 'size' => '2.4',
                        'weight' => 28.500, 'price' => 210000, 'stock' => 3,
                        'stones' => [['type' => 'Ruby', 'carat' => 0.800, 'clarity' => 'Good', 'setting' => 'Bezel']],
                    ],
                    [
                        'sku' => 'HGK-22K-YG-26',
                        'metal' => 'Gold', 'purity' => '22K', 'colour' => 'Yellow Gold', 'size' => '2.6',
                        'weight' => 31.000, 'price' => 230000, 'stock' => 2,
                        'stones' => [['type' => 'Ruby', 'carat' => 0.800, 'clarity' => 'Good', 'setting' => 'Bezel']],
                    ],
                ],
            ],
            [
                'name' => 'Brilliant Diamond Eternity Bangle',
                'category' => 'Bangles',
                'description' => 'A continuous circlet of brilliant-cut diamonds prong-set in lustrous 18K White Gold with hidden safety box clasp.',
                'image_prefix' => 'Diamond-bangle-01',
                'variants' => [
                    [
                        'sku' => 'BDE-18K-WG-24',
                        'metal' => 'Gold', 'purity' => '18K', 'colour' => 'White Gold', 'size' => '2.4',
                        'weight' => 16.200, 'price' => 175000, 'stock' => 4,
                        'stones' => [['type' => 'Diamond', 'carat' => 2.500, 'clarity' => 'VS1', 'setting' => 'Channel']],
                    ],
                ],
            ],
            [
                'name' => 'Starlight Diamond Cluster Bangle',
                'category' => 'Bangles',
                'description' => 'Fine 18K yellow gold oval bangle adorned with pave-set diamond florets and side lock.',
                'image_prefix' => 'Diamond-bangle-02',
                'variants' => [
                    [
                        'sku' => 'SDC-18K-YG-24',
                        'metal' => 'Gold', 'purity' => '18K', 'colour' => 'Yellow Gold', 'size' => '2.4',
                        'weight' => 18.000, 'price' => 185000, 'stock' => 3,
                        'stones' => [['type' => 'Diamond', 'carat' => 1.800, 'clarity' => 'VVS2', 'setting' => 'Pavé']],
                    ],
                ],
            ],
            [
                'name' => 'Royal Zambian Emerald Gold Bangle',
                'category' => 'Bangles',
                'description' => 'Deep vivid green natural Zambian emeralds alternating with natural brilliant diamonds in 18K Yellow Gold.',
                'image_prefix' => 'Emerald-Gold-bangle-01',
                'variants' => [
                    [
                        'sku' => 'RZE-18K-YG-26',
                        'metal' => 'Gold', 'purity' => '18K', 'colour' => 'Yellow Gold', 'size' => '2.6',
                        'weight' => 22.400, 'price' => 195000, 'stock' => 2,
                        'stones' => [
                            ['type' => 'Emerald', 'carat' => 3.200, 'clarity' => 'Eye Clean', 'setting' => 'Prong'],
                            ['type' => 'Diamond', 'carat' => 1.100, 'clarity' => 'VS2', 'setting' => 'Prong'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Imperial Emerald Marquise Gold Bangle',
                'category' => 'Bangles',
                'description' => 'Marquise-cut lush Colombian emeralds flanked by baguette diamonds in solid 22K yellow gold.',
                'image_prefix' => 'Emerald-Gold-bangle-02',
                'variants' => [
                    [
                        'sku' => 'IEM-22K-YG-26',
                        'metal' => 'Gold', 'purity' => '22K', 'colour' => 'Yellow Gold', 'size' => '2.6',
                        'weight' => 24.500, 'price' => 215000, 'stock' => 2,
                        'stones' => [
                            ['type' => 'Emerald', 'carat' => 3.800, 'clarity' => 'Fine', 'setting' => 'Bezel'],
                            ['type' => 'Diamond', 'carat' => 0.900, 'clarity' => 'VS1', 'setting' => 'Channel'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Artisanal Gold Floral Filigree Bangle',
                'category' => 'Bangles',
                'description' => 'Intricate hand-twisted gold wire filigree openwork bangle adorned with fine floral petal motifs in solid 22K yellow gold.',
                'image_prefix' => 'Gold-Floral-bangle-02',
                'variants' => [
                    [
                        'sku' => 'GFF-22K-YG-24',
                        'metal' => 'Gold', 'purity' => '22K', 'colour' => 'Yellow Gold', 'size' => '2.4',
                        'weight' => 26.000, 'price' => 188000, 'stock' => 4,
                    ],
                ],
            ],
            [
                'name' => 'Contemporary 950 Platinum Precision Bangle',
                'category' => 'Bangles',
                'description' => 'Sleek architectural solid 950 Platinum oval bangle with brushed satin finish and flush-set diamonds.',
                'image_prefix' => 'Platinum-bangle-01',
                'variants' => [
                    [
                        'sku' => 'CPP-950-PT-24',
                        'metal' => 'Platinum', 'purity' => '950 Platinum', 'colour' => 'Platinum', 'size' => '2.4',
                        'weight' => 24.000, 'price' => 185000, 'stock' => 3,
                        'stones' => [['type' => 'Diamond', 'carat' => 0.600, 'clarity' => 'VVS1', 'setting' => 'Flush']],
                    ],
                ],
            ],
            [
                'name' => 'Rose Gold Geometric Cutout Bangle',
                'category' => 'Bangles',
                'description' => 'Contemporary 18K rose gold cuff bangle featuring laser-cut geometric hexagonal lattice motifs.',
                'image_prefix' => 'Rose-Gold-Cutout-bangle-02',
                'variants' => [
                    [
                        'sku' => 'RGC-18K-RG-26',
                        'metal' => 'Gold', 'purity' => '18K', 'colour' => 'Rose Gold', 'size' => '2.6',
                        'weight' => 19.500, 'price' => 148000, 'stock' => 5,
                    ],
                ],
            ],
            [
                'name' => 'Artisan Antique 925 Silver Bangle',
                'category' => 'Bangles',
                'description' => 'Solid 925 Sterling Silver cuff bangle with oxidized tribal carvings and openable spring closure mechanism.',
                'image_prefix' => 'Silver-bangle-01',
                'variants' => [
                    [
                        'sku' => 'AAS-925-SL-24',
                        'metal' => 'Silver', 'purity' => '925 Silver', 'colour' => 'Silver', 'size' => '2.4',
                        'weight' => 35.000, 'price' => 14500, 'stock' => 12,
                    ],
                ],
            ],
            [
                'name' => 'Twisted Ribbon 925 Sterling Silver Bangle',
                'category' => 'Bangles',
                'description' => 'Graceful spiral twist silver bangle with polished and diamond-frosted intertwined bands.',
                'image_prefix' => 'Silver-Twist-bangle-02',
                'variants' => [
                    [
                        'sku' => 'TRB-925-SL-24',
                        'metal' => 'Silver', 'purity' => '925 Silver', 'colour' => 'Silver', 'size' => '2.4',
                        'weight' => 28.000, 'price' => 12500, 'stock' => 10,
                    ],
                ],
            ],

            // ==========================================
            // 3. NECKLACES (5 Products)
            // ==========================================
            [
                'name' => 'Gold Classic Choker Necklace',
                'category' => 'Necklaces',
                'description' => 'A regal solid 22K Gold choker necklace with handcrafted floral lattice elements and delicate hanging gold bead drops.',
                'image_prefix' => 'Gold-necklace-01',
                'variants' => [
                    [
                        'sku' => 'GCN-22K-YG-16',
                        'metal' => 'Gold', 'purity' => '22K', 'colour' => 'Yellow Gold', 'size' => '16 Inch',
                        'weight' => 42.000, 'price' => 320000, 'stock' => 2,
                    ],
                ],
            ],
            [
                'name' => 'Diamond Tennis Choker Necklace',
                'category' => 'Necklaces',
                'description' => 'Graduated rivière diamond tennis choker necklace in 18K White Gold, individually four-prong basket set.',
                'image_prefix' => 'Diamond-necklace-01',
                'variants' => [
                    [
                        'sku' => 'DTC-18K-WG-16',
                        'metal' => 'Gold', 'purity' => '18K', 'colour' => 'White Gold', 'size' => '16 Inch',
                        'weight' => 28.000, 'price' => 450000, 'stock' => 1,
                        'stones' => [['type' => 'Diamond', 'carat' => 6.500, 'clarity' => 'VVS2', 'setting' => '4-Prong']],
                    ],
                ],
            ],
            [
                'name' => 'Emerald Gold Royal Heritage Necklace',
                'category' => 'Necklaces',
                'description' => 'Heirloom Nizam-inspired necklace pairing deep Colombian emerald drops with uncut Polki diamond settings.',
                'image_prefix' => 'Emerald-Gold-necklace-01',
                'variants' => [
                    [
                        'sku' => 'EGN-22K-YG-18',
                        'metal' => 'Gold', 'purity' => '22K', 'colour' => 'Yellow Gold', 'size' => '18 Inch',
                        'weight' => 54.000, 'price' => 520000, 'stock' => 1,
                        'stones' => [
                            ['type' => 'Emerald', 'carat' => 8.500, 'clarity' => 'Eye Clean', 'setting' => 'Bezel'],
                            ['type' => 'Polki', 'carat' => 4.200, 'clarity' => 'Natural', 'setting' => 'Kundan'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Silver Filigree Heritage Necklace',
                'category' => 'Necklaces',
                'description' => 'Authentic Cuttack Tarakasi silver filigree bib necklace crafted in fine 925 sterling silver.',
                'image_prefix' => 'Silver-necklace-01',
                'variants' => [
                    [
                        'sku' => 'SFN-925-SL-18',
                        'metal' => 'Silver', 'purity' => '925 Silver', 'colour' => 'Silver', 'size' => '18 Inch',
                        'weight' => 45.000, 'price' => 22000, 'stock' => 10,
                    ],
                ],
            ],
            [
                'name' => 'Platinum Luminary Diamond Necklace',
                'category' => 'Necklaces',
                'description' => 'A statement of pure minimalism: high-purity Pt950 platinum chain featuring a cascading triple diamond drop pendant.',
                'image_prefix' => 'Platinum-necklace-01',
                'variants' => [
                    [
                        'sku' => 'PLN-950-PT-18',
                        'metal' => 'Platinum', 'purity' => '950 Platinum', 'colour' => 'Platinum', 'size' => '18 Inch',
                        'weight' => 18.500, 'price' => 280000, 'stock' => 2,
                        'stones' => [['type' => 'Diamond', 'carat' => 1.800, 'clarity' => 'VVS1', 'setting' => 'Bezel']],
                    ],
                ],
            ],

            // ==========================================
            // 4. EARRINGS (5 Products)
            // ==========================================
            [
                'name' => 'Classic Gold Drop Earrings',
                'category' => 'Earrings',
                'description' => 'Sculpted 22K yellow gold chandelier drop earrings featuring lightweight bell tassels and floral push-back studs.',
                'image_prefix' => 'Gold-earrings-01',
                'variants' => [
                    [
                        'sku' => 'GDE-22K-YG-STD',
                        'metal' => 'Gold', 'purity' => '22K', 'colour' => 'Yellow Gold', 'size' => 'Standard',
                        'weight' => 12.400, 'price' => 95000, 'stock' => 6,
                    ],
                ],
            ],
            [
                'name' => 'Diamond Cascading Dangle Earrings',
                'category' => 'Earrings',
                'description' => 'Fluid articulative dangle earrings encrusted with round brilliant diamonds in 18K White Gold.',
                'image_prefix' => 'Diamond-earrings-01',
                'variants' => [
                    [
                        'sku' => 'DCD-18K-WG-STD',
                        'metal' => 'Gold', 'purity' => '18K', 'colour' => 'White Gold', 'size' => 'Standard',
                        'weight' => 8.200, 'price' => 145000, 'stock' => 4,
                        'stones' => [['type' => 'Diamond', 'carat' => 1.750, 'clarity' => 'VVS2', 'setting' => 'Pavé']],
                    ],
                ],
            ],
            [
                'name' => 'Emerald Gold Halo Stud Earrings',
                'category' => 'Earrings',
                'description' => 'Vibrant Zambian emerald center studs framed by micro diamond halos in 18K Yellow Gold with screw backs.',
                'image_prefix' => 'Emerald-Gold-earrings-01',
                'variants' => [
                    [
                        'sku' => 'EGH-18K-YG-STD',
                        'metal' => 'Gold', 'purity' => '18K', 'colour' => 'Yellow Gold', 'size' => 'Standard',
                        'weight' => 6.800, 'price' => 125000, 'stock' => 5,
                        'stones' => [
                            ['type' => 'Emerald', 'carat' => 1.600, 'clarity' => 'Eye Clean', 'setting' => 'Prong'],
                            ['type' => 'Diamond', 'carat' => 0.600, 'clarity' => 'VS1', 'setting' => 'Halo'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Silver Tribal Chandelier Earrings',
                'category' => 'Earrings',
                'description' => 'Bohemian oxidized 925 sterling silver tribal jhumka earrings adorned with miniature tinkling ghungroo bells.',
                'image_prefix' => 'Silver-earrings-01',
                'variants' => [
                    [
                        'sku' => 'STC-925-SL-STD',
                        'metal' => 'Silver', 'purity' => '925 Silver', 'colour' => 'Silver', 'size' => 'Standard',
                        'weight' => 24.000, 'price' => 9800, 'stock' => 14,
                    ],
                ],
            ],
            [
                'name' => 'Platinum Brilliant Diamond Studs',
                'category' => 'Earrings',
                'description' => 'Pure Pt950 platinum three-prong martini studs featuring certified round brilliant diamonds.',
                'image_prefix' => 'Platinum-earrings-01',
                'variants' => [
                    [
                        'sku' => 'PDS-950-PT-STD',
                        'metal' => 'Platinum', 'purity' => '950 Platinum', 'colour' => 'Platinum', 'size' => 'Standard',
                        'weight' => 4.000, 'price' => 125000, 'stock' => 5,
                        'stones' => [['type' => 'Diamond', 'carat' => 1.500, 'clarity' => 'VVS1', 'setting' => '3-Prong']],
                    ],
                ],
            ],

            // ==========================================
            // 5. BRACELETS (5 Products)
            // ==========================================
            [
                'name' => 'Classic 22K Gold Link Bracelet',
                'category' => 'Bracelets',
                'description' => 'A heavy, luxurious 22K Yellow Gold link bracelet handcrafted with alternating high-polish and satin finish links.',
                'image_prefix' => 'Gold-bracelet-01',
                'variants' => [
                    [
                        'sku' => 'GLB-22K-YG-7',
                        'metal' => 'Gold', 'purity' => '22K', 'colour' => 'Yellow Gold', 'size' => '7 Inch',
                        'weight' => 22.500, 'price' => 165000, 'stock' => 5,
                    ],
                ],
            ],
            [
                'name' => 'Imperial Solitaire Diamond Tennis Bracelet',
                'category' => 'Bracelets',
                'description' => 'Continuous row of laboratory-certified natural round diamonds in four-prong 18K white gold box link setting.',
                'image_prefix' => 'Diamond-bracelet-01',
                'variants' => [
                    [
                        'sku' => 'IDT-18K-WG-7',
                        'metal' => 'Gold', 'purity' => '18K', 'colour' => 'White Gold', 'size' => '7 Inch',
                        'weight' => 14.800, 'price' => 245000, 'stock' => 3,
                        'stones' => [['type' => 'Diamond', 'carat' => 3.200, 'clarity' => 'VVS2', 'setting' => '4-Prong']],
                    ],
                ],
            ],
            [
                'name' => 'Zambian Emerald Gold Crown Bracelet',
                'category' => 'Bracelets',
                'description' => 'Oval Zambian emeralds linked by diamond pavé clusters in solid 18K Yellow Gold with safety clasp.',
                'image_prefix' => 'Emerald-Gold-bracelet-01',
                'variants' => [
                    [
                        'sku' => 'EGB-18K-YG-7',
                        'metal' => 'Gold', 'purity' => '18K', 'colour' => 'Yellow Gold', 'size' => '7 Inch',
                        'weight' => 18.200, 'price' => 195000, 'stock' => 2,
                        'stones' => [
                            ['type' => 'Emerald', 'carat' => 2.800, 'clarity' => 'Eye Clean', 'setting' => 'Prong'],
                            ['type' => 'Diamond', 'carat' => 0.750, 'clarity' => 'VS1', 'setting' => 'Pavé'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Contemporary Platinum Precision Bar Bracelet',
                'category' => 'Bracelets',
                'description' => 'Architectural Pt950 platinum bar links accented with flush-set brilliant diamonds for sleek understated luxury.',
                'image_prefix' => 'Platinum-bracelet-01',
                'variants' => [
                    [
                        'sku' => 'CPB-950-PT-7',
                        'metal' => 'Platinum', 'purity' => '950 Platinum', 'colour' => 'Platinum', 'size' => '7 Inch',
                        'weight' => 19.500, 'price' => 175000, 'stock' => 4,
                        'stones' => [['type' => 'Diamond', 'carat' => 0.450, 'clarity' => 'VVS1', 'setting' => 'Flush']],
                    ],
                ],
            ],
            [
                'name' => 'Handcrafted Sterling Silver Curb Link Bracelet',
                'category' => 'Bracelets',
                'description' => 'Solid 925 sterling silver curb chain bracelet with hand-polished mirror links and custom lobster clasp.',
                'image_prefix' => 'Silver-bracelet-01',
                'variants' => [
                    [
                        'sku' => 'SCB-925-SL-8',
                        'metal' => 'Silver', 'purity' => '925 Silver', 'colour' => 'Silver', 'size' => '8 Inch',
                        'weight' => 28.000, 'price' => 11500, 'stock' => 15,
                    ],
                ],
            ],

            // ==========================================
            // 6. PENDANTS (5 Products)
            // ==========================================
            [
                'name' => 'Diamond Solitaire Classic Pendant',
                'category' => 'Pendants',
                'description' => 'Brilliant solitaire round diamond mounted in a contemporary 18K white gold four-prong floating bail setting.',
                'image_prefix' => 'Diamond-Solitaire-pendant-01',
                'variants' => [
                    [
                        'sku' => 'DSP-18K-WG-STD',
                        'metal' => 'Gold', 'purity' => '18K', 'colour' => 'White Gold', 'size' => 'Standard',
                        'weight' => 3.200, 'price' => 52000, 'stock' => 8,
                        'stones' => [['type' => 'Diamond', 'carat' => 0.600, 'clarity' => 'VVS1', 'setting' => '4-Prong']],
                    ],
                ],
            ],
            [
                'name' => 'Royal Teardrop Emerald Gold Pendant',
                'category' => 'Pendants',
                'description' => 'Lush pear-cut Colombian emerald wrapped in a halo of natural micro-pavé diamonds in 18K yellow gold.',
                'image_prefix' => 'Emerald-Gold-Teardrop-pendant-01',
                'variants' => [
                    [
                        'sku' => 'EGT-18K-YG-STD',
                        'metal' => 'Gold', 'purity' => '18K', 'colour' => 'Yellow Gold', 'size' => 'Standard',
                        'weight' => 4.500, 'price' => 78000, 'stock' => 5,
                        'stones' => [
                            ['type' => 'Emerald', 'carat' => 2.100, 'clarity' => 'Fine', 'setting' => 'Prong'],
                            ['type' => 'Diamond', 'carat' => 0.400, 'clarity' => 'VS1', 'setting' => 'Halo'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Sacred 22K Gold Om Devotional Pendant',
                'category' => 'Pendants',
                'description' => 'Auspicious sacred Om symbol sculpted in pure 22K yellow gold with diamond-cut filigree texture.',
                'image_prefix' => 'Gold-Om-pendant-01',
                'variants' => [
                    [
                        'sku' => 'GOP-22K-YG-STD',
                        'metal' => 'Gold', 'purity' => '22K', 'colour' => 'Yellow Gold', 'size' => 'Standard',
                        'weight' => 6.200, 'price' => 48000, 'stock' => 12,
                    ],
                ],
            ],
            [
                'name' => 'Platinum Diamond Heart Love Pendant',
                'category' => 'Pendants',
                'description' => 'Romantic open heart silhouette in pure 950 Platinum paved with round brilliant natural diamonds.',
                'image_prefix' => 'Platinum-Heart-pendant-01',
                'variants' => [
                    [
                        'sku' => 'PHP-950-PT-STD',
                        'metal' => 'Platinum', 'purity' => '950 Platinum', 'colour' => 'Platinum', 'size' => 'Standard',
                        'weight' => 5.400, 'price' => 86000, 'stock' => 6,
                        'stones' => [['type' => 'Diamond', 'carat' => 0.550, 'clarity' => 'VVS2', 'setting' => 'Pavé']],
                    ],
                ],
            ],
            [
                'name' => 'Silver Artisan Celtic Cross Pendant',
                'category' => 'Pendants',
                'description' => 'Intricately engraved Celtic knotwork cross crafted in 925 sterling silver with vintage patina.',
                'image_prefix' => 'Silver-Cross-pendant-01',
                'variants' => [
                    [
                        'sku' => 'SCP-925-SL-STD',
                        'metal' => 'Silver', 'purity' => '925 Silver', 'colour' => 'Silver', 'size' => 'Standard',
                        'weight' => 8.500, 'price' => 7500, 'stock' => 18,
                    ],
                ],
            ],

            // ==========================================
            // 7. CHAINS (5 Products)
            // ==========================================
            [
                'name' => 'Diamond Riviera Tennis Chain',
                'category' => 'Chains',
                'description' => 'Dazzling continuous diamond riviera chain in 18K white gold with secure double-latch box clasp.',
                'image_prefix' => 'Diamond-Tennis-chain-01',
                'variants' => [
                    [
                        'sku' => 'DTC-18K-WG-20',
                        'metal' => 'Gold', 'purity' => '18K', 'colour' => 'White Gold', 'size' => '20 Inch',
                        'weight' => 24.000, 'price' => 380000, 'stock' => 2,
                        'stones' => [['type' => 'Diamond', 'carat' => 5.000, 'clarity' => 'VVS2', 'setting' => '4-Prong']],
                    ],
                ],
            ],
            [
                'name' => 'Heavy Handcrafted 22K Gold Rope Chain',
                'category' => 'Chains',
                'description' => 'Intricate spiral rope twist chain in solid 22K yellow gold with high-polish diamond-cut surface.',
                'image_prefix' => 'Gold-Rope-chain-01',
                'variants' => [
                    [
                        'sku' => 'GRC-22K-YG-22',
                        'metal' => 'Gold', 'purity' => '22K', 'colour' => 'Yellow Gold', 'size' => '22 Inch',
                        'weight' => 24.000, 'price' => 182000, 'stock' => 4,
                    ],
                ],
            ],
            [
                'name' => 'Sleek 950 Platinum Square Box Chain',
                'category' => 'Chains',
                'description' => 'Flawless Pt950 platinum square box link chain with mirror polish and robust lobster clasp.',
                'image_prefix' => 'Platinum-Box-chain-01',
                'variants' => [
                    [
                        'sku' => 'PBC-950-PT-20',
                        'metal' => 'Platinum', 'purity' => '950 Platinum', 'colour' => 'Platinum', 'size' => '20 Inch',
                        'weight' => 18.000, 'price' => 165000, 'stock' => 4,
                    ],
                ],
            ],
            [
                'name' => 'Artisan Oxidized 925 Silver Curb Chain',
                'category' => 'Chains',
                'description' => 'Classic beveled curb link chain in 925 sterling silver treated with vintage antique dark oxidation.',
                'image_prefix' => 'Silver-Curb-chain-01',
                'variants' => [
                    [
                        'sku' => 'SCC-925-SL-20',
                        'metal' => 'Silver', 'purity' => '925 Silver', 'colour' => 'Silver', 'size' => '20 Inch',
                        'weight' => 22.000, 'price' => 8900, 'stock' => 20,
                    ],
                ],
            ],
            [
                'name' => 'Two-Tone 18K Gold & Silver Figaro Chain',
                'category' => 'Chains',
                'description' => 'Dynamic 3+1 Figaro link chain intertwining 18K yellow gold highlights with solid 925 sterling silver.',
                'image_prefix' => 'Two-Tone-Figaro-chain-01',
                'variants' => [
                    [
                        'sku' => 'TTF-18K-YG-22',
                        'metal' => 'Gold', 'purity' => '18K', 'colour' => 'Yellow Gold', 'size' => '22 Inch',
                        'weight' => 19.000, 'price' => 64000, 'stock' => 6,
                    ],
                ],
            ],

            // ==========================================
            // 8. ANKLETS (5 Products)
            // ==========================================
            [
                'name' => 'Diamond Bezel Station Gold Anklet',
                'category' => 'Anklets',
                'description' => 'Fine 18K yellow gold cable chain station anklet holding five bezel-set sparkling round diamonds.',
                'image_prefix' => 'Diamond-Station-anklet-01',
                'variants' => [
                    [
                        'sku' => 'DSA-18K-YG-10',
                        'metal' => 'Gold', 'purity' => '18K', 'colour' => 'Yellow Gold', 'size' => '10 Inch',
                        'weight' => 7.200, 'price' => 54000, 'stock' => 6,
                        'stones' => [['type' => 'Diamond', 'carat' => 0.400, 'clarity' => 'VS1', 'setting' => 'Bezel']],
                    ],
                ],
            ],
            [
                'name' => 'Auspicious 22K Gold Beaded Ball Anklet',
                'category' => 'Anklets',
                'description' => 'Traditional yellow gold bridal payal adorned with delicate hanging gold beads and secure S-hook clasp.',
                'image_prefix' => 'Gold-Bead-anklet-01',
                'variants' => [
                    [
                        'sku' => 'GBA-22K-YG-10',
                        'metal' => 'Gold', 'purity' => '22K', 'colour' => 'Yellow Gold', 'size' => '10 Inch',
                        'weight' => 18.000, 'price' => 135000, 'stock' => 4,
                    ],
                ],
            ],
            [
                'name' => 'Platinum Shimmering CZ Charm Anklet',
                'category' => 'Anklets',
                'description' => 'Delicate 950 Platinum link anklet dangling bezel charms for luminous elegance.',
                'image_prefix' => 'Platinum-CZ-anklet-01',
                'variants' => [
                    [
                        'sku' => 'PCA-950-PT-10',
                        'metal' => 'Platinum', 'purity' => '950 Platinum', 'colour' => 'Platinum', 'size' => '10 Inch',
                        'weight' => 8.500, 'price' => 72000, 'stock' => 5,
                        'stones' => [['type' => 'Zirconia', 'carat' => 0.500, 'clarity' => 'AAA', 'setting' => 'Bezel']],
                    ],
                ],
            ],
            [
                'name' => 'Traditional Silver Tribal Charm Bridal Payal',
                'category' => 'Anklets',
                'description' => 'Sonorous traditional bridal payal adorned with authentic silver ghungroo bells and filigree peacock motifs.',
                'image_prefix' => 'Silver-Charm-anklet-01',
                'variants' => [
                    [
                        'sku' => 'STP-925-SL-10',
                        'metal' => 'Silver', 'purity' => '925 Silver', 'colour' => 'Silver', 'size' => '10 Inch',
                        'weight' => 48.000, 'price' => 18500, 'stock' => 12,
                    ],
                ],
            ],
            [
                'name' => 'Two-Tone Golden Heart Dangle Anklet',
                'category' => 'Anklets',
                'description' => 'Charming anklet with alternating 18K yellow gold and silver hearts along a diamond-cut link chain.',
                'image_prefix' => 'Two-Tone-Heart-anklet-01',
                'variants' => [
                    [
                        'sku' => 'TTH-18K-YG-10',
                        'metal' => 'Gold', 'purity' => '18K', 'colour' => 'Yellow Gold', 'size' => '10 Inch',
                        'weight' => 8.000, 'price' => 42000, 'stock' => 8,
                    ],
                ],
            ],
        ];

        // Standard 5 angle suffix order
        $angleSuffixes = ['front', '3-4', 'top', 'side', 'closeup'];

        foreach ($jewelleryCatalog as $index => $item) {
            // Evenly distribute across approved vendors
            $vendor = $approvedVendors[$index % $approvedVendors->count()];
            $category = $categories->get($item['category']) ?? $categories->first();
            $slug = Str::slug($item['name']);

            $product = Product::updateOrCreate(
                [
                    'slug' => $slug,
                ],
                [
                    'name' => $item['name'],
                    'user_id' => $vendor->id,
                    'category_id' => $category->id,
                    'description' => $item['description'],
                    'status' => 'active',
                    'is_featured' => ($index < 8),
                ]
            );

            // Clean up existing images for accurate seeding
            ProductImage::where('product_id', $product->id)->forceDelete();

            // Assign exactly the 5 angle photos on disk
            foreach ($angleSuffixes as $sortOrder => $suffix) {
                $imagePath = "sample-products/{$item['image_prefix']}-{$suffix}.jpg";
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $imagePath,
                    'sort_order' => $sortOrder,
                ]);
            }

            // Seed Variants with synchronized lookup attributes and variant stones
            foreach ($item['variants'] as $v) {
                $variant = ProductVariant::updateOrCreate(
                    ['sku' => $v['sku']],
                    [
                        'product_id' => $product->id,
                        'metal' => $v['metal'],
                        'purity' => $v['purity'],
                        'colour' => $v['colour'],
                        'size' => $v['size'],
                        'weight' => $v['weight'],
                        'price' => $v['price'],
                        'stock' => $v['stock'],
                        'status' => 'active',
                    ]
                );

                // Sync stones
                $variant->stones()->delete();
                if (! empty($v['stones'])) {
                    foreach ($v['stones'] as $stone) {
                        $stModel = $stoneTypes->get($stone['type']);
                        if ($stModel) {
                            $variant->stones()->create([
                                'stone_type_id' => $stModel->id,
                                'carat_weight' => $stone['carat'] ?? null,
                                'clarity' => $stone['clarity'] ?? null,
                                'setting_type' => $stone['setting'] ?? null,
                            ]);
                        }
                    }
                }
            }
        }
    }
}
