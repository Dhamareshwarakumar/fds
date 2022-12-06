import React from 'react';
import { useSelector } from 'react-redux';

const RestaurantHeader = () => {
    const restaurant = useSelector(state => state.restaurant);

    return (
        <div className='restaurantHeader'>
            <div className="content">
                <h1>{restaurant.restaurant_name}</h1>
            </div>
        </div>
    );
};

export default RestaurantHeader;