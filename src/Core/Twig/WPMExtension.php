<?php

namespace Nolandartois\BlogOpenclassrooms\Core\Twig;

use Exception;
use Nolandartois\BlogOpenclassrooms\Core\Database\Configuration;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class WPMExtension extends AbstractExtension
{

    public function getFunctions()
    {
        return [
            new TwigFunction('getReadingTime', [$this, 'getReadingTime'])
        ];
    }

    public function getReadingTime(array $postBody): int
    {
        $text = "";

        foreach ($postBody as $block) {
            if (array_key_exists('text', $block['data'])) {
                $text .= $block['data']['text'];
            }
        }

        $nbWords = str_word_count($text);

        $minutes = ($nbWords / (int)Configuration::getConfiguration('words_per_minutes'));

        return round($minutes);
    }
}