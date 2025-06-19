-- 1. Create Schema
CREATE SCHEMA IF NOT EXISTS bookmarker;

-- 2. Enable UUID Extension
CREATE EXTENSION IF NOT EXISTS "pgcrypto";

-- 3. Users
CREATE TABLE bookmarker.users (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    email TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    nickname TEXT UNIQUE NOT NULL,
    avatar_url TEXT,
    role TEXT NOT NULL DEFAULT 'user',
    created_at TIMESTAMP NOT NULL DEFAULT now(),
    updated_at TIMESTAMP NOT NULL DEFAULT now()
);

-- 4. Bookmarks
CREATE TABLE bookmarker.bookmarks (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID NOT NULL REFERENCES bookmarker.users(id) ON DELETE CASCADE,
    url TEXT NOT NULL,
    page_title TEXT NOT NULL,
    page_capture TEXT,
    favicon_url TEXT,
    screenshot_url TEXT,
    created_at TIMESTAMP NOT NULL DEFAULT now(),
    updated_at TIMESTAMP NOT NULL DEFAULT now()
);
CREATE TABLE bookmarker.bookmark (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id text NOT NULL REFERENCES bookmarker.user(id) ON DELETE CASCADE,
    url TEXT NOT NULL,
    page_title TEXT NOT NULL,
    page_capture TEXT,
    favicon_url TEXT,
    screenshot_url TEXT,
    created_at TIMESTAMP NOT NULL DEFAULT now(),
    updated_at TIMESTAMP NOT NULL DEFAULT now()
);
-- 5. Categories
CREATE TABLE bookmarker.categories (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID NOT NULL REFERENCES bookmarker.users(id) ON DELETE CASCADE,
    name TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT now(),
    UNIQUE(user_id, name) -- jeder User kann Kategorie-Namen einmalig vergeben
);

CREATE TABLE bookmarker.category (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id text NOT NULL REFERENCES bookmarker.user(id) ON DELETE CASCADE,
    name TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT now(),
    UNIQUE(user_id, name) -- jeder User kann Kategorie-Namen einmalig vergeben
);

-- 6. Bookmark-Categories (Relation)
CREATE TABLE bookmarker.bookmark_categories (
    bookmark_id UUID NOT NULL REFERENCES bookmarker.bookmarks(id) ON DELETE CASCADE,
    category_id UUID NOT NULL REFERENCES bookmarker.categories(id) ON DELETE CASCADE,
    sort_order INT DEFAULT 0,
    PRIMARY KEY (bookmark_id, category_id)
);
CREATE TABLE bookmarker.bookmark_category (
    bookmark_id UUID NOT NULL REFERENCES bookmarker.bookmark(id) ON DELETE CASCADE,
    category_id UUID NOT NULL REFERENCES bookmarker.category(id) ON DELETE CASCADE,
    sort_order INT DEFAULT 0,
    PRIMARY KEY (bookmark_id, category_id)
);

-- 7. Tags
CREATE TABLE bookmarker.tags (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID NOT NULL REFERENCES bookmarker.users(id) ON DELETE CASCADE,
    name TEXT NOT NULL,
    UNIQUE(user_id, name) -- Tag-Name pro User eindeutig
);
CREATE TABLE bookmarker.tag (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id text NOT NULL REFERENCES bookmarker.user(id) ON DELETE CASCADE,
    name TEXT NOT NULL,
    UNIQUE(user_id, name) -- Tag-Name pro User eindeutig
);

-- 8. Bookmark-Tags (Relation)
CREATE TABLE bookmarker.bookmark_tags (
    bookmark_id UUID NOT NULL REFERENCES bookmarker.bookmarks(id) ON DELETE CASCADE,
    tag_id UUID NOT NULL REFERENCES bookmarker.tags(id) ON DELETE CASCADE,
    PRIMARY KEY (bookmark_id, tag_id)
);
CREATE TABLE bookmarker.bookmark_tag (
    bookmark_id UUID NOT NULL REFERENCES bookmarker.bookmark(id) ON DELETE CASCADE,
    tag_id UUID NOT NULL REFERENCES bookmarker.tag(id) ON DELETE CASCADE,
    PRIMARY KEY (bookmark_id, tag_id)
);
-- 9. Lists
CREATE TABLE bookmarker.lists (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID NOT NULL REFERENCES bookmarker.users(id) ON DELETE CASCADE,
    name TEXT NOT NULL,
    is_public BOOLEAN NOT NULL DEFAULT false,
    share_token TEXT UNIQUE NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT now(),
    updated_at TIMESTAMP NOT NULL DEFAULT now()
);
CREATE TABLE bookmarker.list (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id text NOT NULL REFERENCES bookmarker.user(id) ON DELETE CASCADE,
    name TEXT NOT NULL,
    is_public BOOLEAN NOT NULL DEFAULT false,
    share_token TEXT UNIQUE NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT now(),
    updated_at TIMESTAMP NOT NULL DEFAULT now()
);
-- 10. List-Bookmarks (Relation)
CREATE TABLE bookmarker.list_bookmarks (
    list_id UUID NOT NULL REFERENCES bookmarker.lists(id) ON DELETE CASCADE,
    bookmark_id UUID NOT NULL REFERENCES bookmarker.bookmarks(id) ON DELETE CASCADE,
    sort_order INT DEFAULT 0,
    PRIMARY KEY (list_id, bookmark_id)
);
CREATE TABLE bookmarker.list_bookmark (
    list_id UUID NOT NULL REFERENCES bookmarker.list(id) ON DELETE CASCADE,
    bookmark_id UUID NOT NULL REFERENCES bookmarker.bookmark(id) ON DELETE CASCADE,
    sort_order INT DEFAULT 0,
    PRIMARY KEY (list_id, bookmark_id)
);
-- 11. List-Likes
CREATE TABLE bookmarker.list_likes (
    list_id UUID NOT NULL REFERENCES bookmarker.lists(id) ON DELETE CASCADE,
    user_id UUID NOT NULL REFERENCES bookmarker.users(id) ON DELETE CASCADE,
    created_at TIMESTAMP NOT NULL DEFAULT now(),
    PRIMARY KEY (list_id, user_id)
);
CREATE TABLE bookmarker.list_like (
    list_id UUID NOT NULL REFERENCES bookmarker.list(id) ON DELETE CASCADE,
    user_id text NOT NULL REFERENCES bookmarker.user(id) ON DELETE CASCADE,
    created_at TIMESTAMP NOT NULL DEFAULT now(),
    PRIMARY KEY (list_id, user_id)
);

CREATE TABLE bookmarker.user_setting (
	id text PRIMARY KEY DEFAULT gen_random_uuid(),
	user_id text NOT NULL REFERENCES bookmarker.user(id) ON DELETE CASCADE,
	theme text NOT NULL DEFAULT 'default',
	show_date boolean NOT NULL DEFAULT false,
	show_description boolean NOT NULL DEFAULT true,
	show_favicon boolean NOT NULL DEFAULT true,
	show_lists boolean NOT NULL DEFAULT true,
	show_tags boolean NOT NULL DEFAULT false
);

-- index for user settings


-- 12. Indexes für Performance (optional aber empfohlen)
CREATE INDEX idx_bookmarker_users_email ON bookmarker.users(email);
CREATE INDEX idx_bookmarker_users_nickname ON bookmarker.users(nickname);
CREATE INDEX idx_bookmarker_bookmarks_user_id ON bookmarker.bookmarks(user_id);
CREATE INDEX idx_bookmarker_categories_user_id ON bookmarker.categories(user_id);
CREATE INDEX idx_bookmarker_tags_user_id ON bookmarker.tags(user_id);
CREATE INDEX idx_bookmarker_lists_user_id ON bookmarker.lists(user_id);
CREATE INDEX idx_bookmarker_lists_share_token ON bookmarker.lists(share_token);

CREATE INDEX idx_bookmarker_user_email ON bookmarker.user(email);
CREATE INDEX idx_bookmarker_user_nickname ON bookmarker.user(nickname);
CREATE INDEX idx_bookmarker_bookmark_user_id ON bookmarker.bookmark(user_id);
CREATE INDEX idx_bookmarker_category_user_id ON bookmarker.category(user_id);
CREATE INDEX idx_bookmarker_tag_user_id ON bookmarker.tag(user_id);
CREATE INDEX idx_bookmarker_list_user_id ON bookmarker.list(user_id);
CREATE INDEX idx_bookmarker_list_share_token ON bookmarker.list(share_token);
CREATE INDEX idx_bookmarker_user_setting_user_id ON bookmarker.user_setting(user_id);