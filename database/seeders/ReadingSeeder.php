<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReadingModule;
use App\Models\ReadingCard;

class ReadingSeeder extends Seeder
{
    public function run()
    {
        // ==========================================
        // MODULE 1: HURUF VOKAL (Vowels)
        // ==========================================
        $m1 = ReadingModule::create(['title' => 'Huruf Vokal', 'sequence' => 1, 'icon' => '🅰️']);
        
        $vowels = [
            // Format: Content, Phonetic, Image, Audio File Name
            ['content' => 'a', 'phonetic' => 'aa', 'image' => 'ayam.png', 'audio' => 'sounds/phonics_a.mp3'],
            ['content' => 'i', 'phonetic' => 'ee', 'image' => 'itik.png', 'audio' => 'sounds/phonics_i.mp3'],
            ['content' => 'u', 'phonetic' => 'uu', 'image' => 'ular.png', 'audio' => 'sounds/phonics_u.mp3'],
            ['content' => 'e', 'phonetic' => 'eh', 'image' => 'epal.png', 'audio' => 'sounds/phonics_e.mp3'],
            ['content' => 'o', 'phonetic' => 'oh', 'image' => 'oren.png', 'audio' => 'sounds/phonics_o.mp3'],
        ];

        foreach($vowels as $index => $v) {
            ReadingCard::create([
                'reading_module_id' => $m1->id,
                'content'   => $v['content'],
                'phonetic'  => $v['phonetic'],
                'image_url' => $v['image'],
                'audio_url' => $v['audio'], // Points to your custom MP3
                'sequence'  => $index + 1
            ]);
        }

        // ==========================================
        // MODULE 2: SUKU KATA (KV)
        // ==========================================
        $m2 = ReadingModule::create(['title' => 'Suku Kata (KV)', 'sequence' => 2, 'icon' => '🧩']);
        
        $kv = ['ba', 'bi', 'bu', 'ca', 'ci', 'cu', 'da', 'di', 'du'];
        
        foreach($kv as $index => $syllable) {
            ReadingCard::create([
                'reading_module_id' => $m2->id,
                'content'   => $syllable,
                // Automatically generates filename: sounds/kv_ba.mp3, sounds/kv_bi.mp3
                'audio_url' => 'sounds/kv_' . $syllable . '.mp3', 
                'sequence'  => $index + 1
            ]);
        }
        
        // ==========================================
        // MODULE 3: SENTENCES (Ayat Mudah)
        // ==========================================
        $m3 = ReadingModule::create(['title' => 'Baca Ayat', 'sequence' => 3, 'icon' => '🗣️']);
        
        ReadingCard::create([
            'reading_module_id' => $m3->id, 
            'content'   => 'Saya suka buku', 
            'audio_url' => 'sounds/saya_suka_buku.mp3', // Your custom sentence audio
            'sequence'  => 1
        ]);

        ReadingCard::create([
            'reading_module_id' => $m3->id, 
            'content'   => 'Baju saya biru', 
            'audio_url' => 'sounds/baju_saya_biru.mp3', // Your custom sentence audio
            'sequence'  => 2
        ]);
    }
}