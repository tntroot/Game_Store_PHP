-- --------------------------------------------------------
-- 主機:                           localhost
-- 伺服器版本:                        10.4.32-MariaDB - mariadb.org binary distribution
-- 伺服器作業系統:                      Win64
-- HeidiSQL 版本:                  11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- 傾印 fantasy_universe 的資料庫結構
CREATE DATABASE IF NOT EXISTS `fantasy_universe` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `fantasy_universe`;

-- 傾印  資料表 fantasy_universe.comments 結構
CREATE TABLE IF NOT EXISTS `comments` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `game_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `text` text DEFAULT NULL COMMENT '留言',
  `reply` bigint(20) DEFAULT 0 COMMENT '回覆某人，0 為不回復',
  `created_at` datetime DEFAULT NULL COMMENT '回覆日期時間',
  PRIMARY KEY (`id`),
  KEY `game_id` (`game_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 正在傾印表格  fantasy_universe.comments 的資料：~7 rows (近似值)
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` (`id`, `game_id`, `user_id`, `text`, `reply`, `created_at`) VALUES
	(16, 7, 1, '創造你的人生', 0, '2024-08-11 21:56:42'),
	(17, 7, 1, '改變你的命運', 16, '2024-08-11 21:56:59'),
	(19, 7, 1, '這就是我的未來', 16, '2024-08-11 21:57:49'),
	(23, 7, 1, '在這個世界生存下去吧!', 0, '2024-08-11 21:59:55'),
	(24, 7, 1, '這就是做後一課！', 23, '2024-08-11 22:00:23'),
	(30, 9, 1, '55', 0, '2024-08-12 02:38:58'),
	(31, 9, 1, '455', 0, '2024-08-12 02:39:00');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;

-- 傾印  資料表 fantasy_universe.game_article_data 結構
CREATE TABLE IF NOT EXISTS `game_article_data` (
  `game_id` bigint(20) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `system` varchar(100) DEFAULT NULL COMMENT '系統',
  `cpu` varchar(100) DEFAULT NULL,
  `ram` char(10) DEFAULT NULL COMMENT '記憶體',
  `display_card` varchar(100) DEFAULT NULL,
  `directX` char(12) DEFAULT NULL COMMENT '顯示卡',
  `rom` char(10) DEFAULT NULL COMMENT '儲存空間',
  `date` datetime DEFAULT NULL COMMENT '上架時間',
  KEY `game_id` (`game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 正在傾印表格  fantasy_universe.game_article_data 的資料：~2 rows (近似值)
/*!40000 ALTER TABLE `game_article_data` DISABLE KEYS */;
INSERT INTO `game_article_data` (`game_id`, `content`, `system`, `cpu`, `ram`, `display_card`, `directX`, `rom`, `date`) VALUES
	(8, '《冰與火之舞》——高難度單鍵節奏遊戲。\r\n\r\n只需一個按鍵，控制盤旋飛舞的雙星，踏著搖曳的舞步，在一條跟隨音樂節奏變化的蜿蜒道路上不斷前進，探索音樂的宇宙。\r\n\r\n將節奏巧妙地使用可視的形式呈現出來。\r\n這將是一次充滿挑戰的音樂之旅，保持平穩的心態，跟隨內心的律動，這並不是一款依賴快速反應的遊戲，你需要通過反復練習來學習不同的音樂模式。\r\n你將與各種困難不期而遇，而每一次突破，都會帶來無與倫比的成就感。\r\n\r\n\r\n\r\n探索音樂宇宙：每一片星系都對應著一種截然不同的音樂類型，跟隨糾纏的雙星遊蕩在充滿美妙音樂的宇宙當中。\r\n可預判的機制： 你能通過識別前方的道路來預判即將迎來的音軌節奏。遊戲並不依賴快速反應，註意聆聽音樂，反復練習，掌控應對不同類型的節奏的操作。\r\n未來的音樂關卡將免費更新：我們會在遊戲發售後陸續更新更多新關卡，已經購買過遊戲的玩家無須為這些更新再次付費。但遊戲的售價可能會隨著更新有所提升。\r\n支持延遲校準：既可以隨時通過按鍵手動校準，也可以使用我們的自動校準功能。作為音樂創作者，我們非常了解音軌不同步會極大地影響音樂遊戲的體驗，因此，這款遊戲采用了極其嚴格的時間判定方式。\r\n飆速模式：完成一周目後，你將迎來速度與挑戰不斷飆升的全新模式。專為那些永不滿足的玩家準備。\r\n只需一個按鍵: 你可以使用鍵盤上的大多數按鍵控制這個遊戲。理論上，可以支持多種外設。你甚至可以使用太鼓來遊玩本遊戲。', 'Windows 7 or later', '', '2GB', 'Intel Graphics 4000, 2GB VRAM', '', '1.5G', '2024-08-07 00:00:00'),
	(9, '《重力美術館 Gravitas》是一款遊戲時間不長的第一人稱平台解謎遊戲，故事發生在一間建於太空中的重力美術館 (或稱GORG)，在那裏面你會遇到一位古怪又多話的「館長先生」陪著你欣賞那些令人費解且越來越致命的藝術品，操縱重力與美術館的展示品來證明你就是那位懂得欣賞館長先生的傑作的學徒。\r\n\r\n改變重力以穿梭於館長大人令人好奇的藝術品之間。\r\n\r\n讓「天才」館長大人陪著你探索美術館。\r\n\r\n\r\n善用改變重力的能力操作美術館內的方塊還有避免死亡。\r\n\r\n《Galaxy Shark Studios》是一個由14名遊戲開發者組成的團隊。三年前在研究所時，團隊深受多款解謎遊戲啟發而開始製作《重力美術館 Gravitas》，我們很開心終於可以把遊戲正式發布出來讓其他深愛解謎遊戲的人一起遊玩。如果你想了解更多關於開發團隊或遊戲的訊息，請到我們的官方網站，或者你有任何關於《重力美術館 Gravitas》的建議與問題，歡迎聯絡我們!', 'Windows 7/8/10 64-bit', '64-bit Quad-core Intel or AMD, 2.0 GHz or faster', '4GB', 'OpenGL 3.0+ / DirectX, 1GB Video RAM', '10', '8GB', '2024-08-07 00:00:00');
/*!40000 ALTER TABLE `game_article_data` ENABLE KEYS */;

-- 傾印  資料表 fantasy_universe.game_data 結構
CREATE TABLE IF NOT EXISTS `game_data` (
  `game_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `price` int(11) NOT NULL DEFAULT 0 COMMENT '價格',
  `sale_price` int(11) NOT NULL DEFAULT 0 COMMENT '特價',
  `img` text DEFAULT NULL COMMENT '封面',
  `files` text DEFAULT NULL COMMENT '安裝檔',
  `type` text DEFAULT NULL,
  `date` date NOT NULL COMMENT '上市日期',
  `store_shelves` tinyint(2) DEFAULT 1 COMMENT '是否在架上',
  PRIMARY KEY (`game_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 正在傾印表格  fantasy_universe.game_data 的資料：~3 rows (近似值)
/*!40000 ALTER TABLE `game_data` DISABLE KEYS */;
INSERT INTO `game_data` (`game_id`, `name`, `price`, `sale_price`, `img`, `files`, `type`, `date`, `store_shelves`) VALUES
	(8, '冰與火之舞', 123, 100, 'A Dance of Fire and Ice_6_d1eb06048cef93b2.jpg', 'A Dance of Fire and Ice_4_78adf96c308ee2a3.zip', '節奏,音樂', '2024-08-07', 1),
	(9, 'Gravitas', 0, 0, 'Gravitas_1_bdf518bd7cdf9e4c.jpg', 'Gravitas_1_0220607d921822e3.zip', '單人,第一人稱,解謎,免費遊玩', '2024-08-07', 1);
/*!40000 ALTER TABLE `game_data` ENABLE KEYS */;

-- 傾印  資料表 fantasy_universe.game_type 結構
CREATE TABLE IF NOT EXISTS `game_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 正在傾印表格  fantasy_universe.game_type 的資料：~10 rows (近似值)
/*!40000 ALTER TABLE `game_type` DISABLE KEYS */;
INSERT INTO `game_type` (`id`, `name`) VALUES
	(1, '節奏'),
	(2, '音樂'),
	(3, '單人'),
	(4, '2D'),
	(7, '開放世界'),
	(8, '3D'),
	(9, '沙盒'),
	(10, '第一人稱'),
	(11, '解謎'),
	(12, '免費遊玩');
/*!40000 ALTER TABLE `game_type` ENABLE KEYS */;

-- 傾印  資料表 fantasy_universe.photo_data 結構
CREATE TABLE IF NOT EXISTS `photo_data` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL DEFAULT '0' COMMENT '檔名',
  `game_id` bigint(20) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `game_id` (`game_id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 正在傾印表格  fantasy_universe.photo_data 的資料：~7 rows (近似值)
/*!40000 ALTER TABLE `photo_data` DISABLE KEYS */;
INSERT INTO `photo_data` (`id`, `name`, `game_id`) VALUES
	(53, 'A Dance of Fire and Ice_6_d1eb06048cef93b2.jpg', 8),
	(54, 'A Dance of Fire and Ice_home_39a7d72684610771.jpg', 8),
	(55, 'A Dance of Fire and Ice_5_24c95244c6050726.jpg', 8),
	(56, 'Gravitas_1_bdf518bd7cdf9e4c.jpg', 9),
	(57, 'Gravitas_9_85d8f17bdbf6613b.jpg', 9),
	(58, 'Gravitas_11_b76d703ec9573ed4.jpg', 9),
	(59, 'Gravitas_home_0112c9554250aee8.jpg', 9);
/*!40000 ALTER TABLE `photo_data` ENABLE KEYS */;

-- 傾印  資料表 fantasy_universe.shop_cart_data 結構
CREATE TABLE IF NOT EXISTS `shop_cart_data` (
  `game_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `date` date DEFAULT NULL,
  KEY `game_id` (`game_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 正在傾印表格  fantasy_universe.shop_cart_data 的資料：~0 rows (近似值)
/*!40000 ALTER TABLE `shop_cart_data` DISABLE KEYS */;
INSERT INTO `shop_cart_data` (`game_id`, `user_id`, `date`) VALUES
	(8, 1, '2024-08-15'),
	(9, 1, '2024-08-16');
/*!40000 ALTER TABLE `shop_cart_data` ENABLE KEYS */;

-- 傾印  資料表 fantasy_universe.shop_history 結構
CREATE TABLE IF NOT EXISTS `shop_history` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `order_id` text DEFAULT NULL COMMENT '訂單邊號',
  `game_id` bigint(20) NOT NULL DEFAULT 0,
  `user_id` bigint(20) NOT NULL DEFAULT 0,
  `payment` char(10) DEFAULT NULL COMMENT '付款方式',
  `price` int(11) DEFAULT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_shop_his_data_game_date` (`game_id`),
  KEY `user_id` (`user_id`),
  KEY `order_id` (`order_id`(768))
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 正在傾印表格  fantasy_universe.shop_history 的資料：~8 rows (近似值)
/*!40000 ALTER TABLE `shop_history` DISABLE KEYS */;
INSERT INTO `shop_history` (`id`, `order_id`, `game_id`, `user_id`, `payment`, `price`, `date`) VALUES
	(8, 'GMcisco2024081100001', 8, 1, 'VISA', 123, '2024-08-11 06:45:00'),
	(9, 'GMcisco2024081100001', 9, 1, 'VISA', 0, '2024-08-11 06:45:00'),
	(10, 'GMcisco2024081100003', 7, 1, 'VISA', 1000, '2024-08-11 21:55:00'),
	(11, 'GMasdasd001202408120', 7, 4, 'VISA', 1000, '2024-08-12 22:47:00'),
	(12, 'GMasdasd001202408120', 8, 4, 'VISA', 123, '2024-08-12 23:02:00'),
	(13, 'GMasdasd001202408120', 9, 4, 'VISA', 0, '2024-08-12 23:02:00'),
	(14, 'GMasdasd0012024081200004', 7, 4, 'VISA', 1000, '2024-08-12 23:12:00'),
	(15, 'GMasdasd0012024081200004', 8, 4, 'VISA', 123, '2024-08-12 23:12:00'),
	(16, 'GMcisco2024081300004', 7, 1, 'VISA', 1000, '2024-08-13 00:36:00'),
	(17, 'GMcisco2024081300004', 9, 1, 'VISA', 0, '2024-08-13 00:36:00');
/*!40000 ALTER TABLE `shop_history` ENABLE KEYS */;

-- 傾印  資料表 fantasy_universe.user_data 結構
CREATE TABLE IF NOT EXISTS `user_data` (
  `user_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL DEFAULT '',
  `email` varchar(50) DEFAULT NULL,
  `account` varchar(20) NOT NULL,
  `password` char(70) NOT NULL DEFAULT '0',
  `sex` tinyint(2) DEFAULT 2,
  `birthday` date DEFAULT NULL,
  `phone` char(10) DEFAULT NULL,
  `permission` tinyint(2) NOT NULL DEFAULT 2 COMMENT '特權',
  `date` date NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `account` (`account`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 正在傾印表格  fantasy_universe.user_data 的資料：~5 rows (近似值)
/*!40000 ALTER TABLE `user_data` DISABLE KEYS */;
INSERT INTO `user_data` (`user_id`, `name`, `email`, `account`, `password`, `sex`, `birthday`, `phone`, `permission`, `date`) VALUES
	(1, '凱薩', 'cherhorn@gmail.com', 'cisco', '$2y$10$IaC4ptJsDL3AXnbO0N1NKO0ip./hF.fUMKKEcUHBgzrqYgcmvC3Pq', 2, '2001-05-12', '0444444444', 0, '2024-06-23'),
	(2, 'asd', 'asd@gmail.com', 'asd', '$2y$10$xNWHpNCgZPD6QFuen5MB6OnXKoUYVCiChXuzDg0E9txvaLJPm1HRK', 2, '0000-00-00', '', 2, '2024-07-21'),
	(3, 'asd01', 'asd01@gmail.com', 'asd01', '$2y$10$/bRSVCGD/auwc54J/vGkiOwOjagS08DBLv6gKLs4Zmub4uIAniu32', 2, '0000-00-00', '', 2, '2024-07-21'),
	(4, 'xxxasd', '45668@gmail.com', 'asdasd001', '$2y$10$nPjhuUlFILzoJFS6CJIjuuejSuQooeVQ0BvdrsU5Qpv6zH2Ee.e6a', 2, '2024-07-10', '', 2, '2024-07-27'),
	(7, 'admin', 'admin@gmail.com', 'admin', '$2y$10$ZO/g0Q.84iQRpTKFE5jAmuX0kJgydVLBobOXs0KX0HJwnKz0je3Xm', 0, '0000-00-00', '', 0, '0000-00-00');
/*!40000 ALTER TABLE `user_data` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
