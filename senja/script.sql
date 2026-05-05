-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 05, 2026 at 10:09 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_senja`
--

-- --------------------------------------------------------

--
-- Table structure for table `cafe_tables`
--

CREATE TABLE `cafe_tables` (
  `id` int(10) UNSIGNED NOT NULL,
  `table_id` varchar(10) NOT NULL COMMENT 'Kode meja, mis: C1, F5, B3',
  `zone` varchar(30) NOT NULL COMMENT 'Outdoor | Jendela | Indoor | Bar',
  `type` varchar(30) NOT NULL COMMENT 'Couple (2 org) | Meja 4 Orang | Bar Seat (1 org)',
  `capacity` tinyint(4) NOT NULL DEFAULT 2,
  `is_available` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = Tersedia, 0 = Terisi'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cafe_tables`
--

INSERT INTO `cafe_tables` (`id`, `table_id`, `zone`, `type`, `capacity`, `is_available`) VALUES
(1, 'C1', 'Outdoor', 'Couple (2 org)', 2, 1),
(2, 'C2', 'Outdoor', 'Couple (2 org)', 2, 1),
(3, 'C3', 'Outdoor', 'Couple (2 org)', 2, 1),
(4, 'C4', 'Outdoor', 'Couple (2 org)', 2, 1),
(5, 'C5', 'Outdoor', 'Couple (2 org)', 2, 1),
(6, 'C6', 'Outdoor', 'Couple (2 org)', 2, 1),
(7, 'F1', 'Outdoor', 'Meja 4 Orang', 4, 1),
(8, 'F2', 'Outdoor', 'Meja 4 Orang', 4, 1),
(9, 'C7', 'Jendela', 'Couple (2 org)', 2, 1),
(10, 'C8', 'Jendela', 'Couple (2 org)', 2, 1),
(11, 'C9', 'Jendela', 'Couple (2 org)', 2, 1),
(12, 'C10', 'Jendela', 'Couple (2 org)', 2, 1),
(13, 'C11', 'Jendela', 'Couple (2 org)', 2, 1),
(14, 'C12', 'Jendela', 'Couple (2 org)', 2, 1),
(15, 'F3', 'Jendela', 'Meja 4 Orang', 4, 1),
(16, 'F4', 'Jendela', 'Meja 4 Orang', 4, 1),
(17, 'C13', 'Indoor', 'Couple (2 org)', 2, 0),
(18, 'C14', 'Indoor', 'Couple (2 org)', 2, 0),
(19, 'C15', 'Indoor', 'Couple (2 org)', 2, 0),
(20, 'C16', 'Indoor', 'Couple (2 org)', 2, 0),
(21, 'C17', 'Indoor', 'Couple (2 org)', 2, 0),
(22, 'C18', 'Indoor', 'Couple (2 org)', 2, 0),
(23, 'C19', 'Indoor', 'Couple (2 org)', 2, 0),
(24, 'C20', 'Indoor', 'Couple (2 org)', 2, 0),
(25, 'F5', 'Indoor', 'Meja 4 Orang', 4, 0),
(26, 'F6', 'Indoor', 'Meja 4 Orang', 4, 0),
(27, 'F7', 'Indoor', 'Meja 4 Orang', 4, 0),
(28, 'F8', 'Indoor', 'Meja 4 Orang', 4, 0),
(29, 'F9', 'Indoor', 'Meja 4 Orang', 4, 0),
(30, 'F10', 'Indoor', 'Meja 4 Orang', 4, 0),
(31, 'B1', 'Bar', 'Bar Seat (1 org)', 1, 1),
(32, 'B2', 'Bar', 'Bar Seat (1 org)', 1, 1),
(33, 'B3', 'Bar', 'Bar Seat (1 org)', 1, 1),
(34, 'B4', 'Bar', 'Bar Seat (1 org)', 1, 1),
(35, 'B5', 'Bar', 'Bar Seat (1 org)', 1, 1),
(36, 'B6', 'Bar', 'Bar Seat (1 org)', 1, 1),
(37, 'B7', 'Bar', 'Bar Seat (1 org)', 1, 1),
(38, 'B8', 'Bar', 'Bar Seat (1 org)', 1, 1),
(39, 'B9', 'Bar', 'Bar Seat (1 org)', 1, 1),
(40, 'B10', 'Bar', 'Bar Seat (1 org)', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `coffee_beans`
--

CREATE TABLE `coffee_beans` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `roast_level` varchar(50) NOT NULL,
  `origin` varchar(100) NOT NULL,
  `notes` text NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coffee_beans`
--

INSERT INTO `coffee_beans` (`id`, `name`, `roast_level`, `origin`, `notes`, `image_url`) VALUES
(1, 'Aceh Gayo Permata', 'Medium Roast', 'Sumatra, Indonesia', 'Earthy, dark chocolate, hint of spice', 'assets/beans/aceh_gayo.jpg'),
(2, 'Toraja Sapan', 'Medium-Dark Roast', 'Sulawesi, Indonesia', 'Caramel, herbal, smooth body', 'assets/beans/toraja_sapan.jpg'),
(3, 'Bali Kintamani', 'Light-Medium Roast', 'Bali, Indonesia', 'Citrusy, floral, bright acidity', 'assets/beans/bali_kintamani.jpg'),
(4, 'Ethiopia Yirgacheffe', 'Light Roast', 'Yirgacheffe, Ethiopia', 'Jasmine, blueberry, sweet lemon', 'assets/beans/ethiopia.jpg'),
(5, 'Colombia Supremo', 'Medium Roast', 'Huila, Colombia', 'Milk chocolate, red apple, sweet caramel', 'assets/beans/colombia.jpg'),
(6, 'Guatemala Antigua', 'Medium-Dark Roast', 'Antigua, Guatemala', 'Cocoa, subtle smoke, rich body', 'assets/beans/guatemala.jpg'),
(7, 'Kenya AA', 'Light-Medium Roast', 'Nyeri, Kenya', 'Blackberry, wine-like acidity, brown sugar', 'assets/beans/kenya.jpg'),
(8, 'Brazil Cerrado', 'Dark Roast', 'Minas Gerais, Brazil', 'Roasted nuts, dark chocolate, low acidity', 'assets/beans/brazil.jpg'),
(9, 'Costa Rica Tarrazu', 'Medium Roast', 'Tarrazu, Costa Rica', 'Honey, orange zest, clean finish', 'assets/beans/costarica.jpg'),
(10, 'Java Preanger', 'Medium-Dark Roast', 'West Java, Indonesia', 'Nutty, dark chocolate, syrupy body', 'assets/beans/java_preanger.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `role` varchar(100) NOT NULL,
  `person_name` varchar(100) DEFAULT NULL,
  `contact_type` varchar(50) NOT NULL,
  `display_value` varchar(100) NOT NULL,
  `link_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `role`, `person_name`, `contact_type`, `display_value`, `link_url`) VALUES
(1, 'Admin Reservasi', 'Yescitito Obed', 'whatsapp', '+62 822-2319-7431', 'https://wa.me/6282223197431'),
(2, 'Kerja Sama & Info', '', 'email', 'Yescitito@senjacoffee.com', 'mailto:yescitito.20236050@student.atmi.ac.id');

-- --------------------------------------------------------

--
-- Table structure for table `favorite_coffee`
--

CREATE TABLE `favorite_coffee` (
  `id` int(11) NOT NULL,
  `coffee_name` varchar(100) NOT NULL,
  `sold_count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favorite_coffee`
--

INSERT INTO `favorite_coffee` (`id`, `coffee_name`, `sold_count`) VALUES
(1, 'Espresso Robusta', 450),
(2, 'Aren Latte', 320),
(3, 'Caramel Macchiato', 280),
(4, 'Manual Brew', 150),
(5, 'Non-Coffee', 200);

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` varchar(20) NOT NULL,
  `image_url` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `category`, `name`, `description`, `price`, `image_url`) VALUES
(1, 'Signature', 'Espresso Robusta', 'Strong & Bold', '19k', 'assets/menu_img/Espresso.jpg'),
(2, 'Signature', 'Caramel Macchiato', 'Espresso, Milk, Caramel', '28k', 'assets/menu_img/caramel.jpg'),
(3, 'Signature', 'Senja Aren Latte', 'Best Seller', '25k', 'assets/menu_img/aren.jpg'),
(4, 'Signature', 'Mocha Praline', 'Chocolate & Hazelnut', '30k', 'assets/menu_img/mocha.jpg'),
(5, 'Signature', 'Americano', 'Hot / Iced', '20k', 'assets/menu_img/americano.jpg'),
(6, 'Recommendations', 'Senja Aren Latte', 'Espresso arabica dengan gula aren murni.', '25k', 'assets/menu_img/aren.jpg'),
(7, 'Recommendations', 'Caramel Macchiato', 'Perpaduan sempurna espresso, susu, dan saus karamel.', '28k', 'assets/menu_img/caramel.jpg'),
(8, 'Manual Brew', 'V60', 'Clean & Bright', '25k', 'assets/menu_img/v60.jpg'),
(9, 'Manual Brew', 'Japanese Iced', 'Refreshing', '28k', 'assets/menu_img/japanese.jpg'),
(10, 'Manual Brew', 'Aeropress', 'Bold Body', '25k', 'assets/menu_img/aeropress.jpg'),
(11, 'Manual Brew', 'French Press', 'Classic', '22k', 'assets/menu_img/frenchp.jpg'),
(12, 'Non-Coffee', 'Matcha Fusion', 'Premium Japanese', '30k', 'assets/menu_img/matcha.jpg'),
(13, 'Non-Coffee', 'Red Velvet', 'Creamy & Sweet', '28k', 'assets/menu_img/redv.jpg'),
(14, 'Non-Coffee', 'Taro Latte', 'Sweet Potato', '28k', 'assets/menu_img/tarol.jpg'),
(15, 'Non-Coffee', 'Earl Grey', 'Artisan Tea', '22k', 'assets/menu_img/earl.jpg'),
(16, 'Non-Coffee', 'Chamomile', 'Artisan Tea', '25k', 'assets/menu_img/chamomile.jpg'),
(17, 'Pastries', 'Butter Croissant', 'Flaky, buttery, baked fresh daily.', '20k', 'assets/menu_img/croissant.jpg'),
(18, 'Pastries', 'Fudge Brownie', 'Rich, dense, and super chocolatey.', '22k', 'assets/menu_img/brownies.jpg'),
(19, 'Bites', 'Mix Platter', 'Sausage, Nuggets, Fries', '35k', 'assets/menu_img/mixplat.jpg'),
(20, 'Bites', 'Truffle Fries', 'With Parmesan', '28k', 'assets/menu_img/fries.jpg'),
(21, 'Bites', 'Singkong Keju', 'Crispy Cassava', '18k', 'assets/menu_img/singkong.jpg'),
(22, 'Bites', 'Nasi Goreng Senja', 'Special Spices', '40k', 'assets/menu_img/nasgor.jpg'),
(23, 'Bites', 'Spaghetti Carbonara', 'Creamy & Savory', '45k', 'assets/menu_img/spaghetti.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cafe_tables`
--
ALTER TABLE `cafe_tables`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_table_id` (`table_id`);

--
-- Indexes for table `coffee_beans`
--
ALTER TABLE `coffee_beans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favorite_coffee`
--
ALTER TABLE `favorite_coffee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cafe_tables`
--
ALTER TABLE `cafe_tables`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `coffee_beans`
--
ALTER TABLE `coffee_beans`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `favorite_coffee`
--
ALTER TABLE `favorite_coffee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
