-- Seed existing venue data from gcal-gigs.php

-- Generic venues (alpha_only matching)
INSERT INTO venues (name, match_pattern, match_type, venue_logo, is_active) VALUES
('Farmers Market', 'Farmers Market', 'alpha_only', '/imgs/cal/FarmersMarket.jpg', 1);

-- Specific venues (exact matching)
INSERT INTO venues (name, match_pattern, match_type, venue_logo, is_active) VALUES
('Santa Paula Farmers Market', 'Santa Paula Farmers Market', 'exact', '/imgs/cal/SantaPaulaFarmersMarket.png', 1),
('Coffee A La Mode', 'Coffee A La Mode', 'exact', '/imgs/cal/Coffeelogo.jpg', 1),
('Adventist Health Simi Valley Farmers Market', 'Adventist Health Simi Valley Farmers Market', 'exact', '/imgs/cal/AHSV-FarmersMarket-Logo.png', 1),
('Adventist Health Farmers Market', 'Adventist Health Farmers Market', 'exact', '/imgs/cal/AHSV-FarmersMarket-Logo.png', 1),
('Simi Valley Farmers Market', 'Simi Valley Farmers Market', 'exact', '/imgs/cal/SimiValleyFarmersMarket.jpg', 1);
