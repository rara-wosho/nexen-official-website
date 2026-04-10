-- Adminer 5.2.1 PostgreSQL 17.2 dump
-- PostregSQL

DROP TABLE IF EXISTS "admin";
DROP SEQUENCE IF EXISTS admin_id_seq;
CREATE SEQUENCE admin_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 CACHE 1;

CREATE TABLE "public"."admin" (
    "id" integer DEFAULT nextval('admin_id_seq') NOT NULL,
    "username" character varying(255),
    "password" character varying(255),
    CONSTRAINT "admin_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

INSERT INTO "admin" ("id", "username", "password") VALUES
(1,	'admin',	'admin123'),
(3,	'admin1',	'$2y$10$apWNgC/V5mtTUvc4Nw6aeuxMUVkntnxloDl5S9huFaCY5s1MvJBLK');

DROP TABLE IF EXISTS "content";
DROP SEQUENCE IF EXISTS content_id_seq;
CREATE SEQUENCE content_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 CACHE 1;

CREATE TABLE "public"."content" (
    "id" integer DEFAULT nextval('content_id_seq') NOT NULL,
    "section_name" character varying(500),
    "content_key" character varying(500),
    "content" character varying(500),
    CONSTRAINT "content_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

INSERT INTO "content" ("id", "section_name", "content_key", "content") VALUES
(43,	'blog',	'title2',	'14th Anniversary'),
(60,	'blog',	'date2',	'May 17, 2024'),
(61,	'contact',	'title',	'Address'),
(62,	'contact',	'title1',	'Call Us'),
(63,	'contact',	'title2',	'Email Us'),
(64,	'contact',	'description',	'Nexen Bldg., 68 McKinley St., National Highway, Purok-1, Talic, Oroquieta City, Misamis Occidental, Philppines, 7207'),
(65,	'contact',	'description1',	'0997-609-3356'),
(66,	'contact',	'description2',	'info@nexen.com.ph'),
(4,	'about',	'title',	'Innovating for a Smarter Future'),
(5,	'about',	'description',	'At Nexen Innovation Technologies Inc., we are dedicated to providing cutting-edge IT solutions that streamline human resource transactions and enterprise operations.'),
(6,	'about',	'list',	'Expert IT services tailored to enhance business efficiency.'),
(7,	'about',	'list1',	'Innovative software solutions for seamless HR management.'),
(8,	'about',	'list2',	'Reliable and secure automation to drive digital transformation.'),
(9,	'about',	'footer',	'Established in 2010, our mission is to empower businesses with technology that simplifies processes, improves productivity, and ensures long-term success.'),
(10,	'about',	'video',	'assets/video/nexen2.mp4'),
(57,	'blog',	'date',	'January 31, 2024'),
(58,	'blog',	'date1',	'April 24, 2024'),
(59,	'blog',	'date2',	'May 17, 2024'),
(38,	'blog',	'img',	'assets/img/nscc.jpg'),
(39,	'blog',	'img1',	'assets/img/tam-an.jpg'),
(40,	'blog',	'img2',	'assets/img/anniversary.jpg'),
(42,	'blog',	'title1',	'Tam-an BMPC Partnership'),
(41,	'blog',	'title',	'NSCC Cooperative Partnership'),
(44,	'blog',	'description',	'Newly closed deal HRMAX partner NSCC Cooperative...'),
(45,	'blog',	'description1',	'Tam-an BMPC has signed a strategic partnership with Nexen Innovation Technologies, Inc...'),
(46,	'blog',	'description2',	'A week long celebration of NEXEN 14th years of continuous success... '),
(12,	'why-us',	'card_title1',	'Our Vision'),
(13,	'why-us',	'card_description',	'Bring technological systems innovation to every partner institution to help them reach their full potential.'),
(14,	'why-us',	'card_description1',	'To become the leading provider of innovative business engine solutions.'),
(17,	'why-us',	'card_img1',	'assets/img/nexen2.jpg'),
(19,	'services',	'title',	'Software Development'),
(20,	'services',	'description',	'Custom-built software solutions tailored to meet business needs, from enterprise applications to web platforms and desktop software.'),
(22,	'services',	'title1',	'Payroll Outsourcing'),
(23,	'services',	'description1',	'Efficient and secure payroll processing services to streamline salary computations and compliance.'),
(24,	'services',	'title2',	'Website Development'),
(25,	'services',	'description2',	'Professional website design and development to enhance online presence and user engagement.'),
(26,	'services',	'title3',	'Technical Support Outsourcing'),
(27,	'services',	'description3',	'Reliable IT support services to assist customers with troubleshooting and system maintenance.'),
(28,	'services',	'title4',	'Security and Network Services'),
(29,	'services',	'description4',	'Robust cybersecurity and networking solutions to protect businesses from threats and ensure seamless connectivity.'),
(30,	'services',	'title5',	'Solar Solutions'),
(31,	'services',	'description5',	'Sustainable and cost-effective solar energy systems for residential and commercial use.'),
(32,	'projects',	'img',	'assets/img/hrmax.jpg'),
(33,	'projects',	'img',	'assets/img/medipro.jpg'),
(34,	'projects',	'description',	'A comprehensive Human Capital Operations System'),
(35,	'projects',	'description1',	'A software solution tailored for medical professionals'),
(36,	'projects',	'title',	'HRMAX'),
(37,	'projects',	'title1',	'MEDIPRO'),
(2,	'hero',	'subtitle',	'Your Next Business Engine Solutions'),
(11,	'why-us',	'card_title',	'Our Mission'),
(1,	'hero',	'title',	'WELCOME TO NEXEN'),
(3,	'hero',	'background',	'assets/video/68087064b1914.mp4'),
(16,	'why-us',	'card_img',	'assets/img/68087205ad60a.jpg');

-- 2025-04-23 06:20:49 UTC
