
CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `booked_seats` varchar(45) NOT NULL,
  `book_type` varchar(10) DEFAULT 'reserved',
  `price` varchar(10) DEFAULT NULL,
  `show_date` date DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `genre` varchar(255) NOT NULL,
  `release_date` date NOT NULL,
  `runTime` varchar(10) DEFAULT NULL,
  `director` varchar(255) DEFAULT NULL,
  `cast` varchar(500) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `screenings`
--

CREATE TABLE `screenings` (
  `id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `theater_id` int(11) NOT NULL,
  `showtime` datetime NOT NULL,
  `available_seats` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `seats`
--

CREATE TABLE `seats` (
  `id` int(11) NOT NULL,
  `movie_id` int(11) DEFAULT NULL,
  `availability_status` varchar(255) DEFAULT 'Available',
  `theater_id` int(11) DEFAULT NULL,
  `seat_number` varchar(5) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `theaters`
--

CREATE TABLE `theaters` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `screening_id` (`movie_id`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `screenings`
--
ALTER TABLE `screenings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `movie_id` (`movie_id`),
  ADD KEY `theater_id` (`theater_id`);

--
-- Indexes for table `seats`
--
ALTER TABLE `seats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `movie_id` (`movie_id`),
  ADD KEY `theater_id` (`theater_id`);

--
-- Indexes for table `theaters`
--
ALTER TABLE `theaters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `screenings`
--
ALTER TABLE `screenings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seats`
--
ALTER TABLE `seats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `theaters`
--
ALTER TABLE `theaters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`movie_id`) REFERENCES `screenings` (`id`);

--
-- Constraints for table `screenings`
--
ALTER TABLE `screenings`
  ADD CONSTRAINT `screenings_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`),
  ADD CONSTRAINT `screenings_ibfk_2` FOREIGN KEY (`theater_id`) REFERENCES `theaters` (`id`);

--
-- Constraints for table `seats`
--
ALTER TABLE `seats`
  ADD CONSTRAINT `seats_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`),
  ADD CONSTRAINT `seats_ibfk_2` FOREIGN KEY (`theater_id`) REFERENCES `theaters` (`id`);
  INSERT INTO `seats` (`id`, `movie_id`, `availability_status`, `theater_id`, `seat_number`, `price`) VALUES
(1, 1, 'Available', 1, '1A', '100.00'),
(2, 1, 'Available', 1, '1B', '100.00'),
(3, 1, 'Available', 1, '1C', '100.00'),
(4, 1, 'Available', 1, '1D', '100.00'),
(5, 1, 'Available', 1, '1E', '100.00'),
(6, 1, 'Available', 1, '1F', '200.00'),
(7, 1, 'Available', 1, '1G', '200.00'),
(8, 1, 'Available', 1, '1H', '200.00'),
(9, 1, 'Available', 1, '1I', '200.00'),
(10, 1, 'Available', 1, '1J', '200.00'),
(11, 1, 'Available', 1, '2A', '100.00'),
(12, 1, 'Available', 1, '2B', '100.00'),
(13, 1, 'Available', 1, '2C', '100.00'),
(14, 1, 'Available', 1, '2D', '100.00'),
(15, 1, 'Available', 1, '2E', '100.00'),
(16, 1, 'Available', 1, '2F', '200.00'),
(17, 1, 'Available', 1, '2G', '200.00'),
(18, 1, 'Available', 1, '2H', '200.00'),
(19, 1, 'Available', 1, '2I', '200.00'),
(20, 1, 'Available', 1, '2J', '200.00'),
(21, 1, 'Available', 1, '3A', '100.00'),
(22, 1, 'Available', 1, '3B', '100.00'),
(23, 1, 'Available', 1, '3C', '100.00'),
(24, 1, 'Available', 1, '3D', '100.00'),
(25, 1, 'Booked', 1, '3E', '100.00'),
(26, 1, 'Available', 1, '3F', '200.00'),
(27, 1, 'Available', 1, '3G', '200.00'),
(28, 1, 'Available', 1, '3H', '200.00'),
(29, 1, 'Available', 1, '3I', '200.00'),
(30, 1, 'Available', 1, '3J', '200.00'),
(31, 1, 'Available', 1, '4A', '100.00'),
(32, 1, 'Available', 1, '4B', '100.00'),
(33, 1, 'Available', 1, '4C', '100.00'),
(34, 1, 'Available', 1, '4D', '100.00'),
(35, 1, 'Booked', 1, '4E', '100.00'),
(36, 1, 'Booked', 1, '4F', '200.00'),
(37, 1, 'Available', 1, '4G', '200.00'),
(38, 1, 'Available', 1, '4H', '200.00'),
(39, 1, 'Available', 1, '4I', '200.00'),
(40, 1, 'Available', 1, '4J', '200.00'),
(41, 1, 'Available', 1, '5A', '100.00'),
(42, 1, 'Available', 1, '5B', '100.00'),
(43, 1, 'Available', 1, '5C', '100.00'),
(44, 1, 'Available', 1, '5D', '100.00'),
(45, 1, 'Available', 1, '5E', '100.00'),
(46, 1, 'Available', 1, '5F', '200.00'),
(47, 1, 'Available', 1, '5G', '200.00'),
(48, 1, 'Available', 1, '5H', '200.00'),
(49, 1, 'Available', 1, '5I', '200.00'),
(50, 1, 'Available', 1, '5J', '200.00'),
(51, 1, 'Available', 1, '6A', '100.00'),
(52, 1, 'Available', 1, '6B', '100.00'),
(53, 1, 'Available', 1, '6C', '100.00'),
(54, 1, 'Available', 1, '6D', '100.00'),
(55, 1, 'Available', 1, '6E', '100.00'),
(56, 1, 'Available', 1, '6F', '200.00'),
(57, 1, 'Available', 1, '6G', '200.00'),
(58, 1, 'Available', 1, '6H', '200.00'),
(59, 1, 'Available', 1, '6I', '200.00'),
(60, 1, 'Available', 1, '6J', '200.00'),
(61, 1, 'Available', 1, '7A', '100.00'),
(62, 1, 'Available', 1, '7B', '100.00'),
(63, 1, 'Available', 1, '7C', '100.00'),
(64, 1, 'Available', 1, '7D', '100.00'),
(65, 1, 'Available', 1, '7E', '100.00'),
(66, 1, 'Available', 1, '7F', '200.00'),
(67, 1, 'Available', 1, '7G', '200.00'),
(68, 1, 'Available', 1, '7H', '200.00'),
(69, 1, 'Available', 1, '7I', '200.00'),
(70, 1, 'Available', 1, '7J', '200.00'),
(71, 1, 'Available', 1, '8A', '100.00'),
(72, 1, 'Available', 1, '8B', '100.00'),
(73, 1, 'Available', 1, '8C', '100.00'),
(74, 1, 'Available', 1, '8D', '100.00'),
(75, 1, 'Available', 1, '8E', '100.00'),
(76, 1, 'Available', 1, '8F', '200.00'),
(77, 1, 'Available', 1, '8G', '200.00'),
(78, 1, 'Available', 1, '8H', '200.00'),
(79, 1, 'Available', 1, '8I', '200.00'),
(80, 1, 'Available', 1, '8J', '200.00'),
(81, 1, 'Available', 1, '9A', '100.00'),
(82, 1, 'Available', 1, '9B', '100.00'),
(83, 1, 'Available', 1, '9C', '100.00'),
(84, 1, 'Available', 1, '9D', '100.00'),
(85, 1, 'Available', 1, '9E', '100.00'),
(86, 1, 'Available', 1, '9F', '200.00'),
(87, 1, 'Available', 1, '9G', '200.00'),
(88, 1, 'Available', 1, '9H', '200.00'),
(89, 1, 'Available', 1, '9I', '200.00'),
(90, 1, 'Available', 1, '9J', '200.00'),
(91, 1, 'Available', 1, '10A', '100.00'),
(92, 1, 'Available', 1, '10B', '100.00'),
(93, 1, 'Available', 1, '10C', '100.00'),
(94, 1, 'Available', 1, '10D', '100.00'),
(95, 1, 'Available', 1, '10E', '100.00'),
(96, 1, 'Available', 1, '10F', '200.00'),
(97, 1, 'Available', 1, '10G', '200.00'),
(238, 2, 'Available', 2, 'A1', '200.00');
COMMIT;