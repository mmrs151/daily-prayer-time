<?php

class QuranRandomizer
{
    private $quran = array(
        1 => array(
                "verse" => 7,
                "english" => "Fatihah",
                "bangla" => "আল ফাতিহা"
                
            ),
        2 => array(
                "verse" => 286,
                "english" => "Al-Baqarah",
                "bangla" => "আল-বাকারা"
            ),
        3 => array(
                "verse" => 200,
                "english" => "Aali Imran",
                "bangla" => "আল-ইমরান"
            ),
        4 => array(
                "verse" => 176,
                "english" => "An-Nisa’",
                "bangla" => "নিসা"
            ),
        5 => array(
                "verse" => 120,
                "english" => "Al-Ma’idah",
                "bangla" => "আল-মায়িদাহ"
            ),
        6 => array(
                "verse" => 165,
                "english" => "Al-An’am",
                "bangla" => "আল-আনাম"
            ),
        7 => array(
                "verse" => 206,
                "english" => "Al-A’raf",
                "bangla" => "আল-আরাফ"
            ),
        8 => array(
                "verse" => 75,
                "english" => "Al-Anfal",
                "bangla" => "আল-আনফাল"
            ),
        9 => array(
                "verse" => 129,
                "english" => "At-Taubah",
                "bangla" => "আত-তাওবাহ"
            ),
        10 => array(
                "verse" => 109,
                "english" => "Yunus",
                "bangla" => "ইউনুস"
            ),
        11 => array(
                "verse" => 123,
                "english" => "Hud",
                "bangla" => "হুদ"
            ),
        12 => array(
                "verse" => 111,
                "english" => "Yusuf",
                "bangla" => "ইউসুফ"
            ),
        13 => array(
                "verse" => 43,
                "english" => "Ar-Ra’d",
                "bangla" => "আর-রাদ"
            ),
        14 => array(
                "verse" => 52,
                "english" => "Ibrahim",
                "bangla" => "ইবরাহীম"
            ),
        15 => array(
                "verse" => 99,
                "english" => "Al-Hijr",
                "bangla" => "আল-হিজর"
            ),
        16 => array(
                "verse" => 128,
                "english" => "An-Nahl",
                "bangla" => "আন-নাহল"
            ),
        17 => array(
                "verse" => 111,
                "english" => "Al-Isra’",
                "bangla" => "বনি ইসরাইল"
            ),
        18 => array(
                "verse" => 110,
                "english" => "Al-Kahf",
                "bangla" => "আল-কাহফ"
            ),
        19 => array(
                "verse" => 98,
                "english" => "Maryam",
                "bangla" => "মারিয়াম"
            ),
        20 => array(
                "verse" => 135,
                "english" => "Ta-Ha",
                "bangla" => "ত্বা হা"
            ),
        21 => array(
                "verse" => 112,
                "english" => "Al-Anbiya’",
                "bangla" => "আল-আম্বিয়া"
            ),
        22 => array(
                "verse" => 78,
                "english" => "Al-Haj",
                "bangla" => "আল-হাজ্ব"
            ),
        23 => array(
                "verse" => 118,
                "english" => "Al-Mu’minun",
                "bangla" => "আল-মুমিনুন"
            ),
        24 => array(
                "verse" => 64,
                "english" => "An-Nur",
                "bangla" => "আন-নূর"
            ),
        25 => array(
                "verse" => 77,
                "english" => "Al-Furqan",
                "bangla" => "আল-ফুরকান"
            ),
        26 => array(
                "verse" => 222,
                "english" => "Ash-Shu’ara’",
                "bangla" => "আশ-শুআরা"
            ),
        27 => array(
                "verse" => 93,
                "english" => "An-Naml",
                "bangla" => "আন-নমল"
            ),
        28 => array(
                "verse" => 88,
                "english" => "Al-Qasas",
                "bangla" => "আল-কাসাস"
            ),
        29 => array(
                "verse" => 69,
                "english" => "Al-Ankabut",
                "bangla" => "আল-আনকাবুত"
            ),
        30 => array(
                "verse" => 60,
                "english" => "Ar-Rum",
                "bangla" => "আল-রুম"
            ),
        31 => array(
                "verse" => 34,
                "english" => "Luqman",
                "bangla" => "লুকমান"
            ),
        32 => array(
                "verse" => 30,
                "english" => "As-Sajdah",
                "bangla" => "আস-সাজদাহ"
            ),
        33 => array(
                "verse" => 73,
                "english" => "Al-Ahzab",
                "bangla" => "আল-আহযাব"
            ),
        34 => array(
                "verse" => 54,
                "english" => "Saba’",
                "bangla" => "আস-সাবা"
            ),
        35 => array(
                "verse" => 45,
                "english" => "Al-Fatir",
                "bangla" => "আল-ফাতির"
            ),
        36 => array(
                "verse" => 83,
                "english" => "Ya-Sin",
                "bangla" => "ইয়া সিন"
            ),
        37 => array(
                "verse" => 181,
                "english" => "As-Saffah",
                "bangla" => "আস-সাফফাত"
            ),
        38 => array(
                "verse" => 88,
                "english" => "Sad",
                "bangla" => "সোয়াদ"
            ),
        39 => array(
                "verse" => 75,
                "english" => "Az-Zumar",
                "bangla" => "আয-যুমার"
            ),
        40 => array(
                "verse" => 85,
                "english" => "Ghafar",
                "bangla" => "আল-মুমিন"
            ),
        41 => array(
                "verse" => 54,
                "english" => "Fusilat",
                "bangla" => "হামিম সাজদাহ"
            ),
        42 => array(
                "verse" => 53,
                "english" => "Ash-Shura",
                "bangla" => "আশ-শূরা"
            ),
        43 => array(
                "verse" => 89,
                "english" => "Az-Zukhruf",
                "bangla" => "আয-যুখরুফ"
            ),
        44 => array(
                "verse" => 58,
                "english" => "Ad-Dukhan",
                "bangla" => "আদ-দুখান"
            ),
        45 => array(
                "verse" => 37,
                "english" => "Al-Jathiyah",
                "bangla" => "আল-জাসিয়াহ"
            ),
        46 => array(
                "verse" => 35,
                "english" => "Al-Ahqaf",
                "bangla" => "আল-আহকাফ"
            ),
        47 => array(
                "verse" => 38,
                "english" => "Muhammad",
                "bangla" => "মুহাম্মদ [স:]"
            ),
        48 => array(
                "verse" => 29,
                "english" => "Al-Fat’h",
                "bangla" => "আল-ফাতহ"
            ),
        49 => array(
                "verse" => 18,
                "english" => "Al-Hujurat",
                "bangla" => "আল-হুজুরাত"
            ),
        50 => array(
                "verse" => 45,
                "english" => "Qaf",
                "bangla" => "ক্বাফ"
            ),
        51 => array(
                "verse" => 60,
                "english" => "Adz-Dzariyah",
                "bangla" => "আয-যারিয়াত"
            ),
        52 => array(
                "verse" => 47,
                "english" => "At-Tur",
                "bangla" => "আত-তুর"
            ),
        53 => array(
                "verse" => 62,
                "english" => "An-Najm",
                "bangla" => "আন-নাজম"
            ),
        54 => array(
                "verse" => 55,
                "english" => "Al-Qamar",
                "bangla" => "আল-ক্বমর"
            ),
        55 => array(
                "verse" => 78,
                "english" => "Ar-Rahman",
                "bangla" => "আর-রাহমান"
            ),
        56 => array(
                "verse" => 96,
                "english" => "Al-Waqi’ah",
                "bangla" => "আল-ওয়াকিয়াহ"
            ),
        57 => array(
                "verse" => 29,
                "english" => "Al-Hadid",
                "bangla" => "আল-হাদিদ"
            ),
        58 => array(
                "verse" => 22,
                "english" => "Al-Mujadilah",
                "bangla" => "আল-মুজাদিলাহ"
            ),
        59 => array(
                "verse" => 24,
                "english" => "Al-Hashr",
                "bangla" => "আল-হাশর"
            ),
        60 => array(
                "verse" => 13,
                "english" => "Al-Mumtahanah",
                "bangla" => "আল-মুমতাহানা"
            ),
        61 => array(
                "verse" => 14,
                "english" => "As-Saf",
                "bangla" => "আস-সাফ"
            ),
        62 => array(
                "verse" => 11,
                "english" => "Al-Jum’ah",
                "bangla" => "আল-জুমুআহ"
            ),
        63 => array(
                "verse" => 11,
                "english" => "Al-Munafiqun",
                "bangla" => "আল-মুনাফিকুন"
            ),
        64 => array(
                "verse" => 18,
                "english" => "At-Taghabun",
                "bangla" => "আত-তাগাবুন"
            ),
        65 => array(
                "verse" => 12,
                "english" => "At-Talaq",
                "bangla" => "আত-ত্বালাক"
            ),
        66 => array(
                "verse" => 12,
                "english" => "At-Tahrim",
                "bangla" => "আত-তাহরীম"
            ),
        67 => array(
                "verse" => 30,
                "english" => "Al-Mulk –",
                "bangla" => "আল-মুলক"
            ),
        68 => array(
                "verse" => 52,
                "english" => "Al-Qalam",
                "bangla" => "আল-ক্বলম"
            ),
        69 => array(
                "verse" => 52,
                "english" => "Al-Haqqah",
                "bangla" => "আল-হাক্ক্বাহ"
            ),
        70 => array(
                "verse" => 44,
                "english" => "Al-Ma’arij",
                "bangla" => "আল-মাআরিজ"
            ),
        71 => array(
                "verse" => 28,
                "english" => "Nuh (Nuh)",
                "bangla" => "নূহ"
            ),
        72 => array(
                "verse" => 28,
                "english" => "Al-Jinn",
                "bangla" => "আল-জ্বিন"
            ),
        73 => array(
                "verse" => 20,
                "english" => "Al-Muzammil",
                "bangla" => "মুযাম্মিল"
            ),
        74 => array(
                "verse" => 56,
                "english" => "Al-Mudaththir",
                "bangla" => "মুদাসসির"
            ),
        75 => array(
                "verse" => 40,
                "english" => "Al-Qiyamah",
                "bangla" => "আল-কিয়ামাহ"
            ),
        76 => array(
                "verse" => 31,
                "english" => "Al-Insan",
                "bangla" => "আল-ইনসান"
            ),
        77 => array(
                "verse" => 50,
                "english" => "Al-Mursalat",
                "bangla" => "আল-মুরসালাত"
            ),
        78 => array(
                "verse" => 40,
                "english" => "An-Naba’",
                "bangla" => "আন-নাবা"
            ),
        79 => array(
                "verse" => 44,
                "english" => "An-Nazi’at",
                "bangla" => "আন-নাযিয়াত"
            ),
        80 => array(
                "verse" => 42,
                "english" => "‘Abasa",
                "bangla" => "আবাসা"
            ),
        81 => array(
                "verse" => 29,
                "english" => "At-Takwir",
                "bangla" => "আত-তাকবির"
            ),
        82 => array(
                "verse" => 19,
                "english" => "Al-Infitar",
                "bangla" => "আল-ইনফিতার"
            ),
        83 => array(
                "verse" => 36,
                "english" => "Al-Mutaffifin",
                "bangla" => "আত-তাতফিক"
            ),
        84 => array(
                "verse" => 25,
                "english" => "Al-Inshiqaq",
                "bangla" => "আল-ইনশিকাক"
            ),
        85 => array(
                "verse" => 22,
                "english" => "Al-Buruj",
                "bangla" => "আল-বুরুজ"
            ),
        86 => array(
                "verse" => 17,
                "english" => "At-Tariq",
                "bangla" => "তারিক"
            ),
        87 => array(
                "verse" => 19,
                "english" => "Al-A’la",
                "bangla" => "আল-আলা"
            ),
        88 => array(
                "verse" => 26,
                "english" => "Al-Ghashiyah",
                "bangla" => "আল-গাশিয়াহ"
            ),
        89 => array(
                "verse" => 30,
                "english" => "Al-Fajr",
                "bangla" => "আল-ফজর"
            ),
        90 => array(
                "verse" => 20,
                "english" => "Al-Balad",
                "bangla" => "আল-বালাদ"
            ),
        91 => array(
                "verse" => 15,
                "english" => "Ash-Shams",
                "bangla" => "আশ-শামস"
            ),
        92 => array(
                "verse" => 21,
                "english" => "Al-Layl",
                "bangla" => "আল-লাইল"
            ),
        93 => array(
                "verse" => 11,
                "english" => "Adh-Dhuha",
                "bangla" => "আদ-দুহা"
            ),
        94 => array(
                "verse" => 8,
                "english" => "Al-Inshirah",
                "bangla" => "আল-ইনশিরাহ"
            ),
        95 => array(
                "verse" => 8,
                "english" => "At-Tin",
                "bangla" => "আত-তীন"
            ),
        96 => array(
                "verse" => 19,
                "english" => "Al-‘Alaq",
                "bangla" => "আল-আলাক"
            ),
        97 => array(
                "verse" => 5,
                "english" => "Al-Qadar",
                "bangla" => "আল-ক্বাদর"
            ),
        98 => array(
                "verse" => 8,
                "english" => "Al-Bayinah",
                "bangla" => "আল-বাইয়িনাহ"
            ),
        99 => array(
                "verse" => 8,
                "english" => "Az-Zalzalah",
                "bangla" => "আল-যিলযাল"
            ),
        100 => array(
                "verse" => 11,
                "english" => "Al-‘Adiyah",
                "bangla" => "আল-আদিয়াত"
            ),
        101 => array(
                "verse" => 11,
                "english" => "Al-Qari’ah",
                "bangla" => "আল-কারিয়াহ"
            ),
        102 => array(
                "verse" => 8,
                "english" => "At-Takathur",
                "bangla" => "আত-তাকাছুর"
            ),
        103 => array(
                "verse" => 3,
                "english" => "Al-‘Asr",
                "bangla" => "আল-আসর"
            ),
        104 => array(
                "verse" => 9,
                "english" => "Al-Humazah",
                "bangla" => "আল-হুমাযাহ"
            ),
        105 => array(
                "verse" => 5,
                "english" => "Al-Fil",
                "bangla" => "ফীল"
            ),
        106 => array(
                "verse" => 4,
                "english" => "Quraish",
                "bangla" => "আল-কুরাইশ"
            ),
        107 => array(
                "verse" => 7,
                "english" => "Al-Ma’un",
                "bangla" => "আল-মাউন"
            ),
        108 => array(
                "verse" => 3,
                "english" => "Al-Kauthar",
                "bangla" => "আল-কাওসার"
            ),
        109 => array(
                "verse" => 6,
                "english" => "Al-Kafirun",
                "bangla" => "আল-কাফিরুন"
            ),
        110 => array(
                "verse" => 3,
                "english" => "An-Nasr",
                "bangla" => "আন-নাসর"
            ),
        111 => array(
                "verse" => 5,
                "english" => "Al-Masad",
                "bangla" => "লাহাব"
            ),
        112 => array(
                "verse" => 4,
                "english" => "Al-Ikhlas",
                "bangla" => "আল-ইখলাস"
            ),
        113 => array(
                "verse" => 5,
                "english" => "Al-Falaq",
                "bangla" => "আল-ফালাক"
            ),
        114 => array(
                "verse" => 6,
                "english" => "An-Nas",
                "bangla" => "আন-নাস"
	        )
    );

    public function getSuraName($suraNumber, $lang="english")
    {
        return $this->quran[$suraNumber][$lang];
    }
}
