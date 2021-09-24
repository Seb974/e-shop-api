<?php

namespace App\Service\Image;

class Dimension
{
    public function getWidth(string $instance)
    {
        switch(strtoupper($instance))
        {
            case 'ARTICLE':
                return 750;
            case 'AGENT':
            case 'SELLER-LOGO':
                return 180;
            case 'PRODUCT':
                return 600;
            case 'HERO-DEFAULT':
            case 'HERO-BLACK_FRIDAY':
            case 'HERO-BLACK_FRIDAY_TWO':
            case 'HERO-VALENTINES_DAY':
            case 'COUNTDOWN-VALENTINES_DAY':
            case 'ABOUT-US-HEADER':
                return 1920;
            case 'HERO-CHRISTMAS':
                return 530;
            case 'BANNER-DEFAULT-1-MAIN':
            case 'BANNER-DEFAULT-1':
            case 'BANNER-BLACK_FRIDAY_TWO-2-MAIN':
            case 'BANNER-BLACK_FRIDAY_TWO-2':
                return 575;
            case 'BANNER-BLACK_FRIDAY-1-MAIN':
            case 'BANNER-BLACK_FRIDAY-1':
            case 'BANNER-BLACK_FRIDAY_TWO-1-MAIN':
            case 'BANNER-BLACK_FRIDAY_TWO-1':
            case 'BANNER-CHRISTMAS-1':
            case 'BANNER-CHRISTMAS-1-MAIN':
            case 'ABOUT-US-BANNER':
                return 370;
            case 'BANNER-BLACK_FRIDAY-2-MAIN':
            case 'BANNER-BLACK_FRIDAY-2':
            case 'BANNER-VALENTINES_DAY-1':
            case 'BANNER-VALENTINES_DAY-1-MAIN':
            case 'COUNTDOWN-DEFAULT':
                return 570;
            case 'BANNER-VALENTINES_DAY-2':
            case 'BANNER-VALENTINES_DAY-2-MAIN':
                return 945;
            case 'COUNTDOWN-CHRISTMAS':
                return 549;
            case 'COUNTDOWN-BLACK_FRIDAY':
            case 'COUNTDOWN-BLACK_FRIDAY_TWO':
            default;
                return 600;
        }
    }

    public function getHeight(string $instance)
    {
        switch(strtoupper($instance))
        {
            case 'ARTICLE':
                return 440;
            case 'AGENT':
                return 330;
            case 'SELLER-LOGO':
                return 120;
            case 'HERO-DEFAULT':
                return 775;
            case 'ABOUT-US-HEADER':
                return 750;
            case 'PRODUCT':
            case 'HERO-BLACK_FRIDAY':
            case 'HERO-BLACK_FRIDAY_TWO':
                return 800;
            case 'HERO-VALENTINES_DAY':
                return 802;
            case 'HERO-CHRISTMAS':
                return 638;
            case 'BANNER-DEFAULT-1-MAIN':
            case 'BANNER-BLACK_FRIDAY_TWO-2-MAIN':
                return 610;
            case 'BANNER-DEFAULT-1':
            case 'BANNER-BLACK_FRIDAY_TWO-2':
                return 295;
            case 'BANNER-BLACK_FRIDAY-1-MAIN':
            case 'BANNER-BLACK_FRIDAY-1':
            case 'BANNER-BLACK_FRIDAY_TWO-1-MAIN':
            case 'BANNER-BLACK_FRIDAY_TWO-1':
            case 'BANNER-CHRISTMAS-1':
            case 'BANNER-CHRISTMAS-1-MAIN':
            case 'ABOUT-US-BANNER':
                return 215;
            case 'BANNER-BLACK_FRIDAY-2-MAIN':
            case 'BANNER-BLACK_FRIDAY-2':
                return 347;
            case 'BANNER-VALENTINES_DAY-1':
            case 'BANNER-VALENTINES_DAY-1-MAIN':
            case 'BANNER-VALENTINES_DAY-2':
            case 'BANNER-VALENTINES_DAY-2-MAIN':
                return 300;
            case 'COUNTDOWN-DEFAULT':
                return 525;
            case 'COUNTDOWN-CHRISTMAS':
                return 352;
            case 'COUNTDOWN-VALENTINES_DAY':
                return 482;
            case 'COUNTDOWN-BLACK_FRIDAY':
            case 'COUNTDOWN-BLACK_FRIDAY_TWO':
            default;
                return 800;
        }
    }
}