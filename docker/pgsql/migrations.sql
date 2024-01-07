drop table if exists "order_details";
drop table if exists "addresses";
drop table if exists "payments";
drop table if exists "products";
drop table if exists "categories";
drop table if exists "orders";
drop table if exists "users";


create table "users" (
    "user_id" bigserial not null primary key,
    "username" varchar(255) not null,
    "email" varchar(255) not null unique,
    "registration_date" timestamp(0) without time zone not null default CURRENT_TIMESTAMP,
    "is_active" boolean not null default '1',
    "birth_date" date not null, "created_at" timestamp(0) without time zone null,
    "updated_at" timestamp(0) without time zone null
);

create table "orders" (
    "order_id" bigserial not null primary key,
    "user_id" integer not null,
    "order_date" timestamp(0) without time zone not null default CURRENT_TIMESTAMP,
    "status" varchar(255) check ("status" in ('pending', 'completed', 'cancelled')) not null,
    "total_amount" double precision not null default '0',
    "shipping_information" varchar(255) not null default '',
    "created_at" timestamp(0) without time zone null,
    "updated_at" timestamp(0) without time zone null,
    constraint "orders_user_id_foreign" foreign key ("user_id") references "users"("user_id")
        on delete set null on update cascade
);

create table "categories" (
    "category_id" bigserial not null primary key,
    "category_name" varchar(255) not null unique,
    "parent_category_id" integer not null,
    "image" varchar(255) null,
    constraint "categories_parent_category_id_foreign" foreign key ("parent_category_id") references "categories" ("category_id")
        on delete cascade on update cascade
);

create table "products" (
    "product_id" bigserial not null primary key,
    "category_id" integer not null,
    "product_name" varchar(255) not null,
    "price" decimal(10, 2) not null,
    "description" varchar(255) null,
    "stock_quantity" integer not null default '0',
    constraint "products_category_id_foreign" foreign key ("category_id") references "categories" ("category_id")
        on delete set null on update cascade
);

create table "payments" (
    "payment_id" bigserial not null primary key,
    "order_id" integer not null,
    "payment_date" timestamp(0) without time zone not null default CURRENT_TIMESTAMP,
    "amount" decimal(10, 2) not null,
    "payment_method" varchar(255) check ("payment_method" in ('credit_card', 'paypal', 'cash')) not null,
    "image" varchar(255) null
);

create table "addresses" (
    "address_id" bigserial not null primary key,
    "user_id" integer not null,
    "address" varchar(255) not null,
    "city" varchar(100) not null,
    "state" varchar(100) not null,
    "postal_code" varchar(10) not null,
    constraint "addresses_user_id_foreign" foreign key ("user_id") references "users" ("user_id")
        on delete set null on update cascade
);

create table "order_details" (
    "detail_id" bigserial not null primary key,
    "order_id" integer not null,
    "product_id" integer not null,
    "quantity" integer not null,
    "subtotal" decimal(10, 2) not null,
    constraint "order_details_order_id_foreign" foreign key ("order_id") references "orders" ("order_id")
        on delete set null on update cascade,
    constraint "order_details_product_id_foreign" foreign key ("product_id") references "products" ("product_id")
        on delete set null on update cascade
);

-- docker exec -it db-dbal-benchmarks sh
-- psql -U user -d dbal_benchmarks -f /var/lib/pgsql/migrations.sql
