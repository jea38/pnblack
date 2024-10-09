CREATE DATABASE IF NOT EXISTS `pnblack` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `pnblack`;

CREATE TABLE IF NOT EXISTS `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `registered` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `accounts` (`id`, `email`, `password`, `role`, `first_name`, `last_name`, `address_street`, `address_city`, `address_state`, `address_zip`, `address_country`, `registered`) VALUES (1, 'admin@website.com', '$2y$10$pEHRAE4Ia0mE9BdLmbS.ueQsv/.WlTUSW7/cqF/T36iW.zDzSkx4y', 'Admin', 'John', 'Doe', '98 High Street', 'New York', 'NY', '10001', 'United States', '2022-01-01 00:00:00');

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `categories` (`id`, `name`, `parent_id`) VALUES (1, 'Sale', 0), (2, 'Watches', 0);

CREATE TABLE IF NOT EXISTS `discounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_ids` varchar(50) NOT NULL,
  `product_ids` varchar(50) NOT NULL,
  `discount_code` varchar(50) NOT NULL,
  `discount_type` enum('Percentage','Fixed') NOT NULL,
  `discount_value` decimal(7,2) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `discounts` (`id`, `category_ids`, `product_ids`, `discount_code`, `discount_type`, `discount_value`, `start_date`, `end_date`) VALUES
(1, '', '', 'newyear2022', 'Percentage', '5.00', '2022-01-01 00:00:00', '2022-12-31 00:00:00'),
(2, '', '', '5off', 'Fixed', '5.00', '2022-01-01 00:00:00', '2032-01-01 00:00:00');

CREATE TABLE IF NOT EXISTS `media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `caption` varchar(255) NOT NULL,
  `date_uploaded` datetime NOT NULL DEFAULT current_timestamp(),
  `full_path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

INSERT INTO `media` (`id`, `title`, `caption`, `date_uploaded`, `full_path`) VALUES
(1, 'Watch Front', '', '2022-02-14 15:58:10', 'uploads/watch.jpg'),
(2, 'Watch Side', '', '2022-02-14 15:58:10', 'uploads/watch-2.jpg'),
(3, 'Watch Back', '', '2022-02-14 15:58:10', 'uploads/watch-3.jpg'),
(4, 'Wallet', '', '2022-02-15 02:06:00', 'uploads/wallet.jpg'),
(5, 'Camera', '', '2022-03-04 16:03:37', 'uploads/camera.jpg'),
(6, 'Headphones', '', '2022-03-04 16:03:37', 'uploads/headphones.jpg');

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(7,2) NOT NULL,
  `rrp` decimal(7,2) NOT NULL DEFAULT 0.00,
  `quantity` int(11) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `weight` decimal(7,2) NOT NULL DEFAULT 0.00,
  `url_slug` varchar(255) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO `products` (`id`, `name`, `description`, `price`, `rrp`, `quantity`, `date_added`, `weight`, `url_slug`, `status`) VALUES
(1, 'Watch', '<p>Unique watch made with stainless steel, ideal for those that prefer interative watches.</p>\r\n<h3>Features</h3>\r\n<ul>\r\n<li>Powered by Android with built-in apps.</li>\r\n<li>Adjustable to fit most.</li>\r\n<li>Long battery life, continuous wear for up to 2 days.</li>\r\n<li>Lightweight design, comfort on your wrist.</li>\r\n</ul>', '29.99', '0.00', -1, '2022-01-01 00:00:00', '0.00', 'smart-watch', 1),
(2, 'Wallet', '', '14.99', '19.99', -1, '2022-01-01 00:00:00', '0.00', '', 1),
(3, 'Headphones', '', '19.99', '0.00', -1, '2022-01-01 00:00:00', '34.00', '', 1),
(4, 'Digital Camera', '', '269.99', '0.00', 43768, '2022-01-01 00:00:00', '0.00', '', 1);

CREATE TABLE IF NOT EXISTS `products_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_id` (`product_id`,`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `products_categories` (`id`, `product_id`, `category_id`) VALUES (1, 1, 2), (2, 2, 1);

CREATE TABLE IF NOT EXISTS `products_downloads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_id` (`product_id`,`file_path`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `products_media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `media_id` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

INSERT INTO `products_media` (`id`, `product_id`, `media_id`, `position`) VALUES (1, 1, 1, 1), (2, 1, 2, 2), (3, 1, 3, 3), (4, 2, 4, 1), (5, 3, 6, 1), (6, 4, 5, 1);

CREATE TABLE IF NOT EXISTS `products_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_id` (`product_id`,`title`,`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

INSERT INTO `products_options` (`id`, `title`, `name`, `quantity`, `price`, `price_modifier`, `weight`, `weight_modifier`, `type`, `required`, `position`, `product_id`) VALUES
(1, 'Size', 'Small', 0, '9.99', 'add', '9.99', 'add', 'select', 1, 1, 1),
(2, 'Size', 'Large', -1, '8.99', 'add', '8.99', 'add', 'select', 1, 1, 1),
(3, 'Type', 'Standard', -1, '0.00', 'add', '0.00', 'add', 'radio', 1, 2, 1),
(4, 'Type', 'Deluxe', -1, '10.00', 'add', '0.00', 'add', 'radio', 1, 2, 1),
(5, 'Color', 'Red', -1, '1.00', 'add', '10.00', 'add', 'checkbox', 0, 3, 1),
(6, 'Color', 'Yellow', -1, '2.00', 'add', '10.00', 'add', 'checkbox', 0, 3, 1),
(7, 'Color', 'Blue', -1, '3.00', 'add', '10.00', 'add', 'checkbox', 0, 3, 1),
(8, 'Color', 'Purple', 0, '4.00', 'add', '10.00', 'add', 'checkbox', 0, 3, 1),
(9, 'Color', 'Brown', 0, '5.00', 'add', '10.00', 'add', 'checkbox', 0, 3, 1),
(10, 'Color', 'Pink', 0, '6.00', 'add', '10.00', 'add', 'checkbox', 0, 3, 1),
(11, 'Color', 'Orange', -1, '8.00', 'add', '11.00', 'add', 'checkbox', 0, 3, 1),
(12, 'Delivery Date', '', -1, '5.00', 'add', '0.00', 'add', 'datetime', 0, 4, 1);

CREATE TABLE IF NOT EXISTS `shipping` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` enum('Single Product','Entire Order') NOT NULL DEFAULT 'Single Product',
  `countries` varchar(255) NOT NULL DEFAULT '',
  `price_from` decimal(7,2) NOT NULL,
  `price_to` decimal(7,2) NOT NULL,
  `price` decimal(7,2) NOT NULL,
  `weight_from` decimal(7,2) NOT NULL DEFAULT 0.00,
  `weight_to` decimal(7,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `shipping` (`id`, `name`, `type`, `countries`, `price_from`, `price_to`, `price`, `weight_from`, `weight_to`) VALUES
(1, 'Standard', 'Entire Order', '', '0.00', '99999.00', '3.99', '0.00', '99999.00'),
(2, 'Express', 'Entire Order', '', '0.00', '99999.00', '7.99', '0.00', '99999.00');

CREATE TABLE IF NOT EXISTS `taxes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country` varchar(255) NOT NULL,
  `rate` decimal(5,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `taxes` (`id`, `country`, `rate`) VALUES (1, 'United Kingdom', '20.00');

CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `txn_id` varchar(255) NOT NULL,
  `payment_amount` decimal(7,2) NOT NULL,
  `payment_status` varchar(30) NOT NULL,
  `created` datetime  NOT NULL,
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
  `tracking_number` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `txn_id` (`txn_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `transactions_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `txn_id` varchar(255) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_price` decimal(7,2) NOT NULL,
  `item_quantity` int(11) NOT NULL,
  `item_options` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `admin` (`id`, `username`, `password`, `email`, `date`) VALUES
(1, 'pnbeditor1', '$2y$10$FfhPYubR4sOXAFSd3NzyQ.C77L4.qIsCa/YlYZCn.2eK8rfWr6oiq', 'admin@website.com', '2023-09-19 14:39:53');


CREATE TABLE IF NOT EXISTS `page` (
  `id` int(11) NOT NULL,
  `page_name` varchar(255) DEFAULT NULL,
  `content` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `posted_by` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


INSERT INTO `posts` (`id`, `title`, `description`, `slug`, `posted_by`, `date`) VALUES
(1, 'Cristiano Ronaldo Returns to Manchester United', '<img class=\"w3-image\" src=\"https://assets.manutd.com/AssetPicker/images/0/0/15/128/1016013/CR_Home_21630596121972_large.jpg\" alt=\"\"><p>\r\n\r\nManchester United is delighted to confirm the signing of Cristiano Ronaldo on a two-year contract with the option to extend for a further year, subject to international clearance.\r\n\r\n\r\nCristiano, a five-time Ballon D’or winner, has so far won over 30 major trophies during his career, including five UEFA Champions League titles, four FIFA Club World Cups, seven league titles in England, Spain and Italy, and the UEFA European Championship for his native Portugal. Cristiano is the first player to have won league titles in England, Spain and Italy. He was also the highest goalscorer in last season’s Serie A and won the golden boot at this year’s European Championship. In his first spell for Manchester United, he scored 118 goals in 292 games.</p><p>Cristiano Ronaldo said:\r\n\r\n“Manchester United is a club that has always had a special place in my heart, and I have been overwhelmed by all the messages I have received since the announcement on Friday. I cannot wait to play at Old Trafford in front of a full stadium and see all the fans again. I\'m looking forward to joining up with the team after the international games, and I hope we have a very successful season ahead.”</p><p>Ole Gunnar Solskjaer said:\r\n\r\n“You run out of words to describe Cristiano. He is not only a marvellous player, but also a great human being. To have the desire and the ability to play at the top level for such a long period requires a very special person. I have no doubt that he will continue to impress us all and his experience will be so vital for the younger players in the squad. Ronaldo’s return demonstrates the unique appeal of this club and I am absolutely delighted he is coming home to where it all started.” </p>  ', 'cristiano-ronaldo-returns-to-manchester-united', 'Admin', '2022-02-11 15:50:41'),
(2, 'Leo Messi signs for Paris Saint-Germain', '<p style=\"text-align: center; \"><img src=\"https://images.psg.media/media/209006/leo-cp.jpg?anchor=center&amp;mode=crop&amp;width=800&amp;height=500&amp;quality=80\" alt=\"\"><br></p><p><strong>Paris Saint-Germain is delighted to announce the signing of Leo Messi on a two-year contract with an option of a third year.\r\n\r\nThe six-time Ballon d’Or winner is justifiably considered a legend of the game and a true inspiration for those of all ages inside and outside football.</strong></p><p>The signing of Leo reinforces Paris Saint-Germain’s aspirations as well as providing the club’s loyal fans with not only an exceptionally talented squad, but also moments of incredible football in the coming years.</p><p>Leo Messi said: “I am excited to begin a new chapter of my career at Paris Saint-Germain. Everything about the club matches my football ambitions. I know how talented the squad and the coaching staff are here. I am determined to help build something special for the club and the fans, and I am looking forward to stepping out onto the pitch at the Parc des Princes.”</p><p>Nasser Al-Khelaifi, Chairman and CEO of Paris Saint-Germain said: “I am delighted that Lionel Messi has chosen to join Paris Saint-Germain and we are proud to welcome him and his family to Paris. He has made no secret of his desire to continue competing at the very highest level and winning trophies, and naturally our ambition as a club is to do the same. The addition of Leo to our world class squad continues a very strategic and successful transfer window for the club. Led by our outstanding coach and his staff, I look forward to the team making history together for our fans all around the world.” </p>  ', 'leo-messi-signs-for-paris-saint-germain', 'Admin', '2022-02-11 15:50:41'),
(3, 'Apple Introduces iPhone 13 and iPhone 13 Mini', '<p style=\"text-align: center; \"><img src=\"https://www.apple.com/newsroom/images/product/iphone/geo/Apple_iphone13_hero_geo_09142021_inline.jpg.large.jpg\" alt=\"\"><strong><br></strong></p><p><strong>CUPERTINO, CALIFORNIA</strong> Apple today introduced iPhone 13 and iPhone 13 mini, the next generation of the world’s best smartphone, featuring a beautiful design with sleek flat edges in five gorgeous new colours. Both models feature major innovations, including the most advanced dual-camera system ever on iPhone — with a new Wide camera with bigger pixels and sensor-shift optical image stabilisation (OIS) offering improvements in low-light photos and videos, a new way to personalise the camera with Photographic Styles, and Cinematic mode, which brings a new dimension to video storytelling. iPhone 13 and iPhone 13 mini also boast super-fast performance and power efficiency with A15 Bionic, longer battery life, a brighter Super Retina XDR display that brings content to life, incredible durability with the Ceramic Shield front cover, double the entry-level storage at 128GB, an industry-leading IP68 rating for water resistance, and an advanced 5G experience.</p>', 'apple-introduces-iphone-13-and-iphone-13-mini', 'admin', '2022-02-11 15:50:41'),
(4, 'Apple Reveals Apple Watch Series 7', '<img src=\"https://www.apple.com/newsroom/images/product/watch/standard/Apple_watch-series7_hero_09142021_big.jpg.large.jpg\" alt=\"\"><p><strong>\r\nCUPERTINO, CALIFORNIA</strong> Apple today announced Apple Watch Series 7, featuring a reengineered Always-On Retina display with significantly more screen area and thinner borders, making it the largest and most advanced display ever. The narrower borders allow the display to maximise screen area, while minimally changing the dimensions of the watch itself. The design of Apple Watch Series 7 is refined with softer, more rounded corners, and the display has a unique refractive edge that makes full-screen watch faces and apps appear to seamlessly connect with the curvature of the case. Apple Watch Series 7 also features a user interface optimised for the larger display, offering greater readability and ease of use, plus two unique watch faces — Contour and Modular Duo — designed specifically for the new device. With the improvements to the display, users benefit from the same all-day 18-hour battery life,1 now complemented by 33 percent faster charging.</p>', 'apple-reveals-apple-watch-series-7', 'admin', '2022-02-11 15:50:41');
