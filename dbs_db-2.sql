-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 28, 2025 at 02:55 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbs_db`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `s_content` ()   BEGIN
	SELECT * FROM Books;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Address`
--

CREATE TABLE `Address` (
  `address_id` int(11) NOT NULL,
  `ba_street` varchar(100) DEFAULT NULL,
  `ba_barangay` varchar(100) DEFAULT NULL,
  `ba_city` varchar(100) DEFAULT NULL,
  `ba_province` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Address`
--

INSERT INTO `Address` (`address_id`, `ba_street`, `ba_barangay`, `ba_city`, `ba_province`) VALUES
(1, 'Baker Street', 'Barangay 1', 'London', 'England'),
(2, '5th Avenue', 'Barangay 2', 'New York', 'NY'),
(3, 'Evergreen Terrace', 'Barangay 3', 'Springfield', 'IL');

-- --------------------------------------------------------

--
-- Table structure for table `Authors`
--

CREATE TABLE `Authors` (
  `author_id` int(11) NOT NULL,
  `author_FN` varchar(100) NOT NULL,
  `author_LN` varchar(100) NOT NULL,
  `author_birthday` date NOT NULL,
  `author_nat` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Authors`
--

INSERT INTO `Authors` (`author_id`, `author_FN`, `author_LN`, `author_birthday`, `author_nat`) VALUES
(1, 'F. Scott', 'Fitzgerald', '1896-09-24', 'American'),
(2, 'George', 'Orwell', '1903-06-25', 'British'),
(3, 'Harper', 'Lee', '1926-04-28', 'American'),
(4, 'Jane', 'Austen', '1775-12-16', 'British'),
(5, 'J.K.', 'Rowling', '1965-07-31', 'British');

-- --------------------------------------------------------

--
-- Table structure for table `Books`
--

CREATE TABLE `Books` (
  `book_id` int(11) NOT NULL,
  `book_title` varchar(255) NOT NULL,
  `book_isbn` varchar(20) NOT NULL,
  `book_pubyear` int(11) NOT NULL,
  `quantity_avail` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Books`
--

INSERT INTO `Books` (`book_id`, `book_title`, `book_isbn`, `book_pubyear`, `quantity_avail`) VALUES
(1, 'The Great Gatsby', '9780743273565', 1925, 10),
(2, '1984', '9780451524935', 1949, 8),
(3, 'To Kill a Mockingbird', '9780061120084', 1960, 5),
(4, 'Pride and Prejudice', '9780141040349', 1813, 7),
(5, 'Harry Potter and the Sorcerer’s Stone', '9780590353427', 1997, 12);

-- --------------------------------------------------------

--
-- Table structure for table `Book_Authors`
--

CREATE TABLE `Book_Authors` (
  `ba_id` int(11) NOT NULL,
  `book_id` int(11) DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Book_Authors`
--

INSERT INTO `Book_Authors` (`ba_id`, `book_id`, `author_id`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 3),
(4, 4, 4),
(5, 5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `Book_Copy`
--

CREATE TABLE `Book_Copy` (
  `copy_id` int(11) NOT NULL,
  `book_id` int(11) DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Book_Copy`
--

INSERT INTO `Book_Copy` (`copy_id`, `book_id`, `is_available`) VALUES
(1, 1, 0),
(2, 1, 1),
(3, 2, 0),
(4, 3, 1),
(5, 4, 0),
(6, 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `Borrowers_Address`
--

CREATE TABLE `Borrowers_Address` (
  `ba_bridge_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `address_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Borrowers_Address`
--

INSERT INTO `Borrowers_Address` (`ba_bridge_id`, `user_id`, `address_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 1),
(4, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `Genres`
--

CREATE TABLE `Genres` (
  `genre_id` int(11) NOT NULL,
  `genre_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Genres`
--

INSERT INTO `Genres` (`genre_id`, `genre_name`) VALUES
(3, 'Classic'),
(2, 'Dystopian'),
(5, 'Fantasy'),
(1, 'Fiction'),
(4, 'Romance');

-- --------------------------------------------------------

--
-- Table structure for table `Genre_Books`
--

CREATE TABLE `Genre_Books` (
  `gb_id` int(11) NOT NULL,
  `genre_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Genre_Books`
--

INSERT INTO `Genre_Books` (`gb_id`, `genre_id`, `book_id`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3),
(4, 4, 4),
(5, 5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `Transactions`
--

CREATE TABLE `Transactions` (
  `transaction_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `transaction_date` date NOT NULL,
  `due_date` date NOT NULL,
  `return_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Transactions`
--

INSERT INTO `Transactions` (`transaction_id`, `user_id`, `transaction_date`, `due_date`, `return_date`) VALUES
(1, 1, '2024-01-10', '2024-01-20', '2024-01-18 16:00:00'),
(2, 2, '2024-01-12', '2024-01-22', NULL),
(3, 3, '2024-01-15', '2024-01-25', '2024-01-25 16:00:00'),
(4, 1, '2024-02-01', '2024-02-10', '2024-02-08 16:00:00'),
(5, 2, '2024-02-05', '2024-02-15', '2024-02-13 16:00:00'),
(6, 1, '2025-03-19', '2025-03-26', NULL),
(7, 3, '2025-04-25', '2025-04-30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Transaction_Copies`
--

CREATE TABLE `Transaction_Copies` (
  `tc_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `copy_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `user_id` int(11) NOT NULL,
  `user_FN` varchar(100) NOT NULL,
  `user_LN` varchar(100) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_phone` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`user_id`, `user_FN`, `user_LN`, `user_email`, `user_phone`) VALUES
(1, 'John', 'Doe', 'john.doe@email.com', '1234567890'),
(2, 'Jane', 'Smith', 'jane.smith@email.com', '0987654321'),
(3, 'Alice', 'Brown', 'alice.brown@email.com', '1122334455');

-- --------------------------------------------------------

--
-- Table structure for table `users_pictures`
--

CREATE TABLE `users_pictures` (
  `up_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_pic_url` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Address`
--
ALTER TABLE `Address`
  ADD PRIMARY KEY (`address_id`);

--
-- Indexes for table `Authors`
--
ALTER TABLE `Authors`
  ADD PRIMARY KEY (`author_id`);

--
-- Indexes for table `Books`
--
ALTER TABLE `Books`
  ADD PRIMARY KEY (`book_id`),
  ADD UNIQUE KEY `book_isbn` (`book_isbn`);

--
-- Indexes for table `Book_Authors`
--
ALTER TABLE `Book_Authors`
  ADD PRIMARY KEY (`ba_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `author_id` (`author_id`);

--
-- Indexes for table `Book_Copy`
--
ALTER TABLE `Book_Copy`
  ADD PRIMARY KEY (`copy_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `Borrowers_Address`
--
ALTER TABLE `Borrowers_Address`
  ADD PRIMARY KEY (`ba_bridge_id`),
  ADD KEY `borrower_id` (`user_id`),
  ADD KEY `address_id` (`address_id`);

--
-- Indexes for table `Genres`
--
ALTER TABLE `Genres`
  ADD PRIMARY KEY (`genre_id`),
  ADD UNIQUE KEY `genre_name` (`genre_name`);

--
-- Indexes for table `Genre_Books`
--
ALTER TABLE `Genre_Books`
  ADD PRIMARY KEY (`gb_id`),
  ADD KEY `genre_id` (`genre_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `Transactions`
--
ALTER TABLE `Transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `borrower_id` (`user_id`);

--
-- Indexes for table `Transaction_Copies`
--
ALTER TABLE `Transaction_Copies`
  ADD PRIMARY KEY (`tc_id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `copy_id` (`copy_id`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `borrower_email` (`user_email`);

--
-- Indexes for table `users_pictures`
--
ALTER TABLE `users_pictures`
  ADD PRIMARY KEY (`up_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Address`
--
ALTER TABLE `Address`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `Authors`
--
ALTER TABLE `Authors`
  MODIFY `author_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `Books`
--
ALTER TABLE `Books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `Book_Authors`
--
ALTER TABLE `Book_Authors`
  MODIFY `ba_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `Book_Copy`
--
ALTER TABLE `Book_Copy`
  MODIFY `copy_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `Borrowers_Address`
--
ALTER TABLE `Borrowers_Address`
  MODIFY `ba_bridge_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `Genres`
--
ALTER TABLE `Genres`
  MODIFY `genre_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `Genre_Books`
--
ALTER TABLE `Genre_Books`
  MODIFY `gb_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `Transactions`
--
ALTER TABLE `Transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `Transaction_Copies`
--
ALTER TABLE `Transaction_Copies`
  MODIFY `tc_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users_pictures`
--
ALTER TABLE `users_pictures`
  MODIFY `up_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Book_Authors`
--
ALTER TABLE `Book_Authors`
  ADD CONSTRAINT `book_authors_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `Books` (`book_id`),
  ADD CONSTRAINT `book_authors_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `Authors` (`author_id`);

--
-- Constraints for table `Book_Copy`
--
ALTER TABLE `Book_Copy`
  ADD CONSTRAINT `book_copy_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `Books` (`book_id`);

--
-- Constraints for table `Borrowers_Address`
--
ALTER TABLE `Borrowers_Address`
  ADD CONSTRAINT `borrowers_address_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `borrowers_address_ibfk_2` FOREIGN KEY (`address_id`) REFERENCES `Address` (`address_id`);

--
-- Constraints for table `Genre_Books`
--
ALTER TABLE `Genre_Books`
  ADD CONSTRAINT `genre_books_ibfk_1` FOREIGN KEY (`genre_id`) REFERENCES `Genres` (`genre_id`),
  ADD CONSTRAINT `genre_books_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `Books` (`book_id`);

--
-- Constraints for table `Transactions`
--
ALTER TABLE `Transactions`
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `Transaction_Copies`
--
ALTER TABLE `Transaction_Copies`
  ADD CONSTRAINT `transaction_copies_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `Transactions` (`transaction_id`),
  ADD CONSTRAINT `transaction_copies_ibfk_2` FOREIGN KEY (`copy_id`) REFERENCES `Book_Copy` (`copy_id`);

--
-- Constraints for table `users_pictures`
--
ALTER TABLE `users_pictures`
  ADD CONSTRAINT `users_pictures_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
