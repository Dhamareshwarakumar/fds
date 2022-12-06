import React from 'react';
import './App.css';
import axios from 'axios';
import { useDispatch } from 'react-redux';
import {
	createBrowserRouter,
	RouterProvider
} from 'react-router-dom';

import { login } from './features/authSlice';

import ProtectedRoute from './components/ProtectedRoute';
import Login from './screens/Login';
import Home from './screens/Home';
import Restaurant from './screens/Restaurant';
import Admin from './screens/Admin';
import Dining from './screens/Dining';


const App = () => {
	const dispatch = useDispatch();

	// Set Axios Base URL
	axios.defaults.baseURL = process.env.REACT_APP_BASE_URI;

	// Check Auth Token
	const token = localStorage.getItem('token');
	if (token) {
		dispatch(login({
			token
		}));
	}

	const rootRouter = createBrowserRouter([
		{
			path: '/login',
			element: <Login />
		},
		{
			path: '/restaurant/:restaurantId',
			element: <ProtectedRoute><Restaurant /></ProtectedRoute>
		},
		{
			path: '/admin',
			element: <ProtectedRoute><Admin /></ProtectedRoute>,
		},
		{
			path: '/dining/:restaurantId',
			element: <ProtectedRoute><Dining /></ProtectedRoute>,
		},
		{
			path: '/',
			element: <ProtectedRoute><Home /></ProtectedRoute>
		}
	]);

	return (

		<RouterProvider router={rootRouter} />
	);
};

export default App;