PRAGMA foreign_keys = ON;
-- Insert sample users
INSERT
    OR IGNORE INTO users (
        id,
        name,
        username,
        password,
        email,
        role,
        profile_pic,
        bio
    )
VALUES (
        1,
        'André Restivo',
        'arestivo',
        '$2y$12$KZImD5OjtsFLvWr96BAGXeiPeChrjHc5LjMvwkZONDMjE0qFO7Gpe', -- Password: 123456
        'arestivo@fe.up.pt',
        'admin',
        'database/assets/adminProfilePic.jpg',
        'Administrator of the platform'
    ),
    (
        2,
        'John Doe',
        'john_doe',
        '$2y$12$KZImD5OjtsFLvWr96BAGXeiPeChrjHc5LjMvwkZONDMjE0qFO7Gpe', -- Password: 123456
        'john@example.com',
        'user',
        'database/assets/userProfilePic.jpg',
        'Regular user'
    ),
    (
        3,
        'Jane Smith',
        'jane_smith',
        '$2y$12$KZImD5OjtsFLvWr96BAGXeiPeChrjHc5LjMvwkZONDMjE0qFO7Gpe', -- Password: 123456
        'jane@example.com',
        'freelancer',
        'database/assets/freelancerProfilePic.jpg',
        'Freelancer specializing in design'
    ),
    (
        4,
        'Alice Johnson',
        'alice_johnson',
        '$2y$12$KZImD5OjtsFLvWr96BAGXeiPeChrjHc5LjMvwkZONDMjE0qFO7Gpe', -- Password: 123456
        'alice@example.com',
        'freelancer',
        'database/assets/freelancerProfilePic.jpg',
        'Freelancer specializing in illustration'
    ),
    (
        5,
        'Bob Brown',
        'bob_brown',
        '$2y$12$KZImD5OjtsFLvWr96BAGXeiPeChrjHc5LjMvwkZONDMjE0qFO7Gpe', -- Password: 123456
        'bob@example.com',
        'freelancer',
        'database/assets/freelancerProfilePic.jpg',
        'Freelancer specializing in backend development'
    ),
    (
        6,
        'Charlie Davis',
        'charlie_davis',
        '$2y$12$KZImD5OjtsFLvWr96BAGXeiPeChrjHc5LjMvwkZONDMjE0qFO7Gpe', -- Password: 123456
        'charlie@example.com',
        'freelancer',
        'database/assets/freelancerProfilePic.jpg',
        'Freelancer specializing in video editing'
    ),
    (
        7,
        'Diana Evans',
        'diana_evans',
        '$2y$12$KZImD5OjtsFLvWr96BAGXeiPeChrjHc5LjMvwkZONDMjE0qFO7Gpe', -- Password: 123456
        'diana@example.com',
        'freelancer',
        'database/assets/freelancerProfilePic.jpg',
        'Freelancer specializing in photography'
    ),
    (
        8,
        'Ethan Foster',
        'ethan_foster',
        '$2y$12$KZImD5OjtsFLvWr96BAGXeiPeChrjHc5LjMvwkZONDMjE0qFO7Gpe', -- Password: 123456
        'ethan@example.com',
        'freelancer',
        'database/assets/freelancerProfilePic.jpg',
        'Freelancer specializing in mobile app development'
    ),
    (
        9,
        'Fiona Green',
        'fiona_green',
        '$2y$12$KZImD5OjtsFLvWr96BAGXeiPeChrjHc5LjMvwkZONDMjE0qFO7Gpe', -- Password: 123456
        'fiona@example.com',
        'freelancer',
        'database/assets/freelancerProfilePic.jpg',
        'Freelancer specializing in content marketing'
    ),
    (
        10,
        'George Harris',
        'george_harris',
        '$2y$12$KZImD5OjtsFLvWr96BAGXeiPeChrjHc5LjMvwkZONDMjE0qFO7Gpe', -- Password: 123456
        'george@example.com',
        'freelancer',
        'database/assets/freelancerProfilePic.jpg',
        'Freelancer specializing in SEO optimization'
    ),
    (
        11,
        'Hannah White',
        'hannah_white',
        '$2y$12$KZImD5OjtsFLvWr96BAGXeiPeChrjHc5LjMvwkZONDMjE0qFO7Gpe', -- Password: 123456
        'hannah@example.com',
        'freelancer',
        'database/assets/freelancerProfilePic.jpg',
        'Freelancer specializing in social media management'
    ),
    (
        12,
        'Ian King',
        'ian_king',
        '$2y$12$KZImD5OjtsFLvWr96BAGXeiPeChrjHc5LjMvwkZONDMjE0qFO7Gpe', -- Password: 123456
        'ian@example.com',
        'freelancer',
        'database/assets/freelancerProfilePic.jpg',
        'Freelancer specializing in game development'
    ),
    (
        13,
        'Julia Lee',
        'julia_lee',
        '$2y$12$KZImD5OjtsFLvWr96BAGXeiPeChrjHc5LjMvwkZONDMjE0qFO7Gpe', -- Password: 123456
        'julia@example.com',
        'freelancer',
        'database/assets/freelancerProfilePic.jpg',
        'Freelancer specializing in UX/UI design'
    ),
    (
        14,
        'Pedro Monteiro',
        'pedroafmonteiro',
        '$2y$12$KZImD5OjtsFLvWr96BAGXeiPeChrjHc5LjMvwkZONDMjE0qFO7Gpe', -- Password: 123456
        'pedroafmonteiro10@gmail.com',
        'admin',
        'database/assets/profiles/14/profile_picture.jpg',
        'Administrator of the platform'
    ),
    (
        15,
        'Pedro Lunet',
        'pedrolunet',
        '$2y$12$KZImD5OjtsFLvWr96BAGXeiPeChrjHc5LjMvwkZONDMjE0qFO7Gpe', -- Password: 123456
        'pedrolunet@gmail.com',
        'admin',
        'database/assets/adminProfilePic.jpg',
        'Administrator of the platform'
    ),
    (
        16,
        'João Lopes',
        'joaolopes',
        '$2y$12$KZImD5OjtsFLvWr96BAGXeiPeChrjHc5LjMvwkZONDMjE0qFO7Gpe', -- Password: 123456
        'joaomlopes20059@gmail.com',
        'admin',
        'database/assets/adminProfilePic.jpg',
        'Administrator of the platform'
    );
-- Insert sample categories
INSERT
    OR IGNORE INTO categories (id, name)
VALUES (1, 'Design'),
    (2, 'Programming'),
    (3, 'Writing'),
    (4, 'Photography'),
    (5, 'Video Production'),
    (6, 'Game Development'),
    (7, 'Marketing'),
    (8, 'Music'),
    (9, 'Business'),
    (10, 'Education');
-- Insert sample services
INSERT
    OR IGNORE INTO services (
        id,
        name,
        description,
        price,
        seller,
        category,
        image,
        rating
    )
VALUES (
        1,
        'Logo Design',
        'Professional logo design services',
        50.00,
        3,
        1,
        'assets/placeholder.png',
        4.5
    ),
    (
        2,
        'Web Development',
        'Full-stack web development',
        200.00,
        3,
        2,
        'assets/placeholder.png',
        4.8
    ),
    (
        3,
        'Content Writing',
        'High-quality content writing',
        30.00,
        2,
        3,
        'assets/placeholder.png',
        4.2
    ),
    (
        4,
        'Custom Illustration',
        'Unique illustrations for your project',
        100.00,
        4,
        1,
        'assets/placeholder.png',
        4.7
    ),
    (
        5,
        'Character Design',
        'Professional character design services',
        120.00,
        4,
        1,
        'assets/placeholder.png',
        4.9
    ),
    (
        6,
        'Book Cover Design',
        'Creative book cover designs',
        80.00,
        4,
        1,
        'assets/placeholder.png',
        4.6
    ),
    (
        7,
        'API Development',
        'Custom API development',
        150.00,
        5,
        2,
        'assets/placeholder.png',
        4.8
    ),
    (
        8,
        'Database Optimization',
        'Optimize your database for performance',
        200.00,
        5,
        2,
        'assets/placeholder.png',
        4.7
    ),
    (
        9,
        'Backend Architecture',
        'Design scalable backend systems',
        250.00,
        5,
        2,
        'assets/placeholder.png',
        4.9
    ),
    (
        10,
        'Video Editing',
        'Professional video editing services',
        300.00,
        6,
        1,
        'assets/placeholder.png',
        4.8
    ),
    (
        11,
        'Motion Graphics',
        'Create stunning motion graphics',
        350.00,
        6,
        1,
        'assets/placeholder.png',
        4.9
    ),
    (
        12,
        'Color Grading',
        'Enhance your videos with color grading',
        200.00,
        6,
        1,
        'assets/placeholder.png',
        4.7
    ),
    (
        13,
        'Portrait Photography',
        'High-quality portrait photography',
        150.00,
        7,
        1,
        'assets/placeholder.png',
        4.8
    ),
    (
        14,
        'Event Photography',
        'Capture your events professionally',
        300.00,
        7,
        1,
        'assets/placeholder.png',
        4.9
    ),
    (
        15,
        'Product Photography',
        'Showcase your products with great photos',
        250.00,
        7,
        1,
        'assets/placeholder.png',
        4.7
    ),
    (
        16,
        'Mobile App Development',
        'Build custom mobile apps',
        500.00,
        8,
        2,
        'assets/placeholder.png',
        4.8
    ),
    (
        17,
        'Cross-Platform Apps',
        'Develop apps for multiple platforms',
        600.00,
        8,
        2,
        'assets/placeholder.png',
        4.9
    ),
    (
        18,
        'App Maintenance',
        'Maintain and update your apps',
        300.00,
        8,
        2,
        'assets/placeholder.png',
        4.7
    ),
    (
        19,
        'Content Strategy',
        'Develop a content strategy for your brand',
        200.00,
        9,
        3,
        'assets/placeholder.png',
        4.8
    ),
    (
        20,
        'Blog Writing',
        'Write engaging blog posts',
        100.00,
        9,
        3,
        'assets/placeholder.png',
        4.9
    ),
    (
        21,
        'Copywriting',
        'Create compelling copy for your business',
        150.00,
        9,
        3,
        'assets/placeholder.png',
        4.7
    ),
    (
        22,
        'SEO Audit',
        'Comprehensive SEO audit for your website',
        300.00,
        10,
        3,
        'assets/placeholder.png',
        4.8
    ),
    (
        23,
        'Keyword Research',
        'Find the best keywords for your niche',
        150.00,
        10,
        3,
        'assets/placeholder.png',
        4.9
    ),
    (
        24,
        'Link Building',
        'Build high-quality backlinks',
        400.00,
        10,
        3,
        'assets/placeholder.png',
        4.7
    ),
    (
        25,
        'Social Media Strategy',
        'Develop a social media strategy',
        200.00,
        11,
        3,
        'assets/placeholder.png',
        4.8
    ),
    (
        26,
        'Content Scheduling',
        'Schedule content for your social media',
        150.00,
        11,
        3,
        'assets/placeholder.png',
        4.9
    ),
    (
        27,
        'Analytics Reporting',
        'Analyze and report social media performance',
        250.00,
        11,
        3,
        'assets/placeholder.png',
        4.7
    ),
    (
        28,
        'Game Prototyping',
        'Create prototypes for your game ideas',
        400.00,
        12,
        2,
        'assets/placeholder.png',
        4.8
    ),
    (
        29,
        'Level Design',
        'Design engaging levels for your game',
        350.00,
        12,
        2,
        'assets/placeholder.png',
        4.9
    ),
    (
        30,
        'Game Testing',
        'Test your game for bugs and improvements',
        200.00,
        12,
        2,
        'assets/placeholder.png',
        4.7
    ),
    (
        31,
        'UX Research',
        'Conduct UX research for your product',
        300.00,
        13,
        1,
        'assets/placeholder.png',
        4.8
    ),
    (
        32,
        'Wireframing',
        'Create wireframes for your app or website',
        200.00,
        13,
        1,
        'assets/placeholder.png',
        4.9
    ),
    (
        33,
        'UI Design',
        'Design user interfaces for your product',
        400.00,
        13,
        1,
        'assets/placeholder.png',
        4.7
    );