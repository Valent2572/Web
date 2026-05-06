<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PortofolioController extends Controller
{
    private function getMenus() {
        return [
            'Signature' => [
                ['name' => 'Espresso Robusta', 'description' => 'Strong & Bold', 'price' => '19k', 'image_url' => 'assets/menu_img/Espresso.jpg', 'sold_count' => 450],
                ['name' => 'Caramel Macchiato', 'description' => 'Espresso, Milk, Caramel', 'price' => '28k', 'image_url' => 'assets/menu_img/caramel.jpg', 'sold_count' => 280],
                ['name' => 'Senja Aren Latte', 'description' => 'Best Seller', 'price' => '25k', 'image_url' => 'assets/menu_img/aren.jpg', 'sold_count' => 320],
                ['name' => 'Mocha Praline', 'description' => 'Chocolate & Hazelnut', 'price' => '30k', 'image_url' => 'assets/menu_img/mocha.jpg', 'sold_count' => 0],
                ['name' => 'Americano', 'description' => 'Hot / Iced', 'price' => '20k', 'image_url' => 'assets/menu_img/americano.jpg', 'sold_count' => 0],
            ],
            'Recommendations' => [
                ['name' => 'Senja Aren Latte', 'description' => 'Espresso arabica dengan gula aren murni.', 'price' => '25k', 'image_url' => 'assets/menu_img/aren.jpg', 'sold_count' => 320],
                ['name' => 'Caramel Macchiato', 'description' => 'Perpaduan sempurna espresso, susu, dan saus karamel.', 'price' => '28k', 'image_url' => 'assets/menu_img/caramel.jpg', 'sold_count' => 280],
            ],
            'Manual Brew' => [
                ['name' => 'V60', 'description' => 'Clean & Bright', 'price' => '25k', 'image_url' => 'assets/menu_img/v60.jpg', 'sold_count' => 150],
                ['name' => 'Japanese Iced', 'description' => 'Refreshing', 'price' => '28k', 'image_url' => 'assets/menu_img/japanese.jpg', 'sold_count' => 0],
                ['name' => 'Aeropress', 'description' => 'Bold Body', 'price' => '25k', 'image_url' => 'assets/menu_img/aeropress.jpg', 'sold_count' => 0],
                ['name' => 'French Press', 'description' => 'Classic', 'price' => '22k', 'image_url' => 'assets/menu_img/frenchp.jpg', 'sold_count' => 0],
            ],
            'Non-Coffee' => [
                ['name' => 'Matcha Fusion', 'description' => 'Premium Japanese', 'price' => '30k', 'image_url' => 'assets/menu_img/matcha.jpg', 'sold_count' => 200],
                ['name' => 'Red Velvet', 'description' => 'Creamy & Sweet', 'price' => '28k', 'image_url' => 'assets/menu_img/redv.jpg', 'sold_count' => 0],
                ['name' => 'Taro Latte', 'description' => 'Sweet Potato', 'price' => '28k', 'image_url' => 'assets/menu_img/tarol.jpg', 'sold_count' => 0],
                ['name' => 'Earl Grey', 'description' => 'Artisan Tea', 'price' => '22k', 'image_url' => 'assets/menu_img/earl.jpg', 'sold_count' => 0],
                ['name' => 'Chamomile', 'description' => 'Artisan Tea', 'price' => '25k', 'image_url' => 'assets/menu_img/chamomile.jpg', 'sold_count' => 0],
            ],
            'Pastries' => [
                ['name' => 'Butter Croissant', 'description' => 'Flaky, buttery, baked fresh daily.', 'price' => '20k', 'image_url' => 'assets/menu_img/croissant.jpg', 'sold_count' => 0],
                ['name' => 'Fudge Brownie', 'description' => 'Rich, dense, and super chocolatey.', 'price' => '22k', 'image_url' => 'assets/menu_img/brownies.jpg', 'sold_count' => 0],
            ],
            'Bites' => [
                ['name' => 'Mix Platter', 'description' => 'Sausage, Nuggets, Fries', 'price' => '35k', 'image_url' => 'assets/menu_img/mixplat.jpg', 'sold_count' => 0],
                ['name' => 'Truffle Fries', 'description' => 'With Parmesan', 'price' => '28k', 'image_url' => 'assets/menu_img/fries.jpg', 'sold_count' => 0],
                ['name' => 'Singkong Keju', 'description' => 'Crispy Cassava', 'price' => '18k', 'image_url' => 'assets/menu_img/singkong.jpg', 'sold_count' => 0],
                ['name' => 'Nasi Goreng Senja', 'description' => 'Special Spices', 'price' => '40k', 'image_url' => 'assets/menu_img/nasgor.jpg', 'sold_count' => 0],
                ['name' => 'Spaghetti Carbonara', 'description' => 'Creamy & Savory', 'price' => '45k', 'image_url' => 'assets/menu_img/spaghetti.jpg', 'sold_count' => 0],
            ]
        ];
    }

    private function getBeans() {
        return [
            ['name' => 'Aceh Gayo Permata', 'roast_level' => 'Medium Roast', 'origin' => 'Sumatra, Indonesia', 'notes' => 'Earthy, dark chocolate, hint of spice', 'image_url' => 'assets/beans/aceh_gayo.jpg'],
            ['name' => 'Toraja Sapan', 'roast_level' => 'Medium-Dark Roast', 'origin' => 'Sulawesi, Indonesia', 'notes' => 'Caramel, herbal, smooth body', 'image_url' => 'assets/beans/toraja_sapan.jpg'],
            ['name' => 'Bali Kintamani', 'roast_level' => 'Light-Medium Roast', 'origin' => 'Bali, Indonesia', 'notes' => 'Citrusy, floral, bright acidity', 'image_url' => 'assets/beans/bali_kintamani.jpg'],
            ['name' => 'Ethiopia Yirgacheffe', 'roast_level' => 'Light Roast', 'origin' => 'Yirgacheffe, Ethiopia', 'notes' => 'Jasmine, blueberry, sweet lemon', 'image_url' => 'assets/beans/ethiopia.jpg'],
            ['name' => 'Colombia Supremo', 'roast_level' => 'Medium Roast', 'origin' => 'Huila, Colombia', 'notes' => 'Milk chocolate, red apple, sweet caramel', 'image_url' => 'assets/beans/colombia.jpg'],
            ['name' => 'Guatemala Antigua', 'roast_level' => 'Medium-Dark Roast', 'origin' => 'Antigua, Guatemala', 'notes' => 'Cocoa, subtle smoke, rich body', 'image_url' => 'assets/beans/guatemala.jpg'],
            ['name' => 'Kenya AA', 'roast_level' => 'Light-Medium Roast', 'origin' => 'Nyeri, Kenya', 'notes' => 'Blackberry, wine-like acidity, brown sugar', 'image_url' => 'assets/beans/kenya.jpg'],
            ['name' => 'Brazil Cerrado', 'roast_level' => 'Dark Roast', 'origin' => 'Minas Gerais, Brazil', 'notes' => 'Roasted nuts, dark chocolate, low acidity', 'image_url' => 'assets/beans/brazil.jpg'],
            ['name' => 'Costa Rica Tarrazu', 'roast_level' => 'Medium Roast', 'origin' => 'Tarrazu, Costa Rica', 'notes' => 'Honey, orange zest, clean finish', 'image_url' => 'assets/beans/costarica.jpg'],
            ['name' => 'Java Preanger', 'roast_level' => 'Medium-Dark Roast', 'origin' => 'West Java, Indonesia', 'notes' => 'Nutty, dark chocolate, syrupy body', 'image_url' => 'assets/beans/java_preanger.jpg'],
        ];
    }

    private function getContacts() {
        return [
            'whatsapp' => [
                'role' => 'Admin Reservasi',
                'person_name' => 'Yescitito Obed',
                'display_value' => '+62 822-2319-7431',
                'link_url' => 'https://wa.me/6282223197431'
            ],
            'email' => [
                'role' => 'Kerja Sama & Info',
                'person_name' => '',
                'display_value' => 'Yescitito@senjacoffee.com',
                'link_url' => 'mailto:yescitito.20236050@student.atmi.ac.id'
            ]
        ];
    }

    private function getChartData() {
        return [
            'labels' => ['Espresso Robusta', 'Aren Latte', 'Caramel Macchiato', 'Manual Brew', 'Non-Coffee'],
            'data' => [450, 320, 280, 150, 200]
        ];
    }

    public function home() {
        $beans = $this->getBeans();
        return view('home', compact('beans'));
    }

    public function service() {
        // renamed to serve the menu view but keeping the route logic
        $menus = $this->getMenus();
        return view('service', compact('menus'));
    }

    public function about() {
        $contacts = $this->getContacts();
        $chart = $this->getChartData();
        return view('about', compact('contacts', 'chart'));
    }

    public function contact() {
        // Contact page will just render the contact specific view if they want to scroll to the footer
        $contacts = $this->getContacts();
        return view('contact', compact('contacts'));
    }
}
