import { createSlice } from '@reduxjs/toolkit';
import axios from 'axios';


const initialState = {
    data: [],
};


export const dishesSlice = createSlice({
    name: 'dishes',
    initialState,
    reducers: {
        pushDish: (state, action) => {
            state.data.push(action.payload);
        },
        setDishes: (state, action) => {
            state.data = action.payload;
        },
        popDish: (state, action) => {
            state.data = state.data.filter(dish => dish.dish_id !== action.payload);
        }
    }
});

export const getDishes = userId => dispatch => {
    axios.get(`/api/restaurants/${userId}/dishes`)
        .then(res => {
            dispatch(setDishes(res.data));
        })
        .catch(err => {
            alert(JSON.stringify(err.response.data.error));
        });
};


export const addDish = (dish) => dispatch => {
    axios.post('/api/dishes', dish)
        .then(res => {
            dispatch(pushDish(dish));
        })
        .catch(err => {
            alert(JSON.stringify(err.response.data.error));
        });
}

export const deleteDish = dishId => dispatch => {
    axios.delete(`/api/dishes/${dishId}`)
        .then(res => {
            dispatch(popDish(dishId));
        })
        .catch(err => {
            alert(JSON.stringify(err.response.data.error));
        });
}

export const { setDishes, pushDish, popDish } = dishesSlice.actions;

export default dishesSlice.reducer;