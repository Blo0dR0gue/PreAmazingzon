-- 03.07.2022
-- Model: Amazingzon Database  Version: 1.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE = @@TIME_ZONE */;
/*!40103 SET TIME_ZONE = '+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS = @@UNIQUE_CHECKS, UNIQUE_CHECKS = 0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS = 0 */;
/*!40101 SET @OLD_SQL_MODE = @@SQL_MODE, SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES = @@SQL_NOTES, SQL_NOTES = 0 */;

-- region ############### DATABASE #####################
-- -----------------------------------------------------
-- Schema amazingzon
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `amazingzon`;

CREATE SCHEMA IF NOT EXISTS `amazingzon` DEFAULT CHARACTER SET utf8;
USE `amazingzon`;

-- -----------------------------------------------------
-- Database User
-- -----------------------------------------------------
DROP USER IF EXISTS amazingzon;

CREATE USER 'amazingzon' IDENTIFIED BY 'sh7up#KT!';
GRANT ALL ON `amazingzon`.* TO 'amazingzon';

-- endregion ###########################################


-- region ################ TABLES ######################

-- -----------------------------------------------------
-- Table `amazingzon`.`address`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `amazingzon`.`address`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `amazingzon`.`address`
(
    `id`           int(11)          NOT NULL AUTO_INCREMENT,
    `street`       varchar(255)     NOT NULL,
    `zipCode`      varchar(50)      NOT NULL,
    `streetNumber` varchar(50)      NOT NULL,
    `city`         varchar(255)     NOT NULL,
    `user`         int(10) unsigned NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_address_user`
        FOREIGN KEY (`user`)
            REFERENCES `amazingzon`.`user` (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE
) ENGINE = InnoDB
  AUTO_INCREMENT = 2
  DEFAULT CHARSET = utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

-- -----------------------------------------------------
-- Data for table `amazingzon`.`address`
-- -----------------------------------------------------
LOCK TABLES `address` WRITE;
/*!40000 ALTER TABLE `address` DISABLE KEYS */;
INSERT INTO `amazingzon`.`address` VALUES (1, 'Glockengießerwall', '20095', '8', 'Hamburg', 2);
/*!40000 ALTER TABLE `address` ENABLE KEYS */;
UNLOCK TABLES;


-- -----------------------------------------------------
-- Table `amazingzon`.`category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `amazingzon`.`category`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `amazingzon`.`category`
(
    `id`          int(11)      NOT NULL AUTO_INCREMENT,
    `name`        varchar(255) NOT NULL,
    `description` text         NOT NULL,
    `parent`      int(11) DEFAULT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_category_category1`
        FOREIGN KEY (`parent`)
            REFERENCES `amazingzon`.`category` (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

-- -----------------------------------------------------
-- Data for table `amazingzon`.`category`
-- -----------------------------------------------------
LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;


-- -----------------------------------------------------
-- Table `amazingzon`.`order`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `amazingzon`.`order`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `amazingzon`.`order`
(
    `id`              int(11)          NOT NULL AUTO_INCREMENT,
    `orderDate`       datetime         NOT NULL,
    `deliveryDate`    datetime         NOT NULL,
    `paid`            tinyint(4)       NOT NULL DEFAULT 0,
    `orderState`      int(11)          NOT NULL,
    `user`            int(10) unsigned NOT NULL,
    `shippingAddress` int(11)                   DEFAULT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_order_orderstate1`
        FOREIGN KEY (`orderState`)
            REFERENCES `amazingzon`.`orderstate` (`id`)
            ON DELETE RESTRICT
            ON UPDATE CASCADE,
    CONSTRAINT `fk_order_user1`
        FOREIGN KEY (`user`)
            REFERENCES `amazingzon`.`user` (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
    CONSTRAINT `fk_order_address1`
        FOREIGN KEY (`shippingAddress`)
            REFERENCES `amazingzon`.`address` (`id`)
            ON DELETE SET NULL
            ON UPDATE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

-- -----------------------------------------------------
-- Data for table `amazingzon`.`order`
-- -----------------------------------------------------
LOCK TABLES `order` WRITE;
/*!40000 ALTER TABLE `order` DISABLE KEYS */;
/*!40000 ALTER TABLE `order` ENABLE KEYS */;
UNLOCK TABLES;


-- -----------------------------------------------------
-- Table `amazingzon`.`orderstate`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `amazingzon`.`orderstate`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `amazingzon`.`orderstate`
(
    `id`    int(11)      NOT NULL AUTO_INCREMENT,
    `label` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 5
  DEFAULT CHARSET = utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

-- -----------------------------------------------------
-- Data for table `amazingzon`.`orderstate`
-- -----------------------------------------------------
LOCK TABLES `orderstate` WRITE;
/*!40000 ALTER TABLE `orderstate` DISABLE KEYS */;
INSERT INTO `amazingzon`.`orderstate`
VALUES (1, 'new'),
       (2, 'canceled'),
       (3, 'sent'),
       (4, 'delivered');
/*!40000 ALTER TABLE `orderstate` ENABLE KEYS */;
UNLOCK TABLES;


-- -----------------------------------------------------
-- Table `amazingzon`.`product`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `amazingzon`.`product`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `amazingzon`.`product`
(
    `id`           int(11)                 NOT NULL AUTO_INCREMENT,
    `title`        varchar(255)            NOT NULL,
    `description`  text                    NOT NULL,
    `price`        decimal(15, 2) unsigned NOT NULL,
    `stock`        int(11)                 NOT NULL DEFAULT 0,
    `shippingCost` decimal(15, 2)          NOT NULL,
    `category`     int(11)                          DEFAULT NULL,
    `active`       tinyint(4)              NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_product_category1`
        FOREIGN KEY (`category`)
            REFERENCES `amazingzon`.`category` (`id`)
            ON DELETE SET NULL
            ON UPDATE CASCADE
) ENGINE = InnoDB
  AUTO_INCREMENT = 16
  DEFAULT CHARSET = utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

-- -----------------------------------------------------
-- Data for table `amazingzon`.`product`
-- -----------------------------------------------------
LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `amazingzon`.`product`
VALUES (1, 'SanDisk Ultra 128 GB USB Flash Drive USB 3.0',
        'Transfer a full-length movie in less than 40 seconds (32 GB, 64 GB, 128 GB).\nSecure file encryption and password protection with included SanDisk SecureAccess software.\nUp to 10 times faster than standard USB 2.0.\nUSB 3.0 capable (USB 2.0 compatible), transfers full-length movies in less than 40 seconds.\nTransmission speeds of up to 130MB/s. Available capacities: 16 GB, 32 GB, 64 GB and 128 GB.\nPassword and encryption protection for private files with SanDisk SecureAccess software.\nBox contents: SanDisk Ultra 128 GB USB flash drive USB 3.0 up to 130MB/s read',
        15.90, 39, 0.00, NULL, 1),
       (2, 'Digital Luggage Scales Analogue 50 kg',
        'Digital luggage scale with clear LCD display. The backlight makes weighing at night or in the dark very easy and clear.\nMeasurement up to 50 kg/110 lb with accuracy of 50 g/0.1 lb. Practical, quick and easy to check the packing whether you meet the limits of the free luggage weight.\nIn addition to the weight, the digital luggage scale comes in 4 weight units (lb, g, ounce, kg). You could also use this scales as a hanging scales baby scale.\nLuggage scale stores measurement value for 60s, and switches off automatically after 60. With tare function and auto-zero function.\nStable and high quality, but small and handy design, this hanging scales takes up little space in your luggage, making it ideal for taking with you when travelling. Ideal Christmas gifts for your women, men, dad, mum and children.',
        11.04, 199, 1.20, NULL, 1),
       (3, 'Suitcase, Hard Shell, Material ABS, High-quality, blue',
        'Features: Space dividers and packing straps guarantee optimal storage of your wardrobe\nLock: Three digit combination lock\nDimensions: Small case: (Height x Width x Depth): 54 x 38 x 20 cm. Weight: 2.6 kg. Volume: 34 L. Medium case: 67 x 45 x 26 cm. Weight: 3.5 kg. Volume: 65 L. Large case: 77 x 52 x 29 cm. Weight: 4.1 kg. Volume: 96 L\nWheels: 4x quiet-rolling wheels that move in any direction\nHandle bar: Height-adjustable telescopic pole, top and side handle',
        165.50, 22, 3.50, NULL, 1),
       (4, 'Eastpak Padded Pak&#039;r Backpack, 40 cm, 24 Litres, Army Olive, 23 cm',
        'Brand 	EASTPAK\nColour 	Army Olive;\nMaterial 	Polyester;\nStyle 	Belt bag;\nClosure type 	Zip fastener',
        25.26, 12, 33.99, NULL, 1),
       (5, '20 W USB-C Power Adaptor',
        'With the Apple 20 W USB C power adapter (power supply), you can charge your iPad Air quickly and efficiently at home, in the office or on the go\nThe power adapter is compatible with any USB-C enabled device. For optimal charging, however, Apple recommends using the 11 inches iPad Pro or the 12.9 inches iPad Pro (regeneration)\nYou can also connect it to an iPhone 8 or later to use the quick charge function\nCharging cable sold separately',
        19.99, 66, 0.00, NULL, 1),
       (6, 'SanDisk Extreme PRO 128GB SDXC Memory Card up to 170MBs',
        'Professional performance for fast recording, 4K UHD video and accelerated app performance with the SanDisk memory card\nThanks to the fast transfer of up to 170 MB/s, you no longer have to wait for long while transferring your pictures and videos to your SD card or from your SD card to your computer\nCapture image-lossless video recording thanks to UHS Speed Class 3 (U3) and Video Speed Class 30 (V30) with the SD card for camera\nThe SDXC memory card is designed for extreme conditions and is temperature-resistant, waterproof, shockproof and X-ray proof\nBox contents: SanDisk Extreme Pro SDXC UHS-I 128 GB V30 memory card with transfer speed of 170 MB/s, U3, 4K UHD videos, temperature resistance ',
        29.99, 34, 0.00, NULL, 1),
       (7, 'HP N9J72AE 301 original ink cartridges, black and three-color, 2 packs',
        ' More for you in it! Original HP ink cartridges 2-packs\nReach: CA 190 pages black, about 165 pages Tricolored (actual range depends on the coverage of printed pages based on ISO / IEC 24711) ',
        35.20, 28, 1.20, NULL, 1),
       (8, 'SanDisk Ultra microSDHC memory card up to 120 MBs',
        'Ideal for Android smartphones and tablets as well as MIL cameras\nUp to 1TB of storage for hours of full HD video\nClass 10 for Full HD video recording and playback\nUp to 120MB/s transfer speed that allows you to transfer up to 1000 photos per minute\nFaster loading of apps thanks to A1 performance. ',
        14.99, 26, 0.00, NULL, 1),
       (9, 'WMF Kult Mix Mini Smoothie Maker, Shake Mixer, Blender Electric, 300 Watt',
        'Contents: 1x Mix &amp; Go blender (10.5 x 10.5 x 40 cm, 300 W), 1 x mix container / drinking bottle 600 ml, item number: 0416270011.\nMix container with scale and practical drinking lid for on the go, dishwasher-safe.\nThe high-quality 4-wing stainless steel knife and powerful 300 watts make it easy to prepare fruit smoothies, milk shakes and juices.\nAlso ideal for mixing and chopping nuts and spices, such as salt, pepper and sugar and for crushing ice cubes.\nSafety function: the mixing function only works when the container is attached.',
        34.99, 56, 12.00, NULL, 1),
       (10, 'SodaStream Action Set PET bottles, 3x 1 L PET bottles made of unbreakable PET',
        'Sodastream Pet-Flashe: The Sodastream bubble bottles made of high-quality plastic guarantee the correct and safe handling of your Sodastream bubbler\nPractical: A great addition to our starter sets, each family member has his own bottle, for home, school or on the go\nCaution: bottles suitable for all common Sodastream water bubblers with plastic bottles with screw thread\nCaution: bottles only suitable for use in SodaStream water bubblers with one-click mechanism! At the time of manufacture, these are: Power, Easy, Source, Play\nThe pet bottles are not compatible for glass makers such as SodaStream Crystal or SodaStream Peguin\nDelivery: SodaStream Pet Bottle 2 + 1 Pack with 3x 1 L PET Bottle ( 1 x orange, 1 x green, 1 x white)',
        12.99, 199, 1.00, NULL, 1),
       (11, 'Philips Azur Steam Iron - 2600 W, SteamGlide Advanced Ironing Sole',
        'Fast and powerful: Thanks to the power of 2600 W, the device heats up quickly, so you can get started sooner and get done faster.\nRemove wrinkles with ease: Constant steam output of up to 50 g/min delivers the perfect amount of steam to efficiently remove all wrinkles.\nSmooths stubborn wrinkles: Steam boost of up to 250 g penetrates deeper into the fabric and removes even stubborn wrinkles easily.\nSmooth glide on all fabrics: Our exclusive SteamGlide Advanced ensures ultimate glide on all fabrics thanks to its advanced titanium layer and innovative coating.\nLong service life: Thanks to the quick calc release system, this powerful iron can be easily descaled for reliable steam performance.',
        49.88, 99, 1.99, NULL, 1),
       (12, 'Civil Code - with General Equal Treatment Act, Product Liability Act, Injunctions Act, ...',
        'Contents Civil Code with Introductory Act, General Equal Treatment Act, Product Liability Act, Injunctions Act, Condominium Act, Notarization Act, Heritable Building Rights Act, Rome I to III, Regulations (EC). New edition The new edition includes, among other things, the changes brought about by the Fair Consumer Contracts Act and the Law on the Reform of Tenancy Agreements. Target group For lawyers, judges, students, trainee lawyers and practitioners in business.',
        5.90, 99, 1.99, NULL, 1),
       (13, 'Labor laws - with the most important provisions, procedural law',
        'Contents General Act on Equal Treatment Residence Act (excerpt), Expense Compensation Act, Temporary Employment Contracts with Doctors in Continuing Education, Vocational Training Act, Company Pensions Act (excerpt), Works Constitution Act, Civil Code (excerpt), Federal Data Protection Act (excerpt), Federal Parental Allowance and Parental Leave Act, Bundes-ImmissionsschutzG (excerpt), BundesurlaubsG, DrittelbeteiligungsG, EinkommensteuerG (excerpt), EntgeltfortzahlungsG, EntgelttransparenzG, European Works CouncilsG, FamilienpflegezeitG, Feiertage (overview), GendiagnostikG (excerpt), GerichtskostenG (excerpt), GerichtsverfassungsG (extract), Gewerbeordnung (extract), GeschÃ¤ftsgeheimnisG (extract), Grundgesetz (extract), Grundrechte Charta, Handelsgesetzbuch (extract), HeimarbeitsG, InfektionsschutzG (extract), Insolvenzordnung (extract), JugendarbeitsschutzG (extract).',
        12.90, 55, 1.99, NULL, 1),
       (14, 'PHP 8 and MySQL - The Comprehensive Guide to PHP 8',
        'Program dynamic websites with PHP and MySQL: Everything you need to know is in this book. Benefit from a practical introduction and learn all the new language features of PHP 8. The authors Christian Wenz and Tobias Hauser are experienced PHP programmers and database specialists. They will show you how to use MySQL and other database systems effectively. With this knowledge, you will make yourself completely fit for dynamic websites.',
        49.90, 1, 1.99, NULL, 1),
       (15, 'Java is also an island: The standard work for programmers. Over 1,000 pages of Java knowledge. ',
        'Java books are a dime a dozen, but Java Island is the cult book for programmers and the first choice when it comes to up-to-date and practical Java knowledge. For over a decade, Java beginners, students, and those switching from other programming languages alike have benefited from this standard work among Java books. Instructors and trainers appreciate the book for its numerous examples, assignments, and sample solutions. Readers celebrate it for the comprehensible presentation and the fine humor; programming can also be fun!',
        49.90, 1, 1.99, NULL, 1);
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;


-- -----------------------------------------------------
-- Table `amazingzon`.`product_order`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `amazingzon`.`product_order`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `amazingzon`.`product_order`
(
    `product` int(11)        NOT NULL,
    `order`   int(11)        NOT NULL,
    `amount`  int(11)        NOT NULL DEFAULT 1,
    `price`   decimal(15, 2) NOT NULL,
    PRIMARY KEY (`product`, `order`),
    CONSTRAINT `fk_Product_has_Order_Product1`
        FOREIGN KEY (`product`)
            REFERENCES `amazingzon`.`product` (`id`)
            ON DELETE RESTRICT
            ON UPDATE CASCADE,
    CONSTRAINT `fk_product_has_Order_Order1`
        FOREIGN KEY (`order`)
            REFERENCES `amazingzon`.`order` (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

-- -----------------------------------------------------
-- Data for table `amazingzon`.`product_order`
-- -----------------------------------------------------
LOCK TABLES `product_order` WRITE;
/*!40000 ALTER TABLE `product_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_order` ENABLE KEYS */;
UNLOCK TABLES;


-- -----------------------------------------------------
-- Table `amazingzon`.`review`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `amazingzon`.`review`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `amazingzon`.`review`
(
    `id`      int(11)          NOT NULL AUTO_INCREMENT,
    `title`   varchar(255)     NOT NULL,
    `stars`   int(10) unsigned NOT NULL,
    `text`    text             NOT NULL,
    `user`    int(10) unsigned NOT NULL,
    `product` int(11)          NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_review_user1`
        FOREIGN KEY (`user`)
            REFERENCES `amazingzon`.`user` (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
    CONSTRAINT `fk_review_product1`
        FOREIGN KEY (`product`)
            REFERENCES `amazingzon`.`product` (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

-- -----------------------------------------------------
-- Data for table `amazingzon`.`review`
-- -----------------------------------------------------
LOCK TABLES `review` WRITE;
/*!40000 ALTER TABLE `review` DISABLE KEYS */;
/*!40000 ALTER TABLE `review` ENABLE KEYS */;
UNLOCK TABLES;


-- -----------------------------------------------------
-- Table `amazingzon`.`shoppingcart_product`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `amazingzon`.`shoppingcart_product`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `amazingzon`.`shoppingcart_product`
(
    `user`    int(10) unsigned NOT NULL,
    `product` int(11)          NOT NULL,
    `amount`  int(11)          NOT NULL,
    PRIMARY KEY (`user`, `product`),
    CONSTRAINT `fk_shoppingcart_user1`
        FOREIGN KEY (`user`)
            REFERENCES `amazingzon`.`user` (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
    CONSTRAINT `fk_shoppingcart_Product1`
        FOREIGN KEY (`product`)
            REFERENCES `amazingzon`.`product` (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

-- -----------------------------------------------------
-- Data for table `amazingzon`.`shoppingcart_product`
-- -----------------------------------------------------
LOCK TABLES `shoppingcart_product` WRITE;
/*!40000 ALTER TABLE `shoppingcart_product` DISABLE KEYS */;
/*!40000 ALTER TABLE `shoppingcart_product` ENABLE KEYS */;
UNLOCK TABLES;


-- -----------------------------------------------------
-- Table `amazingzon`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `amazingzon`.`user`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `amazingzon`.`user`
(
    `id`             int(10) unsigned NOT NULL AUTO_INCREMENT,
    `password`       varchar(255)     NOT NULL,
    `email`          varchar(255)     NOT NULL,
    `userRole`       int(11)          NOT NULL,
    `firstname`      varchar(255)     NOT NULL,
    `lastname`       varchar(255)     NOT NULL,
    `defaultAddress` int(11) DEFAULT NULL,
    `active`         tinyint(4)       NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_user_userrole`
        FOREIGN KEY (`userRole`)
            REFERENCES `amazingzon`.`userrole` (`id`)
            ON DELETE RESTRICT
            ON UPDATE CASCADE,
    CONSTRAINT `fk_user_address`
        FOREIGN KEY (`defaultAddress`)
            REFERENCES `amazingzon`.`address` (`id`)
            ON DELETE SET NULL
            ON UPDATE CASCADE
) ENGINE = InnoDB
  AUTO_INCREMENT = 3
  DEFAULT CHARSET = utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

-- -----------------------------------------------------
-- Data for table `amazingzon`.`user`
-- -----------------------------------------------------
LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `amazingzon`.`user`
VALUES (1, '$2y$10$Cvg4eJICZcIhH9cNyYfRu.EZSqpLAXOCQx5kHo3quLT1S9rCCeMnK', 'admin@admin.de', 1, 'Armin', 'Admin', NULL, 1),
       (2, '$2y$10$UDBCdD.eFfj3/PX.2MBIYekKWfXe63XOHv.pBOf7dLpIEtuz/3P6a', 'user@user.de', 2, 'Max', 'Musteruser', 1, 1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;


-- -----------------------------------------------------
-- Table `amazingzon`.`userrole`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `amazingzon`.`userrole`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `amazingzon`.`userrole`
(
    `id`   int(11)     NOT NULL AUTO_INCREMENT,
    `name` varchar(45) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 3
  DEFAULT CHARSET = utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

-- -----------------------------------------------------
-- Data for table `amazingzon`.`userrole`
-- -----------------------------------------------------
LOCK TABLES `userrole` WRITE;
/*!40000 ALTER TABLE `userrole` DISABLE KEYS */;
INSERT INTO `amazingzon`.`userrole`
VALUES (1, 'admin'),
       (2, 'user');
/*!40000 ALTER TABLE `userrole` ENABLE KEYS */;
UNLOCK TABLES;

-- endregion ###########################################


/*!40103 SET TIME_ZONE = @OLD_TIME_ZONE */;
/*!40101 SET SQL_MODE = @OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS = @OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES = @OLD_SQL_NOTES */;