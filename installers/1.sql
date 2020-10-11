-- Creates the report DB

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `discord`
--

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `discord_id` varchar(255) NOT NULL,
  `reporter_discord_id` varchar(255) NOT NULL,
  `reporter_discord_username` varchar(255) NOT NULL,
  `cat` varchar(255) NOT NULL,
  `details` text NOT NULL,
  `epoch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;