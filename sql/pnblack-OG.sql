-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 09, 2023 at 09:04 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pnblack`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Member','Admin') NOT NULL DEFAULT 'Member',
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `address_street` varchar(255) NOT NULL,
  `address_city` varchar(100) NOT NULL,
  `address_state` varchar(100) NOT NULL,
  `address_zip` varchar(50) NOT NULL,
  `address_country` varchar(100) NOT NULL,
  `code` mediumint(50) NOT NULL,
  `registered` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `email`, `password`, `role`, `first_name`, `last_name`, `address_street`, `address_city`, `address_state`, `address_zip`, `address_country`, `code`, `registered`) VALUES
(1, 'admin@website.com', '$2y$10$pEHRAE4Ia0mE9BdLmbS.ueQsv/.WlTUSW7/cqF/T36iW.zDzSkx4y', 'Admin', 'John', 'Doe', '98 High Street', 'New York', 'NY', '10001', 'United States', 0, '2022-01-01 00:00:00'),
(2, 'test@gmail.com', '$2y$10$kJEU/9MsLJC6r536h3m7OuluR5Fw9F4TF4gAEYWZxFyrz0oRPeuwe', 'Member', 'Test', '0', '1400 Shanley dr', 'Columbus', 'OH', '04020', 'United States', 452222, '2023-05-29 18:54:09'),
(3, 'test3@gmail.com', '$2y$10$O3n.wF8zwf2M5G.nH2Z/OucVHa2LiMw0Pibts8acDAq4TAJQE8B0a', 'Member', '', '', '', '', '', '', '', 0, '2023-05-30 19:50:34'),
(4, 'test1@gmail.com', '$2y$10$upb/cFRg4WcklO1dYF2.XOHFLa4Z/YRRE4.BzteiR0uxsBhu7mFwW', 'Member', '', '', '', '', '', '', '', 0, '2023-05-30 19:50:57'),
(5, 'test2@gmail.com', '$2y$10$3wy7r/FJSnZrye5pQfn6EuSwYjGvNlZjFnyYBk.88dSODuWZ5XbOe', 'Member', '', '', '', '', '', '', '', 0, '2023-05-30 19:51:10'),
(6, 'abufawaz@gmail.com', '$2y$10$XoP0hqXHhaFPlQIZaspGvurZEGr9pAp9wLz.w8KcCiPUcGznm3CKi', 'Member', '', '', '', '', '', '', '', 0, '2023-08-02 12:11:19');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `parent_id`) VALUES
(1, 'Sale', 0),
(2, 'Watches', 0),
(3, 'Electronics', 0),
(4, 'Edible Items', 0);

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `id` int(11) NOT NULL,
  `category_ids` varchar(50) NOT NULL,
  `product_ids` varchar(50) NOT NULL,
  `discount_code` varchar(50) NOT NULL,
  `discount_type` enum('Percentage','Fixed') NOT NULL,
  `discount_value` decimal(7,2) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `discounts`
--

INSERT INTO `discounts` (`id`, `category_ids`, `product_ids`, `discount_code`, `discount_type`, `discount_value`, `start_date`, `end_date`) VALUES
(1, '', '', 'newyear2022', 'Percentage', 5.00, '2022-01-01 00:00:00', '2022-12-31 00:00:00'),
(2, '', '', '5off', 'Fixed', 5.00, '2022-01-01 00:00:00', '2032-01-01 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `caption` varchar(255) NOT NULL,
  `date_uploaded` datetime NOT NULL DEFAULT current_timestamp(),
  `full_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`id`, `title`, `caption`, `date_uploaded`, `full_path`) VALUES
(1, 'Watch Front', '', '2022-02-14 15:58:10', 'uploads/watch.jpg'),
(2, 'Watch Side', '', '2022-02-14 15:58:10', 'uploads/watch-2.jpg'),
(3, 'Watch Back', '', '2022-02-14 15:58:10', 'uploads/watch-3.jpg'),
(4, 'Wallet', '', '2022-02-15 02:06:00', 'uploads/wallet.jpg'),
(7, 'top-10-cryptos.jpg.webp', '', '2023-05-31 01:22:42', 'uploads/top-10-cryptos.jpg.webp'),
(8, '876950ea-a19f-4b70-bcd3-3692ecc8d502.b0c0a4bd2f7961c59bfa599ad54b5d39.webp', '', '2023-06-27 23:15:09', 'uploads/876950ea-a19f-4b70-bcd3-3692ecc8d502.b0c0a4bd2f7961c59bfa599ad54b5d39.webp'),
(9, 'e22c79f4-a258-4164-83a2-851be677436c.24cc6068b197e846d08f0b57822e5795.webp', '', '2023-06-27 23:15:09', 'uploads/e22c79f4-a258-4164-83a2-851be677436c.24cc6068b197e846d08f0b57822e5795.webp'),
(10, 'ec804a34-ca86-457b-9682-5f73b81b2f47.964100e06669fcaeef11b5491a9a3238.webp', '', '2023-06-27 23:15:09', 'uploads/ec804a34-ca86-457b-9682-5f73b81b2f47.964100e06669fcaeef11b5491a9a3238.webp'),
(11, '0b4b07e7-8e37-4caa-a480-b9660b22072c.2dd7cf6e3e724a7be16e591ceb65be79.webp', '', '2023-06-27 23:28:19', 'uploads/0b4b07e7-8e37-4caa-a480-b9660b22072c.2dd7cf6e3e724a7be16e591ceb65be79.webp'),
(12, '790e88d6-0b4d-4297-9508-f18c7c2ea80b.78d72a60b75f4545574048cfdb003fb4.webp', '', '2023-06-27 23:28:19', 'uploads/790e88d6-0b4d-4297-9508-f18c7c2ea80b.78d72a60b75f4545574048cfdb003fb4.webp'),
(13, '353249aa-4d0b-41d8-ae35-785d585868db.8a3cb570c126d3b339fd08984c45beb7.webp', '', '2023-06-27 23:28:19', 'uploads/353249aa-4d0b-41d8-ae35-785d585868db.8a3cb570c126d3b339fd08984c45beb7.webp'),
(14, 'cad74d30-74b6-48c7-b1db-e2fd0b49acc0.34147354cbdf3030d43c09605ea88f99.webp', '', '2023-06-27 23:28:19', 'uploads/cad74d30-74b6-48c7-b1db-e2fd0b49acc0.34147354cbdf3030d43c09605ea88f99.webp'),
(15, 'ed8b57a1-3d29-4b62-ab38-1e3ba2400d69.1ace98535c5773a32d941e30ba771016.webp', '', '2023-06-27 23:28:19', 'uploads/ed8b57a1-3d29-4b62-ab38-1e3ba2400d69.1ace98535c5773a32d941e30ba771016.webp'),
(16, 'D9-10mg-Mixed-Variety-Back.webp', '', '2023-06-27 23:39:29', 'uploads/D9-10mg-Mixed-Variety-Back.webp'),
(17, 'D9-10mg-Mixed-Variety-Front.webp', '', '2023-06-27 23:39:29', 'uploads/D9-10mg-Mixed-Variety-Front.webp'),
(18, 'delta-9-mixed-flavors_d1d38f19-b23e-4fae-8809-ecbea61736e2.webp', '', '2023-06-27 23:39:29', 'uploads/delta-9-mixed-flavors_d1d38f19-b23e-4fae-8809-ecbea61736e2.webp'),
(19, 'D9.webp', '', '2023-06-27 23:50:13', 'uploads/D9.webp'),
(21, 'photo_5940282943006162075_y.jpg', '', '2023-08-05 19:18:05', 'uploads/photo_5940282943006162075_y.jpg'),
(22, 'photo_5940282943006162076_y.jpg', '', '2023-08-05 19:18:05', 'uploads/photo_5940282943006162076_y.jpg'),
(23, 'photo_5940282943006162077_y.jpg', '', '2023-08-05 19:18:05', 'uploads/photo_5940282943006162077_y.jpg'),
(24, 'photo_5940282943006162078_y.jpg', '', '2023-08-05 19:18:05', 'uploads/photo_5940282943006162078_y.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(7,2) NOT NULL,
  `rrp` decimal(7,2) NOT NULL DEFAULT 0.00,
  `quantity` int(11) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `weight` decimal(7,2) NOT NULL DEFAULT 0.00,
  `url_slug` varchar(255) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `rrp`, `quantity`, `date_added`, `weight`, `url_slug`, `status`) VALUES
(1, 'Watch', '<p>Unique watch made with stainless steel, ideal for those that prefer interative watches.</p>\r\n<h3>Features</h3>\r\n<ul>\r\n<li>Powered by Android with built-in apps.</li>\r\n<li>Adjustable to fit most.</li>\r\n<li>Long battery life, continuous wear for up to 2 days.</li>\r\n<li>Lightweight design, comfort on your wrist.</li>\r\n</ul>', 29.99, 0.00, -1, '2022-01-01 00:00:00', 0.00, 'smart-watch', 1),
(2, 'Wallet', '', 14.99, 19.99, -1, '2022-01-01 00:00:00', 0.00, '', 1),
(5, 'Crypto Currency', '<ul>\r\n<li>-We have a 6% commission fee</li>\r\n<li>-Your funds will be delivered on the same day</li>\r\n<li>-Necessary screenshots and receipts will be sent to your email<li>\r\n<p>-Make the necessary selections.</p>\r\n<li>-Enter the right Address. PAYMENTS AREN\'T REFUNDABLE</li>\r\n</ul>', 100.00, 0.00, 4980, '2023-05-31 01:18:00', 0.00, '', 1),
(6, 'Dell Alienware m15 R4 Gaming Laptop', '<p>\r\n(Intel i7-10870H 8-Core, 16GB RAM, 512GB SSD, 15.6\" Full HD (1920x1080), NVIDIA RTX 3070, Wifi, Bluetooth, Webcam, 1xHDMI, 1 mini Display Port, Win 10 Home)\r\n</p>', 2200.00, 0.00, 16, '2023-06-27 23:05:00', 0.00, '', 1),
(7, ' iPhone 14 Pro 128GB Deep Purple', '<p>Brand new iPhone 14 Pro</p>', 1030.00, 0.00, 15, '2023-06-27 23:26:00', 0.00, '', 1),
(10, 'Watches', '<ul>\r\n<li>Plain or Diamond covered watches.</li>\r\n</ul>', 10000.00, 0.00, 20, '2023-08-05 19:13:00', 0.00, '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `products_categories`
--

CREATE TABLE `products_categories` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `products_categories`
--

INSERT INTO `products_categories` (`id`, `product_id`, `category_id`) VALUES
(1, 1, 2),
(2, 2, 1),
(3, 6, 3),
(4, 7, 3),
(11, 10, 2);

-- --------------------------------------------------------

--
-- Table structure for table `products_downloads`
--

CREATE TABLE `products_downloads` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `position` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products_media`
--

CREATE TABLE `products_media` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `media_id` int(11) NOT NULL,
  `position` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `products_media`
--

INSERT INTO `products_media` (`id`, `product_id`, `media_id`, `position`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 2),
(3, 1, 3, 3),
(4, 2, 4, 1),
(7, 5, 7, 1),
(8, 6, 9, 1),
(9, 6, 10, 2),
(10, 6, 8, 3),
(11, 7, 13, 1),
(12, 7, 15, 2),
(13, 7, 12, 3),
(14, 7, 14, 4),
(15, 7, 11, 5),
(20, 10, 21, 3),
(21, 10, 22, 2),
(22, 10, 23, 1),
(23, 10, 24, 4);

-- --------------------------------------------------------

--
-- Table structure for table `products_options`
--

CREATE TABLE `products_options` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(7,2) NOT NULL,
  `price_modifier` enum('add','subtract') NOT NULL,
  `weight` decimal(7,2) NOT NULL,
  `weight_modifier` enum('add','subtract') NOT NULL,
  `type` enum('select','radio','checkbox','text','datetime') NOT NULL,
  `required` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `products_options`
--

INSERT INTO `products_options` (`id`, `title`, `name`, `quantity`, `price`, `price_modifier`, `weight`, `weight_modifier`, `type`, `required`, `position`, `product_id`) VALUES
(1, 'Size', 'Small', 0, 9.99, 'add', 9.99, 'add', 'select', 1, 1, 1),
(2, 'Size', 'Large', -1, 8.99, 'add', 8.99, 'add', 'select', 1, 1, 1),
(3, 'Type', 'Standard', -1, 0.00, 'add', 0.00, 'add', 'radio', 1, 2, 1),
(4, 'Type', 'Deluxe', -1, 10.00, 'add', 0.00, 'add', 'radio', 1, 2, 1),
(5, 'Color', 'Red', -1, 1.00, 'add', 10.00, 'add', 'checkbox', 0, 3, 1),
(6, 'Color', 'Yellow', -1, 2.00, 'add', 10.00, 'add', 'checkbox', 0, 3, 1),
(7, 'Color', 'Blue', -1, 3.00, 'add', 10.00, 'add', 'checkbox', 0, 3, 1),
(8, 'Color', 'Purple', 0, 4.00, 'add', 10.00, 'add', 'checkbox', 0, 3, 1),
(9, 'Color', 'Brown', 0, 5.00, 'add', 10.00, 'add', 'checkbox', 0, 3, 1),
(10, 'Color', 'Pink', 0, 6.00, 'add', 10.00, 'add', 'checkbox', 0, 3, 1),
(11, 'Color', 'Orange', -1, 8.00, 'add', 11.00, 'add', 'checkbox', 0, 3, 1),
(12, 'Delivery Date', '', -1, 5.00, 'add', 0.00, 'add', 'datetime', 0, 4, 1),
(13, 'Select', 'BTC', -1, 0.00, 'add', 0.00, 'add', 'radio', 1, 1, 5),
(14, 'Select', 'ETH', -1, 0.00, 'add', 0.00, 'add', 'radio', 1, 1, 5),
(15, 'Select', 'LTC', -1, 0.00, 'add', 0.00, 'add', 'radio', 1, 1, 5),
(16, 'Select', 'Tether/USDT', -1, 0.00, 'add', 0.00, 'add', 'radio', 1, 1, 5),
(29, 'Recipient Address', '', -1, 0.00, 'add', 0.00, 'add', 'text', 1, 2, 5),
(70, 'Style', 'Plain', 10, 5000.00, 'subtract', 0.00, 'add', 'radio', 0, 1, 10),
(71, 'Style', 'Diamonds', 10, 5000.00, 'add', 0.00, 'add', 'radio', 0, 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `shipping`
--

CREATE TABLE `shipping` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('Single Product','Entire Order') NOT NULL DEFAULT 'Single Product',
  `countries` varchar(255) NOT NULL DEFAULT '',
  `price_from` decimal(7,2) NOT NULL,
  `price_to` decimal(7,2) NOT NULL,
  `price` decimal(7,2) NOT NULL,
  `weight_from` decimal(7,2) NOT NULL DEFAULT 0.00,
  `weight_to` decimal(7,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `taxes`
--

CREATE TABLE `taxes` (
  `id` int(11) NOT NULL,
  `country` varchar(255) NOT NULL,
  `rate` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `taxes`
--

INSERT INTO `taxes` (`id`, `country`, `rate`) VALUES
(1, 'United Kingdom', 20.00);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `txn_id` varchar(255) NOT NULL,
  `payment_amount` decimal(7,2) NOT NULL,
  `payment_status` varchar(30) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `payer_email` varchar(255) NOT NULL DEFAULT '',
  `first_name` varchar(100) NOT NULL DEFAULT '',
  `last_name` varchar(100) NOT NULL DEFAULT '',
  `address_street` varchar(255) NOT NULL DEFAULT '',
  `address_city` varchar(100) NOT NULL DEFAULT '',
  `address_state` varchar(100) NOT NULL DEFAULT '',
  `address_zip` varchar(50) NOT NULL DEFAULT '',
  `address_country` varchar(100) NOT NULL DEFAULT '',
  `account_id` int(11) DEFAULT NULL,
  `payment_method` varchar(50) NOT NULL DEFAULT 'website',
  `shipping_method` varchar(255) NOT NULL DEFAULT '',
  `shipping_amount` decimal(7,2) NOT NULL DEFAULT 0.00,
  `discount_code` varchar(50) NOT NULL DEFAULT '',
  `carrier` varchar(50) NOT NULL DEFAULT '',
  `tracking_number` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `txn_id`, `payment_amount`, `payment_status`, `created`, `payer_email`, `first_name`, `last_name`, `address_street`, `address_city`, `address_state`, `address_zip`, `address_country`, `account_id`, `payment_method`, `shipping_method`, `shipping_amount`, `discount_code`, `carrier`, `tracking_number`) VALUES
(1, 'SC647699C6132ED9968B', 5003.99, 'Pending', '2023-05-30 02:50:00', 'admin@website.com', 'John', 'Doe', '98 High Street', 'New York', 'NY', '10001', 'United States', 1, 'website', 'Standard', 3.99, '', '', ''),
(2, 'SC647B7FC0AAAA9F7672', 100.00, 'Pending', '2023-06-03 20:00:32', 'admin@website.com', 'John', 'Doe', '98 High Street', 'New York', 'NY', '10001', 'United States', 1, 'website', '', 0.00, '', '', ''),
(3, 'SC647B7FE57A761F6ACC', 100.00, 'Pending', '2023-06-03 20:01:09', 'admin@website.com', 'John', 'Doe', '98 High Street', 'New York', 'NY', '10001', 'United States', 1, 'website', '', 0.00, '', '', ''),
(4, 'SC6480E2F0DCDA78BB0E', 14.99, 'Completed', '2023-06-07 22:05:00', 'admin@website.com', 'John', 'Doe', '98 High Street', 'New York', 'NY', '10001', 'United States', 1, 'website', '', 0.00, '', '', ''),
(5, 'SC64AC8AF7094BE28C76', 5945.00, 'Pending', '2023-07-11 00:49:27', 'admin@website.com', 'John', 'Doe', '98 High Street', 'New York', 'NY', '10001', 'United States', 1, 'website', '', 0.00, '5off', '', ''),
(6, 'SC64C3DFFBAC4CFEA206', 4400.00, 'Pending', '2023-07-28 17:34:19', 'test@gmail.com', 'Test', '0', '1400 Shanley dr', 'Columbus', 'OH', '04020', 'United States', 2, 'website', '', 0.00, '', '', ''),
(7, 'SC64C3E0377C5CD5DCD4', 1700.00, 'Pending', '2023-07-28 17:35:19', 'test@gmail.com', 'Test', '0', '1400 Shanley dr', 'Columbus', 'OH', '04020', 'United States', 2, 'website', '', 0.00, '', '', ''),
(8, 'SC64C3E11AE246BC971D', 14.99, 'Pending', '2023-07-28 17:39:06', 'test@gmail.com', 'Test', '0', '1400 Shanley dr', 'Columbus', 'OH', '04020', 'United States', 2, 'website', '', 0.00, '', '', ''),
(9, 'SC64C3E185D7A3763EF6', 2200.00, 'Pending', '2023-07-28 17:40:53', 'test@gmail.com', 'Test', '0', '1400 Shanley dr', 'Columbus', 'OH', '04020', 'United States', 2, 'website', '', 0.00, '', '', ''),
(10, 'SC64C3E1CCF41D922EA7', 56.00, 'Pending', '2023-07-28 17:42:04', 'test@gmail.com', 'Test', '0', '1400 Shanley dr', 'Columbus', 'OH', '04020', 'United States', 2, 'website', '', 0.00, '', '', ''),
(11, 'SC64D2E5F9DFF6D810A0', 2200.00, 'Pending', '2023-08-09 01:03:53', 'admin@website.com', 'John', 'Doe', '98 High Street', 'New York', 'NY', '10001', 'United States', 1, 'website', '', 0.00, '', '', ''),
(12, 'SC64D2E6B8EF27DE16FB', 100.00, 'Pending', '2023-08-08 21:07:04', 'admin@website.com', 'John', 'Doe', '98 High Street', 'New York', 'NY', '10001', 'United States', 1, 'website', '', 0.00, '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `transactions_items`
--

CREATE TABLE `transactions_items` (
  `id` int(11) NOT NULL,
  `txn_id` varchar(255) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_price` decimal(7,2) NOT NULL,
  `item_quantity` int(11) NOT NULL,
  `item_options` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `transactions_items`
--

INSERT INTO `transactions_items` (`id`, `txn_id`, `item_id`, `item_price`, `item_quantity`, `item_options`) VALUES
(1, 'SC64768DA58606793CDF', 5, 5000.00, 1, 'Select-BTC,Recipient Address-35EA2UTB5tjVchVUuNxz2mucLxuwQUgSQi'),
(2, 'SC64768F9FBD10DD212A', 2, 14.99, 4, ''),
(3, 'SC6476956E1B42BB7935', 5, 5000.00, 19, 'Select-ETH,Recipient Address-gg'),
(4, 'SC647699C6132ED9968B', 5, 5000.00, 1, 'Select-ETH,Recipient Address-ff'),
(5, 'SC647B7FC0AAAA9F7672', 5, 100.00, 1, 'Select-LTC,Recipient Address-gg'),
(6, 'SC647B7FE57A761F6ACC', 5, 100.00, 1, 'Select-LTC,Recipient Address-kk'),
(7, 'SC6480E2F0DCDA78BB0E', 2, 14.99, 1, ''),
(8, 'SC64AC8AF7094BE28C76', 8, 39.88, 20, ''),
(9, 'SC64AC8AF7094BE28C76', 7, 1029.50, 5, ''),
(10, 'SC64C3DFFBAC4CFEA206', 6, 2200.00, 2, ''),
(11, 'SC64C3E0377C5CD5DCD4', 5, 100.00, 17, 'Select-Tether/USDT,Recipient Address-fdfcg'),
(12, 'SC64C3E11AE246BC971D', 2, 14.99, 1, ''),
(13, 'SC64C3E185D7A3763EF6', 6, 2200.00, 1, ''),
(14, 'SC64C3E1CCF41D922EA7', 9, 7.00, 8, ''),
(15, 'SC64D2E5F9DFF6D810A0', 6, 2200.00, 1, ''),
(16, 'SC64D2E6B8EF27DE16FB', 5, 100.00, 1, 'Select-LTC,Recipient Address-tt');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products_categories`
--
ALTER TABLE `products_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_id` (`product_id`,`category_id`);

--
-- Indexes for table `products_downloads`
--
ALTER TABLE `products_downloads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_id` (`product_id`,`file_path`);

--
-- Indexes for table `products_media`
--
ALTER TABLE `products_media`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products_options`
--
ALTER TABLE `products_options`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_id` (`product_id`,`title`,`name`) USING BTREE;

--
-- Indexes for table `shipping`
--
ALTER TABLE `shipping`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `taxes`
--
ALTER TABLE `taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `txn_id` (`txn_id`);

--
-- Indexes for table `transactions_items`
--
ALTER TABLE `transactions_items`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `products_categories`
--
ALTER TABLE `products_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `products_downloads`
--
ALTER TABLE `products_downloads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products_media`
--
ALTER TABLE `products_media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `products_options`
--
ALTER TABLE `products_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `shipping`
--
ALTER TABLE `shipping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `taxes`
--
ALTER TABLE `taxes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `transactions_items`
--
ALTER TABLE `transactions_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `email`, `date`) VALUES
(1, 'Admin', '$2y$10$FfhPYubR4sOXAFSd3NzyQ.C77L4.qIsCa/YlYZCn.2eK8rfWr6oiq', 'admin@example.org', '2021-09-19 14:39:53');

-- --------------------------------------------------------

--
-- Table structure for table `page`
--

CREATE TABLE `page` (
  `id` int(11) NOT NULL,
  `page_name` varchar(255) DEFAULT NULL,
  `content` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `posted_by` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `description`, `slug`, `posted_by`, `date`) VALUES
(1, 'Cristiano Ronaldo Returns to Manchester United', '<img src=\"https://i.pinimg.com/564x/38/3c/e6/383ce6d5b267fc3f8ebf2558ac3af625.jpg\" alt=\"\"><p>Manchester United is delighted to confirm the signing of Cristiano Ronaldo on a two-year contract with the option to extend for a further year, subject to international clearance.\r\n\r\n\r\nCristiano, a five-time Ballon D’or winner, has so far won over 30 major trophies during his career, including five UEFA Champions League titles, four FIFA Club World Cups, seven league titles in England, Spain and Italy, and the UEFA European Championship for his native Portugal. Cristiano is the first player to have won league titles in England, Spain and Italy. He was also the highest goalscorer in last season’s Serie A and won the golden boot at this year’s European Championship. In his first spell for Manchester United, he scored 118 goals in 292 games.</p><p>Cristiano Ronaldo said:\r\n\r\n“Manchester United is a club that has always had a special place in my heart, and I have been overwhelmed by all the messages I have received since the announcement on Friday. I cannot wait to play at Old Trafford in front of a full stadium and see all the fans again. I\'m looking forward to joining up with the team after the international games, and I hope we have a very successful season ahead.”</p><p>Ole Gunnar Solskjaer said:\r\n\r\n“You run out of words to describe Cristiano. He is not only a marvellous player, but also a great human being. To have the desire and the ability to play at the top level for such a long period requires a very special person. I have no doubt that he will continue to impress us all and his experience will be so vital for the younger players in the squad. Ronaldo’s return demonstrates the unique appeal of this club and I am absolutely delighted he is coming home to where it all started.” </p>    ', 'cristiano-ronaldo-returns-to-manchester-united', 'Admin', '2023-07-06 23:11:49'),
(5, 'We Launched Today', '<img src=\"https://i.pinimg.com/564x/eb/8e/a3/eb8ea3667bc8fc8bb801e25fe63eca8a.jpg\" alt=\"YB\"><p><strong>PnBlack</strong> launched today, have fun using our website and spread the word.</p>  ', 'we-launched-today', 'admin', '2023-07-06 21:40:55');


CREATE TABLE IF NOT EXISTS `lsaccounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `role` enum('Guest','Operator','Admin') NOT NULL,
  `secret` varchar(255) NOT NULL DEFAULT '',
  `last_seen` datetime NOT NULL,
  `status` enum('Occupied','Waiting','Idle','Away') NOT NULL DEFAULT 'Idle',
  `photo_url` varchar(255) NOT NULL DEFAULT '',
  `ip` varchar(255) NOT NULL DEFAULT '',
  `user_agent` varchar(255) NOT NULL DEFAULT '',
  `registered` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `lsaccounts` (`id`, `email`, `password`, `full_name`, `role`, `secret`, `last_seen`, `status`, `photo_url`, `ip`, `user_agent`, `registered`) VALUES
(1, 'support@website.com', '$2y$10$ZU7Jq5yZ1U/ifeJoJzvLbenjRyJVkSzmQKQc.X0KDPkfR3qs/iA7O', 'Admin', 'Admin', '', '2022-06-14 12:00:00', 'Idle', '', '', '', '2022-06-14 12:00:00'),
(2, 'operator@website.com', '$2y$10$thE7hIJF/EJvKjmJy7hd5uH3a/BNgSUepkYoES0q80YEzi7VqWsRG', 'Operator', 'Operator', '', '2022-06-14 12:00:00', 'Idle', '', '', '', '2022-06-14 12:00:00');

CREATE TABLE IF NOT EXISTS `conversations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_sender_id` int(11) NOT NULL,
  `account_receiver_id` int(11) NOT NULL,
  `submit_date` datetime NOT NULL,
  `status` enum('Open','Archived') NOT NULL DEFAULT 'Open',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `conversation_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `msg` text NOT NULL,
  `attachments` text NOT NULL,
  `submit_date` datetime NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `presets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `msg` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `presets` (`id`, `msg`) VALUES
(1, 'Thank you for choosing our website! If there is anything else you need, don\'t hesitate to contact us!'),
(2, 'Hi {name}, how may I help you today?');

CREATE TABLE IF NOT EXISTS `word_filters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word` varchar(255) NOT NULL,
  `replacement` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;