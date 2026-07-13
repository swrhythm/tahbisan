<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class TahbisanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(AdminSeeder::class);

        $event1 = Event::create([
            'date' => '2026-10-12',
            'jam' => '09.00 WIB',
            'lokasi' => 'Gereja Katedral, Jakarta',
        ]);

        // Shared schedule, set by the admin — visible to every candidate in this event.
        $event1->scheduleItems()->create([
            'tanggal' => '2026-10-12',
            'jam' => '09:00',
            'acara' => 'Misa Tahbisan Diakon',
            'lokasi' => 'Gereja Katedral, Jakarta',
            'catatan' => 'Mohon hadir 30 menit sebelum misa dimulai.',
        ]);

        $c1 = $event1->candidates()->create([
            'category' => 'diakon',
            'name' => 'Yohanes Adi Nugroho',
            'password' => 'diakon123',
            'biography' => '<p>Sejak kecil aku dibesarkan dalam keluarga sederhana di Yogyakarta, di mana doa malam bersama keluarga menjadi kebiasaan yang tak pernah putus.</p><p>Panggilan ini mulai terasa nyata ketika aku aktif menjadi misdinar di paroki, dan semakin diteguhkan sepanjang masa pendidikan di seminari.</p><p>Dengan penuh syukur, aku melangkah menuju tahbisan diakon, percaya bahwa inilah jalan yang Tuhan siapkan bagiku.</p>',
        ]);

        // Personal schedule, set by the candidate themself — only shown on their own timeline.
        $c1->scheduleItems()->createMany([
            [
                'tanggal' => '2026-06-01',
                'jam' => '08:00',
                'acara' => 'Retret Pra-Tahbisan',
                'lokasi' => 'Wisma Retret Girisonta',
                'catatan' => null,
            ],
            [
                'tanggal' => '2026-10-05',
                'jam' => '19:00',
                'acara' => 'Pertemuan Komunitas',
                'lokasi' => 'Rumah Retret Keuskupan',
                'catatan' => null,
            ],
            [
                'tanggal' => '2026-10-12',
                'jam' => '12:00',
                'acara' => 'Acara Ramah Tamah',
                'lokasi' => 'Aula Paroki St. Yohanes',
                'catatan' => null,
            ],
        ]);

        $wish1 = $c1->wishlistItems()->create(['nama' => 'Alkitab Liturgi (Lectionary)', 'qty' => 1]);
        $wish2 = $c1->wishlistItems()->create(['nama' => 'Jubah Misa Putih', 'qty' => 0]);
        $wish2->claims()->create(['nama' => 'Ibu Maria Susanti', 'qty' => 1]);
        $wish3 = $c1->wishlistItems()->create(['nama' => 'Buku Ibadat Harian', 'qty' => 5]);
        $wish3->claims()->create(['nama' => 'Bpk. Agus Santoso', 'qty' => 2]);

        $event1->candidates()->create([
            'category' => 'diakon',
            'name' => 'Bernardus Tri Wahyu',
            'password' => 'diakon456',
        ]);

        $event2 = Event::create([
            'date' => '2026-12-05',
            'jam' => '10.00 WIB',
            'lokasi' => 'Gereja Katedral, Jakarta',
        ]);

        $event2->candidates()->create([
            'category' => 'imam',
            'name' => 'Andreas Kurniawan',
            'password' => 'imam123',
        ]);
    }
}
