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
            case 'PRODUCT':
                return 600;
            case 'HERO-DEFAULT':
            case 'HERO-BLACK_FRIDAY':
            case 'HERO-BLACK_FRIDAY_TWO':
            case 'HERO-VALENTINES_DAY':
                return 1920;
            case 'HERO-CHRISTMAS':
                return 530;
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
            case 'HERO-DEFAULT':
                return 775;
            case 'PRODUCT':
            case 'HERO-BLACK_FRIDAY':
            case 'HERO-BLACK_FRIDAY_TWO':
                return 800;
            case 'HERO-VALENTINES_DAY':
                return 802;
            case 'HERO-CHRISTMAS':
                return 638;
            default;
                return 800;
        }
    }
}