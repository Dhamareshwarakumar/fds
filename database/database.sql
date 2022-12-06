-- Active: 1668920595276@@127.0.0.1@3306@food_delivery_system

------------------------------------------------------------------------

------------------------- Food Delivery System -------------------------

------------------------------------------------------------------------

CREATE DATABASE food_delivery_system;

------------------------------------------------------------------------

-- users Table --

------------------------------------------------------------------------

DROP TABLE users;

CREATE TABLE
    users (
        user_id VARCHAR(36) NOT NULL DEFAULT (UUID()),
        username VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        role TINYINT NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT NOW(),
        updated_at TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW(),
        CONSTRAINT pk_users PRIMARY KEY (user_id)
    );

------------------------------------------------------------------------

-- Restaurants Table --

------------------------------------------------------------------------

DROP TABLE IF EXISTS restaurants;

CREATE TABLE
    restaurants (
        restaurant_id VARCHAR(36) NOT NULL,
        restaurant_name VARCHAR(255) NOT NULL,
        restaurant_lat FLOAT NOT NULL,
        restaurant_lng FLOAT NOT NULL,
        restaurant_city VARCHAR(255) NOT NULL,
        restaurant_contact VARCHAR(255) NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT NOW(),
        updated_at TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW(),
        CONSTRAINT pk_restaurants PRIMARY KEY (restaurant_id),
        CONSTRAINT uk_restaurant_lat_lng UNIQUE (
            restaurant_lat,
            restaurant_lng
        ),
        CONSTRAINT uk_restaurant_name_city UNIQUE (
            restaurant_name,
            restaurant_city
        ),
        CONSTRAINT uk_restaurant_contact UNIQUE (restaurant_contact),
        CONSTRAINT fk_restaurant_users FOREIGN KEY (restaurant_id) REFERENCES users (user_id)
    );

------------------------------------------------------------------------

-- Dishes Table --

------------------------------------------------------------------------

DROP TABLE IF EXISTS dishes;

CREATE TABLE
    dishes (
        dish_id VARCHAR(36) NOT NULL DEFAULT (UUID()),
        dish_name VARCHAR(255) NOT NULL,
        dish_price FLOAT NOT NULL,
        dish_description VARCHAR(255) NOT NULL,
        dish_image VARCHAR(255) NOT NULL,
        dish_type ENUM('veg', 'non-veg', 'mixed') NOT NULL,
        restaurant_id VARCHAR(36) NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT NOW(),
        updated_at TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW(),
        CONSTRAINT pk_dishes PRIMARY KEY (dish_id),
        CONSTRAINT fk_restaurant_dishes FOREIGN KEY (restaurant_id) REFERENCES restaurants (restaurant_id) ON DELETE CASCADE
    );

------------------------------------------------------------------------

-- Basket Table --

------------------------------------------------------------------------

DROP TABLE IF EXISTS baskets;

CREATE TABLE
    baskets (
        basket_id VARCHAR(36) NOT NULL DEFAULT (UUID()),
        user_id VARCHAR(36) NOT NULL,
        restaurant_id VARCHAR(36) NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT NOW(),
        updated_at TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW(),
        CONSTRAINT pk_basket PRIMARY KEY (basket_id),
        CONSTRAINT fk_basket_users FOREIGN KEY (user_id) REFERENCES users (user_id) ON DELETE CASCADE,
        CONSTRAINT fk_basket_restaurant FOREIGN KEY (restaurant_id) REFERENCES restaurants (restaurant_id) ON DELETE CASCADE
    );

DROP TABLE IF EXISTS basket_dishes;

CREATE TABLE
    basket_dishes (
        basket_id VARCHAR(36) NOT NULL,
        dish_id VARCHAR(36) NOT NULL,
        quantity INT NOT NULL CHECK (quantity > 0),
        created_at TIMESTAMP NOT NULL DEFAULT NOW(),
        updated_at TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW(),
        CONSTRAINT pk_basket_dishes PRIMARY KEY (basket_id, dish_id),
        CONSTRAINT fk_basket_dishes_basket FOREIGN KEY (basket_id) REFERENCES baskets (basket_id) ON DELETE CASCADE,
        CONSTRAINT fk_basket_dishes_dishes FOREIGN KEY (dish_id) REFERENCES dishes (dish_id) ON DELETE CASCADE
    );

------------------------------------------------------------------------

-- Orders Table --

------------------------------------------------------------------------

DROP TABLE IF EXISTS orders;

CREATE TABLE
    orders (
        order_id VARCHAR(36) NOT NULL DEFAULT (UUID()),
        user_id VARCHAR(36) NOT NULL,
        restaurant_id VARCHAR(36) NOT NULL,
        order_status ENUM(
            'pending',
            'accepted',
            'rejected',
            'delivered'
        ) NOT NULL DEFAULT 'pending',
        created_at TIMESTAMP NOT NULL DEFAULT NOW(),
        updated_at TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW(),
        CONSTRAINT pk_orders PRIMARY KEY (order_id),
        CONSTRAINT fk_orders_users FOREIGN KEY (user_id) REFERENCES users (user_id),
        CONSTRAINT fk_orders_restaurants FOREIGN KEY (restaurant_id) REFERENCES restaurants (restaurant_id)
    );

CREATE TABLE
    order_dishes (
        order_id VARCHAR(36) NOT NULL,
        dish_id VARCHAR(36) NOT NULL,
        quantity INT NOT NULL,
        dish_price FLOAT NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT NOW(),
        updated_at TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW(),
        CONSTRAINT pk_order_dishes PRIMARY KEY (order_id, dish_id),
        CONSTRAINT fk_order_dishes_orders FOREIGN KEY (order_id) REFERENCES orders (order_id) ON DELETE CASCADE,
        CONSTRAINT fk_order_dishes_dishes FOREIGN KEY (dish_id) REFERENCES dishes (dish_id)
    );

------------------------------------------------------------------------

-- Dining Table --

------------------------------------------------------------------------

DROP TABLE IF EXISTS dining;

CREATE TABLE
    dining (
        dining_id VARCHAR(36) NOT NULL DEFAULT (UUID()),
        table_name VARCHAR(255) NOT NULL,
        restaurant_id VARCHAR(36) NOT NULL,
        user_id VARCHAR(36),
        dining_status ENUM('0', '1') NOT NULL DEFAULT '0',
        created_at TIMESTAMP NOT NULL DEFAULT NOW(),
        updated_at TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW(),
        CONSTRAINT pk_dining PRIMARY KEY (table_name, restaurant_id),
        CONSTRAINT fk_dining_restaurants FOREIGN KEY (restaurant_id) REFERENCES restaurants (restaurant_id) ON DELETE CASCADE,
        CONSTRAINT fk_dining_users FOREIGN KEY (user_id) REFERENCES users (user_id)
    );

INSERT INTO
    dining (table_name, restaurant_id)
VALUES (
        'Table 1',
        '0ab0e0da-6963-11ed-ae95-f80dac7fa2d9'
    );

INSERT INTO
    dining (table_name, restaurant_id)
VALUES (
        'Table 2',
        '0ab0e0da-6963-11ed-ae95-f80dac7fa2d9'
    );

INSERT INTO
    dining (table_name, restaurant_id)
VALUES (
        'Table 3',
        '0ab0e0da-6963-11ed-ae95-f80dac7fa2d9'
    );

INSERT INTO
    dining (table_name, restaurant_id)
VALUES (
        'Table 4',
        '0ab0e0da-6963-11ed-ae95-f80dac7fa2d9'
    );

INSERT INTO
    dining (table_name, restaurant_id)
VALUES (
        'Table 5',
        '0ab0e0da-6963-11ed-ae95-f80dac7fa2d9'
    );

INSERT INTO
    dining (table_name, restaurant_id)
VALUES (
        'Table 6',
        '0ab0e0da-6963-11ed-ae95-f80dac7fa2d9'
    );

INSERT INTO
    dining (table_name, restaurant_id)
VALUES (
        'Table 1',
        '116f2ca9-696c-11ed-ae95-f80dac7fa2d9'
    );

INSERT INTO
    dining (table_name, restaurant_id)
VALUES (
        'Table 2',
        '116f2ca9-696c-11ed-ae95-f80dac7fa2d9'
    );