<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\CorePublication;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

class CorePublicationSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Loop to create 10 records
        for ($i = 0; $i < 10; $i++) {
            // Generate fake title and name
            $title = $faker->sentence;
            $name = $faker->word;

            // Generate the fake PDF content
            $fakeFile = $this->generateFakePdf($title);

            // Upload the file using the fileUpload helper
            $filePath  = 'backend/images/core_publication/document.pdf';;

            // Insert record into the database with the file path
            $corePublication = new CorePublication();
            $corePublication->title = $title;
            $corePublication->document = $filePath;
            $corePublication->save();
        }
    }

    /**
     * Generate a fake PDF file and return it as an UploadedFile instance
     *
     * @param string $title
     * @return UploadedFile
     */
    private function generateFakePdf(string $title): UploadedFile
    {
        // Content for the fake PDF (basic HTML)
        $pdfContent = "<html><body><h1>$title</h1><p>This is a fake PDF for testing purposes.</p></body></html>";

        // Create a temporary file to hold the content
        $tempFile = tempnam(sys_get_temp_dir(), 'pdf_');
        file_put_contents($tempFile, $pdfContent);

        // Return the temporary file as an UploadedFile instance
        return new UploadedFile($tempFile, 'fake_file.pdf', 'application/pdf', null, true);
    }
}
