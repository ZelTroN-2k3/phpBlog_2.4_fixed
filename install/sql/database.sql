DROP TABLE IF EXISTS `albums`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `comments`;
DROP TABLE IF EXISTS `files`;
DROP TABLE IF EXISTS `gallery`;
DROP TABLE IF EXISTS `menu`;
DROP TABLE IF EXISTS `messages`;
DROP TABLE IF EXISTS `newsletter`;
DROP TABLE IF EXISTS `pages`;
DROP TABLE IF EXISTS `posts`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `widgets`;

-- --------------------------------------------------------

CREATE TABLE `albums` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `albums` (`id`, `title`) VALUES
(1, 'General');

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` (`id`, `category`, `slug`) VALUES
(1, 'Site News', 'site-news');

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `approved` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Yes',
  `guest` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `gallery` (
  `id` int(11) NOT NULL,
  `album_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(11) NOT NULL,
  `page` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fa_icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `menu` (`id`, `page`, `path`, `fa_icon`) VALUES
(1, 'Home', 'index', 'fa-home'),
(2, 'About', 'page?name=about', 'fa-info-circle'),
(3, 'Gallery', 'gallery', 'fa-images'),
(4, 'Posts', 'blog', 'fa-list'),
(5, 'Contact', 'contact', 'fa-envelope');

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `viewed` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `newsletter` (
  `id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `pages` (`id`, `title`, `slug`, `content`) VALUES
(1, 'About', 'about', '&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus faucibus auctor nisl vitae fermentum. Vivamus diam risus, hendrerit id lobortis sed, commodo ut tellus. Nulla ultricies magna a libero auctor, id tincidunt elit vulputate. Nullam ut dictum tellus. In ut consequat velit. Vivamus lorem dui, cursus in turpis eget, congue adipiscing risus. Nullam sit amet lorem sed nisl scelerisque facilisis vel vel tellus. Curabitur euismod justo nec sapien viverra, id consectetur justo tincidunt.&lt;br /&gt;\r\n&lt;br /&gt;\r\nPellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Ut ultrices ornare enim sed mollis. Sed porttitor nulla ac purus hendrerit ultrices. Nullam sed diam quis turpis varius suscipit ut vel massa. Nulla nisi arcu, viverra ac nisl at, vulputate ornare lectus. Pellentesque eget velit dui. Maecenas mollis congue sem, nec fringilla ligula cursus quis. Phasellus euismod elementum rutrum. Morbi elementum mi in arcu dapibus sagittis. Aliquam fringilla neque sed dui lacinia interdum. Duis a odio dui. Proin rutrum nulla nulla, sed aliquam neque commodo sed. Proin diam urna, volutpat vel felis et, volutpat iaculis nisl.&lt;br /&gt;\r\n&lt;br /&gt;\r\nAenean sagittis egestas volutpat. Sed facilisis sagittis tempus. Donec ante magna, faucibus eu urna eu, suscipit porttitor justo. Vivamus dictum justo vel lectus pretium, sit amet tempor dui tempus. Aliquam et risus quam. Vivamus mattis elit sit amet sem condimentum dignissim. Nullam purus ipsum, vehicula non fringilla et, faucibus varius nisl. Fusce nec rhoncus felis, id interdum risus. Vestibulum vitae dignissim diam. Donec bibendum enim lacus, et placerat urna lobortis non. Phasellus adipiscing molestie lectus, at mattis metus malesuada sit amet. Maecenas in est pretium, tincidunt nisl cursus, accumsan mi. Sed elementum, diam et suscipit adipiscing, quam odio tempor nisl, nec suscipit orci lectus id arcu. Suspendisse potenti. Phasellus id euismod erat. Nulla ligula justo, pharetra a bibendum non, sodales et ipsum.&lt;/p&gt;\r\n');

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `author_id` int(11) NOT NULL DEFAULT 1,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Yes',
  `featured` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'No',
  `views` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `widgets` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Sidebar'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `widgets` (`id`, `title`, `content`,  `position`) VALUES
(1, 'Text Widget', '&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam ornare sem tempor massa volutpat, quis varius urna placerat. Aliquam erat volutpat. Suspendisse lorem odio, imperdiet ut elit vitae, dignissim pretium odio. &lt;/p&gt;\r\n', 'Sidebar');

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'assets/img/avatar.png',
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'User'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- -------------------------------------------------------

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL DEFAULT 1,
  `site_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sitename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gcaptcha_sitekey` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gcaptcha_secretkey` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `head_customcode` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `facebook` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `instagram` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `twitter` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `youtube` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `linkedin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comments` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rtl` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_format` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `layout` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `latestposts_bar` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sidebar_position` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `posts_per_row` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `theme` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `background_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` (`id`, `site_url`, `sitename`, `description`, `email`, `gcaptcha_sitekey`, `gcaptcha_secretkey`, `head_customcode`, `facebook`, `instagram`, `twitter`, `youtube`, `linkedin`, `comments`, `rtl`, `date_format`, `layout`, `latestposts_bar`, `sidebar_position`, `posts_per_row`, `theme`, `background_image`) VALUES
(1, '', 'phpBlog', 'Content Management System', '', '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI', '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe', 'IDwhLS0gR29vZ2xlIEFuYWx5dGljcyA0IChHQTQpIFRyYWNraW5nIENvZGUgLS0+DQogPHNjcmlwdCBhc3luYyBzcmM9Imh0dHBzOi8vd3d3Lmdvb2dsZXRhZ21hbmFnZXIuY29tL2d0YWcvanM/aWQ9Ry1YWFhYWFhYWFhYIj48L3NjcmlwdD4NCiA8c2NyaXB0Pg0KICAgd2luZG93LmRhdGFMYXllciA9IHdpbmRvdy5kYXRhTGF5ZXIgfHwgW107DQogICBmdW5jdGlvbiBndGFnKCl7ZGF0YUxheWVyLnB1c2goYXJndW1lbnRzKTt9DQogICBndGFnKCdqcycsIG5ldyBEYXRlKCkpOw0KICAgZ3RhZygnY29uZmlnJywgJ0ctWFhYWFhYWFhYWCcpOw0KIDwvc2NyaXB0Pg0KPCEtLSBSZXN0IG9mIHlvdXIgaGVhZCBjb250ZW50IC0tPg==', '', '', '', '', '', 'guests', 'No', 'd.m.Y', 'Boxed', 'Enabled', 'Right', '3', 'Bootstrap 5', '');
-- ------------------------------------------------------

ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `albums`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `newsletter`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `widgets`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `albums`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `newsletter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `widgets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;