drop table if exists "order_details";
drop table if exists "addresses";
drop table if exists "payments";
drop table if exists "products";
drop table if exists "categories";
drop table if exists "orders";
drop table if exists "users";

-- docker exec -it db-dbal-benchmarks sh
-- psql -U user -d dbal_benchmarks -f /var/lib/pgsql/drop-tables.sql
