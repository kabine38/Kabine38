CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100),
    role ENUM('admin','editor') DEFAULT 'editor',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE
);

INSERT INTO categories (name, slug) VALUES
('Top News','top-news'),
('Herren','herren'),
('Jugend','jugend');

CREATE TABLE news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    teaser TEXT,
    content LONGTEXT,
    author_id INT,
    is_premium TINYINT(1) DEFAULT 0,
    show_slider TINYINT(1) DEFAULT 0,
    is_pinned TINYINT(1) DEFAULT 0,
    status ENUM('draft','published') DEFAULT 'draft',
    published_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id)
);

CREATE TABLE news_categories (
    news_id INT,
    category_id INT,
    PRIMARY KEY(news_id, category_id),
    FOREIGN KEY(news_id) REFERENCES news(id) ON DELETE CASCADE,
    FOREIGN KEY(category_id) REFERENCES categories(id) ON DELETE CASCADE
);

CREATE TABLE news_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    news_id INT,
    image VARCHAR(255),
    sort_order INT DEFAULT 0,
    FOREIGN KEY(news_id) REFERENCES news(id) ON DELETE CASCADE
);