import { createSlice } from '@reduxjs/toolkit';
import axios from 'axios';

const initialState = {
    data: [],
};


export const ordersSlice = createSlice({
    name: 'orders',
    initialState,
    reducers: {
        setOrders: (state, action) => {
            state.data = action.payload;
        }
    }
});

export const getOrders = () => dispatch => {
    axios.get('/api/orders')
        .then(res => {
            dispatch(setOrders(res.data));
        })
        .catch(err => {
            alert(JSON.stringify(err.response.data.error));
        });
}

export const { setOrders } = ordersSlice.actions;

export default ordersSlice.reducer;