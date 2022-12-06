import React, { useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import { useDispatch, useSelector } from 'react-redux';

import { getRestaurant } from '../features/restaurantSlice';
import { getDishes } from '../features/dishesSlice';
import { getBasket, checkout } from '../features/basketSlice';

import DishItem from '../components/DishItem';
import RestaurantHeader from '../components/RestaurantHeader';

const Restaurant = () => {
    const dispatch = useDispatch();
    const restaurant = useSelector(state => state.restaurant);
    const dishes = useSelector(state => state.dishes);
    const basket = useSelector(state => state.basket);
    const { restaurantId } = useParams();

    useEffect(() => {
        dispatch(getRestaurant(restaurantId));
        dispatch(getDishes(restaurantId));
        dispatch(getBasket(restaurantId));
    }, [dispatch, restaurantId]);

    useEffect(() => {
        document.title = restaurant.restaurant_name + ' | Zomato';
    }, [restaurant]);

    return (
        <div>
            <RestaurantHeader />
            <div className="container-fluid">
                <div className="row mt-2">
                    <div className="col-1"></div>
                    <div className="col-7">
                        <Link to={`/dining/${restaurantId}`}>
                            <button className="btn btn-primary">Book Dining</button>
                        </Link>
                        {dishes.data.length && dishes.data.map(dish => (
                            <DishItem key={dish.dish_id} dish={dish} />
                        ))}
                    </div>
                    <div className="col-4">
                        <div className="row justify-content-center mt-3">
                            <div className="col-10">
                                <h1 className='text-center'>Basket</h1>
                                {basket.data.length !== 0 && basket.data.map(dish => (
                                    <div key={dish.dish_id} className="border my-2 px-2 pt-1">
                                        <p>{dish.dish_name}</p>
                                        <p>{dish.quantity} x {dish.dish_price} = ₹{dish.quantity * dish.dish_price}</p>
                                    </div>
                                ))}
                                {basket.data.length !== 0 ? (
                                    <div className='d-grid'>
                                        <button className='btn btn-primary' onClick={() => dispatch(checkout(restaurant.restaurant_id))}>Place Order ₹{basket.data.reduce((accumulator, dish) => accumulator + dish.dish_price * dish.quantity, 0)}</button>
                                    </div>

                                ) : (
                                    <p className='text-center text-secondary'>Your basket is empty</p>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    );
};

export default Restaurant;