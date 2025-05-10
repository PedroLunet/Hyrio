PRAGMA foreign_keys = ON;
-- Insert sample users
INSERT
    OR IGNORE INTO users (
        id,
        name,
        password,
        email,
        role,
        profile_pic,
        bio
    )
VALUES (
        1,
        'Admin',
        'admin',
        'admin@example.com',
        'admin',
        'database/assets/adminProfilePic.jpg',
        'Administrator of the platform'
    ),
    (
        2,
        'John Doe',
        'hashed_password2',
        'john@example.com',
        'user',
        'database/assets/userProfilePic.jpg',
        'Regular user'
    ),
    (
        3,
        'Jane Smith',
        'hashed_password3',
        'jane@example.com',
        'freelancer',
        'database/assets/freelancerProfilePic.jpg',
        'Freelancer specializing in design'
    ),
    (
        4,
        'Alice Johnson',
        'hashed_password4',
        'alice@example.com',
        'freelancer',
        'database/assets/freelancerProfilePic.jpg',
        'Freelancer specializing in illustration'
    ),
    (
        5,
        'Bob Brown',
        'hashed_password5',
        'bob@example.com',
        'freelancer',
        'database/assets/freelancerProfilePic.jpg',
        'Freelancer specializing in backend development'
    ),
    (
        6,
        'Charlie Davis',
        'hashed_password6',
        'charlie@example.com',
        'freelancer',
        'database/assets/freelancerProfilePic.jpg',
        'Freelancer specializing in video editing'
    ),
    (
        7,
        'Diana Evans',
        'hashed_password7',
        'diana@example.com',
        'freelancer',
        'database/assets/freelancerProfilePic.jpg',
        'Freelancer specializing in photography'
    ),
    (
        8,
        'Ethan Foster',
        'hashed_password8',
        'ethan@example.com',
        'freelancer',
        'database/assets/freelancerProfilePic.jpg',
        'Freelancer specializing in mobile app development'
    ),
    (
        9,
        'Fiona Green',
        'hashed_password9',
        'fiona@example.com',
        'freelancer',
        'database/assets/freelancerProfilePic.jpg',
        'Freelancer specializing in content marketing'
    ),
    (
        10,
        'George Harris',
        'hashed_password10',
        'george@example.com',
        'freelancer',
        'database/assets/freelancerProfilePic.jpg',
        'Freelancer specializing in SEO optimization'
    ),
    (
        11,
        'Hannah White',
        'hashed_password11',
        'hannah@example.com',
        'freelancer',
        'database/assets/freelancerProfilePic.jpg',
        'Freelancer specializing in social media management'
    ),
    (
        12,
        'Ian King',
        'hashed_password12',
        'ian@example.com',
        'freelancer',
        'database/assets/freelancerProfilePic.jpg',
        'Freelancer specializing in game development'
    ),
    (
        13,
        'Julia Lee',
        'hashed_password13',
        'julia@example.com',
        'freelancer',
        'database/assets/freelancerProfilePic.jpg',
        'Freelancer specializing in UX/UI design'
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