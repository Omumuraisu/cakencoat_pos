-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2025 at 12:02 AM
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
-- Database: `cakencoat_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `inventory_category`
--

CREATE TABLE `inventory_category` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory_category`
--

INSERT INTO `inventory_category` (`id`, `name`) VALUES
(9, 'Canned'),
(11, 'Coffee'),
(8, 'Condiments'),
(4, 'Dairy'),
(7, 'Fruits'),
(5, 'Grains'),
(3, 'Liquids'),
(1, 'Meat'),
(12, 'Non Coffee'),
(2, 'Seasoning'),
(6, 'Vegetables');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_item`
--

CREATE TABLE `inventory_item` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `quantity` decimal(10,2) DEFAULT 0.00,
  `unit` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory_item`
--

INSERT INTO `inventory_item` (`id`, `category_id`, `name`, `quantity`, `unit`) VALUES
(1, 1, 'Chicken', 3.00, 'kg'),
(2, 1, 'Beef', 3.00, 'kg'),
(3, 1, 'Pork', 12.00, 'kg'),
(4, 1, 'Fish', 1.00, 'kg'),
(5, 1, 'Sausage', 2.00, 'packs'),
(6, 1, 'Hotdog', 4.00, 'packs'),
(7, 1, 'Bacon', 2.00, 'packs'),
(8, 1, 'Ham', 1.00, 'pack'),
(9, 2, 'Salt', 2.00, 'kg'),
(10, 2, 'Black Pepper', 1.00, 'kg'),
(11, 2, 'Garlic Powder', 0.50, 'kg'),
(12, 2, 'Onion Powder', 0.30, 'kg'),
(13, 2, 'Paprika', 0.70, 'kg'),
(14, 2, 'Oregano', 0.20, 'kg'),
(15, 2, 'Thyme', 0.15, 'kg'),
(16, 2, 'Chili Flakes', 0.25, 'kg'),
(17, 2, 'Bay Leaves', 0.10, 'kg'),
(18, 2, 'Curry Powder', 0.40, 'kg'),
(19, 3, 'Water', 20.00, 'L'),
(20, 3, 'Soy Sauce', 3.00, 'g'),
(21, 3, 'Vinegar', 2.00, 'L'),
(22, 3, 'Olive Oil', 1.00, 'L'),
(23, 3, 'Vegetable Oil', 5.00, 'L'),
(24, 3, 'Coconut Milk', 2.00, 'L'),
(25, 3, 'Fish Sauce', 1.00, 'L'),
(26, 3, 'Lemon Juice', 0.50, 'L'),
(27, 3, 'Milk', 10.00, 'L'),
(28, 3, 'Broth', 4.00, 'L'),
(29, 4, 'Cheese', 2.00, 'kg'),
(30, 4, 'Butter', 1.00, 'kg'),
(31, 4, 'Milk', 8.00, 'L'),
(32, 4, 'Yogurt', 6.00, 'packs'),
(33, 4, 'Cream Cheese', 3.00, 'packs'),
(34, 4, 'Sour Cream', 2.00, 'packs'),
(35, 4, 'Heavy Cream', 1.00, 'L'),
(36, 4, 'Condensed Milk', 5.00, 'cans'),
(37, 4, 'Evaporated Milk', 4.00, 'cans'),
(38, 5, 'Rice', 25.00, 'kg'),
(39, 5, 'Pasta', 10.00, 'kg'),
(40, 5, 'Bread', 8.00, 'loaves'),
(41, 5, 'Flour', 15.00, 'kg'),
(42, 5, 'Cornstarch', 2.00, 'kg'),
(43, 5, 'Oats', 5.00, 'kg'),
(44, 5, 'Cereal', 6.00, 'boxes'),
(45, 5, 'Noodles', 7.00, 'packs'),
(46, 5, 'Potatoes', 10.00, 'kg'),
(47, 5, 'Sweet Potatoes', 6.00, 'kg'),
(48, 6, 'Carrot', 1.00, 'g'),
(49, 6, 'Onion', 4.00, 'kg'),
(50, 6, 'Garlic', 2.00, 'kg'),
(51, 6, 'Bell Pepper', 3.00, 'kg'),
(52, 6, 'Tomato', 6.00, 'kg'),
(53, 6, 'Cabbage', 4.00, 'heads'),
(54, 6, 'Broccoli', 5.00, 'heads'),
(55, 6, 'Lettuce', 6.00, 'heads'),
(56, 6, 'Spinach', 2.00, 'kg'),
(57, 6, 'Eggplant', 3.00, 'kg'),
(58, 7, 'Apple', 10.00, 'pcs'),
(59, 7, 'Banana', 12.00, 'pcs'),
(60, 7, 'Orange', 8.00, 'pcs'),
(61, 7, 'Mango', 6.00, 'pcs'),
(62, 7, 'Pineapple', 3.00, 'pcs'),
(63, 7, 'Grapes', 2.00, 'kg'),
(64, 7, 'Lemon', 5.00, 'pcs'),
(65, 7, 'Lime', 5.00, 'pcs'),
(66, 7, 'Watermelon', 2.00, 'pcs'),
(67, 7, 'Strawberry', 1.00, 'kg'),
(68, 8, 'Ketchup', 3.00, 'bottles'),
(69, 8, 'Mustard', 2.00, 'bottles'),
(70, 8, 'Mayonnaise', 2.00, 'bottles'),
(71, 8, 'Soy Sauce', 1.00, 'bottle'),
(72, 8, 'Hot Sauce', 2.00, 'bottles'),
(73, 8, 'Barbecue Sauce', 1.00, 'bottle'),
(74, 8, 'Salad Dressing', 2.00, 'bottles'),
(75, 8, 'Worcestershire Sauce', 1.00, 'bottle'),
(76, 8, 'Relish', 1.00, 'jar'),
(77, 8, 'Pickles', 2.00, 'jars'),
(78, 9, 'Canned Tuna', 1.00, 'cans'),
(79, 9, 'Canned Sardines', 8.00, 'cans'),
(80, 9, 'Canned Corn', 6.00, 'cans'),
(81, 9, 'Canned Mushrooms', 5.00, 'cans'),
(82, 9, 'Canned Tomatoes', 7.00, 'cans'),
(83, 9, 'Canned Beans', 9.00, 'cans'),
(84, 9, 'Canned Peaches', 4.00, 'cans'),
(85, 9, 'Canned Pineapple', 3.00, 'cans'),
(86, 9, 'Canned Soup', 6.00, 'cans'),
(87, 9, 'Canned Milk', 5.00, 'cans'),
(96, 11, 'Espresso Beans', 1.00, 'kg'),
(97, 11, 'Ground Coffee', 5.00, 'kg'),
(98, 11, 'Sugar', 3.00, 'kg'),
(99, 11, 'Milk', 10.00, 'L'),
(100, 11, 'Ice Cubes', 20.00, 'kg'),
(101, 11, 'Vanilla Syrup', 2.00, 'bottles'),
(102, 11, 'Caramel Syrup', 2.00, 'bottles'),
(103, 11, 'Hazelnut Syrup', 1.00, 'bottle'),
(104, 11, 'Matcha Powder', 1.00, 'kg'),
(105, 12, 'Cucumber', 10.00, 'pcs'),
(106, 12, 'Lemon', 5.00, 'pcs'),
(107, 12, 'Pineapple Juice', 3.00, 'L'),
(108, 12, 'Four Seasons Juice', 2.00, 'L'),
(109, 12, 'Matcha Powder', 1.00, 'kg'),
(110, 12, 'Milk', 5.00, 'L'),
(111, 12, 'Sugar', 2.00, 'kg');

-- --------------------------------------------------------

--
-- Table structure for table `inv_menu`
--

CREATE TABLE `inv_menu` (
  `im_id` int(11) NOT NULL,
  `inv_id` int(11) NOT NULL,
  `fid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inv_menu`
--

INSERT INTO `inv_menu` (`im_id`, `inv_id`, `fid`) VALUES
(29, 96, 1),
(30, 19, 1);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `food_id` int(3) NOT NULL,
  `food_name` varchar(100) NOT NULL,
  `price` double NOT NULL,
  `availability` varchar(100) NOT NULL,
  `category` enum('hot coffee','iced coffee','non coffee','sandwich','pastry','pasta','specialty','grilled','stewed') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`food_id`, `food_name`, `price`, `availability`, `category`) VALUES
(1, 'Hot Americano', 85, 'available', 'hot coffee'),
(2, 'Hot Classic Latte', 100, 'available', 'hot coffee'),
(3, 'Hot Caramel Latte', 120, 'available', 'hot coffee'),
(4, 'Hot French Vanilla', 120, 'unavailable', 'hot coffee'),
(5, 'Hot Creamy Vanilla', 120, 'available', 'hot coffee'),
(6, 'Hot Hazelnut', 120, 'available', 'hot coffee'),
(7, 'Hot Matcha Cafe Latte', 140, 'available', 'hot coffee'),
(8, 'Iced Americano', 105, 'available', 'iced coffee'),
(9, 'Iced Classic Latte', 120, 'available', 'iced coffee'),
(10, 'Iced Caramel Latte', 140, 'available', 'iced coffee'),
(11, 'Iced French Vanilla', 140, 'available', 'iced coffee'),
(12, 'Iced Creamy Vanilla', 140, 'available', 'iced coffee'),
(13, 'Iced Hazelnut', 140, 'available', 'iced coffee'),
(14, 'Iced Matcha Cafe Latte', 170, 'available', 'iced coffee'),
(15, 'Cucumber Lemonade', 35, 'available', 'non coffee'),
(16, 'Pineapple Four Seasons', 35, 'available', 'non coffee'),
(17, 'Matcha Latte', 120, 'available', 'non coffee'),
(18, 'Tuna Mayo Sandwich', 95, 'available', 'sandwich'),
(19, 'Chicken Salad Sandwich', 105, 'available', 'sandwich'),
(20, 'Spam Y Queso Boyo', 35, 'available', 'sandwich'),
(21, 'Crema De Fruta', 75, 'available', 'pastry'),
(22, 'Chocolate Ganache Cake', 95, 'available', 'pastry'),
(23, 'Rich Velvety Ube Cake', 95, 'available', 'pastry'),
(24, 'Bibingka', 45, 'available', 'pastry'),
(25, 'Chickene Alfredo', 145, 'available', 'pasta'),
(26, 'Beef Bolognese', 135, 'available', 'pasta'),
(27, 'Spaghetti', 135, 'available', 'pasta'),
(28, 'Dinuguan', 75, 'available', 'specialty'),
(29, 'Linagpang', 75, 'available', 'specialty'),
(30, 'Pork BBQ', 175, 'available', 'grilled'),
(31, 'Chicken BBQ Pecho', 75, 'available', 'grilled'),
(32, 'Chicken BBQ Paa', 95, 'available', 'grilled'),
(33, 'Boneless Bangrus', 195, 'available', 'grilled'),
(34, 'Beef W Mushroom', 155, 'available', 'stewed'),
(35, 'Estufado', 145, 'available', 'stewed'),
(36, 'Chicken Ala King', 125, 'available', 'stewed');

-- --------------------------------------------------------

--
-- Table structure for table `orderitems`
--

CREATE TABLE `orderitems` (
  `orderedItemId` int(3) NOT NULL,
  `oid` int(3) NOT NULL,
  `itemName` varchar(100) NOT NULL,
  `itemNo` int(3) NOT NULL,
  `price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderitems`
--

INSERT INTO `orderitems` (`orderedItemId`, `oid`, `itemName`, `itemNo`, `price`) VALUES
(1, 1, 'Carbonara', 2, 290),
(2, 1, 'Caramel Latte (Iced)', 2, 280),
(8, 7, 'Hot Classic Latte', 1, 100),
(9, 7, 'Hot Caramel Latte', 1, 120),
(10, 8, 'Iced Classic Latte', 1, 120),
(11, 8, 'Iced Caramel Latte', 1, 140),
(12, 8, 'Chickene Alfredo', 2, 145),
(13, 9, 'Hot Classic Latte', 1, 100),
(14, 9, 'Hot Caramel Latte', 1, 120),
(15, 9, 'Chickene Alfredo', 1, 145),
(16, 10, 'Hot Classic Latte', 1, 100),
(17, 10, 'Hot Caramel Latte', 1, 120),
(18, 10, 'Hot French Vanilla', 1, 120),
(20, 12, 'Iced Classic Latte', 1, 120),
(21, 12, 'Iced Caramel Latte', 2, 140),
(22, 12, 'Iced French Vanilla', 1, 140),
(23, 12, 'Chocolate Ganache Cake', 1, 95),
(25, 14, 'Hot Caramel Latte', 1, 120),
(26, 14, 'Hot Classic Latte', 1, 100),
(27, 15, 'Hot Caramel Latte', 3, 120),
(28, 16, 'Hot Caramel Latte', 2, 120);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `oid` int(3) NOT NULL,
  `customerName` varchar(100) NOT NULL,
  `totalPrice` double(10,0) NOT NULL,
  `status` varchar(100) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`oid`, `customerName`, `totalPrice`, `status`, `date`) VALUES
(1, 'Lorenz and Kai', 570, 'complete', '2025-05-24'),
(7, 'simon', 220, 'complete', '2025-05-31'),
(8, 'jill and jj', 550, 'complete', '2025-05-31'),
(9, 'Jonah', 365, 'complete', '2025-05-31'),
(10, 'Anton', 340, 'complete', '2025-06-01'),
(12, 'tom cruz', 635, 'complete', '2025-06-01'),
(14, 'carl', 220, 'complete', '2025-06-05'),
(15, 'Dordus', 360, 'complete', '2025-06-05'),
(16, 'z', 240, 'complete', '2025-06-05');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `uid` int(3) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `keyLvl` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uid`, `username`, `password`, `keyLvl`) VALUES
(5, 'admin', 'cakencoat', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inventory_category`
--
ALTER TABLE `inventory_category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `inventory_item`
--
ALTER TABLE `inventory_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_category` (`category_id`);

--
-- Indexes for table `inv_menu`
--
ALTER TABLE `inv_menu`
  ADD PRIMARY KEY (`im_id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`food_id`);

--
-- Indexes for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD PRIMARY KEY (`orderedItemId`),
  ADD KEY `fk_ordertoitems` (`oid`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`oid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inventory_category`
--
ALTER TABLE `inventory_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `inventory_item`
--
ALTER TABLE `inventory_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `inv_menu`
--
ALTER TABLE `inv_menu`
  MODIFY `im_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `food_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `orderitems`
--
ALTER TABLE `orderitems`
  MODIFY `orderedItemId` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `oid` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `uid` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inventory_item`
--
ALTER TABLE `inventory_item`
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `inventory_category` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD CONSTRAINT `fk_ordertoitems` FOREIGN KEY (`oid`) REFERENCES `orders` (`oid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
