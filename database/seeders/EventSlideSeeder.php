<?php

namespace Database\Seeders;

use App\Models\EventSlide;
use Illuminate\Database\Seeder;

class EventSlideSeeder extends Seeder
{
    public function run(): void
    {
        $slides = [
            [
                'title_en' => 'Artisanal Details',
                'title_ar' => 'تفاصيل فنية',
                'subtitle_en' => 'Event Experience',
                'subtitle_ar' => 'تجربة الفعاليات',
                'media_type' => 'image',
                'media_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDRobX-vJYDzIEz5deICaxrRBQpUOcKPDS2mN8UvmJ98ABMCLiiBXL4301knRtSEAmOjOZbUrIhJq-Q4nI_UvRGb_Y2Yi2C366KhaO8ae64ciHt2VG9CcLl4qYdPqr9qH8A7-KO5NsFXDzdnWYUANZh91oy2zcR5X539Gl0WWbAOqVdpMioDjMoXbLod9IXb0tkP1svv966Sz7HtYzVWKvqkD5fXUATw3Z_nRhDneYc3fxJ1ettv_9j0LfNA2Vhjwuh4Med0wYoBEk',
                'sort_order' => 1,
            ],
            [
                'title_en' => 'Magical Moments',
                'title_ar' => 'لحظات ساحرة',
                'subtitle_en' => 'Unforgettable Atmosphere',
                'subtitle_ar' => 'أجواء لا تنسى',
                'media_type' => 'image',
                'media_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuC-SFJIgfD9rFHAMIHJbaMsakRiN_L9foes2dx5H4YkoH3ewcn83lNftM_Em0O4spXoTRr58zEoM3U1KAvmZ4a0v6XCWFbgRLq9Ai7xmcnd7kq4LzgsLjDpL1Yfc9j2lOhPyZknp_T9yXz1wAQ2JQ3P46fQc2qGm_xSbFbW5LEQ-Uw5uVb2i37r9HmI5ZKZ0aI7ZABCBx3hSD5G-mMrbOP3lAmn61mDfIIC17qYAfH2SnBi00aaYtC0WHq-Dzf-nIvtA7l6Q8uWHRo',
                'sort_order' => 2,
            ],
        ];

        foreach ($slides as $s) {
            EventSlide::create($s);
        }
    }
}
