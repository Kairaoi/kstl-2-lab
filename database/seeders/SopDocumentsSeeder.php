<?php

namespace Database\Seeders;

use App\Models\Kstl\Document;
use App\Models\User;
use Illuminate\Database\Seeder;

class SopDocumentsSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::role(['super_admin', 'director', 'admin'])->first();
        $createdBy = $admin?->id;

        $sops = [
            // Microbiological
            ['reference_code' => 'MSOP01',   'title' => 'Total Coliforms & E. coli',          'subcategory' => 'microbiological'],
            ['reference_code' => 'MSOP02',   'title' => 'Enterococci',                         'subcategory' => 'microbiological'],
            ['reference_code' => 'MSOP06',   'title' => 'Aerobic Plate Count (APC)',           'subcategory' => 'microbiological'],
            ['reference_code' => 'MSOP07',   'title' => 'Staphylococcus aureus',               'subcategory' => 'microbiological'],
            ['reference_code' => 'MSOP08',   'title' => 'Yeast & Mould',                       'subcategory' => 'microbiological'],
            ['reference_code' => 'MSOP09',   'title' => 'E. coli / Coliform (Rapid)',          'subcategory' => 'microbiological'],
            ['reference_code' => 'MSOP10',   'title' => 'Salmonella Species',                  'subcategory' => 'microbiological'],
            ['reference_code' => 'MSOP11.A', 'title' => 'Listeria monocytogenes',              'subcategory' => 'microbiological'],
            ['reference_code' => 'MSOP11.B', 'title' => 'Listeria Species',                    'subcategory' => 'microbiological'],
            // Chemical
            ['reference_code' => 'CHMSOP01', 'title' => 'Moisture Content',                    'subcategory' => 'chemical'],
            ['reference_code' => 'CHMSOP02', 'title' => 'ELISA Histamine Rapid Kit',           'subcategory' => 'chemical'],
            ['reference_code' => 'CHMSOP03', 'title' => 'pH Determination',                    'subcategory' => 'chemical'],
            ['reference_code' => 'CHMSOP04', 'title' => 'Conductivity',                        'subcategory' => 'chemical'],
            ['reference_code' => 'CHMSOP05', 'title' => 'Water Activity (aw)',                 'subcategory' => 'chemical'],
        ];

        foreach ($sops as $sop) {
            Document::firstOrCreate(
                ['reference_code' => $sop['reference_code'], 'category' => 'sop'],
                [
                    'title'       => $sop['title'],
                    'subcategory' => $sop['subcategory'],
                    'description' => 'Standard Operating Procedure — ' . $sop['reference_code'],
                    'created_by'  => $createdBy,
                ]
            );
        }
    }
}
