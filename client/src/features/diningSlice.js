import { createSlice } from '@reduxjs/toolkit';
import axios from 'axios';

const initialState = {
    data: [],
};


export const diningSlice = createSlice({
    name: 'dining',
    initialState,
    reducers: {
        setDining: (state, action) => {
            state.data = action.payload;
        }
    }
});

export const getDining = restaurantId => dispatch => {
    axios.get(`/api/dinings/${restaurantId}`)
        .then(res => {
            dispatch(setDining(res.data));
        })
        .catch(err => {
            alert(JSON.stringify(err.response.data.error));
        });
}

export const reserveDining = (restaurantId, diningId) => dispatch => {
    axios.put(`/api/dinings/${diningId}`)
        .then(res => {
            dispatch(getDining(restaurantId));
        })
        .catch(err => {
            alert(JSON.stringify(err.response.data.error));
        });
}


export const { setDining } = diningSlice.actions;

export default diningSlice.reducer;