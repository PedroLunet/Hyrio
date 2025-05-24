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

-- Insert sample ratings
INSERT
    OR IGNORE INTO ratings (
        id,
        user_id,
        service_id,
        rating,
        review,
        created_at
    )
VALUES 
    -- Ratings for Logo Design (Service 1)
    (1, 2, 1, 5, 'Amazing logo design! Jane exceeded my expectations with creative concepts and quick turnaround.', '2024-01-10 14:30:00'),
    (2, 14, 1, 4, 'Great work on the logo. Very professional and responsive to feedback.', '2024-01-12 16:20:00'),
    (3, 15, 1, 5, 'Fantastic logo that perfectly captures our brand identity. Highly recommended!', '2024-01-15 11:45:00'),
    
    -- Ratings for Web Development (Service 2)
    (4, 2, 2, 5, 'Excellent web development service. The website is fast, responsive, and looks great!', '2024-01-08 09:15:00'),
    (5, 16, 2, 5, 'Outstanding work! The developer delivered exactly what we needed and more.', '2024-01-11 13:30:00'),
    (6, 1, 2, 4, 'Good quality work, though communication could have been better.', '2024-01-14 10:20:00'),
    
    -- Ratings for Content Writing (Service 3)
    (7, 14, 3, 4, 'Well-written content that engages our audience. Good value for money.', '2024-01-09 15:45:00'),
    (8, 15, 3, 4, 'Quality writing with good research. Delivered on time as promised.', '2024-01-13 12:10:00'),
    (9, 16, 3, 4, 'Professional content writing service. Happy with the results.', '2024-01-16 14:25:00'),
    
    -- Ratings for Custom Illustration (Service 4)
    (10, 2, 4, 5, 'Beautiful illustrations! Alice has incredible talent and attention to detail.', '2024-01-07 16:30:00'),
    (11, 15, 4, 5, 'Stunning artwork that perfectly matched our vision. Absolutely love it!', '2024-01-10 11:20:00'),
    (12, 1, 4, 4, 'Great illustrations, very creative and unique style.', '2024-01-18 13:45:00'),
    
    -- Ratings for Character Design (Service 5)
    (13, 16, 5, 5, 'Amazing character designs! Alice brought our characters to life perfectly.', '2024-01-06 10:15:00'),
    (14, 14, 5, 5, 'Incredible creativity and skill. The character designs exceeded expectations.', '2024-01-12 15:30:00'),
    (15, 2, 5, 5, 'Absolutely fantastic work! Highly recommend for any character design needs.', '2024-01-17 12:40:00'),
    
    -- Ratings for Book Cover Design (Service 6)
    (16, 15, 6, 5, 'Perfect book cover design! Captures the essence of the story beautifully.', '2024-01-08 14:20:00'),
    (17, 1, 6, 4, 'Good design work, professional and eye-catching.', '2024-01-11 16:35:00'),
    (18, 16, 6, 5, 'Outstanding book cover that really stands out. Great work!', '2024-01-19 11:10:00'),
    
    -- Ratings for API Development (Service 7)
    (19, 14, 7, 5, 'Excellent API development! Clean code, well-documented, and reliable.', '2024-01-05 13:25:00'),
    (20, 2, 7, 5, 'Top-notch API development service. Bob knows his stuff!', '2024-01-09 10:45:00'),
    (21, 15, 7, 4, 'Good API development with solid architecture and performance.', '2024-01-14 15:20:00'),
    
    -- Ratings for Database Optimization (Service 8)
    (22, 1, 8, 5, 'Incredible improvement in database performance! Worth every penny.', '2024-01-06 12:30:00'),
    (23, 16, 8, 4, 'Good optimization work. Database queries are much faster now.', '2024-01-13 14:15:00'),
    (24, 14, 8, 5, 'Excellent database optimization. Professional and thorough analysis.', '2024-01-16 16:40:00'),
    
    -- Ratings for Backend Architecture (Service 9)
    (25, 15, 9, 5, 'Brilliant backend architecture! Scalable and well-designed system.', '2024-01-04 11:20:00'),
    (26, 2, 9, 5, 'Outstanding work on backend architecture. Highly recommended!', '2024-01-10 13:50:00'),
    (27, 1, 9, 5, 'Exceptional backend development. Clean, efficient, and robust.', '2024-01-15 10:25:00'),
    
    -- Ratings for Video Editing (Service 10)
    (28, 16, 10, 5, 'Amazing video editing! Charlie transformed our raw footage into something spectacular.', '2024-01-07 15:45:00'),
    (29, 14, 10, 4, 'Good video editing with professional quality. Happy with the result.', '2024-01-12 12:20:00'),
    (30, 2, 10, 5, 'Excellent video editing service. Great attention to detail!', '2024-01-18 14:30:00'),
    
    -- Ratings for Motion Graphics (Service 11)
    (31, 15, 11, 5, 'Stunning motion graphics! Really brought our project to life.', '2024-01-06 16:15:00'),
    (32, 1, 11, 5, 'Incredible motion graphics work. Very creative and professional.', '2024-01-11 11:40:00'),
    (33, 16, 11, 5, 'Outstanding motion graphics! Exceeded all expectations.', '2024-01-17 13:25:00'),
    
    -- Ratings for Color Grading (Service 12)
    (34, 14, 12, 4, 'Good color grading work. Enhanced the visual appeal of our video.', '2024-01-08 10:30:00'),
    (35, 2, 12, 5, 'Excellent color grading! The video looks cinematic now.', '2024-01-13 15:45:00'),
    (36, 15, 12, 4, 'Professional color grading service. Good improvement in video quality.', '2024-01-19 12:10:00'),
    
    -- Ratings for Portrait Photography (Service 13)
    (37, 1, 13, 5, 'Beautiful portrait photography! Diana captured perfect shots.', '2024-01-05 14:20:00'),
    (38, 16, 13, 5, 'Amazing portrait session! Professional and friendly photographer.', '2024-01-10 16:35:00'),
    (39, 14, 13, 4, 'Good portrait photography with great lighting and composition.', '2024-01-16 11:50:00'),
    
    -- Ratings for Event Photography (Service 14)
    (40, 15, 14, 5, 'Fantastic event photography! Captured all the important moments perfectly.', '2024-01-04 13:15:00'),
    (41, 2, 14, 5, 'Outstanding event photographer! Professional and unobtrusive.', '2024-01-09 12:40:00'),
    (42, 1, 14, 5, 'Excellent event photography service. Highly recommend Diana!', '2024-01-14 15:25:00'),
    
    -- Ratings for Product Photography (Service 15)
    (43, 16, 15, 4, 'Good product photography. Clean shots that showcase our products well.', '2024-01-07 10:45:00'),
    (44, 14, 15, 5, 'Excellent product photography! Really makes our products shine.', '2024-01-12 14:30:00'),
    (45, 15, 15, 5, 'Amazing product photos! Great for our e-commerce site.', '2024-01-18 16:20:00'),
    
    -- Ratings for Mobile App Development (Service 16)
    (46, 2, 16, 5, 'Excellent mobile app development! Ethan delivered a fantastic app.', '2024-01-03 11:30:00'),
    (47, 1, 16, 4, 'Good mobile app development. App works well and looks professional.', '2024-01-08 13:45:00'),
    (48, 15, 16, 5, 'Outstanding mobile app! Exceeded our expectations in every way.', '2024-01-13 15:10:00'),
    
    -- Ratings for Cross-Platform Apps (Service 17)
    (49, 14, 17, 5, 'Amazing cross-platform development! Works perfectly on all devices.', '2024-01-06 12:15:00'),
    (50, 16, 17, 5, 'Excellent cross-platform app development. Very skilled developer!', '2024-01-11 14:40:00'),
    (51, 2, 17, 5, 'Outstanding work on cross-platform development. Highly recommended!', '2024-01-17 10:55:00'),
    
    -- Ratings for App Maintenance (Service 18)
    (52, 15, 18, 4, 'Good app maintenance service. Keeps our app running smoothly.', '2024-01-09 16:30:00'),
    (53, 1, 18, 5, 'Excellent app maintenance! Quick response to issues and updates.', '2024-01-14 12:20:00'),
    (54, 14, 18, 4, 'Reliable app maintenance service. Professional and thorough.', '2024-01-19 13:45:00'),
    
    -- Ratings for Content Strategy (Service 19)
    (55, 16, 19, 5, 'Brilliant content strategy! Fiona really understands our audience.', '2024-01-05 15:25:00'),
    (56, 2, 19, 4, 'Good content strategy development. Clear and actionable plan.', '2024-01-10 11:40:00'),
    (57, 15, 19, 5, 'Excellent content strategy! Helped us focus our marketing efforts.', '2024-01-16 14:15:00'),
    
    -- Ratings for Blog Writing (Service 20)
    (58, 14, 20, 5, 'Amazing blog writing! Engaging content that our readers love.', '2024-01-04 13:50:00'),
    (59, 1, 20, 5, 'Excellent blog posts! Fiona has a great writing style.', '2024-01-12 16:25:00'),
    (60, 16, 20, 5, 'Outstanding blog writing service. Consistently high quality content.', '2024-01-18 11:35:00'),
    
    -- Ratings for Copywriting (Service 21)
    (61, 15, 21, 4, 'Good copywriting service. Compelling copy that converts well.', '2024-01-07 14:10:00'),
    (62, 2, 21, 5, 'Excellent copywriting! Really captures our brand voice perfectly.', '2024-01-13 12:45:00'),
    (63, 14, 21, 4, 'Professional copywriting service. Good results for our campaigns.', '2024-01-17 15:30:00'),
    
    -- Ratings for SEO Audit (Service 22)
    (64, 1, 22, 5, 'Comprehensive SEO audit! George identified many improvement opportunities.', '2024-01-06 10:20:00'),
    (65, 16, 22, 4, 'Good SEO audit with actionable recommendations. Seeing improvements already.', '2024-01-11 13:15:00'),
    (66, 15, 22, 5, 'Excellent SEO audit service! Very detailed and professional analysis.', '2024-01-15 16:40:00'),
    
    -- Ratings for Keyword Research (Service 23)
    (67, 14, 23, 5, 'Amazing keyword research! Found perfect keywords for our niche.', '2024-01-08 12:30:00'),
    (68, 2, 23, 5, 'Excellent keyword research service. Very thorough and insightful.', '2024-01-14 14:20:00'),
    (69, 1, 23, 5, 'Outstanding keyword research! Helped us rank for competitive terms.', '2024-01-19 11:25:00'),
    
    -- Ratings for Link Building (Service 24)
    (70, 15, 24, 4, 'Good link building service. Quality backlinks that improved our rankings.', '2024-01-05 15:45:00'),
    (71, 16, 24, 5, 'Excellent link building! High-quality links from relevant sites.', '2024-01-10 12:50:00'),
    (72, 14, 24, 4, 'Professional link building service. Ethical and effective approach.', '2024-01-16 13:35:00'),
    
    -- Ratings for Social Media Strategy (Service 25)
    (73, 2, 25, 5, 'Brilliant social media strategy! Hannah really knows her stuff.', '2024-01-04 16:15:00'),
    (74, 1, 25, 4, 'Good social media strategy development. Clear roadmap for growth.', '2024-01-09 14:30:00'),
    (75, 15, 25, 5, 'Excellent social media strategy! Increased our engagement significantly.', '2024-01-18 10:45:00'),
    
    -- Ratings for Content Scheduling (Service 26)
    (76, 16, 26, 5, 'Amazing content scheduling service! Keeps our social media active consistently.', '2024-01-07 11:20:00'),
    (77, 14, 26, 5, 'Excellent content scheduling! Saves us so much time and effort.', '2024-01-12 15:40:00'),
    (78, 2, 26, 5, 'Outstanding content scheduling service. Very organized and efficient.', '2024-01-17 12:55:00'),
    
    -- Ratings for Analytics Reporting (Service 27)
    (79, 15, 27, 4, 'Good analytics reporting. Clear insights into our social media performance.', '2024-01-06 13:25:00'),
    (80, 1, 27, 5, 'Excellent analytics reporting! Detailed insights that help us improve.', '2024-01-11 16:10:00'),
    (81, 16, 27, 4, 'Professional analytics reporting service. Good data visualization.', '2024-01-15 14:35:00'),
    
    -- Ratings for Game Prototyping (Service 28)
    (82, 14, 28, 5, 'Amazing game prototyping! Ian brought our game concept to life.', '2024-01-03 12:40:00'),
    (83, 2, 28, 4, 'Good game prototyping service. Solid foundation for our game development.', '2024-01-08 15:25:00'),
    (84, 15, 28, 5, 'Excellent game prototyping! Great understanding of game mechanics.', '2024-01-13 11:50:00'),
    
    -- Ratings for Level Design (Service 29)
    (85, 1, 29, 5, 'Incredible level design! Engaging and challenging levels that players love.', '2024-01-05 14:15:00'),
    (86, 16, 29, 5, 'Outstanding level design service! Very creative and well-balanced.', '2024-01-10 13:30:00'),
    (87, 14, 29, 5, 'Excellent level design! Ian has great game design instincts.', '2024-01-16 15:45:00'),
    
    -- Ratings for Game Testing (Service 30)
    (88, 15, 30, 4, 'Good game testing service. Found important bugs and provided useful feedback.', '2024-01-07 16:20:00'),
    (89, 2, 30, 5, 'Excellent game testing! Thorough testing that improved our game quality.', '2024-01-12 12:35:00'),
    (90, 1, 30, 4, 'Professional game testing service. Detailed bug reports and suggestions.', '2024-01-18 14:50:00'),
    
    -- Ratings for UX Research (Service 31)
    (91, 16, 31, 5, 'Amazing UX research! Julia provided valuable insights into user behavior.', '2024-01-04 11:45:00'),
    (92, 14, 31, 4, 'Good UX research service. Helpful data for improving our product.', '2024-01-09 13:20:00'),
    (93, 15, 31, 5, 'Excellent UX research! Professional methodology and clear recommendations.', '2024-01-14 16:35:00'),
    
    -- Ratings for Wireframing (Service 32)
    (94, 2, 32, 5, 'Excellent wireframing service! Clear and detailed wireframes for our app.', '2024-01-06 15:10:00'),
    (95, 1, 32, 5, 'Outstanding wireframing! Julia really understands user experience design.', '2024-01-11 12:25:00'),
    (96, 16, 32, 5, 'Amazing wireframing service! Perfect foundation for our UI development.', '2024-01-17 14:40:00'),
    
    -- Ratings for UI Design (Service 33)
    (97, 15, 33, 4, 'Good UI design service. Clean and modern interface design.', '2024-01-08 13:55:00'),
    (98, 14, 33, 5, 'Excellent UI design! Beautiful and intuitive interface.', '2024-01-13 16:15:00'),
    (99, 2, 33, 4, 'Professional UI design service. Good attention to detail and usability.', '2024-01-19 10:30:00');

-- Insert sample purchases (users must purchase before they can rate)
INSERT
    OR IGNORE INTO purchases (
        id,
        user_id,
        service_id,
        price,
        message,
        purchased_at
    )
VALUES 
    -- Purchases for Logo Design (Service 1) - Users: 2, 14, 15
    (1, 2, 1, 50.00, 'Need a professional logo for my startup', '2024-01-09 10:00:00'),
    (2, 14, 1, 50.00, 'Logo design for our new project', '2024-01-11 09:30:00'),
    (3, 15, 1, 50.00, 'Creating a brand identity package', '2024-01-14 08:45:00'),
    
    -- Purchases for Web Development (Service 2) - Users: 2, 16, 1
    (4, 2, 2, 200.00, 'Need a complete website for my business', '2024-01-07 11:20:00'),
    (5, 16, 2, 200.00, 'Portfolio website development', '2024-01-10 14:15:00'),
    (6, 1, 2, 200.00, 'Corporate website redesign', '2024-01-13 16:30:00'),
    
    -- Purchases for Content Writing (Service 3) - Users: 14, 15, 16
    (7, 14, 3, 30.00, 'Blog content for our website', '2024-01-08 13:45:00'),
    (8, 15, 3, 30.00, 'Product descriptions and web copy', '2024-01-12 10:30:00'),
    (9, 16, 3, 30.00, 'Marketing content creation', '2024-01-15 12:45:00'),
    
    -- Purchases for Custom Illustration (Service 4) - Users: 2, 15, 1
    (10, 2, 4, 100.00, 'Custom illustrations for our app', '2024-01-06 15:20:00'),
    (11, 15, 4, 100.00, 'Book illustrations project', '2024-01-09 11:30:00'),
    (12, 1, 4, 100.00, 'Marketing material illustrations', '2024-01-17 14:20:00'),
    
    -- Purchases for Character Design (Service 5) - Users: 16, 14, 2
    (13, 16, 5, 120.00, 'Character design for our game', '2024-01-05 09:45:00'),
    (14, 14, 5, 120.00, 'Mascot character for brand', '2024-01-11 13:20:00'),
    (15, 2, 5, 120.00, 'Animation character concepts', '2024-01-16 11:40:00'),
    
    -- Purchases for Book Cover Design (Service 6) - Users: 15, 1, 16
    (16, 15, 6, 80.00, 'Novel cover design', '2024-01-07 13:30:00'),
    (17, 1, 6, 80.00, 'Technical manual cover', '2024-01-10 16:20:00'),
    (18, 16, 6, 80.00, 'E-book cover design', '2024-01-18 10:45:00'),
    
    -- Purchases for API Development (Service 7) - Users: 14, 2, 15
    (19, 14, 7, 150.00, 'REST API for mobile app', '2024-01-04 12:15:00'),
    (20, 2, 7, 150.00, 'Payment processing API', '2024-01-08 10:30:00'),
    (21, 15, 7, 150.00, 'Data synchronization API', '2024-01-13 15:10:00'),
    
    -- Purchases for Database Optimization (Service 8) - Users: 1, 16, 14
    (22, 1, 8, 200.00, 'Performance tuning for production DB', '2024-01-05 11:45:00'),
    (23, 16, 8, 200.00, 'Query optimization project', '2024-01-12 14:30:00'),
    (24, 14, 8, 200.00, 'Database restructuring', '2024-01-15 16:20:00'),
    
    -- Purchases for Backend Architecture (Service 9) - Users: 15, 2, 1
    (25, 15, 9, 250.00, 'Scalable backend for e-commerce', '2024-01-03 10:30:00'),
    (26, 2, 9, 250.00, 'Microservices architecture', '2024-01-09 13:45:00'),
    (27, 1, 9, 250.00, 'System architecture review', '2024-01-14 09:25:00'),
    
    -- Purchases for Video Editing (Service 10) - Users: 16, 14, 2
    (28, 16, 10, 300.00, 'Corporate video editing', '2024-01-06 14:20:00'),
    (29, 14, 10, 300.00, 'Marketing video production', '2024-01-11 11:15:00'),
    (30, 2, 10, 300.00, 'Event footage editing', '2024-01-17 13:30:00'),
    
    -- Purchases for Motion Graphics (Service 11) - Users: 15, 1, 16
    (31, 15, 11, 350.00, 'Animated logo and graphics', '2024-01-05 15:40:00'),
    (32, 1, 11, 350.00, 'Presentation animations', '2024-01-10 12:25:00'),
    (33, 16, 11, 350.00, 'Marketing motion graphics', '2024-01-16 14:10:00'),
    
    -- Purchases for Color Grading (Service 12) - Users: 14, 2, 15
    (34, 14, 12, 200.00, 'Video color correction', '2024-01-07 09:45:00'),
    (35, 2, 12, 200.00, 'Cinematic color grading', '2024-01-12 15:20:00'),
    (36, 15, 12, 200.00, 'Documentary color enhancement', '2024-01-18 11:30:00'),
    
    -- Purchases for Portrait Photography (Service 13) - Users: 1, 16, 14
    (37, 1, 13, 150.00, 'Executive portrait session', '2024-01-04 13:45:00'),
    (38, 16, 13, 150.00, 'Professional headshots', '2024-01-09 16:20:00'),
    (39, 14, 13, 150.00, 'Team portrait photography', '2024-01-15 10:30:00'),
    
    -- Purchases for Event Photography (Service 14) - Users: 15, 2, 1
    (40, 15, 14, 300.00, 'Conference photography', '2024-01-03 12:30:00'),
    (41, 2, 14, 300.00, 'Wedding photography', '2024-01-08 11:45:00'),
    (42, 1, 14, 300.00, 'Corporate event coverage', '2024-01-13 14:20:00'),
    
    -- Purchases for Product Photography (Service 15) - Users: 16, 14, 15
    (43, 16, 15, 250.00, 'E-commerce product shots', '2024-01-06 10:15:00'),
    (44, 14, 15, 250.00, 'Catalog photography', '2024-01-11 13:40:00'),
    (45, 15, 15, 250.00, 'Product lifestyle photos', '2024-01-17 15:50:00'),
    
    -- Purchases for Mobile App Development (Service 16) - Users: 2, 1, 15
    (46, 2, 16, 500.00, 'iOS and Android app', '2024-01-02 11:30:00'),
    (47, 1, 16, 500.00, 'Business productivity app', '2024-01-07 14:20:00'),
    (48, 15, 16, 500.00, 'Customer service app', '2024-01-12 16:45:00'),
    
    -- Purchases for Cross-Platform Apps (Service 17) - Users: 14, 16, 2
    (49, 14, 17, 600.00, 'React Native application', '2024-01-05 12:45:00'),
    (50, 16, 17, 600.00, 'Flutter app development', '2024-01-10 15:30:00'),
    (51, 2, 17, 600.00, 'Xamarin cross-platform app', '2024-01-16 10:20:00'),
    
    -- Purchases for App Maintenance (Service 18) - Users: 15, 1, 14
    (52, 15, 18, 300.00, 'Monthly app maintenance', '2024-01-08 16:15:00'),
    (53, 1, 18, 300.00, 'App update and bug fixes', '2024-01-13 11:40:00'),
    (54, 14, 18, 300.00, 'Performance optimization', '2024-01-18 13:20:00'),
    
    -- Purchases for Content Strategy (Service 19) - Users: 16, 2, 15
    (55, 16, 19, 200.00, 'Social media content plan', '2024-01-04 14:50:00'),
    (56, 2, 19, 200.00, 'Marketing content strategy', '2024-01-09 12:15:00'),
    (57, 15, 19, 200.00, 'Brand content roadmap', '2024-01-15 13:40:00'),
    
    -- Purchases for Blog Writing (Service 20) - Users: 14, 1, 16
    (58, 14, 20, 100.00, 'Weekly blog posts', '2024-01-03 13:25:00'),
    (59, 1, 20, 100.00, 'Technical blog content', '2024-01-11 15:50:00'),
    (60, 16, 20, 100.00, 'Industry blog articles', '2024-01-17 11:15:00'),
    
    -- Purchases for Copywriting (Service 21) - Users: 15, 2, 14
    (61, 15, 21, 150.00, 'Website copy and sales pages', '2024-01-06 13:35:00'),
    (62, 2, 21, 150.00, 'Email marketing copy', '2024-01-12 12:20:00'),
    (63, 14, 21, 150.00, 'Ad copy and marketing materials', '2024-01-16 14:45:00'),
    
    -- Purchases for SEO Audit (Service 22) - Users: 1, 16, 15
    (64, 1, 22, 300.00, 'Complete website SEO audit', '2024-01-05 09:50:00'),
    (65, 16, 22, 300.00, 'E-commerce SEO analysis', '2024-01-10 12:40:00'),
    (66, 15, 22, 300.00, 'Technical SEO review', '2024-01-14 16:15:00'),
    
    -- Purchases for Keyword Research (Service 23) - Users: 14, 2, 1
    (67, 14, 23, 150.00, 'Industry keyword analysis', '2024-01-07 12:10:00'),
    (68, 2, 23, 150.00, 'Long-tail keyword research', '2024-01-13 14:35:00'),
    (69, 1, 23, 150.00, 'Competitive keyword study', '2024-01-18 10:55:00'),
    
    -- Purchases for Link Building (Service 24) - Users: 15, 16, 14
    (70, 15, 24, 400.00, 'High-authority backlinks', '2024-01-04 15:20:00'),
    (71, 16, 24, 400.00, 'Niche-relevant link building', '2024-01-09 13:10:00'),
    (72, 14, 24, 400.00, 'Local SEO link building', '2024-01-15 12:25:00'),
    
    -- Purchases for Social Media Strategy (Service 25) - Users: 2, 1, 15
    (73, 2, 25, 200.00, 'Instagram marketing strategy', '2024-01-03 15:45:00'),
    (74, 1, 25, 200.00, 'LinkedIn business strategy', '2024-01-08 14:10:00'),
    (75, 15, 25, 200.00, 'Multi-platform social strategy', '2024-01-17 10:30:00'),
    
    -- Purchases for Content Scheduling (Service 26) - Users: 16, 14, 2
    (76, 16, 26, 150.00, 'Monthly content scheduling', '2024-01-06 11:45:00'),
    (77, 14, 26, 150.00, 'Social media automation', '2024-01-11 15:20:00'),
    (78, 2, 26, 150.00, 'Content calendar management', '2024-01-16 12:35:00'),
    
    -- Purchases for Analytics Reporting (Service 27) - Users: 15, 1, 16
    (79, 15, 27, 250.00, 'Social media analytics', '2024-01-05 13:15:00'),
    (80, 1, 27, 250.00, 'Marketing performance reports', '2024-01-10 16:40:00'),
    (81, 16, 27, 250.00, 'ROI analysis and reporting', '2024-01-14 14:25:00'),
    
    -- Purchases for Game Prototyping (Service 28) - Users: 14, 2, 15
    (82, 14, 28, 400.00, 'Mobile game prototype', '2024-01-02 12:20:00'),
    (83, 2, 28, 400.00, 'VR game concept development', '2024-01-07 15:35:00'),
    (84, 15, 28, 400.00, 'Educational game prototype', '2024-01-12 11:50:00'),
    
    -- Purchases for Level Design (Service 29) - Users: 1, 16, 14
    (85, 1, 29, 350.00, 'RPG level design', '2024-01-04 14:30:00'),
    (86, 16, 29, 350.00, 'Puzzle game levels', '2024-01-09 13:25:00'),
    (87, 14, 29, 350.00, 'Action game level design', '2024-01-15 15:40:00'),
    
    -- Purchases for Game Testing (Service 30) - Users: 15, 2, 1
    (88, 15, 30, 200.00, 'Beta testing and QA', '2024-01-06 16:10:00'),
    (89, 2, 30, 200.00, 'Gameplay testing session', '2024-01-11 12:30:00'),
    (90, 1, 30, 200.00, 'Bug testing and reporting', '2024-01-17 14:55:00'),
    
    -- Purchases for UX Research (Service 31) - Users: 16, 14, 15
    (91, 16, 31, 300.00, 'User behavior analysis', '2024-01-03 11:20:00'),
    (92, 14, 31, 300.00, 'Usability testing study', '2024-01-08 13:50:00'),
    (93, 15, 31, 300.00, 'User journey mapping', '2024-01-13 16:25:00'),
    
    -- Purchases for Wireframing (Service 32) - Users: 2, 1, 16
    (94, 2, 32, 200.00, 'Mobile app wireframes', '2024-01-05 15:30:00'),
    (95, 1, 32, 200.00, 'Website wireframe design', '2024-01-10 12:15:00'),
    (96, 16, 32, 200.00, 'Dashboard wireframing', '2024-01-16 14:20:00'),
    
    -- Purchases for UI Design (Service 33) - Users: 15, 14, 2
    (97, 15, 33, 400.00, 'Modern web interface', '2024-01-07 13:40:00'),
    (98, 14, 33, 400.00, 'Mobile app UI design', '2024-01-12 16:10:00'),
    (99, 2, 33, 400.00, 'Dashboard UI redesign', '2024-01-18 10:15:00');