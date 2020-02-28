-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 29, 2020 at 06:08 AM
-- Server version: 10.3.17-MariaDB-0+deb10u1
-- PHP Version: 7.3.11-1~deb10u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `codePeak`
--
CREATE DATABASE IF NOT EXISTS `codePeak` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `codePeak`;

-- --------------------------------------------------------

--
-- Table structure for table `code`
--

CREATE TABLE `code` (
  `id` int(11) NOT NULL COMMENT '流水號',
  `code` text COLLATE utf8_bin NOT NULL COMMENT '程式碼',
  `language` int(11) NOT NULL COMMENT '程式語言',
  `questionId` int(11) NOT NULL COMMENT '對應到的題目',
  `uploader` text COLLATE utf8_bin NOT NULL COMMENT '上傳者的帳號',
  `uploadTime` datetime NOT NULL COMMENT '上傳時間',
  `comment` text COLLATE utf8_bin NOT NULL COMMENT '備註'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `code`
--

INSERT INTO `code` (`id`, `code`, `language`, `questionId`, `uploader`, `uploadTime`, `comment`) VALUES
(1, 'main.cpp\n#include <iostream>\n#include <string.h>\n\nusing std::cout;\nusing std::cin;\nusing std::string;\n\nint main()\n{\n    string input;\n\n    while(getline(cin, input))\n    {\n        cout << input << \"\\n\";\n    }\n\n    return 0;\n}\n\n\n/0', 1, 1, 'admin', '2019-11-12 10:47:37', ''),
(2, 'main.c\n#include <stdio.h>\n#include <stdlib.h>\n\nint main()\n{\n    char input[100];\n\n    while(scanf(\"%[^\\n]100s\", input) != EOF)\n    {\n        printf(\"%s\\n\", input);\n\n        // Throw \\n out of input buffer.\n        scanf(\"%*c\");\n    }\n\n    return 0;\n}\n\n\n/0', 0, 1, 'admin', '2019-11-12 10:55:09', ''),
(3, 'Main.java\nimport java.util.Scanner;\n\npublic class Main\n{\n    public static void main(String args[])\n    {\n        Scanner keyboardInput = new Scanner(System.in);\n        String input = \"\";\n\n        while(keyboardInput.hasNext())\n        {\n            input = keyboardInput.nextLine();\n            System.out.println(input);\n        }\n\n        keyboardInput.close();\n    }\n}\n\n\n/0', 5, 1, 'admin', '2019-11-12 11:06:55', '');

-- --------------------------------------------------------

--
-- Table structure for table `question_detail`
--

CREATE TABLE `question_detail` (
  `id` int(11) NOT NULL COMMENT '流水號',
  `content` text COLLATE utf8_bin NOT NULL COMMENT '問題內容',
  `inputInstruct` text COLLATE utf8_bin NOT NULL COMMENT '輸入的測資',
  `outputInstruct` text COLLATE utf8_bin NOT NULL COMMENT '輸出的測資',
  `inputSample` text COLLATE utf8_bin NOT NULL COMMENT '範例輸入測資',
  `outputSample` text COLLATE utf8_bin NOT NULL COMMENT '範例輸出測資',
  `hint` text COLLATE utf8_bin NOT NULL COMMENT '解題導引'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `question_detail`
--

INSERT INTO `question_detail` (`id`, `content`, `inputInstruct`, `outputInstruct`, `inputSample`, `outputSample`, `hint`) VALUES
(1, '試設計一程式：\r\n讀取使用者輸入的一行字串後將其印出。\r\n\r\n該程式必須能夠重複讀取多行輸入，直到讀到EOF。', '輸入為一字串，長度不超過99。', '每次使用者輸入一行後，請輸出使用者輸入的內容，輸出結尾須換行。', 'Hello world.\r\nI am a copy cat program.\r\nI love CodePeak.\r\nI\r\nlove\r\nNUTN\r\nCSIE\r\n.\r\n', 'Hello world.\r\nI am a copy cat program.\r\nI love CodePeak.\r\nI\r\nlove\r\nNUTN\r\nCSIE\r\n.\r\n', '[C]：\r\nwhile(scanf(\"%[\\n]s\", input) != EOF)\r\n{ }\r\n\r\n[C++]：\r\nwhile(getline(cin, input))\r\n{ }\r\n\r\n[Java]：\r\nhasNext()\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `question_info`
--

CREATE TABLE `question_info` (
  `id` int(11) NOT NULL COMMENT '流水號',
  `title` text COLLATE utf8_bin NOT NULL COMMENT '問題標題',
  `lastModify` datetime NOT NULL COMMENT '上次修改時間',
  `privilege` text COLLATE utf8_bin NOT NULL COMMENT '存取權限'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `question_info`
--

INSERT INTO `question_info` (`id`, `title`, `lastModify`, `privilege`) VALUES
(1, 'Hello world', '2019-11-11 19:28:17', '4_');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `username` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '帳號',
  `password` text COLLATE utf8_bin NOT NULL COMMENT '密碼',
  `nickname` text COLLATE utf8_bin NOT NULL COMMENT '暱稱',
  `privilege` int(11) NOT NULL COMMENT '權限'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `password`, `nickname`, `privilege`) VALUES
('admin', '$1$qybFIXF8$Gmwqi7TV/oFSabKJF.zzo/', 'Admin', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `code`
--
ALTER TABLE `code`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `question_detail`
--
ALTER TABLE `question_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `question_info`
--
ALTER TABLE `question_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `code`
--
ALTER TABLE `code`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水號', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `question_detail`
--
ALTER TABLE `question_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水號', AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `question_info`
--
ALTER TABLE `question_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水號', AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
