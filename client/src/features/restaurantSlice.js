import { createSlice } from '@reduxjs/toolkit';
import axios from 'axios';

const initialState = {
    restaurant_id: '',
    restaurant_name: '',
    restaurant_lat: '',
    restaurant_lng: '',
    restaurant_city: '',
    restaurant_contact: '',
}

export const restaurantSlice = createSlice({
    name: 'restaurant',
    initialState,
    reducers: {
        setRestaurant: (state, action) => {
            state.restaurant_id = action.payload.restaurant_id;
            state.restaurant_name = action.payload.restaurant_name;
            state.restaurant_lat = action.payload.restaurant_lat;
            state.restaurant_lng = action.payload.restaurant_lng;
            state.restaurant_city = action.payload.restaurant_city;
            state.restaurant_contact = action.payload.restaurant_contact;
        }
    }
});

export const getRestaurant = restaurantId => dispatch => {
    axios.get('/api/restaurants/' + restaurantId)
        .then(res => {
            dispatch(setRestaurant(res.data.data));
        })
        .catch(err => {
            alert(JSON.stringify(err));
        });
}

export const { setRestaurant } = restaurantSlice.actions;

export default restaurantSlice.reducer;