<?php
namespace App\Api\Version1\Tokenizers;

use Fukuball\Jieba\Finalseg;
use Fukuball\Jieba\Jieba;
use TeamTNT\TNTSearch\Support\AbstractTokenizer;
use TeamTNT\TNTSearch\Support\TokenizerInterface;

class TNTSearchJieBaTokenizer extends AbstractTokenizer implements TokenizerInterface {

    public function tokenize($text, $stopwords = []) {
        ini_set('memory_limit', '1024M');

        Jieba::init([
            'mode' => 'default',
            'dict' => 'big',
            'cjk'  => 'all',
        ]);

        Jieba::loadUserDict(storage_path().'/dict.big.cantonese.txt');

        Finalseg::init();

        $tokens = Jieba::cut($text, true);
        $tokens = array_filter($tokens, 'trim');

        return array_diff($tokens, $stopwords);
    }

}
