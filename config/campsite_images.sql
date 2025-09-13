-- Add a new table for campsite images
CREATE TABLE `campsite_images` (
  `image_id` INT NOT NULL AUTO_INCREMENT,
  `site_id` INT NOT NULL,
  `file_path` VARCHAR(255) NOT NULL,
  `file_name` VARCHAR(255) NOT NULL,
  `uploaded_by` VARCHAR(50) DEFAULT NULL,
  `uploaded_on` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`image_id`),
  FOREIGN KEY (`site_id`) REFERENCES `site` (`IDX`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;