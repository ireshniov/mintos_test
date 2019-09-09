<?php

namespace App\Service;

/**
 * Class WordFrequencyService
 * @package App\Service
 */
class WordFrequencyService
{
    private $stopWords = ["the","be","to","of","and","a","in","that","have","I","it","for","not","on","with","he","as","you","do","at","this","but","his","by","from","they","we","say","her","she","or","an","will","my","one","all","would","there","their","what","so","up","out","if","about","who","get","which","go","me"];

    /**
     * @param array $feed
     * @param int|null $mostFrequentCount
     * @return mixed
     */
    public function getWordFrequency(array $feed, ?int $mostFrequentCount = 10)
    {
        $text = sprintf("%s %s", $feed['title'], $feed['description']);

        foreach($feed['items'] as $item) {
            $text .= sprintf("%s %s", $item['title'], $item['description']);
        }

        $text = strtolower(strip_tags($text));

        $wordCount = array_count_values(str_word_count($text, 1));

        $filteredWordCount = array_diff_key($wordCount, array_flip($this->stopWords));

        arsort($filteredWordCount);

        return array_slice($filteredWordCount, 0, $mostFrequentCount);
    }
}