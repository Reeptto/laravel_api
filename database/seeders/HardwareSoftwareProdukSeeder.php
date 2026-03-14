<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produk;

class HardwareSoftwareProdukSeeder extends Seeder
{
    public function run(): void
    {
        $hardware = [
            'Intel Core i5 14600K',
            'Intel Core i7 14700K',
            'AMD Ryzen 5 7600X',
            'AMD Ryzen 7 7800X3D',
            'NVIDIA RTX 4070 Super',
            'NVIDIA RTX 4080 Super',
            'AMD Radeon RX 7800 XT',
            'Corsair Vengeance 16GB DDR5',
            'G.Skill Trident Z5 32GB DDR5',
            'Kingston Fury 16GB DDR4',
            'Samsung 980 Pro 1TB NVMe SSD',
            'Samsung 990 Pro 2TB NVMe SSD',
            'WD Black SN850X 1TB SSD',
            'Seagate Barracuda 2TB HDD',
            'WD Blue 4TB HDD',
            'ASUS ROG Strix Z790 Motherboard',
            'MSI B650 Tomahawk Motherboard',
            'Gigabyte Aorus B760 Elite',
            'Corsair RM750x Power Supply',
            'Cooler Master 850W PSU',
            'NZXT H510 PC Case',
            'Lian Li O11 Dynamic Case',
            'Corsair iCUE H150i Liquid Cooler',
            'Noctua NH-D15 CPU Cooler',
            'Logitech G Pro X Keyboard',
            'Razer BlackWidow V4 Keyboard',
            'Logitech G502 Gaming Mouse',
            'Razer DeathAdder V3 Mouse',
            'SteelSeries Arctis 7 Headset',
            'HyperX Cloud II Headset',
            'Dell Ultrasharp 27 Monitor',
            'LG UltraGear 32 Gaming Monitor',
            'Samsung Odyssey G7 Monitor',
            'ASUS TUF Gaming Monitor',
            'TP-Link AX3000 WiFi Router',
            'Netgear Nighthawk AX5400 Router',
            'Synology NAS DS220+',
            'QNAP TS-264 NAS',
            'Kingston 64GB USB Flash Drive',
            'SanDisk Extreme Portable SSD'
        ];

        $software = [
            'Microsoft Windows 11 Pro',
            'Microsoft Windows 11 Home',
            'Microsoft Office 365',
            'Microsoft Office 2021',
            'Adobe Photoshop CC',
            'Adobe Illustrator CC',
            'Adobe Premiere Pro',
            'Adobe After Effects',
            'JetBrains IntelliJ IDEA Ultimate',
            'JetBrains PhpStorm',
            'JetBrains WebStorm',
            'Visual Studio Professional',
            'Visual Studio Code Premium Extensions',
            'Sublime Text License',
            'WinRAR License',
            '7-Zip Premium Tools',
            'CorelDRAW Graphics Suite',
            'AutoCAD 2024 License',
            'SketchUp Pro',
            'Blender Studio Tools',
            'Unity Pro License',
            'Unreal Engine Tools Pack',
            'Docker Desktop Pro',
            'Postman API Pro',
            'TablePlus Database Tool',
            'Navicat Premium License',
            'MySQL Enterprise License',
            'MongoDB Atlas Subscription',
            'Bitdefender Antivirus Plus',
            'Kaspersky Internet Security',
            'Norton 360 Deluxe',
            'Avast Premium Security',
            'VMware Workstation Pro',
            'Parallels Desktop License',
            'TeamViewer Business License',
            'AnyDesk Professional',
            'Notion AI Subscription',
            'Slack Pro Workspace',
            'Zoom Workplace Pro',
            'Figma Professional Plan'
        ];

        $data = [];

        $i = 1;
        foreach ($hardware as $item) {
            $data[] = [
                'kode_barang' => 'HW' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nama_barang' => $item,
                'stok' => rand(5, 50),
                'harga' => rand(500000, 25000000),
                'kategori' => 'Hardware',
                'rating' => rand(40, 50) / 10,
                'created_at' => now(),
                'updated_at' => now()
            ];
            $i++;
        }

        $j = 1;
        foreach ($software as $item) {
            $data[] = [
                'kode_barang' => 'SW' . str_pad($j, 3, '0', STR_PAD_LEFT),
                'nama_barang' => $item,
                'stok' => rand(10, 100),
                'harga' => rand(200000, 8000000),
                'kategori' => 'Software',
                'rating' => rand(40, 50) / 10,
                'created_at' => now(),
                'updated_at' => now()
            ];
            $j++;
        }

        Produk::insert($data);
    }
}