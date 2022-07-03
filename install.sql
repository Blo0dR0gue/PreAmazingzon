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
INSERT INTO `address`
VALUES (1, 'Glockengießerwall', '20095', '8', 'Hamburg', 2),
       (2, 'Pfaffendorfer Str.', '04105', '29', 'Leipzig', 3),
       (4, 'O7', '68161', '16', 'Mannheim', 3),
       (5, 'Coblitzallee', '68163', '1-9', 'Mannheim', 1),
       (6, 'Diamond St.', '92683', '15872', 'Westminster', 4),
       (7, 'Lottumstraße', '10119', '17', 'Berlin', 4),
       (8, 'Wilhelmstraße', '10117', '65', 'Berlin', 2),
       (9, 'Wolfstalflurstraße', '97941', '1', 'Tauberbischofsheim', 5);
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
INSERT INTO `category`
VALUES (1, 'Technology', 'Technology stuff', NULL),
       (2, 'Memory', 'Memory stuff', 1),
       (3, 'Printer', 'Printer stuff', 1),
       (4, 'Travel', 'Travel stuff', NULL),
       (5, 'Living', 'Home stuff', NULL),
       (6, 'Books', 'Books', NULL),
       (7, 'USB-Drive', 'USB Storage', 2),
       (8, 'Cards', 'cards', 2),
       (9, 'Charger', 'Charger', 1);
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
INSERT INTO `order`
VALUES (1, '2022-07-03 12:08:53', '2022-07-13 00:00:00', 1, 2, 3, 2),
       (2, '2022-07-03 12:25:27', '2022-07-13 00:00:00', 0, 1, 3, 4),
       (3, '2022-07-03 12:34:06', '2022-07-13 00:00:00', 1, 4, 4, 6),
       (4, '2022-07-03 12:36:24', '2022-07-13 00:00:00', 1, 3, 2, 1),
       (5, '2022-07-03 12:36:54', '2022-07-13 00:00:00', 1, 4, 2, 1),
       (6, '2022-07-03 12:37:14', '2022-07-13 00:00:00', 0, 2, 2, 1),
       (7, '2022-07-03 12:38:58', '2022-07-13 00:00:00', 1, 1, 2, 1),
       (8, '2022-07-03 12:39:36', '2022-07-13 00:00:00', 0, 1, 2, 8),
       (10, '2022-07-03 13:13:33', '2022-07-13 00:00:00', 1, 4, 1, 5);
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
INSERT INTO `orderstate`
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
INSERT INTO `product`
VALUES (1, 'SanDisk Ultra 128 GB USB Flash Drive USB 3.0',
        'Transfer a full-length movie in less than 40 seconds (32 GB, 64 GB, 128 GB).\r\nSecure file encryption and password protection with included SanDisk SecureAccess software.\r\nUp to 10 times faster than standard USB 2.0.\r\nUSB 3.0 capable (USB 2.0 compatible), transfers full-length movies in less than 40 seconds.\r\nTransmission speeds of up to 130MB/s. Available capacities: 16 GB, 32 GB, 64 GB and 128 GB.\r\nPassword and encryption protection for private files with SanDisk SecureAccess software.\r\nBox contents: SanDisk Ultra 128 GB USB flash drive USB 3.0 up to 130MB/s read',
        15.90, 36, 0.00, 7, 1),
       (2, 'Digital Luggage Scales Analogue 50 kg',
        'Digital luggage scale with clear LCD display. The backlight makes weighing at night or in the dark very easy and clear.\r\nMeasurement up to 50 kg/110 lb with accuracy of 50 g/0.1 lb. Practical, quick and easy to check the packing whether you meet the limits of the free luggage weight.\r\nIn addition to the weight, the digital luggage scale comes in 4 weight units (lb, g, ounce, kg). You could also use this scales as a hanging scales baby scale.\r\nLuggage scale stores measurement value for 60s, and switches off automatically after 60. With tare function and auto-zero function.\r\nStable and high quality, but small and handy design, this hanging scales takes up little space in your luggage, making it ideal for taking with you when travelling. Ideal Christmas gifts for your women, men, dad, mum and children.',
        11.04, 197, 1.20, 4, 1),
       (3, 'Suitcase, Hard Shell, Material ABS, High-quality, blue',
        'Features: Space dividers and packing straps guarantee optimal storage of your wardrobe\r\nLock: Three digit combination lock\r\nDimensions: Small case: (Height x Width x Depth): 54 x 38 x 20 cm. Weight: 2.6 kg. Volume: 34 L. Medium case: 67 x 45 x 26 cm. Weight: 3.5 kg. Volume: 65 L. Large case: 77 x 52 x 29 cm. Weight: 4.1 kg. Volume: 96 L\r\nWheels: 4x quiet-rolling wheels that move in any direction\r\nHandle bar: Height-adjustable telescopic pole, top and side handle',
        165.50, 20, 3.50, 4, 1),
       (4, 'Eastpak Padded Pak&#039;r Backpack, 40 cm, 24 Litres, Army Olive, 23 cm',
        'Brand 	EASTPAK\r\nColour 	Army Olive;\r\nMaterial 	Polyester;\r\nStyle 	Belt bag;\r\nClosure type 	Zip fastener',
        25.26, 10, 33.99, 4, 1),
       (5, '20 W USB-C Power Adaptor',
        'With the Apple 20 W USB C power adapter (power supply), you can charge your iPad Air quickly and efficiently at home, in the office or on the go\r\nThe power adapter is compatible with any USB-C enabled device. For optimal charging, however, Apple recommends using the 11 inches iPad Pro or the 12.9 inches iPad Pro (regeneration)\r\nYou can also connect it to an iPhone 8 or later to use the quick charge function\r\nCharging cable sold separately',
        19.99, 66, 0.00, 9, 1),
       (6, 'SanDisk Extreme PRO 128GB SDXC Memory Card up to 170MBs',
        'Professional performance for fast recording, 4K UHD video and accelerated app performance with the SanDisk memory card\r\nThanks to the fast transfer of up to 170 MB/s, you no longer have to wait for long while transferring your pictures and videos to your SD card or from your SD card to your computer\r\nCapture image-lossless video recording thanks to UHS Speed Class 3 (U3) and Video Speed Class 30 (V30) with the SD card for camera\r\nThe SDXC memory card is designed for extreme conditions and is temperature-resistant, waterproof, shockproof and X-ray proof\r\nBox contents: SanDisk Extreme Pro SDXC UHS-I 128 GB V30 memory card with transfer speed of 170 MB/s, U3, 4K UHD videos, temperature resistance ',
        29.99, 33, 0.00, 8, 1),
       (7, 'HP N9J72AE 301 original ink cartridges, black and three-color, 2 packs',
        ' More for you in it! Original HP ink cartridges 2-packs\r\nReach: CA 190 pages black, about 165 pages Tricolored (actual range depends on the coverage of printed pages based on ISO / IEC 24711) ',
        35.20, 26, 1.20, 3, 1),
       (8, 'SanDisk Ultra microSDHC memory card up to 120 MBs',
        'Ideal for Android smartphones and tablets as well as MIL cameras\r\nUp to 1TB of storage for hours of full HD video\r\nClass 10 for Full HD video recording and playback\r\nUp to 120MB/s transfer speed that allows you to transfer up to 1000 photos per minute\r\nFaster loading of apps thanks to A1 performance. ',
        14.99, 70, 0.25, 8, 1),
       (9, 'WMF Kult Mix Mini Smoothie Maker, Shake Mixer, Blender Electric, 300 Watt',
        'Contents: 1x Mix &amp; Go blender (10.5 x 10.5 x 40 cm, 300 W), 1 x mix container / drinking bottle 600 ml, item number: 0416270011.\r\nMix container with scale and practical drinking lid for on the go, dishwasher-safe.\r\nThe high-quality 4-wing stainless steel knife and powerful 300 watts make it easy to prepare fruit smoothies, milk shakes and juices.\r\nAlso ideal for mixing and chopping nuts and spices, such as salt, pepper and sugar and for crushing ice cubes.\r\nSafety function: the mixing function only works when the container is attached.',
        34.99, 55, 12.00, 5, 1),
       (10, 'SodaStream Action Set PET bottles, 3x 1 L PET bottles made of unbreakable PET',
        'Sodastream Pet-Flashe: The Sodastream bubble bottles made of high-quality plastic guarantee the correct and safe handling of your Sodastream bubbler\r\nPractical: A great addition to our starter sets, each family member has his own bottle, for home, school or on the go\r\nCaution: bottles suitable for all common Sodastream water bubblers with plastic bottles with screw thread\r\nCaution: bottles only suitable for use in SodaStream water bubblers with one-click mechanism! At the time of manufacture, these are: Power, Easy, Source, Play\r\nThe pet bottles are not compatible for glass makers such as SodaStream Crystal or SodaStream Peguin\r\nDelivery: SodaStream Pet Bottle 2 + 1 Pack with 3x 1 L PET Bottle ( 1 x orange, 1 x green, 1 x white)',
        12.99, 169, 1.00, 5, 1),
       (11, 'Philips Azur Steam Iron - 2600 W, SteamGlide Advanced Ironing Sole',
        'Fast and powerful: Thanks to the power of 2600 W, the device heats up quickly, so you can get started sooner and get done faster.\r\nRemove wrinkles with ease: Constant steam output of up to 50 g/min delivers the perfect amount of steam to efficiently remove all wrinkles.\r\nSmooths stubborn wrinkles: Steam boost of up to 250 g penetrates deeper into the fabric and removes even stubborn wrinkles easily.\r\nSmooth glide on all fabrics: Our exclusive SteamGlide Advanced ensures ultimate glide on all fabrics thanks to its advanced titanium layer and innovative coating.\r\nLong service life: Thanks to the quick calc release system, this powerful iron can be easily descaled for reliable steam performance.',
        49.88, 98, 1.99, 5, 1),
       (12, 'Civil Code - with General Equal Treatment Act, Product Liability Act, Injunctions Act, ...',
        'Contents Civil Code with Introductory Act, General Equal Treatment Act, Product Liability Act, Injunctions Act, Condominium Act, Notarization Act, Heritable Building Rights Act, Rome I to III, Regulations (EC). New edition The new edition includes, among other things, the changes brought about by the Fair Consumer Contracts Act and the Law on the Reform of Tenancy Agreements. Target group For lawyers, judges, students, trainee lawyers and practitioners in business.',
        5.90, 99, 1.99, 6, 0),
       (13, 'Labor laws - with the most important provisions, procedural law',
        'Contents General Act on Equal Treatment Residence Act (excerpt), Expense Compensation Act, Temporary Employment Contracts with Doctors in Continuing Education, Vocational Training Act, Company Pensions Act (excerpt), Works Constitution Act, Civil Code (excerpt), Federal Data Protection Act (excerpt), Federal Parental Allowance and Parental Leave Act, Bundes-ImmissionsschutzG (excerpt), BundesurlaubsG, DrittelbeteiligungsG, EinkommensteuerG (excerpt), EntgeltfortzahlungsG, EntgelttransparenzG, European Works CouncilsG, FamilienpflegezeitG, Feiertage (overview), GendiagnostikG (excerpt), GerichtskostenG (excerpt), GerichtsverfassungsG (extract), Gewerbeordnung (extract), GeschÃ¤ftsgeheimnisG (extract), Grundgesetz (extract), Grundrechte Charta, Handelsgesetzbuch (extract), HeimarbeitsG, InfektionsschutzG (extract), Insolvenzordnung (extract), JugendarbeitsschutzG (extract).',
        12.90, 55, 1.99, 6, 0),
       (14, 'PHP 8 and MySQL - The Comprehensive Guide to PHP 8',
        'Program dynamic websites with PHP and MySQL: Everything you need to know is in this book. Benefit from a practical introduction and learn all the new language features of PHP 8. The authors Christian Wenz and Tobias Hauser are experienced PHP programmers and database specialists. They will show you how to use MySQL and other database systems effectively. With this knowledge, you will make yourself completely fit for dynamic websites.',
        49.90, 0, 1.99, 6, 1),
       (15, 'Java is also an island: The standard work for programmers. Over 1,000 pages of Java knowledge. ',
        'Java books are a dime a dozen, but Java Island is the cult book for programmers and the first choice when it comes to up-to-date and practical Java knowledge. For over a decade, Java beginners, students, and those switching from other programming languages alike have benefited from this standard work among Java books. Instructors and trainers appreciate the book for its numerous examples, assignments, and sample solutions. Readers celebrate it for the comprehensible presentation and the fine humor; programming can also be fun!',
        49.90, 1, 1.99, 6, 1),
       (16, 'Elotrans solution',
        'Size: 20 pieces (1 pack)\r\nBy compensating for water and mineral losses, Elotrans supports the regeneration of the body in the event of diarrheal diseases.\r\nEspecially in children, the loss of minerals and fluids should be compensated for immediately.\r\nThe drinking solution supplies the body with electrolytes, liquid and glucose. Dissolve the powder in a sachet in 200ml of drinking water, use the ml specifications exactly to ensure an optimal composition of sugar, salts and liquid.\r\nElotrans supports the body with salts / minerals and glucose to compensate for the loss of electrolytes and fluids caused by diarrhea and thus improve the general well-being of those affected.',
        17.99, 41, 1.00, NULL, 1);
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
INSERT INTO `product_order`
VALUES (1, 3, 1, 15.90),
       (1, 4, 2, 15.90),
       (2, 2, 1, 12.24),
       (2, 5, 1, 12.24),
       (3, 2, 1, 169.00),
       (3, 6, 1, 169.00),
       (4, 3, 1, 59.25),
       (4, 7, 1, 59.25),
       (6, 1, 1, 29.99),
       (8, 1, 4, 14.99),
       (8, 3, 2, 15.24),
       (9, 3, 1, 46.99),
       (11, 8, 1, 51.87),
       (14, 4, 1, 51.89),
       (16, 10, 1, 18.99);
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
INSERT INTO `review`
VALUES (1, 'Good Quality', 4,
        'Super product for a good price. The storage capacity also corresponds to the specified size.', 3, 8),
       (2, 'Not 170 MB/s', 2,
        'I tested the card when it arrived today and the capacity is correct. However it does not reach the transfer speed of 170 MB/s.',
        3, 6),
       (3, 'Good Product', 5, 'Does what it is supposed to do and the quality is outstanding.', 3, 2),
       (4, 'Good Product', 4, 'Good usb flash drive to store many data.', 2, 1),
       (5, 'Product is OK', 3, 'Good quality for a good price.', 2, 2),
       (7, 'Not so good', 2, 'Product was damanged.', 4, 4),
       (8, 'Love this smoothie maker', 5, 'I can now create any type of smoothie I want.', 4, 9),
       (9, 'At 23 finally free of a hangover', 5,
        'At my advanced age of 23, after various festivities that I was lucky enough to be alive the next day, I realized that the hangover has now lasted two days. Now I was looking for a way to alleviate this problem. Convinced of the many medically valuable recessions, I decided to go with Elotrans. The price is within the framework and lo and behold: no hangover! Before going to sleep I treated myself to 1 liter of water and two sachets and the next morning I no longer felt like 106 but like 16 years!!! An indescribable and emotional experience.\r\nFrom now on I always carry three or four bags of Elotrans in my toiletry bag for uncultivated evenings!\r\n\r\nPS: the taste is okay, it&#039;s not a Sacher cake, but it&#039;s not bitter either.',
        1, 16);
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
INSERT INTO `shoppingcart_product`
VALUES (2, 3, 2),
       (2, 5, 5),
       (2, 15, 1),
       (4, 1, 1),
       (4, 7, 3),
       (4, 8, 1);
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
INSERT INTO `user`
VALUES (1, '$2y$10$jz/NBTdqKo2rMsAT917c6uGhZqTzsppbq5xVdle0.D8U.0RUxLZ8u', 'admin@admin.de', 1, 'Armin', 'Admin', 5, 1),
       (2, '$2y$10$UDBCdD.eFfj3/PX.2MBIYekKWfXe63XOHv.pBOf7dLpIEtuz/3P6a', 'user@user.de', 2, 'Max', 'Musteruser', 1,
        1),
       (3, '$2y$10$dxkl9EZ8kL2aXVtLtOcKg.KfZoKEmGxmyOZo0dB.1KvvJJY99XgRa', 'tom.harris@gmail.com', 2, 'Tom', 'Harris',
        4, 1),
       (4, '$2y$10$MdopazyC5sETjfmva0MycuyIiAyd3.b.U2OT3qe5frcdpvUnK/o3m', 'Lisa.Clark@gmail.com', 2, 'Lisa', 'Clark',
        6, 1),
       (5, '$2y$10$X7cTabuuGaMNdRgW11iINuIn98sX6gn0FiC6VAd5Emc0PqxHdrQ3O', 'Thomas.Thompson@amazingzon.com', 1,
        'Thomas', 'Thompson', 9, 0);
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
INSERT INTO `userrole`
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