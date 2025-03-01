<?php

namespace Database\Seeders;

use App\Models\KeyDocument;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class KeyDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Loop to create 10 records
        for ($i = 0; $i < 10; $i++) {
            // Upload the file using the fileUpload helper
            $filePath  = 'backend/images/key_document/document.pdf';;
            // Insert record into the database with the file path
            KeyDocument::create([
                'document' => $filePath,
            ]);
        }
    }
}
