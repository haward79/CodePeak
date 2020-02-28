<?php

    /*
     *  Language:
     *  0 : C
     *  1 : C++ 11
     *  2 : C++ 14
     *  3 : Java 1.6
     *  4 : Java 1.7
     *  5 : Java 1.8
     *  6 : Python 2
     *  7 : Python 3
     */

    const kMaxLanguage = 7;

    function langCodeToString($langCode)
    {
        switch($langCode)
        {
            case 0:
                return 'C';

            case 1:
                return 'C++ 11';

            case 2:
                return 'C++ 14';

            case 3:
                return 'Java 1.6';

            case 4;
                return 'Java 1.7';

            case 5:
                return 'Java 1.8';

            case 6:
                return 'Python 2';
            
            case 7:
                return 'Python 3';

            default:
                return '未知';
        }
    }

