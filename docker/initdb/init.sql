-- Init SQL: create a sample users table
CREATE TABLE IF NOT EXISTS public.users (
  id serial PRIMARY KEY,
  name text NOT NULL,
  email text UNIQUE NOT NULL
);
