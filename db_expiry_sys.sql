-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 29 Mar 2019, 13:42
-- Wersja serwera: 10.1.32-MariaDB
-- Wersja PHP: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `db_expiry_sys`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `expiry_date`
--

CREATE TABLE `expiry_date` (
  `id` int(11) NOT NULL,
  `id_product` int(11) NOT NULL,
  `date` date NOT NULL,
  `amount` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `expiry_date`
--

INSERT INTO `expiry_date` (`id`, `id_product`, `date`, `amount`) VALUES
(1, 1, '2019-03-27', NULL),
(2, 2, '2019-03-27', NULL),
(7, 1, '2019-03-26', NULL),
(8, 4, '2019-03-26', NULL),
(9, 5, '2019-03-26', NULL),
(10, 6, '2019-03-28', NULL),
(11, 5, '2019-03-28', NULL),
(12, 2, '2019-03-29', NULL),
(13, 4, '2019-03-29', NULL),
(14, 6, '2019-03-29', NULL),
(15, 5, '2019-03-29', NULL),
(16, 2, '2019-03-29', NULL),
(17, 3, '2019-03-29', NULL),
(18, 4, '2019-03-29', NULL),
(19, 5, '2019-03-29', NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `contents` text COLLATE utf8_polish_ci NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `id_user` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `rank` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `messages`
--

INSERT INTO `messages` (`id`, `contents`, `date_start`, `date_end`, `id_user`, `active`, `rank`) VALUES
(1, 'Słuchajcie, słuchajcie, mieszkańcy Khorinis!\r\n\r\nNa rozkaz wielmożnego lorda Hagena ogłasza się co następuje.\r\n\r\nW związku z zaistniałą sytuacją dla własnego bezpieczeństwa, obywatele powinni unikać lasów i bezdroży dookoła miasta co więcej zabrania się kontaktów ze zbuntowanymi wieśniakami.\r\n\r\nOd chwili obecnej lord Andre przejmuje wyłączne dowództwo nad naszą strażą.\r\n\r\nWszyscy mieszkańcy którzy posiadają jakiekolwiek przeszkolenie w zakresie walki niech wstępują w szeregi straży .\r\n\r\nWszelkie środki bezpieczeństwa dotyczące górnego miasta zostaną jeszcze bardziej zaostrzone.\r\n\r\nStrażnicy strzegący bram nie będą przepuszczać nikogo kto nie posiada zezwolenia na wejście od miasta.\r\n\r\nWe wszystkich miastach i regionach królestwa zostaje wprowadzony stan wojenny.\r\n\r\nSędziowie cywilni zostają pozbawieni swych praw a ich obowiązki przejmuj królewscy paladyni.\r\n\r\nKażdy kto popełnił przestępstwo lub sprzeciwi się królewskiej straży podlega surowej krze.\r\n\r\nEgzekucją tego prawa zajmie się wielmożny lord Andre.\r\n\r\nKażdy mieszkaniec Khorinis, który popełnił jakiekolwiek wykroczenie ma obowiązek zgłosić się do lorda Andre.\r\n\r\nW związku z atakiem zagrażającym naszemu miastu ma obowiązek przygotować się do walki tak jak pozwala mu jego stan majątkowy, dotyczy to zaopatrzenia się w zbroję i oręż, a także natychmiastowe rozpoczęcie treningu bojowego.', '2019-03-24', '2019-03-31', 1, 1, 3),
(2, 'Testowa krótka wiadomość. Proszę tego nie czytać. To służy tylko testowaniu aplikacji. yoyo', '2019-03-24', '2019-03-31', 2, 1, 0),
(4, 'tututuutututu', '2019-03-01', '2019-03-30', 2, 1, 3),
(5, 'pam pam pam ', '2019-03-13', '2019-03-29', 1, 1, 0),
(6, 'ważna wiadmość', '2019-03-01', '2019-04-30', 1, 1, 2);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(30) COLLATE utf8_polish_ci NOT NULL,
  `ean_code` varchar(30) COLLATE utf8_polish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `products`
--

INSERT INTO `products` (`id`, `name`, `ean_code`) VALUES
(1, 'product_test_1', NULL),
(2, 'product_test_2', NULL),
(3, 'product_test_3', NULL),
(4, 'product_test_4', NULL),
(5, 'product_test_5', NULL),
(6, 'product_test_6', NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` varchar(20) COLLATE utf8_polish_ci NOT NULL,
  `password` varchar(20) COLLATE utf8_polish_ci NOT NULL,
  `email` varchar(30) COLLATE utf8_polish_ci DEFAULT NULL,
  `name` varchar(10) COLLATE utf8_polish_ci NOT NULL,
  `power` int(2) NOT NULL,
  `last_login` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `email`, `name`, `power`, `last_login`) VALUES
(1, 'damian', 'zamrix', 'damianzamroczynski@gmail.com', 'Damian', 10, '2019-03-27'),
(2, 'benek', 'franek', NULL, 'Benek', 0, '2019-03-29');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `expiry_date`
--
ALTER TABLE `expiry_date`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_product` (`id_product`) USING BTREE;

--
-- Indeksy dla tabeli `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`) USING BTREE;

--
-- Indeksy dla tabeli `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `expiry_date`
--
ALTER TABLE `expiry_date`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT dla tabeli `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT dla tabeli `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `expiry_date`
--
ALTER TABLE `expiry_date`
  ADD CONSTRAINT `expiry_date_ibfk_1` FOREIGN KEY (`id_product`) REFERENCES `products` (`id`);

--
-- Ograniczenia dla tabeli `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
