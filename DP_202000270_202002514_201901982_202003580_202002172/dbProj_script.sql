-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 22, 2024 at 06:50 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;



-- --------------------------------------------------------

--
-- Table structure for table `dbProj_catering`
--

CREATE TABLE `dbProj_catering` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dbProj_catering`
--

INSERT INTO `dbProj_catering` (`id`, `name`, `description`, `price`) VALUES
(1, 'Breakfast', 'Continental breakfast including pastries, fruits, and beverages', '10.00'),
(2, 'Lunch', 'Buffet lunch including salads, main courses, and desserts', '20.00'),
(3, 'Hot Beverages', 'Selection of hot beverages including coffee and tea', '5.00'),
(4, 'Cold Beverages', 'Selection of cold beverages including soft drinks and juices', '5.00');

-- --------------------------------------------------------

--
-- Table structure for table `dbProj_catering_reservations`
--

CREATE TABLE `dbProj_catering_reservations` (
  `id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL,
  `catering_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dbProj_events`
--

CREATE TABLE `dbProj_events` (
  `id` int(11) NOT NULL,
  `event_name` varchar(100) NOT NULL,
  `event_date` date NOT NULL,
  `hall_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dbProj_events`
--

INSERT INTO `dbProj_events` (`id`, `event_name`, `event_date`, `hall_id`, `client_id`) VALUES
(5, 'some event', '2024-05-22', 6, 15);

-- --------------------------------------------------------

--
-- Table structure for table `dbProj_halls`
--

CREATE TABLE `dbProj_halls` (
  `id` int(11) NOT NULL,
  `hall_name` varchar(100) NOT NULL,
  `capacity` int(11) NOT NULL,
  `available` tinyint(1) NOT NULL DEFAULT 1,
  `description` text DEFAULT NULL,
  `rental_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dbProj_halls`
--

INSERT INTO `dbProj_halls` (`id`, `hall_name`, `capacity`, `available`, `description`, `rental_cost`, `image`) VALUES
(1, 'Main Seminar Hall', 80, 1, 'A fully equipped seminar hall with a capacity of 80 audiences.', '0.00', 'hall1.png'),
(2, 'Small Hall 1', 30, 1, 'A small hall with a capacity of 30 audiences.', '0.00', 'hall1.png'),
(3, 'Small Hall 2', 30, 1, 'A small hall with a capacity of 30 audiences.', '0.00', 'hall1.png'),
(4, 'Small Hall 3', 30, 1, 'A small hall with a capacity of 30 audiences.', '0.00', 'hall1.png'),
(5, 'Computer Lab', 40, 1, 'A fully equipped lab with 40 desktop computers.', '0.00', 'hall1.png'),
(6, 'Main Seminar Hall', 80, 1, 'A fully equipped seminar hall with a capacity of 80 audiences.', '100.00', 'hall1.png'),
(7, 'Small Hall', 30, 1, 'A small hall with a capacity of 30 audiences.', '50.00', 'hall1.png'),
(8, 'Medium Hall', 30, 1, 'A medium hall with a capacity of 30 audiences.', '70.00', 'hall1.png'),
(9, 'Big Hall', 30, 1, 'A big hall with a capacity of 30 audiences.', '80.00', 'hall1.png'),
(10, 'Computer Lab', 40, 1, 'A fully equipped lab with 40 desktop computers.', '70.00', 'hall1.png');

-- --------------------------------------------------------

--
-- Table structure for table `dbProj_reservations`
--

CREATE TABLE `dbProj_reservations` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `hall_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `number_of_audiences` int(11) NOT NULL,
  `total_cost` decimal(10,2) NOT NULL,
  `reservation_code` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dbProj_reservations`
--

INSERT INTO `dbProj_reservations` (`id`, `client_id`, `hall_id`, `start_date`, `end_date`, `number_of_audiences`, `total_cost`, `reservation_code`) VALUES
(27, 15, 6, '2024-05-22', '2024-05-23', 10, '4800.00', 'RES-664e1f4118e026.69068786');

-- --------------------------------------------------------

--
-- Table structure for table `dbProj_users`
--

CREATE TABLE `dbProj_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `user_type` enum('client','employee','administrator') NOT NULL,
  `booked_events` int(11) DEFAULT 0,
  `client_status` enum('Gold','Silver','Bronze','None') DEFAULT 'None'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dbProj_users`
--

INSERT INTO `dbProj_users` (`id`, `username`, `password`, `email`, `user_type`, `booked_events`, `client_status`) VALUES
(15, 'administrator', '$2y$10$hgbZ3Jk2adf0FtKUcTUhj.22sbcdKFn1NttZc9XEOAgZnhP8aBV2C', '202002172@student.polytechnic.bh', 'administrator', 0, 'None');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dbProj_catering`
--
ALTER TABLE `dbProj_catering`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dbProj_catering_reservations`
--
ALTER TABLE `dbProj_catering_reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reservation_id` (`reservation_id`),
  ADD KEY `catering_id` (`catering_id`);

--
-- Indexes for table `dbProj_events`
--
ALTER TABLE `dbProj_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hall_id` (`hall_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `dbProj_halls`
--
ALTER TABLE `dbProj_halls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dbProj_reservations`
--
ALTER TABLE `dbProj_reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `hall_id` (`hall_id`);

--
-- Indexes for table `dbProj_users`
--
ALTER TABLE `dbProj_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dbProj_catering`
--
ALTER TABLE `dbProj_catering`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `dbProj_catering_reservations`
--
ALTER TABLE `dbProj_catering_reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `dbProj_events`
--
ALTER TABLE `dbProj_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `dbProj_halls`
--
ALTER TABLE `dbProj_halls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `dbProj_reservations`
--
ALTER TABLE `dbProj_reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `dbProj_users`
--
ALTER TABLE `dbProj_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dbProj_catering_reservations`
--
ALTER TABLE `dbProj_catering_reservations`
  ADD CONSTRAINT `dbproj_catering_reservations_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `dbProj_reservations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dbproj_catering_reservations_ibfk_2` FOREIGN KEY (`catering_id`) REFERENCES `dbProj_catering` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `dbProj_events`
--
ALTER TABLE `dbProj_events`
  ADD CONSTRAINT `dbproj_events_ibfk_1` FOREIGN KEY (`hall_id`) REFERENCES `dbProj_halls` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dbproj_events_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `dbProj_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `dbProj_reservations`
--
ALTER TABLE `dbProj_reservations`
  ADD CONSTRAINT `dbproj_reservations_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `dbProj_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dbproj_reservations_ibfk_2` FOREIGN KEY (`hall_id`) REFERENCES `dbProj_halls` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- Amending Reservation Details

DELIMITER //

CREATE PROCEDURE AmendReservationDetails(IN reservation_id INT, IN new_start_date DATE, IN new_end_date DATE)
BEGIN
    DECLARE original_cost DECIMAL(10,2);
    DECLARE updated_cost DECIMAL(10,2);
    
    -- Get the original cost of the reservation
    SELECT total_cost INTO original_cost FROM dbProj_reservations WHERE id = reservation_id;
    
    -- Check if the reservation can be amended
    IF DATEDIFF(new_start_date, CURDATE()) <= 2 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Reservation cannot be amended within 2 days of the event.';
    END IF;

    -- Calculate the updated cost with a 5% charge
    SET updated_cost = original_cost * 1.05;

    -- Update reservation details
    UPDATE dbProj_reservations
    SET start_date = new_start_date, end_date = new_end_date, total_cost = updated_cost
    WHERE id = reservation_id;

END //

DELIMITER ;

-- Enter Personal/ Business details to Confirm Reservation


DELIMITER //

CREATE PROCEDURE MakeReservation(
    IN user_id INT,
    IN hall_id INT,
    IN start_date DATE,
    IN end_date DATE,
    IN number_of_audiences INT,
    IN catering_selections VARCHAR(255),
    IN payment_details VARCHAR(255)
)
BEGIN
    DECLARE total_cost DECIMAL(10, 2);
    DECLARE discount DECIMAL(10, 2);
    DECLARE final_cost DECIMAL(10, 2);

    -- Calculate total cost based on hall rental and catering selections
    SELECT SUM(h.rental_cost) INTO total_cost
    FROM dbProj_halls h
    WHERE h.id = hall_id;
    
    -- Add catering costs
    SELECT SUM(c.price) INTO total_cost
    FROM dbProj_catering c
    WHERE FIND_IN_SET(c.id, catering_selections) > 0;

    -- Apply discount based on client status
    SELECT u.client_status INTO discount
    FROM dbProj_users u
    WHERE u.id = user_id;

    CASE discount
        WHEN 'Gold' THEN SET total_cost = total_cost * 0.9; -- 10% discount for Gold status
        WHEN 'Silver' THEN SET total_cost = total_cost * 0.95; -- 5% discount for Silver status
        WHEN 'Bronze' THEN SET total_cost = total_cost * 0.97; -- 3% discount for Bronze status
    END CASE;

    -- Confirm reservation
    INSERT INTO dbProj_reservations (client_id, hall_id, start_date, end_date, number_of_audiences, total_cost)
    VALUES (user_id, hall_id, start_date, end_date, number_of_audiences, total_cost);

    -- Display reservation confirmation and unique code
    SELECT CONCAT('Reservation confirmed. Total cost: $', total_cost) AS Confirmation;

    -- Generate and display unique reservation code
    SELECT CONCAT('Your reservation code is: RES-', LAST_INSERT_ID()) AS ReservationCode;

    -- Give royalty points to the client
    UPDATE dbProj_users SET booked_events = booked_events + 1 WHERE id = user_id;

    -- Display royalty points awarded
    SELECT 'Royalty points awarded.' AS RoyaltyPoints;
END //

DELIMITER ;