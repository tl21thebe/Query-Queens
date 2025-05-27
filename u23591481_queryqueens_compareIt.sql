-- phpMyAdmin SQL Dump
-- version 5.0.4deb2~bpo10+1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 27, 2025 at 05:47 PM
-- Server version: 10.3.39-MariaDB-0+deb10u2
-- PHP Version: 7.3.31-1~deb10u7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u23591481_queryqueens_compareIt`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `brandID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`brandID`, `name`) VALUES
(1, 'Nike'),
(2, 'Adidas'),
(3, 'Puma'),
(4, 'New Balance'),
(5, 'Reebok'),
(6, 'Asics'),
(7, 'Under Armour'),
(8, 'Fila'),
(9, 'Converse'),
(10, 'Vans'),
(11, 'Birkenstock'),
(12, 'Zara'),
(13, 'Converse'),
(28, 'temus');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `categoryID` int(11) NOT NULL,
  `catType` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`categoryID`, `catType`) VALUES
(1, 'Running'),
(2, 'Casual'),
(3, 'Formal'),
(4, 'Sneakers'),
(5, 'Boots'),
(6, 'Basketball'),
(7, 'Training'),
(8, 'Sandals'),
(9, 'Slip-ons'),
(10, 'Hiking'),
(14, 'Another one'),
(15, 'Update category work');

-- --------------------------------------------------------

--
-- Table structure for table `online_store`
--

CREATE TABLE `online_store` (
  `storeID` int(11) DEFAULT NULL,
  `URL` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `online_store`
--

INSERT INTO `online_store` (`storeID`, `URL`) VALUES
(1, 'https://superbalist.com/'),
(2, 'https://www.takealot.com/'),
(3, 'https://www.nike.com/za/'),
(6, 'https://bash.com/sneaker-factory'),
(9, 'https://www.zando.co.za');

-- --------------------------------------------------------

--
-- Table structure for table `physical_store`
--

CREATE TABLE `physical_store` (
  `storeID` int(11) DEFAULT NULL,
  `country` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `street` varchar(100) NOT NULL,
  `openHours` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `physical_store`
--

INSERT INTO `physical_store` (`storeID`, `country`, `city`, `street`, `openHours`) VALUES
(4, 'South Africa', 'Pretoria', '5th Avenue 123', '10:00-21:00'),
(5, 'South Africa', 'Johannesburg', 'Nicol Drive 456', '09:00-20:00'),
(7, 'South Africa', 'Pretoria', 'Hatfield Avenue 789', '10:00-21:00'),
(8, 'South Africa', 'Durban', 'Collins Avenue 101', '09:00-20:00'),
(10, 'South Africa', 'Cape Town', 'Peachtree Street 303', '10:00-20:00');

-- --------------------------------------------------------

--
-- Table structure for table `reviews_rating`
--

CREATE TABLE `reviews_rating` (
  `reviewID` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `R_userID` int(11) DEFAULT NULL,
  `R_shoesID` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL
) ;

--
-- Dumping data for table `reviews_rating`
--

INSERT INTO `reviews_rating` (`reviewID`, `description`, `R_userID`, `R_shoesID`, `rating`) VALUES
(26, 'Very comfortable, but could get one cheaper for the same comfort', 10, 43, NULL),
(31, 'Add it one time', 10, 43, NULL),
(33, 'These are shoes are amazing!! Absolutely with the price! Cant wait to buy another one in a different colour!!', 10, 25, NULL),
(34, 'Cute and comfortable!', 10, 37, NULL),
(36, 'Love love thesee!', 10, 21, 5),
(37, 'I dont like the material of the laces. Very cehap', 10, 21, 3),
(38, 'Got these as a birthday gift!Thanks mum!', 10, 22, 4),
(39, 'testinggg', 10, 43, 4),
(40, 'Great shoes!Love the design', 14, 22, 4),
(41, 'Looks exactly like the picture.', 14, 29, 5),
(42, 'Love it', 14, 41, 5),
(43, 'Good shoes, even better price', 14, 12, 4),
(44, 'Just started running and these shoes make my experience even better!', 14, 1, 5),
(45, 'Love these', 15, 29, 4),
(46, 'Love these! So comfortable', 15, 24, 5),
(47, 'so cute and comfortable ! Perfect for winter and some cutie outfits ! I am in love', 19, 24, 5),
(48, 'Horrible! Not comfortable at all!!', 23, 24, 1),
(49, 'I love these boots so much perfect for fall , cant wait to see how many more outfits i can style these with', 24, 26, 3),
(50, 'so cute , i love these shoes so much', 1, 60, 5);

-- --------------------------------------------------------

--
-- Table structure for table `shoes`
--

CREATE TABLE `shoes` (
  `shoeID` int(11) NOT NULL,
  `categoryID` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `brandID` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `releaseDate` date NOT NULL,
  `description` text NOT NULL,
  `material` text NOT NULL,
  `gender` enum('Male','Female','Prefer not to say') DEFAULT NULL,
  `image_url` varchar(255) NOT NULL,
  `size_range` varchar(50) DEFAULT NULL,
  `colour` varchar(100) NOT NULL,
  `Upref_stores` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shoes`
--

INSERT INTO `shoes` (`shoeID`, `categoryID`, `name`, `brandID`, `price`, `releaseDate`, `description`, `material`, `gender`, `image_url`, `size_range`, `colour`, `Upref_stores`) VALUES
(1, 1, 'Air Zoom Pegasus 40', 1, '1299.99', '2024-03-15', 'Premium running shoe with responsive Zoom Air cushioning for daily training', 'Mesh/Synthetic', 'Female', 'https://i.pinimg.com/736x/97/8e/f8/978ef8395cef63f22efff742f18f77be.jpg', '7-12', 'Black/White', 1),
(2, 1, 'Ultraboost 23', 1, '1899.99', '2024-02-20', 'Energy-returning running shoe with Boost midsole technology', 'Primeknit', 'Male', 'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/99486859-0ff3-46b4-949b-2d16af2ad421/custom-nike-dunk-high-by-you-shoes.png', '5-10', 'Core Black', 2),
(3, 1, 'Gel-Cumulus 27', 6, '1599.99', '2024-01-10', 'Experience a soft feel underfoot with the new ASICS GEL-CUMULUS 27 running shoes. This neutral everyday trainer is a versatile choice for various running workouts and distances.', 'Mesh/Synthetic', 'Female', 'https://res.cloudinary.com/moresport/image/upload/a_0,c_fill,f_auto,q_auto:good/v1575961299/assets/1180345_1180345-ULB_001.jpg', '5-11', 'Ube/light Ube', 3),
(4, 1, 'Fresh Foam X 1080v13', 4, '1749.99', '2024-04-05', 'Ultra-soft running shoe with Fresh Foam X midsole for maximum comfort', 'Hypoknit', 'Male', 'https://i.pinimg.com/736x/dd/03/57/dd0357e0a3382c9943bd89579d163225.jpg', '7-13', 'Navy/Orange', 4),
(5, 1, 'Hoka Clifton 9', 6, '1649.99', '2024-02-28', 'Lightweight daily trainer with maximum cushioning and smooth ride', 'Engineered Mesh', 'Female', 'https://i.pinimg.com/736x/54/33/a5/5433a5672cdffaf71c2db5f7e6da65a5.jpg', '5-10', 'White/Blue', 5),
(6, 1, 'Floatride Energy 5', 5, '1199.99', '2024-01-25', 'Responsive daily trainer with FloatRide Energy foam', 'Mesh/Synthetic', 'Female', 'https://i.pinimg.com/736x/ed/57/f5/ed57f53cd782aa11203d003e45da5637.jpg', '7-12', 'Blue/Orange', 6),
(7, 2, 'Stan Smith', 2, '899.99', '2024-01-15', 'Iconic casual sneaker with clean white leather design', 'Leather', 'Prefer not to say', 'https://i.pinimg.com/736x/83/76/5f/83765f2e61e233a4a0950138db3d51ef.jpg', '4-12', 'White/Green', 7),
(8, 2, 'Air Force 1 Low', 1, '1199.99', '2024-03-20', 'Classic basketball-inspired lifestyle shoe with Air cushioning', 'Leather', 'Male', 'https://static.nike.com/a/images/t_PDP_1728_v1/f_auto,q_auto:eco/b7d9211c-26e7-431a-ac24-b0540fb3c00f/air-force-1-07-shoes-WrLlWX.png', '6-13', 'Triple White', 8),
(9, 2, 'Suede Classic', 3, '749.99', '2024-02-10', 'Timeless suede sneaker with classic basketball heritage', 'Suede', 'Female', 'https://images.puma.com/image/upload/f_auto,q_auto,b_rgb:fafafa,w_2000,h_2000/global/374915/25/sv01/fnd/PNA/fmt/png/Suede-Classic-XXI-Sneakers', '5-10', 'Blue/White', 9),
(10, 2, 'Authentic Fits', 10, '649.99', '2024-01-30', 'Original skate shoe with waffle outsole and timeless style', 'Canvas', 'Prefer not to say', 'https://i.pinimg.com/736x/81/c5/ad/81c5adec10fca20646fc4d984bb7ba1f.jpg', '6-12', 'Navy', 10),
(11, 2, 'Runner Classics', 5, '899.99', '2024-02-17', 'New Reebok Leather Classic Sneakers Shoes Black Gum', 'Leather', 'Female', 'https://i.pinimg.com/736x/5a/40/3d/5a403d421776654f997b0e502371d0a0.jpg', '7-12', 'Black', 6),
(12, 3, 'Air Max SC', 1, '829.99', '2024-01-25', 'Clean and classic design inspired by heritage running shoes', 'Synthetic Leather', 'Male', 'https://i.pinimg.com/736x/e9/05/e2/e905e2afd70ef5cce74fd3f9f620c4b2.jpg', '7-12', 'Black/White', 1),
(13, 3, 'Leather Loafers', 12, '4999.99', '2024-03-01', 'Luxury Italian leather loafers with classic design', 'Premium Leather', 'Male', 'https://i.pinimg.com/736x/cb/e8/0f/cbe80fee1c1691862b64dcb9d18d2c30.jpg', '7-11', 'Black', 2),
(14, 3, 'Sling Pump', 12, '1899.99', '2024-02-14', 'Sophisticated slingback pumps with designer styling', 'Leather', 'Female', 'https://i.pinimg.com/736x/8c/bf/95/8cbf9503abb8756dd73fadcc6141a108.jpg', '5-10', 'Black', 3),
(15, 3, 'Leather Pumps', 12, '2299.99', '2024-03-05', 'Elegant black leather pumps with pointed toe and mid-height heel', 'Leather', 'Female', 'https://i.pinimg.com/736x/41/fa/3c/41fa3cae913a958a538cb0eb19f7c123.jpg', '5-10', 'Black', 4),
(16, 3, 'Gemari Oxford Shoes', 12, '1599.99', '2024-02-20', 'Classic leather oxford shoes with traditional lace-up design', 'Leather', 'Male', 'https://i.pinimg.com/736x/42/a9/32/42a932917e9c1f01d39bd24183236a06.jpg', '7-12', 'Brown', 5),
(17, 3, 'Las-Botas Boots', 12, '759.99', '2024-02-25', 'sleek black design, a high heel for added height', 'Leather', 'Female', 'https://i.pinimg.com/736x/51/fb/12/51fb129cccfa3e16173f8bd3b90c7a8e.jpg', '5-10', 'Black', 6),
(18, 4, 'Chuck Taylor All Star', 13, '699.99', '2024-01-20', 'Classic canvas sneaker with timeless design', 'Canvas', 'Prefer not to say', 'https://i.pinimg.com/736x/da/cf/cc/dacfcc62ec4e03af6a55591144df3d7e.jpg', '4-13', 'White', 6),
(19, 4, 'Old Skool Sneakers', 10, '849.99', '2024-02-25', 'Iconic skate shoe with waffle outsole and side stripe', 'Canvas/Suede', 'Male', 'https://i.pinimg.com/736x/9f/a1/57/9fa157575229b152b33416330f212fcc.jpg', '6-12', 'Black/White', 7),
(20, 4, 'Gazelle Bold', 2, '1299.99', '2024-03-10', 'Elevated version of iconic Gazelle with platform sole', 'Suede', 'Female', 'https://i.pinimg.com/736x/fe/b6/14/feb614b486afe70a2ab101b357f45dc8.jpg', '5-10', 'Pink', 8),
(21, 4, 'Dunk Low SP', 1, '1599.99', '2024-02-05', 'Basketball-inspired sneaker with premium leather construction', 'Leather', 'Male', 'https://i.pinimg.com/736x/e9/e9/50/e9e950a61366d470be18ff089e49ee36.jpg', '7-12', 'White/Red', 9),
(22, 4, '302 Court Sneakers', 4, '999.99', '2024-01-18', 'Classic court-inspired sneakers with vintage aesthetics', 'Leather/Synthetic', 'Female', 'https://i.pinimg.com/736x/d7/a9/0a/d7a90acc098188bcf1b5854697bf466a.jpg', '5-10', 'White/Blue', 10),
(23, 4, 'Samba XLG', 2, '1199.99', '2024-03-12', 'Oversized version of classic Samba with bold proportions', 'Leather/Suede', 'Male', 'https://i.pinimg.com/736x/b5/f2/51/b5f25103a678753ac01986eab88bd9e0.jpg', '7-12', 'Black/White', 1),
(24, 5, 'UGG-Classic Mini', 12, '1999.99', '2024-01-30', 'Iconic sheepskin boots offering warmth and comfort', 'Sheepskin', 'Female', 'https://i.pinimg.com/736x/76/da/88/76da887ff96fc0091c9b7c7a2b657f02.jpg', '5-10', 'Chestnut', 2),
(25, 5, 'Betty Black Ankle Boots', 12, '1599.99', '2024-02-15', 'Stylish black ankle boots with premium leather construction', 'Leather', 'Female', 'https://i.pinimg.com/736x/55/17/9a/55179a2a7d46fa89e1c26d4e07331339.jpg', '6-10', 'Black', 3),
(26, 5, 'Zara Leather Boots', 12, '1799.99', '2024-03-05', 'Contemporary leather boots with modern silhouette', 'Leather', 'Female', 'https://i.pinimg.com/736x/26/88/b5/2688b54491c99cb45ec46f8938741455.jpg', '5-10', 'Brown', 4),
(27, 5, 'White Combat Boots', 12, '1299.99', '2024-02-22', 'Trendy white leather combat boots with lace-up design', 'Leather', 'Prefer not to say', 'https://i.pinimg.com/736x/41/4d/f8/414df878b1c8413564dead9c5d22d0fa.jpg', '6-11', 'White', 5),
(28, 5, 'Zet-Zair Premium', 12, '1365.99', '2024-01-18', '6-inch Lace Up Waterproof Boot was built for it all', 'Leather', 'Male', 'https://i.pinimg.com/736x/5d/f6/87/5df6874b5f7a3baec6a0b5920c8df7ae.jpg', '9-12', 'Brown', 8),
(29, 6, 'Air Jordan 1 Mid', 1, '1799.99', '2024-02-20', 'Iconic basketball shoe with classic Jordan design', 'Leather/Synthetic', 'Male', 'https://i.pinimg.com/736x/87/40/e7/8740e752c5fe5e410bef7f069f09ef04.jpg', '7-13', 'Black/Green', 6),
(30, 6, 'Dame 8', 2, '1499.99', '2024-01-18', 'Performance basketball shoe with Bounce cushioning', 'Textile/Synthetic', 'Male', 'https://i.pinimg.com/736x/42/b3/58/42b3583227535f188a35fc6bfdc140f1.jpg', '7-13', 'Black/Gold', 7),
(31, 6, 'Clyde All-Pro', 3, '1399.99', '2024-03-08', 'Modern basketball shoe with PUMA Hoops technology', 'Synthetic/Mesh', 'Male', 'https://images.puma.com/image/upload/f_auto,q_auto,b_rgb:fafafa,w_2000,h_2000/global/194039/03/sv01/fnd/PNA/fmt/png/Clyde-All-Pro-Basketball-Shoes', '7-12', 'Blue/White', 8),
(32, 6, 'NB Leaf 574', 4, '699.99', '2024-01-18', 'Classic court-inspired sneakers with vintage aesthetics', 'Leather/Synthetic', 'Female', 'https://i.pinimg.com/736x/0f/98/05/0f9805f202293ee1ac116409d69090c4.jpg', '5-10', 'White/Blue', 10),
(33, 7, 'N-Metcon 9', 1, '1599.99', '2024-03-12', 'Cross-training shoe built for versatile workouts', 'Synthetic/Mesh', 'Male', 'https://i.pinimg.com/736x/13/87/2e/13872ee99c517f8ab921992120228b6b.jpg', '7-12', 'Black/Blue', 9),
(34, 7, 'UA HOVR Rise 4', 7, '1299.99', '2024-02-08', 'Training shoe with HOVR cushioning technology', 'Mesh/Synthetic', 'Female', 'https://i.pinimg.com/736x/d9/25/8e/d9258e4200f2cdf39c5dd76688fc7c38.jpg', '5-10', 'White/Pink', 10),
(35, 7, 'Nano X3', 5, '1399.99', '2024-01-22', 'CrossFit training shoe with enhanced stability and durability', 'Synthetic/Textile', 'Male', 'https://i.pinimg.com/736x/18/b4/e7/18b4e7fef3457cdf0b8c86ed157afdf4.jpg', '7-13', 'Black/Red', 1),
(36, 7, 'Gel-Cumulus 26', 6, '1599.99', '2024-01-10', 'Neutral running shoe with GEL technology for superior shock absorption', 'Mesh/Synthetic', 'Female', 'https://i.pinimg.com/1200x/6a/32/24/6a32243c45aadea1456f45e768a1ff37.jpg', '5-11', 'Pink/White', 3),
(37, 8, 'Arizona Sandal Suite', 11, '899.99', '2024-01-22', 'Classic two-strap sandal with contoured footbed', 'Birko-Flor', 'Prefer not to say', 'https://i.pinimg.com/736x/77/28/b4/7728b4b399ee1b1ea2c644478b903891.jpg', '6-12', 'Brown', 2),
(38, 8, 'Calm Sandals', 1, '649.99', '2024-02-18', 'Minimalist slip-on sandals with soft foam cushioning', 'Foam/Rubber', 'Prefer not to say', 'https://i.pinimg.com/736x/e3/b0/f3/e3b0f35257197971df54dcfe4afd7898.jpg', '6-12', 'Black', 3),
(39, 8, 'Steve Madden Platform', 11, '1199.99', '2024-03-08', 'Trendy platform sandals with chunky sole design', 'Synthetic', 'Female', 'https://i.pinimg.com/736x/5b/5e/1f/5b5e1f46bc6e731d6f3f1753c10f67c5.jpg', '5-10', 'Beige', 4),
(40, 8, 'Boston Clog', 11, '1299.99', '2024-02-25', 'Soft footbed suede clogs with adjustable strap', 'Suede', 'Prefer not to say', 'https://i.pinimg.com/736x/c3/97/3c/c3973c19876b20c7c9acc4ecd7ccc501.jpg', '6-11', 'Taupe', 5),
(41, 8, 'Adilette Lite', 2, '1499.99', '2024-01-18', 'Performance Adidas Orignals Adilette Lite Slide', 'Textile/Synthetic', 'Male', 'https://i.pinimg.com/736x/30/cb/f8/30cbf8743e2437ff5a569bbe816dd53d.jpg', '7-13', 'Black', 7),
(42, 9, 'Classic Slip-On', 10, '749.99', '2024-01-28', 'Iconic slip-on shoe with checkerboard pattern', 'Canvas', 'Prefer not to say', 'https://i.pinimg.com/736x/2d/0c/c2/2d0cc223a9ea3c36ac86257b69bdae61.jpg', '6-12', 'Black/White', 6),
(43, 9, 'Adilette Comfort', 2, '399.99', '2024-02-12', 'Comfortable slip-on slides for post-workout recovery', 'Synthetic', 'Prefer not to say', 'https://i.pinimg.com/736x/86/bc/50/86bc5073c126976750a8142b98973f7f.jpg', '6-12', 'Black', 7),
(44, 9, 'Victori One', 1, '499.99', '2024-03-15', 'Lightweight slip-on slides with textured footbed', 'Synthetic', 'Male', 'https://i.pinimg.com/736x/58/55/15/5855150e4b4f514ad8e8c2714f2ffe19.jpg', '7-12', 'Black/White', 8),
(45, 9, 'Comfort Loaf Slip-ons', 4, '1599.99', '2024-02-05', 'Therapeutic loafers with orthopedic support and cushioned insole', 'Leather', 'Male', 'https://i.pinimg.com/736x/bf/6d/b4/bf6db4ccbdc020fde49e00061790db80.jpg', '7-12', 'Brown', 9),
(46, 9, 'NR Slip-ons', 4, '1749.99', '2024-04-05', 'Ultra-soft comfort slip', 'Hypoknit', 'Male', 'https://i.pinimg.com/736x/8e/9e/d5/8e9ed5e2140f38e0eb69aaca7e017a58.jpg', '7-13', 'Navy/Orange', 4),
(47, 10, 'Merrell Moab 3', 4, '1999.99', '2024-03-18', 'Durable hiking shoe with Vibram outsole and breathable mesh', 'Suede/Mesh', 'Male', 'https://i.pinimg.com/736x/89/85/ea/8985ea773c5ca99ca2c2538cf851a9d0.jpg', '7-13', 'Brown/Orange', 10),
(48, 10, 'Salomon X Ultra 4', 5, '2199.99', '2024-02-22', 'Technical hiking shoe with advanced grip technology', 'Synthetic/Textile', 'Female', 'https://i.pinimg.com/736x/f8/6d/9a/f86d9a53b9d81e0a3f7ce82958a63f6e.jpg', '5-10', 'Grey/Blue', 1),
(49, 10, 'Columbia Redmond III', 7, '1299.99', '2024-01-16', 'Waterproof hiking shoe with Omni-Grip outsole', 'Leather/Mesh', 'Male', 'https://i.pinimg.com/736x/fc/dd/79/fcdd798c4ca6cc020b977b623035a72e.jpg', '7-12', 'Brown', 2),
(50, 10, 'Gel-Venture 9', 6, '999.99', '2024-03-01', 'Trail running shoe with GEL technology for outdoor adventures', 'Synthetic/Mesh', 'Female', 'https://i.pinimg.com/736x/a7/dc/57/a7dc5740ea7ed285ecf6735b77026eb8.jpg', '5-10', 'Black/Pink', 3),
(60, 2, 'Pink Handball Spezial', 2, '1899.00', '2025-05-26', 'Originally designed for fast-paced indoor sports, these adidas Handball Spezial Shoes playing days are done but keep your style game strong. Crafted for comfort, their leather and textile upper surrounds a synthetic lining. Underneath, a rubber outsole maintains grip on city streets and beyond. A timeless T-toe classic, the Spezial stands out with serrated 3-Stripes and gold-letter branding along the side. Bring the energy and attitude of sport to your everyday hustle with these iconic low-profile trainers.', '', 'Prefer not to say', 'https://i.pinimg.com/736x/2e/44/a4/2e44a4870355edb5ce85ff0dc5fc4d3f.jpg', NULL, 'Black', NULL),
(62, 2, 'Blue Handball Spezial', 2, '1899.00', '2025-05-26', 'A retro classic reborn. Originally released in the 70s, the Handball Spezial has been revived for today. The suede and textile upper provides a vintage look with modern comfort while a gum rubber outsole offers grip and durability. A legend of the adidas archives, these shoes continue their legacy of laid-back cool.', '', 'Prefer not to say', 'https://i.pinimg.com/736x/c6/b9/31/c6b931d21b699e893f22fb6916259769.jpg', NULL, 'Black', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `storeID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `shoeID` varchar(50) DEFAULT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`storeID`, `name`, `shoeID`, `email`) VALUES
(1, 'Superbalist', '23', 'info@superbalist.com'),
(2, 'Takealot', '26', 'info@takealot.com'),
(3, 'Nike Store', '46', 'info@nikestore.com'),
(4, 'Adidas SA', '39', 'info@adidassa.com'),
(5, 'Sportscene', '2', 'info@sportscene.com'),
(6, 'Sneaker Factory', '8', 'info@sneakerfactory.com'),
(7, 'Footgear', '35', 'info@footgear.com'),
(8, 'SideStep', '48', 'info@sidestep.com'),
(9, 'Zando', '8', 'info@zando.com'),
(10, 'Archive', '46', 'info@archive.com');

-- --------------------------------------------------------

--
-- Table structure for table `stores_phoneno`
--

CREATE TABLE `stores_phoneno` (
  `storeID` int(11) NOT NULL,
  `st_phoneNo` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stores_phoneno`
--

INSERT INTO `stores_phoneno` (`storeID`, `st_phoneNo`) VALUES
(1, '087-362-7300'),
(1, '087-362-8000'),
(2, '087-758-8619'),
(3, '086-000-1004'),
(4, '060-944-4884'),
(4, '066-456-7891'),
(5, '055-567-8901'),
(6, '044-678-9012'),
(7, '033-789-0123'),
(8, '022-890-1234'),
(9, '011-901-2345'),
(10, '080-012-3456');

-- --------------------------------------------------------

--
-- Table structure for table `store_inventory`
--

CREATE TABLE `store_inventory` (
  `storeID` int(11) NOT NULL,
  `shoeID` int(11) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `store_inventory`
--

INSERT INTO `store_inventory` (`storeID`, `shoeID`, `price`) VALUES
(1, 1, '1299.99'),
(1, 5, '1471.17'),
(1, 7, '1677.01'),
(1, 8, '1072.07'),
(1, 13, '1545.28'),
(1, 22, '1566.08'),
(1, 37, '1694.84'),
(1, 38, '1544.52'),
(1, 42, '855.09'),
(1, 47, '1151.79'),
(2, 1, '1249.00'),
(2, 3, '1265.31'),
(2, 12, '1047.37'),
(2, 18, '1239.49'),
(2, 24, '1140.74'),
(2, 27, '1678.17'),
(2, 28, '799.20'),
(2, 35, '1295.35'),
(2, 37, '1628.80'),
(2, 39, '1178.46'),
(2, 44, '1030.32'),
(2, 47, '854.26'),
(2, 50, '1601.88'),
(3, 2, '1023.12'),
(3, 14, '1030.70'),
(3, 15, '925.48'),
(3, 18, '1126.51'),
(3, 20, '1336.06'),
(3, 21, '1213.06'),
(3, 23, '1466.75'),
(3, 28, '898.27'),
(3, 30, '1423.12'),
(3, 38, '1472.95'),
(3, 44, '1364.04'),
(4, 2, '1199.50'),
(4, 5, '818.37'),
(4, 7, '1427.87'),
(4, 8, '1339.42'),
(4, 11, '1256.76'),
(4, 14, '1291.37'),
(4, 22, '817.14'),
(4, 34, '1388.88'),
(4, 39, '1062.93'),
(4, 41, '939.68'),
(4, 43, '1307.11'),
(4, 45, '1229.97'),
(4, 47, '1389.49'),
(5, 3, '1095.75'),
(5, 8, '1767.56'),
(5, 21, '1798.36'),
(5, 22, '1502.61'),
(5, 30, '1376.87'),
(5, 33, '1740.67'),
(5, 41, '1072.77'),
(5, 43, '1786.50'),
(5, 46, '1376.02'),
(6, 8, '1547.57'),
(6, 29, '1007.32'),
(6, 36, '1781.22'),
(6, 39, '1545.07'),
(6, 50, '1055.43'),
(7, 3, '1120.00'),
(7, 7, '1734.11'),
(7, 9, '976.38'),
(7, 14, '1567.16'),
(7, 17, '1322.02'),
(7, 22, '1429.62'),
(7, 33, '1379.86'),
(7, 50, '1086.06'),
(8, 1, '1385.86'),
(8, 5, '899.99'),
(8, 7, '1603.34'),
(8, 14, '1594.44'),
(8, 15, '1478.95'),
(8, 20, '970.80'),
(8, 24, '1056.13'),
(8, 28, '1003.88'),
(8, 39, '1749.59'),
(8, 40, '1425.08'),
(9, 4, '1350.00'),
(9, 12, '1031.78'),
(9, 13, '1050.44'),
(9, 14, '1023.24'),
(9, 17, '1317.08'),
(9, 25, '996.67'),
(9, 26, '1651.37'),
(9, 30, '1490.45'),
(9, 32, '1540.87'),
(9, 40, '1479.09'),
(9, 45, '799.47'),
(9, 47, '1694.71'),
(10, 3, '1714.93'),
(10, 4, '1399.90'),
(10, 6, '811.69'),
(10, 8, '1294.59'),
(10, 9, '922.56'),
(10, 10, '913.40'),
(10, 12, '1020.35'),
(10, 14, '1399.78'),
(10, 16, '1694.17'),
(10, 18, '1103.73'),
(10, 19, '1242.47'),
(10, 20, '1119.93'),
(10, 22, '1525.51'),
(10, 26, '1721.77'),
(10, 29, '1260.04'),
(10, 43, '1594.10'),
(10, 45, '1246.32');

-- --------------------------------------------------------

--
-- Table structure for table `userpref_brands`
--

CREATE TABLE `userpref_brands` (
  `userPrefID` int(11) NOT NULL,
  `Upref_brands` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userpref_brands`
--

INSERT INTO `userpref_brands` (`userPrefID`, `Upref_brands`) VALUES
(1, 'Zara'),
(2, 'Nike'),
(3, 'Gucci'),
(4, 'Nike'),
(5, '7'),
(6, '4'),
(7, '4'),
(8, '12'),
(9, '6'),
(10, '11'),
(11, '11'),
(12, '13'),
(13, '2'),
(14, '12'),
(15, '12'),
(16, '13'),
(17, '13'),
(18, '1');

-- --------------------------------------------------------

--
-- Table structure for table `userpref_cat`
--

CREATE TABLE `userpref_cat` (
  `userPrefID` int(11) NOT NULL,
  `Upref_categ` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userpref_cat`
--

INSERT INTO `userpref_cat` (`userPrefID`, `Upref_categ`) VALUES
(1, 2),
(2, 6),
(3, 4),
(4, 3),
(5, 5),
(6, 7),
(7, 7),
(8, 5),
(9, 6),
(10, 2),
(11, 2),
(12, 6),
(13, 6),
(14, 5),
(15, 5),
(16, 6),
(17, 7),
(18, 2);

-- --------------------------------------------------------

--
-- Table structure for table `userpref_stores`
--

CREATE TABLE `userpref_stores` (
  `userPrefID` int(11) NOT NULL,
  `Upref_stores` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userpref_stores`
--

INSERT INTO `userpref_stores` (`userPrefID`, `Upref_stores`) VALUES
(1, 3),
(2, 5),
(3, 6),
(4, 5),
(5, 4),
(6, 7),
(7, 7),
(8, 2),
(9, 10),
(10, 10),
(11, 10),
(12, 4),
(13, 8),
(14, 2),
(15, 1),
(16, 3),
(17, 7),
(18, 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `registrationDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `name` varchar(100) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `phoneNo` varchar(20) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `street` varchar(100) DEFAULT NULL,
  `user_type` enum('Customer','Admin') DEFAULT NULL,
  `apiKey` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `password`, `email`, `registrationDate`, `name`, `surname`, `phoneNo`, `country`, `city`, `street`, `user_type`, `apiKey`) VALUES
(1, '$2y$10$dBnr.qKF5xiHYkx4/VqUqO9wcXtGpSoEXJNc/.wFM48tsHtnpt8Nu', 'didi@gmail.com', '2025-05-21 18:58:21', 'Didi', 'Tlaka', '', '', '', '', 'Customer', 'f3ba53c60d079ebe1809615c31a350bb'),
(2, '$2y$10$mj3yA99CWCZk4PDuOcBQoetlIYZuCVnba1FY7qplHWaNAKG84K/j6', 'test@example.com', '2025-05-21 20:55:28', 'Test', 'User', '000-000-0000', 'South Africa', 'Pretoria', 'Default Street', 'Customer', 'e101a4fd42d3fffbee5ff34b5cedaa5c'),
(3, '$2y$10$.rTuMBsC0vMO/hu1vlkMoujKdSZrZbB1YBOc4.mzFS4KQhC1EZhGu', 'aisha@example.com', '2025-05-22 08:49:11', 'Aisha', 'ibrahim', '000-000-0000', 'South Africa', 'Pretoria', 'Default Street', NULL, 'bc86aea49f36520742ba52368f07316a'),
(4, '$2y$10$xzSaG3nRHUD3Ay7M46Vm8.2z2Xjnx.MQ1r5/q1wEL6O/eiLiWK9SK', 'sabira@gmail.com', '2025-05-22 08:52:38', 'Sabira', 'Karie', '000-000-0000', 'South Africa', 'Pretoria', 'Default Street', NULL, 'bed20a21cb5bed559b86c43df491dec8'),
(7, '$2y$10$B8lQ7gFqR4gE6Fr8.92NHumcyobAH6kb60NozmzDCKXni1kcCGNS6', 'barbie@gmail.com', '2025-05-22 13:05:03', 'Barbie', 'Malibu', NULL, NULL, NULL, NULL, 'Customer', 'cd2ec9a9d4dcab6d597fcce2f5e75041'),
(8, '$2y$10$nEC/vSnsJz3NAFAQ9P92oe8oTQ1qEsnhtw5YJC3ZGu7NF/TTuR0w6', 'iron@gmail.com', '2025-05-22 14:58:35', 'Iron', 'Man', '000-000-0000', 'South Africa', 'Pretoria', 'Default Street', NULL, '6f3bddd57dc21e7a3126bf6d6414f0b6'),
(10, '$2y$10$WxO/O6aJN7eTgAP/fjCoouNWkk9IkfW9bfMeL5/zRBOc6mDXpI6Ku', 'sabirakarie@gmail.com', '2025-05-24 05:09:33', 'Sabira', 'Karie', '000-000-0000', 'South Africa', 'Pretoria', 'Default Street', NULL, 'fa9358fa72f9b1654dac78ee3a30358b'),
(14, '$2y$10$1Iumhsrv6.Sll4Eaye20tu.Oy3efR3G3zGqYYAFnsDm30j9b2Q.ta', 'tester@gmail.com', '2025-05-24 08:49:08', 'Testeer', 'review', '000-000-0000', 'South Africa', 'Pretoria', 'Default Street', NULL, 'd9c20dade5fc1796003d7c5768212e7d'),
(15, '$2y$10$BRgr92r5fYt5UJy3p8J45u2HOVDHF5958SGFhKIBCIG9sqVX9YsEq', 'testingregister@gmail.com', '2025-05-24 10:31:21', 'Final', 'TesterForregister', '0789081234', 'Zambia', 'Lusaka', '123 WallStreet', 'Customer', '14e350a4ad8b719b2e88c5869d4b3b89'),
(16, '$2y$10$ZMJVuyhA2g.8GDlVPqVnJOgVMcyiEoJEQOtpi4uJU0bgvoxZ8j4sy', 'abyan@gmail.com', '2025-05-24 11:02:08', 'Abyan', 'Karie', '0617891234', 'South Africa', 'Pretoria', '123 MoneyStreet', 'Admin', '867cd3ee21988a199d67b8afa294a16c'),
(17, '$2y$10$rpxuUZQzSqxRQX4N.XwrPecyZ2tVEK5IQnVnTIxqsLsXkgPp6XXxu', 'giftmohuba1@gmail.com', '2025-05-24 12:46:33', 'Gift', 'Mohuba', '0813036172', 'South Africa', 'TEMBISA', '2013', 'Admin', 'b815c32ff773dacdf1b87ed9009add2e'),
(18, '$2y$10$rKLGMjPYGQstydnLkmsZvOAqnJnr3DYJddpWY3QQ71i.jTpirf4lu', 'giftmohuba15@gmail.com', '2025-05-24 13:19:40', 'lebo', 'Mohuba', '0813036172', 'South Africa', 'TEMBISA', '2013', 'Customer', 'a2a7648397849e93bc78e735d9d59b2c'),
(19, '$2y$10$7BUyBg6UGZkQ8uxqUr5QBuWzRs.eFnqXXVx5aQ1uHM.K7MJKrXObe', 'sam@gmail.com', '2025-05-25 17:17:42', 'sam', 'pucket', '012 345 678', 'South Africa', 'Pretoria', 'Burnet', 'Admin', 'e59a9325541e54ae1ac13cecbae17117'),
(20, '$2y$10$eKDw8AlaMuptLnHl38AlB.sNOp.nvzSkWbF91CpHhLl.HOOYBru32', 'nailaahmed@gmail.com', '2025-05-25 18:48:06', 'Naila', 'Ahmed', '0781236789', 'South Africa ', 'Cape Town', '234 Milky Street', 'Customer', '7e09a605611c5c2e5b9fba1f6607531d'),
(21, '$2y$10$WejNEI4puOki/L8d0LyeAeQgBuf3Pz.8Y4BEgwWAFE70TOEU8BbRm', 'ibrahim@gmail.com', '2025-05-26 09:06:50', 'Ibrahim', 'Karie', '0981237890', 'South Africa', 'Pretoria', '190 Money Street', 'Admin', '7d64b29cd0d7ed46422b2208553e9b0e'),
(22, '$2y$10$VJg3RKAO5mvh4FsQh3z/7.EiONJHADH7cF3NJ5LzpJykB354P3kb2', 'langavaks@gmail.com', '2025-05-26 09:19:18', 'Langazelelwa', 'Vakalisa', '0718765763', 'South Africa', 'Pretoria', 'UniversityRd', 'Admin', '618273f85341600afd7b9093d1f09f93'),
(23, '$2y$10$cx7ruF6AzJG4BMa54FTiBOZzHoDKk5ivOCpbeg0LmxlPx.I2tjpA2', 'kwanele@gmail.com', '2025-05-26 12:34:08', 'Kwanele', 'Rob', '0781234567', 'Zambia', 'Lusaka', '89 Small Street', 'Customer', 'f4c86a8fb5e2a112aeffd81ab7374ae1'),
(24, '$2y$10$/8j9dO6sWfonK5BjiC6P8OrQdnd1eV1J19Gc95g.DINPlpa8ZV.FO', 'hlohlotlaka@gmail.com', '2025-05-26 14:12:34', 'hlohlo', 'tlaka', '1234567890', 'South Africa', 'Pretoria', 'Stellenberg', 'Customer', 'af46c671b3577c39c37ce2be64121152'),
(25, '$2y$10$c2LJ.cYc7Zwp8x/MAzXhkO5GXva.7/7iimgEM6hVhp..7tJ5Tj9i6', 'ismeal@gmail.com', '2025-05-27 08:15:13', 'Ismeal', 'Karie', '0781237890', 'Burkino Faso', 'Big City', '190 Milky Street', 'Customer', '493234397826bafe4b28ec1afb7f22cc'),
(26, '$2y$10$/2YR.QQzU/HUJOu/L4tw4ehvByqQXjbU3Po9u1riQWQuxhmdzYtra', 'anepha157@gmail.com', '2025-05-27 14:17:23', 'Kwanele', 'Phakathi', '0798067804', 'South Africa', 'Phalaborwa', '33 Forssman Street', 'Customer', '9d6a8ac34d8808d57e58b267b676930f');

-- --------------------------------------------------------

--
-- Table structure for table `user_phoneno`
--

CREATE TABLE `user_phoneno` (
  `userID` int(11) NOT NULL,
  `U_phoneNo` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_phoneno`
--

INSERT INTO `user_phoneno` (`userID`, `U_phoneNo`) VALUES
(1, ''),
(2, '000-000-0000'),
(3, '000-000-0000'),
(4, '000-000-0000'),
(8, '000-000-0000'),
(10, '000-000-0000'),
(14, '000-000-0000'),
(15, '0789081234'),
(16, '0617891234'),
(17, '0813036172'),
(18, '0813036172'),
(19, '012 345 678'),
(20, '0781236789'),
(21, '0981237890'),
(22, '0718765763'),
(23, '0781234567'),
(24, '1234567890');

-- --------------------------------------------------------

--
-- Table structure for table `user_preferences`
--

CREATE TABLE `user_preferences` (
  `userpref_ID` int(11) NOT NULL,
  `userpref_UserID` int(11) NOT NULL,
  `max_price` decimal(10,2) DEFAULT NULL,
  `min_price` decimal(10,2) DEFAULT NULL,
  `only_available` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_preferences`
--

INSERT INTO `user_preferences` (`userpref_ID`, `userpref_UserID`, `max_price`, `min_price`, `only_available`) VALUES
(1, 14, '1500.00', '500.00', 1),
(2, 14, '800.00', '900.00', 1),
(3, 14, '2000.00', '1000.00', 1),
(4, 14, '900.00', '678.00', 1),
(5, 15, '3000.00', '800.00', 1),
(6, 15, '1000.00', '300.00', 1),
(7, 15, '1000.00', '300.00', 1),
(8, 15, '2000.00', '1000.00', 1),
(9, 15, NULL, NULL, 0),
(10, 15, '2900.00', '1200.00', 1),
(11, 15, '2900.00', '1200.00', 1),
(12, 15, '2300.00', '700.00', 1),
(13, 20, '1267.00', '780.00', 1),
(14, 14, '2000.00', '200.00', 1),
(15, 14, '2300.00', '500.00', 1),
(16, 14, '2400.00', '500.00', 1),
(17, 20, '2300.00', '500.00', 1),
(18, 1, '2000.00', '500.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_shoes`
--

CREATE TABLE `user_shoes` (
  `h_userID` int(11) NOT NULL,
  `h_shoeID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`brandID`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categoryID`);

--
-- Indexes for table `online_store`
--
ALTER TABLE `online_store`
  ADD KEY `storeID` (`storeID`);

--
-- Indexes for table `physical_store`
--
ALTER TABLE `physical_store`
  ADD KEY `storeID` (`storeID`);

--
-- Indexes for table `reviews_rating`
--
ALTER TABLE `reviews_rating`
  ADD PRIMARY KEY (`reviewID`),
  ADD KEY `R_userID` (`R_userID`),
  ADD KEY `R_shoesID` (`R_shoesID`);

--
-- Indexes for table `shoes`
--
ALTER TABLE `shoes`
  ADD PRIMARY KEY (`shoeID`),
  ADD KEY `brandID` (`brandID`),
  ADD KEY `idx_categoryID` (`categoryID`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`storeID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `stores_phoneno`
--
ALTER TABLE `stores_phoneno`
  ADD PRIMARY KEY (`storeID`,`st_phoneNo`);

--
-- Indexes for table `store_inventory`
--
ALTER TABLE `store_inventory`
  ADD PRIMARY KEY (`storeID`,`shoeID`),
  ADD KEY `shoeID` (`shoeID`);

--
-- Indexes for table `userpref_brands`
--
ALTER TABLE `userpref_brands`
  ADD PRIMARY KEY (`userPrefID`,`Upref_brands`);

--
-- Indexes for table `userpref_cat`
--
ALTER TABLE `userpref_cat`
  ADD PRIMARY KEY (`userPrefID`,`Upref_categ`);

--
-- Indexes for table `userpref_stores`
--
ALTER TABLE `userpref_stores`
  ADD PRIMARY KEY (`userPrefID`,`Upref_stores`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_phoneno`
--
ALTER TABLE `user_phoneno`
  ADD PRIMARY KEY (`userID`,`U_phoneNo`);

--
-- Indexes for table `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD PRIMARY KEY (`userpref_ID`),
  ADD KEY `userpref_UserID` (`userpref_UserID`);

--
-- Indexes for table `user_shoes`
--
ALTER TABLE `user_shoes`
  ADD PRIMARY KEY (`h_userID`,`h_shoeID`),
  ADD KEY `h_shoeID` (`h_shoeID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `brandID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `categoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `reviews_rating`
--
ALTER TABLE `reviews_rating`
  MODIFY `reviewID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shoes`
--
ALTER TABLE `shoes`
  MODIFY `shoeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `storeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `user_preferences`
--
ALTER TABLE `user_preferences`
  MODIFY `userpref_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `online_store`
--
ALTER TABLE `online_store`
  ADD CONSTRAINT `online_store_ibfk_1` FOREIGN KEY (`storeID`) REFERENCES `stores` (`storeID`);

--
-- Constraints for table `physical_store`
--
ALTER TABLE `physical_store`
  ADD CONSTRAINT `physical_store_ibfk_1` FOREIGN KEY (`storeID`) REFERENCES `stores` (`storeID`);

--
-- Constraints for table `reviews_rating`
--
ALTER TABLE `reviews_rating`
  ADD CONSTRAINT `reviews_rating_ibfk_1` FOREIGN KEY (`R_userID`) REFERENCES `users` (`userID`),
  ADD CONSTRAINT `reviews_rating_ibfk_2` FOREIGN KEY (`R_shoesID`) REFERENCES `shoes` (`shoeID`);

--
-- Constraints for table `shoes`
--
ALTER TABLE `shoes`
  ADD CONSTRAINT `shoes_ibfk_1` FOREIGN KEY (`brandID`) REFERENCES `brands` (`brandID`);

--
-- Constraints for table `stores_phoneno`
--
ALTER TABLE `stores_phoneno`
  ADD CONSTRAINT `stores_phoneno_ibfk_1` FOREIGN KEY (`storeID`) REFERENCES `stores` (`storeID`);

--
-- Constraints for table `store_inventory`
--
ALTER TABLE `store_inventory`
  ADD CONSTRAINT `store_inventory_ibfk_1` FOREIGN KEY (`storeID`) REFERENCES `stores` (`storeID`) ON DELETE CASCADE,
  ADD CONSTRAINT `store_inventory_ibfk_2` FOREIGN KEY (`shoeID`) REFERENCES `shoes` (`shoeID`) ON DELETE CASCADE;

--
-- Constraints for table `userpref_brands`
--
ALTER TABLE `userpref_brands`
  ADD CONSTRAINT `userpref_brands_ibfk_1` FOREIGN KEY (`userPrefID`) REFERENCES `user_preferences` (`userpref_ID`);

--
-- Constraints for table `userpref_cat`
--
ALTER TABLE `userpref_cat`
  ADD CONSTRAINT `userpref_cat_ibfk_1` FOREIGN KEY (`userPrefID`) REFERENCES `user_preferences` (`userpref_ID`);

--
-- Constraints for table `userpref_stores`
--
ALTER TABLE `userpref_stores`
  ADD CONSTRAINT `userpref_stores_ibfk_1` FOREIGN KEY (`userPrefID`) REFERENCES `user_preferences` (`userpref_ID`);

--
-- Constraints for table `user_phoneno`
--
ALTER TABLE `user_phoneno`
  ADD CONSTRAINT `user_phoneno_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);

--
-- Constraints for table `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD CONSTRAINT `user_preferences_ibfk_1` FOREIGN KEY (`userpref_UserID`) REFERENCES `users` (`userID`);

--
-- Constraints for table `user_shoes`
--
ALTER TABLE `user_shoes`
  ADD CONSTRAINT `user_shoes_ibfk_1` FOREIGN KEY (`h_userID`) REFERENCES `users` (`userID`),
  ADD CONSTRAINT `user_shoes_ibfk_2` FOREIGN KEY (`h_shoeID`) REFERENCES `shoes` (`shoeID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
