import { createSlice } from '@reduxjs/toolkit';
import axios from 'axios';

const initialState = {
    data: [],
};


export const basketSlice = createSlice({
    name: 'basket',
    initialState,
    reducers: {
        setBasket: (state, action) => {
            state.data = action.payload;
        }
    }
});

export const getBasket = restaurantId => dispatch => {
    axios.get(`/api/baskets/${restaurantId}`)
        .then(res => {
            dispatch(setBasket(res.data));
        })
        .catch(err => {
            alert(JSON.stringify(err.response.data.error));
        });
}

export const addDishToBasket = (dishId, restaurantId, quantity = 1) => dispatch => {
    axios.post(`/api/baskets/`, { dish_id: dishId, quantity })
        .then(res => {
            dispatch(getBasket(restaurantId));
        })
        .catch(err => {
            alert(JSON.stringify(err.response.data.error));
        });
}

export const deleteDishFromBasket = (dishId, restaurantId) => dispatch => {
    axios.delete(`/api/baskets/${dishId}`)
        .then(res => {
            dispatch(getBasket(restaurantId));
        })
        .catch(err => {
            alert(JSON.stringify(err.response.data.error));
        });
}

export const incrementDishQuantity = (dishId, restaurantId) => dispatch => {
    axios.put(`/api/baskets/${dishId}/increment`)
        .then(res => {
            dispatch(getBasket(restaurantId));
        })
        .catch(err => {
            alert(JSON.stringify(err.response.data.error));
        });
}

export const decrementDishQuantity = (dishId, restaurantId) => dispatch => {
    axios.put(`/api/baskets/${dishId}/decrement`)
        .then(res => {
            dispatch(getBasket(restaurantId));
        })
        .catch(err => {
            alert(JSON.stringify(err.response.data.error));
        });
}

export const checkout = restaurantId => dispatch => {
    axios.get(`/api/baskets/${restaurantId}/checkout`)
        .then(res => {
            dispatch(getBasket(restaurantId));
        })
        .catch(err => {
            alert(JSON.stringify(err.response.data.error));
        });
}

export const { setBasket } = basketSlice.actions;

export default basketSlice.reducer;