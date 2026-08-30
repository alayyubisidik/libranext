<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            // Fiksi
            ['category' => 'Fiksi', 'isbn' => '978-602-03-1234-1', 'title' => 'Laskar Pelangi', 'author' => 'Andrea Hirata', 'publisher' => 'Bentang Pustaka', 'publication_year' => 2005, 'stock' => 5, 'description' => 'Kisah inspiratif tentang sepuluh anak dari Belitung yang berjuang menggapai mimpi di tengah keterbatasan.', 'status' => 'active'],
            ['category' => 'Fiksi', 'isbn' => '978-602-03-1234-2', 'title' => 'Bumi Manusia', 'author' => 'Pramoedya Ananta Toer', 'publisher' => 'Hasta Mitra', 'publication_year' => 1980, 'stock' => 4, 'description' => 'Novel pertama dari Tetralogi Buru yang mengisahkan perjuangan Minke di masa kolonial Belanda.', 'status' => 'active'],
            ['category' => 'Fiksi', 'isbn' => '978-602-03-1234-3', 'title' => 'Negeri 5 Menara', 'author' => 'Ahmad Fuadi', 'publisher' => 'Gramedia Pustaka Utama', 'publication_year' => 2009, 'stock' => 6, 'description' => 'Perjalanan spiritual dan intelektual enam santri di pondok pesantren Gontor.', 'status' => 'active'],
            ['category' => 'Fiksi', 'isbn' => '978-602-03-1234-4', 'title' => 'Ronggeng Dukuh Paruk', 'author' => 'Ahmad Tohari', 'publisher' => 'Gramedia Pustaka Utama', 'publication_year' => 1982, 'stock' => 3, 'description' => 'Kisah Srintil, seorang ronggeng dari Dukuh Paruk, yang hidup di tengah gejolak politik Indonesia.', 'status' => 'active'],
            ['category' => 'Fiksi', 'isbn' => '978-602-03-1234-5', 'title' => 'Pulang', 'author' => 'Tere Liye', 'publisher' => 'Republika', 'publication_year' => 2015, 'stock' => 7, 'description' => 'Novel aksi tentang seorang pria yang terlibat dalam dunia bawah tanah dan perjalanannya pulang.', 'status' => 'active'],
            ['category' => 'Fiksi', 'isbn' => '978-602-03-1234-6', 'title' => 'Perahu Kertas', 'author' => 'Dee Lestari', 'publisher' => 'Bentang Pustaka', 'publication_year' => 2009, 'stock' => 5, 'description' => 'Kisah cinta dua anak muda yang dipertemukan oleh kesamaan mimpi dan semangat berkarya.', 'status' => 'active'],
            ['category' => 'Fiksi', 'isbn' => '978-602-03-1234-7', 'title' => 'Ayah', 'author' => 'Andrea Hirata', 'publisher' => 'Bentang Pustaka', 'publication_year' => 2015, 'stock' => 4, 'description' => 'Kisah seorang ayah sederhana dari Belitung yang membesarkan putrinya dengan penuh cinta.', 'status' => 'active'],

            // Non-Fiksi
            ['category' => 'Non-Fiksi', 'isbn' => '978-602-03-2234-1', 'title' => 'Sapiens: Riwayat Singkat Umat Manusia', 'author' => 'Yuval Noah Harari', 'publisher' => 'KPG', 'publication_year' => 2017, 'stock' => 5, 'description' => 'Penelusuran perjalanan umat manusia dari zaman batu hingga era modern.', 'status' => 'active'],
            ['category' => 'Non-Fiksi', 'isbn' => '978-602-03-2234-2', 'title' => 'Filosofi Teras', 'author' => 'Henry Manampiring', 'publisher' => 'Kompas', 'publication_year' => 2018, 'stock' => 8, 'description' => 'Panduan filsafat Stoa untuk menghadapi masalah sehari-hari dengan pikiran jernih.', 'status' => 'active'],
            ['category' => 'Non-Fiksi', 'isbn' => '978-602-03-2234-3', 'title' => 'Atomic Habits', 'author' => 'James Clear', 'publisher' => 'Gramedia Pustaka Utama', 'publication_year' => 2020, 'stock' => 6, 'description' => 'Cara membangun kebiasaan baik dan menghilangkan kebiasaan buruk secara efektif.', 'status' => 'active'],

            // Sains & Teknologi
            ['category' => 'Sains & Teknologi', 'isbn' => '978-602-03-3234-1', 'title' => 'A Brief History of Time', 'author' => 'Stephen Hawking', 'publisher' => 'Gramedia Pustaka Utama', 'publication_year' => 2019, 'stock' => 4, 'description' => 'Penjelasan ilmiah tentang alam semesta, waktu, dan lubang hitam untuk pembaca awam.', 'status' => 'active'],
            ['category' => 'Sains & Teknologi', 'isbn' => '978-602-03-3234-2', 'title' => 'Clean Code', 'author' => 'Robert C. Martin', 'publisher' => 'Prentice Hall', 'publication_year' => 2008, 'stock' => 3, 'description' => 'Panduan menulis kode program yang bersih, mudah dibaca, dan mudah dipelihara.', 'status' => 'active'],
            ['category' => 'Sains & Teknologi', 'isbn' => '978-602-03-3234-3', 'title' => 'The Pragmatic Programmer', 'author' => 'Andrew Hunt & David Thomas', 'publisher' => 'Addison-Wesley', 'publication_year' => 2000, 'stock' => 3, 'description' => 'Tips dan praktik terbaik bagi programmer untuk meningkatkan kualitas pekerjaan.', 'status' => 'active'],

            // Sejarah
            ['category' => 'Sejarah', 'isbn' => '978-602-03-4234-1', 'title' => 'Indonesia dalam Arus Sejarah', 'author' => 'Taufik Abdullah', 'publisher' => 'PT Ichtiar Baru van Hoeve', 'publication_year' => 2012, 'stock' => 3, 'description' => 'Kumpulan esai tentang perjalanan bangsa Indonesia dari masa prasejarah hingga masa kini.', 'status' => 'active'],
            ['category' => 'Sejarah', 'isbn' => '978-602-03-4234-2', 'title' => 'Sejarah Indonesia Modern', 'author' => 'M.C. Ricklefs', 'publisher' => 'Serambi', 'publication_year' => 2008, 'stock' => 4, 'description' => 'Kajian komprehensif sejarah Indonesia dari abad ke-16 hingga awal abad ke-21.', 'status' => 'active'],

            // Filsafat
            ['category' => 'Filsafat', 'isbn' => '978-602-03-5234-1', 'title' => 'Dunia Sophie', 'author' => 'Jostein Gaarder', 'publisher' => 'Mizan', 'publication_year' => 1996, 'stock' => 5, 'description' => 'Pengantar filsafat barat yang dikemas dalam sebuah novel misterius untuk remaja.', 'status' => 'active'],
            ['category' => 'Filsafat', 'isbn' => '978-602-03-5234-2', 'title' => 'Manusia dan Kebudayaan di Indonesia', 'author' => 'Koentjaraningrat', 'publisher' => 'Djambatan', 'publication_year' => 1985, 'stock' => 3, 'description' => 'Kajian antropologi tentang berbagai suku bangsa dan kebudayaan di Nusantara.', 'status' => 'active'],

            // Pendidikan
            ['category' => 'Pendidikan', 'isbn' => '978-602-03-6234-1', 'title' => 'Guru yang Menginspirasi', 'author' => 'Munif Chatib', 'publisher' => 'Kaifa', 'publication_year' => 2011, 'stock' => 5, 'description' => 'Panduan bagi guru untuk menciptakan pembelajaran yang bermakna dan menyenangkan.', 'status' => 'active'],
            ['category' => 'Pendidikan', 'isbn' => '978-602-03-6234-2', 'title' => 'The Element', 'author' => 'Ken Robinson', 'publisher' => 'Kaifa', 'publication_year' => 2013, 'stock' => 4, 'description' => 'Bagaimana menemukan bakat dan passion untuk mencapai potensi penuh diri sendiri.', 'status' => 'active'],

            // Ekonomi & Bisnis
            ['category' => 'Ekonomi & Bisnis', 'isbn' => '978-602-03-7234-1', 'title' => 'Rich Dad Poor Dad', 'author' => 'Robert T. Kiyosaki', 'publisher' => 'Gramedia Pustaka Utama', 'publication_year' => 2015, 'stock' => 7, 'description' => 'Pelajaran tentang uang, investasi, dan kebebasan finansial dari dua sosok ayah yang berbeda.', 'status' => 'active'],
            ['category' => 'Ekonomi & Bisnis', 'isbn' => '978-602-03-7234-2', 'title' => 'Zero to One', 'author' => 'Peter Thiel', 'publisher' => 'Gramedia Pustaka Utama', 'publication_year' => 2016, 'stock' => 4, 'description' => 'Catatan tentang startup dan cara membangun bisnis masa depan yang inovatif.', 'status' => 'active'],
            ['category' => 'Ekonomi & Bisnis', 'isbn' => '978-602-03-7234-3', 'title' => 'The Lean Startup', 'author' => 'Eric Ries', 'publisher' => 'Crown Business', 'publication_year' => 2011, 'stock' => 3, 'description' => 'Metodologi untuk membangun bisnis dan produk baru secara efisien dan adaptif.', 'status' => 'active'],

            // Agama & Spiritualitas
            ['category' => 'Agama & Spiritualitas', 'isbn' => '978-602-03-8234-1', 'title' => 'La Tahzan', 'author' => 'Dr. Aidh al-Qarni', 'publisher' => 'Qisthi Press', 'publication_year' => 2004, 'stock' => 6, 'description' => 'Kumpulan nasihat dan renungan untuk menenangkan hati dan menjalani hidup dengan ikhlas.', 'status' => 'active'],
            ['category' => 'Agama & Spiritualitas', 'isbn' => '978-602-03-8234-2', 'title' => 'Dalam Dekapan Ukhuwah', 'author' => 'Salim A. Fillah', 'publisher' => 'Pro-U Media', 'publication_year' => 2010, 'stock' => 4, 'description' => 'Refleksi tentang persaudaraan Islam, persahabatan sejati, dan kebersamaan dalam iman.', 'status' => 'active'],

            // Psikologi
            ['category' => 'Psikologi', 'isbn' => '978-602-03-9234-1', 'title' => 'Ikigai', 'author' => 'Hector Garcia & Francesc Miralles', 'publisher' => 'Gramedia Pustaka Utama', 'publication_year' => 2019, 'stock' => 5, 'description' => 'Rahasia orang Jepang untuk menemukan makna hidup dan kebahagiaan sejati.', 'status' => 'active'],
            ['category' => 'Psikologi', 'isbn' => '978-602-03-9234-2', 'title' => 'Berani Tidak Disukai', 'author' => 'Ichiro Kishimi & Fumitake Koga', 'publisher' => 'Gramedia Pustaka Utama', 'publication_year' => 2019, 'stock' => 6, 'description' => 'Dialog filosofis tentang ajaran psikologi Adler dan kebebasan untuk menjadi diri sendiri.', 'status' => 'active'],
            ['category' => 'Psikologi', 'isbn' => '978-602-03-9234-3', 'title' => 'Man\'s Search for Meaning', 'author' => 'Viktor E. Frankl', 'publisher' => 'Noura Books', 'publication_year' => 2016, 'stock' => 4, 'description' => 'Pengalaman seorang psikiater bertahan hidup di kamp konsentrasi Nazi dan menemukan makna hidup.', 'status' => 'active'],

            // Seni & Budaya
            ['category' => 'Seni & Budaya', 'isbn' => '978-602-03-0234-1', 'title' => 'Nyanyi Sunyi Seorang Bisu', 'author' => 'Pramoedya Ananta Toer', 'publisher' => 'Hasta Mitra', 'publication_year' => 1995, 'stock' => 3, 'description' => 'Catatan harian dan renungan Pramoedya selama menjalani pembuangan di Pulau Buru.', 'status' => 'active'],
            ['category' => 'Seni & Budaya', 'isbn' => '978-602-03-0234-2', 'title' => 'Kebudayaan dan Kesenian Bali', 'author' => 'I Made Bandem', 'publisher' => 'BP ISI Denpasar', 'publication_year' => 2004, 'stock' => 2, 'description' => 'Kajian mendalam tentang seni pertunjukan, tradisi, dan kebudayaan masyarakat Bali.', 'status' => 'active'],
        ];

        foreach ($books as $bookData) {
            $categoryName = $bookData['category'];
            unset($bookData['category']);

            $category = Category::where('name', $categoryName)->first();
            if ($category) {
                Book::firstOrCreate(
                    ['isbn' => $bookData['isbn']],
                    array_merge($bookData, ['category_id' => $category->id])
                );
            }
        }
    }
}
