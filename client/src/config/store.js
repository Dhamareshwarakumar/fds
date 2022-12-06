import { configureStore } from '@reduxjs/toolkit';

import authReducer from '../features/authSlice';
import restaurantReducer from '../features/restaurantSlice';
import dishesReducer from '../features/dishesSlice';
import basketReducer from '../features/basketSlice';
import ordersReducer from '../features/ordersSlice';
import diningSlice from '../features/diningSlice';

export const store = configureStore({
    reducer: {
        auth: authReducer,
        restaurant: restaurantReducer,
        dishes: dishesReducer,
        basket: basketReducer,
        orders: ordersReducer,
        dining: diningSlice,
    },
});