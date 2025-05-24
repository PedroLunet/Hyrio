PRAGMA foreign_keys = ON;
-- Insert sample users
INSERT
    OR IGNORE INTO users (
        id,
        name,
        username,
        password,
        email,
        is_seller,
        is_admin,
        profile_pic,
        bio
    )
VALUES (
        1,
        'André Restivo',
        'arestivo',
        '$2y$12$KZImD5OjtsFLvWr96BAGXeiPeChrjHc5LjMvwkZONDMjE0qFO7Gpe', -- Password: 123456
        'arestivo@fe.up.pt',
        0,
        1,
        '/database/assets/adminProfilePic.jpg',
        'Administrator of the platform'
    ),
    (
        2,
        'John Doe',
        'john_doe',
        '$2y$12$KZImD5OjtsFLvWr96BAGXeiPeChrjHc5LjMvwkZONDMjE0qFO7Gpe', -- Password: 123456
        'john@example.com',
        0,
        0,
        '/database/assets/userProfilePic.jpg',
        'Regular user'
    ),
    (
        3,
        'Jane Smith',
        'jane_smith',
        '$2y$12$KZImD5OjtsFLvWr96BAGXeiPeChrjHc5LjMvwkZONDMjE0qFO7Gpe', -- Password: 123456
        'jane@example.com',
        1,
        0,
        '/database/assets/freelancerProfilePic.jpg',
        'Freelancer specializing in design'
    ),
    (
        4,
        'Alice Johnson',
        'alice_johnson',
        '$2y$12$KZImD5OjtsFLvWr96BAGXeiPeChrjHc5LjMvwkZONDMjE0qFO7Gpe', -- Password: 123456
        'alice@example.com',
        1,
        0,
        '/database/assets/freelancerProfilePic.jpg',
        'Freelancer specializing in illustration'
    ),
    (
        5,
        'Bob Brown',
        'bob_brown',
        '$2y$12$KZImD5OjtsFLvWr96BAGXeiPeChrjHc5LjMvwkZONDMjE0qFO7Gpe', -- Password: 123456
        'bob@example.com',
        1,
        0,
        '/database/assets/freelancerProfilePic.jpg',
        'Freelancer specializing in backend development'
    ),
    (
        6,
        'Charlie Davis',
        'charlie_davis',
        '$2y$12$KZImD5OjtsFLvWr96BAGXeiPeChrjHc5LjMvwkZONDMjE0qFO7Gpe', -- Password: 123456
        'charlie@example.com',
        1,
        0,
        '/database/assets/freelancerProfilePic.jpg',
        'Freelancer specializing in video editing'
    ),
    (
        7,
        'Diana Evans',
        'diana_evans',
        '$2y$12$KZImD5OjtsFLvWr96BAGXeiPeChrjHc5LjMvwkZONDMjE0qFO7Gpe', -- Password: 123456
        'diana@example.com',
        1,
        0,
        '/database/assets/freelancerProfilePic.jpg',
        'Freelancer specializing in photography'
    ),
    (
        8,
        'Ethan Foster',
        'ethan_foster',
        '$2y$12$KZImD5OjtsFLvWr96BAGXeiPeChrjHc5LjMvwkZONDMjE0qFO7Gpe', -- Password: 123456
        'ethan@example.com',
        1,
        0,
        '/database/assets/freelancerProfilePic.jpg',
        'Freelancer specializing in mobile app development'
    ),
    (
        9,
        'Fiona Green',
        'fiona_green',
        '$2y$12$KZImD5OjtsFLvWr96BAGXeiPeChrjHc5LjMvwkZONDMjE0qFO7Gpe', -- Password: 123456
        'fiona@example.com',
        1,
        0,
        '/database/assets/freelancerProfilePic.jpg',
        'Freelancer specializing in content marketing'
    ),
    (
        10,
        'George Harris',
        'george_harris',
        '$2y$12$KZImD5OjtsFLvWr96BAGXeiPeChrjHc5LjMvwkZONDMjE0qFO7Gpe', -- Password: 123456
        'george@example.com',
        1,
        0,
        '/database/assets/freelancerProfilePic.jpg',
        'Freelancer specializing in SEO optimization'
    ),
    (
        11,
        'Hannah White',
        'hannah_white',
        '$2y$12$KZImD5OjtsFLvWr96BAGXeiPeChrjHc5LjMvwkZONDMjE0qFO7Gpe', -- Password: 123456
        'hannah@example.com',
        1,
        0,
        '/database/assets/freelancerProfilePic.jpg',
        'Freelancer specializing in social media management'
    ),
    (
        12,
        'Ian King',
        'ian_king',
        '$2y$12$KZImD5OjtsFLvWr96BAGXeiPeChrjHc5LjMvwkZONDMjE0qFO7Gpe', -- Password: 123456
        'ian@example.com',
        1,
        0,
        '/database/assets/freelancerProfilePic.jpg',
        'Freelancer specializing in game development'
    ),
    (
        13,
        'Julia Lee',
        'julia_lee',
        '$2y$12$KZImD5OjtsFLvWr96BAGXeiPeChrjHc5LjMvwkZONDMjE0qFO7Gpe', -- Password: 123456
        'julia@example.com',
        1,
        0,
        '/database/assets/freelancerProfilePic.jpg',
        'Freelancer specializing in UX/UI design'
    ),
    (
        14,
        'Pedro Monteiro',
        'pedroafmonteiro',
        '$2y$12$KZImD5OjtsFLvWr96BAGXeiPeChrjHc5LjMvwkZONDMjE0qFO7Gpe', -- Password: 123456
        'pedroafmonteiro10@gmail.com',
        0,
        1,
        '/database/assets/profiles/14/profile_picture.jpg',
        'Administrator of the platform'
    ),
    (
        15,
        'Pedro Lunet',
        'pedrolunet',
        '$2y$12$KZImD5OjtsFLvWr96BAGXeiPeChrjHc5LjMvwkZONDMjE0qFO7Gpe', -- Password: 123456
        'pedrolunet@gmail.com',
        0,
        1,
        '/database/assets/adminProfilePic.jpg',
        'Administrator of the platform'
    ),
    (
        16,
        'João Lopes',
        'jlopes_15',
        '$2y$12$KZImD5OjtsFLvWr96BAGXeiPeChrjHc5LjMvwkZONDMjE0qFO7Gpe', -- Password: 123456
        'joaomiguel20059@gmail.com',
        0,
        1,
        '/database/assets/adminProfilePic.jpg',
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
        '/assets/placeholder.png',
        4.5
    ),
    (
        2,
        'Web Development',
        'Full-stack web development',
        200.00,
        3,
        2,
        '/assets/placeholder.png',
        4.8
    ),
    (
        3,
        'Content Writing',
        'High-quality content writing',
        30.00,
        2,
        3,
        '/assets/placeholder.png',
        4.2
    ),
    (
        4,
        'Custom Illustration',
        'Unique illustrations for your project',
        100.00,
        4,
        1,
        '/assets/placeholder.png',
        4.7
    ),
    (
        5,
        'Character Design',
        'Professional character design services',
        120.00,
        4,
        1,
        '/assets/placeholder.png',
        4.9
    ),
    (
        6,
        'Book Cover Design',
        'Creative book cover designs',
        80.00,
        4,
        1,
        '/assets/placeholder.png',
        4.6
    ),
    (
        7,
        'API Development',
        'Custom API development',
        150.00,
        5,
        2,
        '/assets/placeholder.png',
        4.8
    ),
    (
        8,
        'Database Optimization',
        'Optimize your database for performance',
        200.00,
        5,
        2,
        '/assets/placeholder.png',
        4.7
    ),
    (
        9,
        'Backend Architecture',
        'Design scalable backend systems',
        250.00,
        5,
        2,
        '/assets/placeholder.png',
        4.9
    ),
    (
        10,
        'Video Editing',
        'Professional video editing services',
        300.00,
        6,
        1,
        '/assets/placeholder.png',
        4.8
    ),
    (
        11,
        'Motion Graphics',
        'Create stunning motion graphics',
        350.00,
        6,
        1,
        '/assets/placeholder.png',
        4.9
    ),
    (
        12,
        'Color Grading',
        'Enhance your videos with color grading',
        200.00,
        6,
        1,
        '/assets/placeholder.png',
        4.7
    ),
    (
        13,
        'Portrait Photography',
        'High-quality portrait photography',
        150.00,
        7,
        1,
        '/assets/placeholder.png',
        4.8
    ),
    (
        14,
        'Event Photography',
        'Capture your events professionally',
        300.00,
        7,
        1,
        '/assets/placeholder.png',
        4.9
    ),
    (
        15,
        'Product Photography',
        'Showcase your products with great photos',
        250.00,
        7,
        1,
        '/assets/placeholder.png',
        4.7
    ),
    (
        16,
        'Mobile App Development',
        'Build custom mobile apps',
        500.00,
        8,
        2,
        '/assets/placeholder.png',
        4.8
    ),
    (
        17,
        'Cross-Platform Apps',
        'Develop apps for multiple platforms',
        600.00,
        8,
        2,
        '/assets/placeholder.png',
        4.9
    ),
    (
        18,
        'App Maintenance',
        'Maintain and update your apps',
        300.00,
        8,
        2,
        '/assets/placeholder.png',
        4.7
    ),
    (
        19,
        'Content Strategy',
        'Develop a content strategy for your brand',
        200.00,
        9,
        3,
        '/assets/placeholder.png',
        4.8
    ),
    (
        20,
        'Blog Writing',
        'Write engaging blog posts',
        100.00,
        9,
        3,
        '/assets/placeholder.png',
        4.9
    ),
    (
        21,
        'Copywriting',
        'Create compelling copy for your business',
        150.00,
        9,
        3,
        '/assets/placeholder.png',
        4.7
    ),
    (
        22,
        'SEO Audit',
        'Comprehensive SEO audit for your website',
        300.00,
        10,
        3,
        '/assets/placeholder.png',
        4.8
    ),
    (
        23,
        'Keyword Research',
        'Find the best keywords for your niche',
        150.00,
        10,
        3,
        '/assets/placeholder.png',
        4.9
    ),
    (
        24,
        'Link Building',
        'Build high-quality backlinks',
        400.00,
        10,
        3,
        '/assets/placeholder.png',
        4.7
    ),
    (
        25,
        'Social Media Strategy',
        'Develop a social media strategy',
        200.00,
        11,
        3,
        '/assets/placeholder.png',
        4.8
    ),
    (
        26,
        'Content Scheduling',
        'Schedule content for your social media',
        150.00,
        11,
        3,
        '/assets/placeholder.png',
        4.9
    ),
    (
        27,
        'Analytics Reporting',
        'Analyze and report social media performance',
        250.00,
        11,
        3,
        '/assets/placeholder.png',
        4.7
    ),
    (
        28,
        'Game Prototyping',
        'Create prototypes for your game ideas',
        400.00,
        12,
        2,
        '/assets/placeholder.png',
        4.8
    ),
    (
        29,
        'Level Design',
        'Design engaging levels for your game',
        350.00,
        12,
        2,
        '/assets/placeholder.png',
        4.9
    ),
    (
        30,
        'Game Testing',
        'Test your game for bugs and improvements',
        200.00,
        12,
        2,
        '/assets/placeholder.png',
        4.7
    ),
    (
        31,
        'UX Research',
        'Conduct UX research for your product',
        300.00,
        13,
        1,
        '/assets/placeholder.png',
        4.8
    ),
    (
        32,
        'Wireframing',
        'Create wireframes for your app or website',
        200.00,
        13,
        1,
        '/assets/placeholder.png',
        4.9
    ),
    (
        33,
        'UI Design',
        'Design user interfaces for your product',
        400.00,
        13,
        1,
        '/assets/placeholder.png',
        4.7
    );

-- Insert sample favorites
INSERT
    OR IGNORE INTO favorites (
        id,
        user_id,
        service_id,
        created_at
    )
VALUES (1, 2, 1, '2024-01-15 10:30:00'),
    (2, 2, 4, '2024-01-16 14:20:00'),
    (3, 2, 10, '2024-01-17 09:45:00'),
    (4, 14, 2, '2024-01-18 16:10:00'),
    (5, 14, 7, '2024-01-19 11:25:00'),
    (6, 14, 16, '2024-01-20 13:55:00'),
    (7, 15, 1, '2024-01-21 08:30:00'),
    (8, 15, 13, '2024-01-22 15:40:00'),
    (9, 15, 19, '2024-01-23 12:15:00'),
    (10, 16, 5, '2024-01-24 17:20:00'),
    (11, 16, 12, '2024-01-25 10:05:00'),
    (12, 16, 25, '2024-01-26 14:45:00'),
    (13, 1, 8, '2024-01-27 09:15:00'),
    (14, 1, 22, '2024-01-28 16:30:00'),
    (15, 1, 31, '2024-01-29 11:50:00');

-- Insert sample messages
INSERT
    OR IGNORE INTO messages (
        id,
        sender,
        receiver,
        message_text,
        timestamp,
        read_timestamp
    )
VALUES (
        1,
        'john_doe',
        'jane_smith',
        'Hi Jane! I am interested in your logo design service. Could you provide more details about your process?',
        1705507200, -- 2024-01-17 12:00:00
        1705510800  -- 2024-01-17 13:00:00 (read)
    ),
    (
        2,
        'jane_smith',
        'john_doe',
        'Hi John! Thank you for your interest. I typically start with a consultation to understand your brand vision, then provide 3 initial concepts. The whole process takes about 5-7 days.',
        1705511400, -- 2024-01-17 13:10:00
        1705514400  -- 2024-01-17 14:00:00 (read)
    ),
    (
        3,
        'john_doe',
        'jane_smith',
        'That sounds perfect! What information do you need from me to get started?',
        1705515000, -- 2024-01-17 14:10:00
        1705518000  -- 2024-01-17 15:00:00 (read)
    ),
    (
        4,
        'pedroafmonteiro',
        'alice_johnson',
        'Hello Alice! I saw your illustration work and I am very impressed. Would you be available for a book cover project?',
        1705593600, -- 2024-01-18 12:00:00
        1705597200  -- 2024-01-18 13:00:00 (read)
    ),
    (
        5,
        'alice_johnson',
        'pedroafmonteiro',
        'Hi Pedro! Thank you so much! Yes, I would love to work on a book cover. Could you tell me more about the book and your vision for the cover?',
        1705598400, -- 2024-01-18 13:20:00
        1705601400  -- 2024-01-18 14:10:00 (read)
    ),
    (
        6,
        'pedrolunet',
        'bob_brown',
        'Hi Bob! I need help with backend development for an e-commerce platform. Are you experienced with payment integrations?',
        1705680000, -- 2024-01-19 12:00:00
        1705683600  -- 2024-01-19 13:00:00 (read)
    ),
    (
        7,
        'bob_brown',
        'pedrolunet',
        'Hello Pedro! Yes, I have extensive experience with payment integrations including Stripe, PayPal, and other providers. I would be happy to help with your e-commerce project.',
        1705684800, -- 2024-01-19 13:20:00
        1705687800  -- 2024-01-19 14:10:00 (read)
    ),
    (
        8,
        'jlopes_15',
        'charlie_davis',
        'Hi Charlie! I have some raw footage from an event that needs professional editing. Can you handle corporate video editing?',
        1705766400, -- 2024-01-20 12:00:00
        1705770000  -- 2024-01-20 13:00:00 (read)
    ),
    (
        9,
        'charlie_davis',
        'jlopes_15',
        'Hi João! Absolutely! Corporate video editing is one of my specialties. I can help you create a polished, professional video. How long is the raw footage?',
        1705771200, -- 2024-01-20 13:20:00
        1705774200  -- 2024-01-20 14:10:00 (read)
    ),
    (
        10,
        'john_doe',
        'diana_evans',
        'Hello Diana! I am planning a family photoshoot next month. Do you do outdoor sessions?',
        1705852800, -- 2024-01-21 12:00:00
        0           -- unread
    ),
    (
        11,
        'pedroafmonteiro',
        'ethan_foster',
        'Hi Ethan! I am looking to develop a mobile app for my business. Could we schedule a call to discuss the requirements?',
        1705939200, -- 2024-01-22 12:00:00
        1705942800  -- 2024-01-22 13:00:00 (read)
    ),
    (
        12,
        'ethan_foster',
        'pedroafmonteiro',
        'Hi Pedro! I would be happy to discuss your mobile app project. I am available for a call this week. What type of business app are you looking to develop?',
        1705944000, -- 2024-01-22 13:20:00
        0           -- unread
    ),
    (
        13,
        'pedrolunet',
        'fiona_green',
        'Hello Fiona! I need help with content marketing strategy for a tech startup. Do you have experience in the tech industry?',
        1706025600, -- 2024-01-23 12:00:00
        1706029200  -- 2024-01-23 13:00:00 (read)
    ),
    (
        14,
        'fiona_green',
        'pedrolunet',
        'Hi Pedro! Yes, I have worked with several tech startups on their content marketing strategies. I can help you develop a comprehensive plan to reach your target audience.',
        1706030400, -- 2024-01-23 13:20:00
        1706033400  -- 2024-01-23 14:10:00 (read)
    ),
    (
        15,
        'jlopes_15',
        'george_harris',
        'Hi George! Our website needs SEO optimization. Can you do a complete audit and provide improvement recommendations?',
        1706112000, -- 2024-01-24 12:00:00
        1706115600  -- 2024-01-24 13:00:00 (read)
    ),
    (
        16,
        'george_harris',
        'jlopes_15',
        'Hello João! Yes, I can definitely help with a comprehensive SEO audit. I will analyze your current performance and provide actionable recommendations to improve your rankings.',
        1706116800, -- 2024-01-24 13:20:00
        0           -- unread
    );