CREATE TABLE IF NOT EXISTS "migrations"(
  "id" integer primary key autoincrement not null,
  "migration" varchar not null,
  "batch" integer not null
);
CREATE TABLE IF NOT EXISTS "users"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "email" varchar not null,
  "email_verified_at" datetime,
  "password" varchar not null,
  "remember_token" varchar,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "users_email_unique" on "users"("email");
CREATE TABLE IF NOT EXISTS "password_reset_tokens"(
  "email" varchar not null,
  "token" varchar not null,
  "created_at" datetime,
  primary key("email")
);
CREATE TABLE IF NOT EXISTS "sessions"(
  "id" varchar not null,
  "user_id" integer,
  "ip_address" varchar,
  "user_agent" text,
  "payload" text not null,
  "last_activity" integer not null,
  primary key("id")
);
CREATE INDEX "sessions_user_id_index" on "sessions"("user_id");
CREATE INDEX "sessions_last_activity_index" on "sessions"("last_activity");
CREATE TABLE IF NOT EXISTS "cache"(
  "key" varchar not null,
  "value" text not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE INDEX "cache_expiration_index" on "cache"("expiration");
CREATE TABLE IF NOT EXISTS "cache_locks"(
  "key" varchar not null,
  "owner" varchar not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE INDEX "cache_locks_expiration_index" on "cache_locks"("expiration");
CREATE TABLE IF NOT EXISTS "jobs"(
  "id" integer primary key autoincrement not null,
  "queue" varchar not null,
  "payload" text not null,
  "attempts" integer not null,
  "reserved_at" integer,
  "available_at" integer not null,
  "created_at" integer not null
);
CREATE INDEX "jobs_queue_index" on "jobs"("queue");
CREATE TABLE IF NOT EXISTS "job_batches"(
  "id" varchar not null,
  "name" varchar not null,
  "total_jobs" integer not null,
  "pending_jobs" integer not null,
  "failed_jobs" integer not null,
  "failed_job_ids" text not null,
  "options" text,
  "cancelled_at" integer,
  "created_at" integer not null,
  "finished_at" integer,
  primary key("id")
);
CREATE TABLE IF NOT EXISTS "failed_jobs"(
  "id" integer primary key autoincrement not null,
  "uuid" varchar not null,
  "connection" text not null,
  "queue" text not null,
  "payload" text not null,
  "exception" text not null,
  "failed_at" datetime not null default CURRENT_TIMESTAMP
);
CREATE UNIQUE INDEX "failed_jobs_uuid_unique" on "failed_jobs"("uuid");
CREATE TABLE IF NOT EXISTS "prefectures"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "service_methods"(
  "id" integer primary key autoincrement not null,
  "key" varchar not null,
  "label" varchar not null,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "service_methods_key_unique" on "service_methods"("key");
CREATE TABLE IF NOT EXISTS "building_types"(
  "id" integer primary key autoincrement not null,
  "key" varchar not null,
  "label" varchar not null,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "building_types_key_unique" on "building_types"("key");
CREATE TABLE IF NOT EXISTS "service_categories"(
  "id" integer primary key autoincrement not null,
  "key" varchar not null,
  "label" varchar not null,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "service_categories_key_unique" on "service_categories"(
  "key"
);
CREATE TABLE IF NOT EXISTS "companies"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "slug" varchar not null,
  "description" text,
  "email_quote" varchar not null,
  "phone" varchar,
  "address_text" text,
  "max_floor" integer,
  "emergency_supported" tinyint(1) not null default '0',
  "insurance" tinyint(1) not null default '0',
  "price_note" text,
  "rank_score" integer not null default '0',
  "published_at" datetime,
  "created_at" datetime,
  "updated_at" datetime,
  "website_url" varchar,
  "service_areas" text,
  "rope_support" tinyint(1) not null default '0',
  "security_points" text,
  "performance_summary" varchar,
  "strength_tags" text,
  "recommend_score" integer not null default '0',
  "safety_score" integer not null default '0',
  "performance_score" integer not null default '0',
  "review_score" numeric not null default '0',
  "review_count" integer not null default '0',
  "gondola_supported" tinyint(1) not null default '0',
  "official_url" varchar,
  "areas" text,
  "achievements_summary" varchar,
  "safety_items" text,
  "is_featured" tinyint(1) not null default '0',
  "sort_order" integer not null default '0'
);
CREATE UNIQUE INDEX "companies_slug_unique" on "companies"("slug");
CREATE TABLE IF NOT EXISTS "company_prefecture"(
  "company_id" integer not null,
  "prefecture_id" integer not null,
  foreign key("company_id") references "companies"("id") on delete cascade,
  foreign key("prefecture_id") references "prefectures"("id") on delete cascade,
  primary key("company_id", "prefecture_id")
);
CREATE TABLE IF NOT EXISTS "company_service_method"(
  "company_id" integer not null,
  "service_method_id" integer not null,
  foreign key("company_id") references "companies"("id") on delete cascade,
  foreign key("service_method_id") references "service_methods"("id") on delete cascade,
  primary key("company_id", "service_method_id")
);
CREATE TABLE IF NOT EXISTS "building_type_company"(
  "building_type_id" integer not null,
  "company_id" integer not null,
  foreign key("building_type_id") references "building_types"("id") on delete cascade,
  foreign key("company_id") references "companies"("id") on delete cascade,
  primary key("building_type_id", "company_id")
);
CREATE TABLE IF NOT EXISTS "company_service_category"(
  "company_id" integer not null,
  "service_category_id" integer not null,
  foreign key("company_id") references "companies"("id") on delete cascade,
  foreign key("service_category_id") references "service_categories"("id") on delete cascade,
  primary key("company_id", "service_category_id")
);
CREATE TABLE IF NOT EXISTS "company_assets"(
  "id" integer primary key autoincrement not null,
  "company_id" integer not null,
  "kind" varchar check("kind" in('logo', 'gallery')) not null,
  "path" varchar not null,
  "caption" varchar,
  "sort_order" integer not null default '0',
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("company_id") references "companies"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "quote_requests"(
  "id" integer primary key autoincrement not null,
  "public_id" varchar not null,
  "type" varchar check("type" in('bulk', 'single')) not null default 'bulk',
  "client_kind" varchar check("client_kind" in('corp', 'personal')) not null,
  "company_name" varchar,
  "name" varchar not null,
  "email" varchar not null,
  "phone" varchar,
  "prefecture_id" integer not null,
  "city_text" varchar,
  "building_type_id" integer not null,
  "floors" integer not null,
  "glass_area_type" varchar check("glass_area_type" in('small', 'medium', 'large')) not null,
  "service_category_id" integer not null,
  "preferred_service_method_id" integer,
  "preferred_timing" varchar check("preferred_timing" in('urgent', 'this_week', 'this_month', 'undecided')) not null,
  "note" text,
  "attachments" text,
  "status" varchar check("status" in('new', 'sent', 'done', 'invalid')) not null default 'new',
  "utm_source" varchar,
  "utm_medium" varchar,
  "utm_campaign" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("prefecture_id") references "prefectures"("id"),
  foreign key("building_type_id") references "building_types"("id"),
  foreign key("service_category_id") references "service_categories"("id"),
  foreign key("preferred_service_method_id") references "service_methods"("id")
);
CREATE UNIQUE INDEX "quote_requests_public_id_unique" on "quote_requests"(
  "public_id"
);
CREATE TABLE IF NOT EXISTS "quote_recipients"(
  "id" integer primary key autoincrement not null,
  "quote_request_id" integer not null,
  "company_id" integer not null,
  "delivery_status" varchar check("delivery_status" in('queued', 'sent', 'failed')) not null default 'queued',
  "sent_at" datetime,
  "error_message" text,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("quote_request_id") references "quote_requests"("id") on delete cascade,
  foreign key("company_id") references "companies"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "site_settings"(
  "id" integer primary key autoincrement not null,
  "key" varchar not null,
  "value" text,
  "type" varchar not null default 'text',
  "label" varchar not null,
  "description" text,
  "created_at" datetime,
  "updated_at" datetime,
  "original_filename" varchar
);
CREATE UNIQUE INDEX "site_settings_key_unique" on "site_settings"("key");
CREATE TABLE IF NOT EXISTS "reviews"(
  "id" integer primary key autoincrement not null,
  "company_id" integer not null,
  "reviewer_name" varchar not null,
  "status" varchar check("status" in('published', 'pending', 'rejected')) not null default 'pending',
  "created_at" datetime,
  "updated_at" datetime,
  "company_name" varchar,
  "service_category" varchar,
  "building_type" varchar,
  "project_scale" varchar,
  "usage_period" varchar,
  "continue_request" varchar,
  "good_points" text not null,
  "improvement_points" text,
  "service_quality" integer,
  "staff_response" integer,
  "value_for_money" integer,
  "would_use_again" integer,
  "total_score" numeric,
  foreign key("company_id") references "companies"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "articles"(
  "id" integer primary key autoincrement not null,
  "title" varchar not null,
  "slug" varchar not null,
  "excerpt" text,
  "content" text not null,
  "featured_image" varchar,
  "is_published" tinyint(1) not null default '0',
  "published_at" datetime,
  "created_at" datetime,
  "updated_at" datetime,
  "is_featured" tinyint(1) not null default '0',
  "content_json" text,
  "content_html" text
);
CREATE UNIQUE INDEX "articles_slug_unique" on "articles"("slug");
CREATE INDEX "reviews_company_status_score_index" on "reviews"(
  "company_id",
  "status",
  "total_score"
);

INSERT INTO migrations VALUES(1,'0001_01_01_000000_create_users_table',1);
INSERT INTO migrations VALUES(2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO migrations VALUES(3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO migrations VALUES(4,'2026_02_20_012801_create_prefectures_table',1);
INSERT INTO migrations VALUES(5,'2026_02_20_012846_create_service_methods_table',1);
INSERT INTO migrations VALUES(6,'2026_02_20_012851_create_building_types_table',1);
INSERT INTO migrations VALUES(7,'2026_02_20_012853_create_service_categories_table',1);
INSERT INTO migrations VALUES(8,'2026_02_20_012855_create_companies_table',1);
INSERT INTO migrations VALUES(9,'2026_02_20_012903_create_company_prefecture_table',1);
INSERT INTO migrations VALUES(10,'2026_02_20_012905_create_company_service_method_table',1);
INSERT INTO migrations VALUES(11,'2026_02_20_012907_create_company_building_type_table',1);
INSERT INTO migrations VALUES(12,'2026_02_20_012909_create_company_service_category_table',1);
INSERT INTO migrations VALUES(13,'2026_02_20_012911_create_company_assets_table',1);
INSERT INTO migrations VALUES(14,'2026_02_20_012915_create_quote_requests_table',1);
INSERT INTO migrations VALUES(15,'2026_02_20_012917_create_quote_recipients_table',1);
INSERT INTO migrations VALUES(16,'2026_02_20_024719_create_site_settings_table',1);
INSERT INTO migrations VALUES(17,'2026_02_21_153008_add_company_card_fields_to_companies_table',1);
INSERT INTO migrations VALUES(18,'2026_02_23_024808_add_card_ui_fields_to_companies_table',1);
INSERT INTO migrations VALUES(20,'2026_02_23_024922_create_reviews_table',2);
INSERT INTO migrations VALUES(21,'2026_02_23_071216_add_original_filename_to_site_settings_table',3);
INSERT INTO migrations VALUES(22,'2026_02_23_091526_create_articles_table',4);
INSERT INTO migrations VALUES(23,'2026_02_24_032618_add_is_featured_to_articles_table',5);
INSERT INTO migrations VALUES(24,'2026_02_24_033440_add_content_json_html_to_articles_table',5);
INSERT INTO migrations VALUES(25,'2026_02_25_090106_add_review_fields_to_reviews_table',6);
INSERT INTO migrations VALUES(26,'2026_02_25_090950_update_review_fields_for_new_structure',7);
INSERT INTO migrations VALUES(27,'2026_02_25_093144_add_rating_columns_to_reviews_table',8);
