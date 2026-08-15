<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        $vendors = [
            [
                'email' => 'vendor1@jewellery.com',
                'name' => 'Rajesh Sharma',
                'business_name' => 'Royal Heritage Diamonds',
                'phone' => '+91 9820012345',
                'address' => 'Zaveri Bazaar, Mumbai, MH',
                'description' => 'Master craftsmen creating bespoke 18K and 22K gold diamond jewellery since 1985.',
                'status' => 'approved',
            ],
            [
                'email' => 'vendor2@jewellery.com',
                'name' => 'Ananya Verma',
                'business_name' => 'Luxe Carat Studio',
                'phone' => '+91 9811122233',
                'address' => 'Connaught Place, New Delhi, DL',
                'description' => 'Modern minimalist platinum and rose gold contemporary fine jewellery.',
                'status' => 'approved',
            ],
            [
                'email' => 'vendor3@jewellery.com',
                'name' => 'Vikramaditya Rao',
                'business_name' => 'Jaipur Gem Palace',
                'phone' => '+91 9414055555',
                'address' => 'Johari Bazaar, Jaipur, RJ',
                'description' => 'Authentic Kundan, Polki, and emerald traditional bridal jewellery.',
                'status' => 'approved',
            ],
            [
                'email' => 'vendor4@jewellery.com',
                'name' => 'Siddharth Mehta',
                'business_name' => 'Aura Silver & Co',
                'phone' => '+91 9723044444',
                'address' => 'CG Road, Ahmedabad, GJ',
                'description' => 'Premium 925 Sterling Silver artisan creations.',
                'status' => 'pending',
            ],
            [
                'email' => 'vendor5@jewellery.com',
                'name' => 'Karan Patel',
                'business_name' => 'Patel Gold House',
                'phone' => '+91 9898011111',
                'address' => 'Manek Chowk, Ahmedabad, GJ',
                'description' => 'Fine traditional bangles and gold chains.',
                'status' => 'suspended',
            ],
            [
                'email' => 'vendor6@jewellery.com',
                'name' => 'Meera Nambiar',
                'business_name' => 'Deccan Pearl & Jade Emporium',
                'phone' => '+91 9848012345',
                'address' => 'Charminar, Hyderabad, TS',
                'description' => 'Finest South Sea pearls, Basra pearls, and Colombian jade creations.',
                'status' => 'approved',
            ],
            [
                'email' => 'vendor7@jewellery.com',
                'name' => 'Arun Mishra',
                'business_name' => 'Varanasi Gold Guild',
                'phone' => '+91 9540023456',
                'address' => 'Thatheri Bazaar, Varanasi, UP',
                'description' => 'Sacred temple jewellery crafted in solid 22K hallmarked gold.',
                'status' => 'approved',
            ],
            [
                'email' => 'vendor8@jewellery.com',
                'name' => 'Zubair Wani',
                'business_name' => 'Kashmir Sapphire Works',
                'phone' => '+91 9419034567',
                'address' => 'Residency Road, Srinagar, JK',
                'description' => 'Natural royal blue unheated Kashmir sapphire designer pieces.',
                'status' => 'approved',
            ],
            [
                'email' => 'vendor9@jewellery.com',
                'name' => 'Bhavin Shah',
                'business_name' => 'Surat Solitaire Atelier',
                'phone' => '+91 9825045678',
                'address' => 'Varachha Road, Surat, GJ',
                'description' => 'Precision cut VVS1 certified loose diamonds and custom solitaires.',
                'status' => 'approved',
            ],
            [
                'email' => 'vendor10@jewellery.com',
                'name' => 'Gopalan Nair',
                'business_name' => 'Malabar Gold Crafters',
                'phone' => '+91 9847056789',
                'address' => 'SM Street, Kozhikode, KL',
                'description' => 'Traditional antique South Indian bridal jewellery and temple ornaments.',
                'status' => 'approved',
            ],
            [
                'email' => 'vendor11@jewellery.com',
                'name' => 'Debabrata Sen',
                'business_name' => 'Bengal Filigree Art',
                'phone' => '+91 9830067890',
                'address' => 'Bowbazar, Kolkata, WB',
                'description' => 'Delicate hand-twisted Tarakasi and gold filigree heritage collections.',
                'status' => 'approved',
            ],
            [
                'email' => 'vendor12@jewellery.com',
                'name' => 'Pooja Taneja',
                'business_name' => 'Tanishka Platinum Vault',
                'phone' => '+91 9810078901',
                'address' => 'South Extension, New Delhi, DL',
                'description' => 'High-end Pt950 platinum wedding bands and rare gemstone creations.',
                'status' => 'approved',
            ],
            [
                'email' => 'vendor13@jewellery.com',
                'name' => 'Manasvi Das',
                'business_name' => 'Orissa Silver Heritage',
                'phone' => '+91 9437089012',
                'address' => 'Nayasarak, Cuttack, OD',
                'description' => 'Centuries-old Cuttack silver filigree and ethnic jewellery.',
                'status' => 'pending',
            ],
            [
                'email' => 'vendor14@jewellery.com',
                'name' => 'Kavita Goyal',
                'business_name' => 'Chandni Chowk Gems',
                'phone' => '+91 9811090123',
                'address' => 'Dariba Kalan, Delhi, DL',
                'description' => 'Traditional Jadau, Meenakari, and uncut diamond ornaments.',
                'status' => 'pending',
            ],
            [
                'email' => 'vendor15@jewellery.com',
                'name' => 'Sunil Joshi',
                'business_name' => 'Meenakari Jewels',
                'phone' => '+91 9425001234',
                'address' => 'Sarafa Bazaar, Indore, MP',
                'description' => 'Authentic enamel and gold fusion antique ornaments.',
                'status' => 'suspended',
            ],
            [
                'email' => 'vendor16@jewellery.com',
                'name' => 'Feroz Khan',
                'business_name' => 'Royal Nizam Jewels',
                'phone' => '+91 9849012345',
                'address' => 'Banjara Hills, Hyderabad, TS',
                'description' => 'Historic Nizami Golconda diamond and emerald heirloom necklaces.',
                'status' => 'suspended',
            ],
        ];

        foreach ($vendors as $v) {
            $user = User::updateOrCreate(
                ['email' => $v['email']],
                [
                    'name' => $v['name'],
                    'password' => Hash::make('SonarVendor@2026!'),
                    'role' => 'vendor',
                    'status' => $v['status'],
                    'email_verified_at' => now(),
                ]
            );

            $user->vendorProfile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'business_name' => $v['business_name'],
                    'phone' => $v['phone'],
                    'address' => $v['address'],
                    'description' => $v['description'],
                ]
            );
        }
    }
}
