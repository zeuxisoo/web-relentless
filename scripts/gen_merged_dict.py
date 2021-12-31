#!/usr/bin/env python3

# Reference: https://github.com/ayaka14732/cantoseg

import pycantonese
from collections import Counter
from os import path
from urllib import request

CURRENT_PATH = path.abspath(path.dirname(__file__))
BIG_DICT_URL = 'https://raw.githubusercontent.com/fxsjy/jieba/master/extra_dict/dict.txt.big'

def gen_cantonese_dict():
    corpus  = pycantonese.hkcancor()
    counter = Counter()

    for token in corpus.tokens(by_utterances=False):
        if token.pos.isalpha():
            counter[token.word, token.pos.lower()] += 1


    with open(path.join(CURRENT_PATH, 'dict.cantonese.txt'), 'w', encoding='utf8') as f:
        for (word, pos), freq in counter.most_common():
            print(word, freq, pos, file=f)

def download_big_dict():
    BIG_DICT_PATH = path.join(CURRENT_PATH, 'dict.big.txt')

    if not path.exists(BIG_DICT_PATH):
        request.urlretrieve(BIG_DICT_URL, BIG_DICT_PATH)

def merge_dict():
    # big
    counter_big = Counter()

    with open(path.join(CURRENT_PATH, 'dict.big.txt'), encoding='utf8') as f:
        for line in f:
            word, freq, tag = line.rstrip('\n').split(' ')
            counter_big[word, tag] += int(freq)

    de_freq = counter_big['的', 'uj']

    # cantonese
    counter_cantonese = Counter()

    with open(path.join(CURRENT_PATH, 'dict.cantonese.txt'), encoding='utf8') as f:
        for line in f:
            word, freq, tag = line.rstrip('\n').split(' ')
            counter_cantonese[word, tag] += int(freq)

    ge_freq = counter_cantonese['嘅', 'u']

    # merge
    counter = Counter()
    weight  = de_freq / ge_freq

    for k, v in counter_big.items():
        counter[k] += v

    for k, v in counter_cantonese.items():
        counter[k] += round(v * weight)

    with open(path.join(CURRENT_PATH, 'dict.big.cantonese.txt'), 'w', encoding='utf8') as f:
        for (word, pos), freq in counter.most_common():
            print(word, freq, pos, file=f)

if __name__ == "__main__":
    gen_cantonese_dict()
    download_big_dict()
    merge_dict()
