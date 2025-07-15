<?php

namespace App;

use simplehtmldom\HtmlWeb;

class RatePharser
{
    private string $url = "https://www.rate.am/en/armenian-dram-exchange-rates/banks";

    protected HtmlWeb $html;

    public function __construct()
    {
        $this->html = new HtmlWeb();
    }

    public function getRatesForBank(string $bankName): ?array
    {
        $dom = $this->html->load($this->url);

        $bankIndex = null;
        foreach ($dom->find('a.text-sm') as $index => $bankLink) {
            if (str_contains(trim($bankLink->plaintext), $bankName)) {
                $bankIndex = $index;
                break;
            }
        }

        if ($bankIndex === null) {
            return null;
        }

        $rateTable = $dom->find('div.rateTable', 0);
        $rows = $rateTable->children();
        $lastRow = end($rows);
        $bankColumn = $lastRow->children()[$bankIndex] ?? null;

        if (!$bankColumn) {
            return null;
        }

        $buy = $bankColumn->children()[0]->children()[0]->children()[0]->children()[0]->plaintext ?? 'N/A';
        $sell = $bankColumn->children()[0]->children()[1]->children()[0]->children()[0]->plaintext ?? 'N/A';

        return [
            'bank' => $bankName,
            'buy'  => trim($buy),
            'sell' => trim($sell),
        ];
    }
}