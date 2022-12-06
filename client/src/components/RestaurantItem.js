import React from 'react';

const RestaurantItem = ({ restaurant_name }) => {
    return (
        <div className="restaurantItem">
            <div className="restaurantItem__image">
                <img src="https://b.zmtcdn.com/data/pictures/5/19623675/36c97edb89efbaca8a002c9475454cac_o2_featured_v2.jpg" alt="restaurant" />
            </div>
            <div className="restaurantItem__details mt-2 d-flex flex-row justify-content-between px-2">
                <div className="restaurantItem__details__name">
                    <h5>{restaurant_name}</h5>
                </div>
                <div className="restaurantItem__details__ratings__stars">4.5</div>
            </div>
        </div>
    );
};

export default RestaurantItem;