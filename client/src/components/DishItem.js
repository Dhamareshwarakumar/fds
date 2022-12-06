import React from 'react';
import { useSelector, useDispatch } from 'react-redux';
import {
    addDishToBasket,
    incrementDishQuantity,
    decrementDishQuantity
} from '../features/basketSlice';


const DishItem = ({ dish }) => {
    const dispatch = useDispatch();
    const basket = useSelector(state => state.basket);
    const basketDish = basket.data.find(basketDish => basketDish.dish_id === dish.dish_id);

    return (
        <div className='row DishItemContainer my-2 align-items-center'>
            <div className="col-auto dishImageContainer">
                <img src={dish.dish_image} alt="" />
            </div>
            <div className="col-auto my-2 dishInfoContainer">
                <h3>{dish.dish_name}</h3>
                <p>₹ {dish.dish_price}</p>
                <p>{dish.dish_description}</p>
                <p>{dish.dish_type}</p>
            </div>
            <div className="col-auto ms-auto">
                {basketDish ? (
                    <div className="row align-items-center">
                        <div className="col-auto">
                            <button className="btn btn-danger" onClick={() => dispatch(decrementDishQuantity(dish.dish_id, dish.restaurant_id))}>-</button>
                        </div>
                        <div className="col-auto">
                            <p>{basketDish.quantity}</p>
                        </div>
                        <div className="col-auto">
                            <button className="btn btn-success" onClick={() => dispatch(incrementDishQuantity(dish.dish_id, dish.restaurant_id))}>+</button>
                        </div>
                    </div>
                ) : (
                    <button className="btn btn-primary" onClick={() => dispatch(addDishToBasket(dish.dish_id, dish.restaurant_id))}>Add to Cart</button>
                )}
            </div>
        </div>
    );
};

export default DishItem;