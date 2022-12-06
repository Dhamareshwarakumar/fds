import { createSlice } from '@reduxjs/toolkit';
import setAuthToken from '../utils/setAuthToken';
import jwt_decode from 'jwt-decode';

const initialState = {
    isAuthenticated: false,
    email: '',
    role: '',
    user_id: '',
};

export const authSlice = createSlice({
    name: 'auth',
    initialState,
    reducers: {
        login: (state, action) => {
            const decoded = jwt_decode(action.payload.token);

            if (Date.now() / 1000 > decoded.exp) {
                console.log(`'logging Out...`)
                return logout();
            }

            state.isAuthenticated = true;
            state.email = decoded.email;
            state.role = decoded.role;
            state.user_id = decoded.user_id;

            localStorage.setItem('token', action.payload.token);
            setAuthToken(action.payload.token);
        },
        logout: (state) => {
            state.isAuthenticated = false;
            state.email = '';

            localStorage.removeItem('token');
            setAuthToken();
        }
    },
});

export const { login, logout } = authSlice.actions

export default authSlice.reducer